<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Service;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ServiceController extends Controller {

    public function index() {
        $pageTitle = "Manage Services";
        $services  = Service::searchable(['name'])->with('category')->latest()->paginate(getPaginate());
        return view('admin.service.index', compact('pageTitle', 'services'));
    }

    public function create() {
        $pageTitle  = "Create Service";
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.service.create', compact('categories', 'pageTitle'));
    }

    public function edit($id) {
        $pageTitle  = "Edit Service";
        $service    = Service::with(['faqs'])->findOrFail($id);
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.service.edit', compact('service', 'pageTitle', 'categories'));
    }

    public function save(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255|unique:services,name',
            'category_id' => 'required|integer|exists:categories,id',
            'image'       => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],

        ]);

        $category = Category::active()->findOrFail($request->category_id);
        if (!$category) {
            $notify[] = ['error', 'Category not found'];
            return back()->withNotify($notify);
        }

        $service              = new Service();
        $service->name        = $request->name;
        $service->slug        = slug($request->name);
        $service->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            try {
                $service->image = fileUploader($request->image, getFilePath('service'), getFileSize('service'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $service->cover_image = fileUploader($request->cover_image, getFilePath('coverImage'), getFileSize('coverImage'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your cover image'];
                return back()->withNotify($notify);
            }
        }
        $service->save();
        $notify[] = ['success', 'Service information added successfully'];
        return to_route('admin.service.overview', $service->id)->withNotify($notify);
    }

    public function overview($id) {
        $pageTitle = 'Service Overview';
        $service   = Service::where('id', $id)->firstOrFail();
        return view('admin.service.overview', compact('pageTitle', 'service'));
    }

    public function overviewSave(Request $request, $id) {
        $request->validate([
            'note'          => 'nullable|array',
            'overview'      => 'required|string',
            'details'       => 'required|string',
            'faq'           => 'nullable|array',
            'faq.*.title'   => 'required|string',
            'faq.*.details' => 'required|string',
        ]);

        $service           = Service::where('id', $id)->firstOrFail();
        $service->note     = implode(", ", $request->note);
        $service->overview = $request->overview;
        $service->details  = $request->details;
        $service->save();

        if ($request->faq) {
            if ($id) {
                Faq::where('service_id', $service->id)->delete();
            }
            foreach ($request->faq as $faq) {
                $faqs              = new Faq();
                $faqs->service_id  = $service->id;
                $faqs->title       = $faq['title'];
                $faqs->description = $faq['details'];
                $faqs->save();
            }
        }

        $notify[] = ['success', 'Service added successfully'];
        return to_route('admin.service.index')->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'          => 'required',
            'details'       => 'required',
            'overview'      => 'required',
            'note'          => 'nullable|array',
            'category_id'   => 'required|integer|exists:categories,id',
            'faq'           => 'nullable|array',
            'faq.*.title'   => 'string',
            'faq.*.details' => 'string',
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'cover_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);

        $service = Service::findOrFail($id);

        $slugCheck = Service::where('name', $request->name)
            ->where('category_id', $request->category_id)
            ->where('id', '!=', $id)
            ->first();

        if ($slugCheck) {
            return back()->withNotify([['error', 'This service already exists.']]);
        }

        $service->name        = $request->name;
        $service->slug        = slug($request->name);
        $service->overview    = $request->overview;
        $service->details     = $request->details;
        $service->category_id = $request->category_id;
        $service->note        = $request->note ? implode(", ", $request->note) : null;

        if ($request->hasFile('image')) {
            try {
                $old            = $service->image;
                $service->image = fileUploader($request->image, getFilePath('service'), getFileSize('service'), $old);
            } catch (\Exception $exp) {
                return back()->withNotify([['error', 'Couldn\'t upload your image']]);
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $old_cover            = $service->cover_image;
                $service->cover_image = fileUploader($request->cover_image, getFilePath('coverImage'), getFileSize('coverImage'), $old_cover);
            } catch (\Exception $exp) {
                return back()->withNotify([['error', 'Couldn\'t upload your cover image']]);
            }
        }

        $service->save();

        if ($request->faq) {
            if ($id) {
                Faq::where('service_id', $service->id)->delete();
            }

            foreach ($request->faq as $faq) {
                $faqs              = new Faq();
                $faqs->service_id  = $service->id;
                $faqs->title       = $faq['title'];
                $faqs->description = $faq['details'];
                $faqs->save();
            }
        }

        return back()->withNotify([['success', 'Service updated successfully']]);
    }

    public function status($id) {
        return Service::changeStatus($id);
    }

    public function featured($id) {
        $data              = Service::findOrFail($id);
        $data->is_featured = $data->is_featured == Status::DISABLE ? Status::ENABLE : Status::DISABLE;
        $data->save();
        $notify[] = ['success', 'Service featured status updated successfully'];
        return back()->withNotify($notify);

    }

}
