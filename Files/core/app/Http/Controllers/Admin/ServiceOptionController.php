<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ServiceOptionController extends Controller {
    public function index() {
        $pageTitle      = "Manage Service Option";
        $serviceOptions = ServiceOption::searchable(['name', 'service:name'])->with('service')->latest()->paginate(getPaginate());
        return view('admin.service_option.index', compact('pageTitle', 'serviceOptions'));
    }

    public function create() {
        $pageTitle = "Create Service Option";
        $services  = Service::active()->orderBy('name')->get();
        $parents   = ServiceOption::active()->orderBy('name')->get();
        return view('admin.service_option.create', compact('services', 'pageTitle', 'parents'));
    }

    public function edit($id) {

        $pageTitle      = "Edit Service  option";
        $service_option = ServiceOption::with(['service'])->findOrFail($id);
        $services       = Service::active()->orderBy('name')->get();
        $parents        = ServiceOption::active()->orderBy('name')->get();
        return view('admin.service_option.edit', compact('services', 'pageTitle', 'service_option', 'parents'));
    }

    public function save(Request $request) {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric|gte:0',
            'service_id'  => 'required|integer|exists:services,id',
            'image'       => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $service_option             = new ServiceOption();
        $service_option->name       = $request->name;
        $service_option->slug       = slug($request->name);
        $service_option->price      = $request->price;
        $service_option->service_id = $request->service_id;
        $service_option->parent_id  = $request->parent_id ?? 0;
        $service_option->note       = implode(", ", $request->note);

        if ($request->hasFile('image')) {
            try {
                $service_option->image = fileUploader($request->image, getFilePath('serviceOption'), getFileSize('serviceOption'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $service_option->save();
        $notify[] = ['success', 'Service Option added successfully'];
        return to_route('admin.service.serviceOption.index')->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'        => 'required|string',
            'note'        => 'nullable|array',
            'price'       => 'nullable|integer|gte:0',
            'service_id'  => 'required|integer|exists:services,id',
            'image'       => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $service_option             = ServiceOption::findOrFail($id);
        $service_option->name       = $request->name;
        $service_option->slug       = slug($request->name);
        $service_option->price      = $request->price;
        $service_option->service_id = $request->service_id;
        $service_option->parent_id  = $request->parent_id ?? 0;
        $service_option->note       = implode(", ", $request->note);

        if ($request->hasFile('image')) {
            try {
                $old                   = $service_option->image;
                $service_option->image = fileUploader($request->image, getFilePath('serviceOption'), getFileSize('serviceOption'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $service_option->save();
        $notify[] = ['success', 'Service Option updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id) {
        return ServiceOption::changeStatus($id);
    }
}
