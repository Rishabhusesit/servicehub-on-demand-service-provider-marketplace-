<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Service;

class ManageOffersController extends Controller
{
    public function index()
    {
        $pageTitle     = "Manage Offers";
        $offers        = Offer::searchable(['name'])->paginate(getPaginate());
        return view('admin.offers.index', compact('pageTitle', 'offers'));
    }

    public function create()
    {
        $pageTitle  = "Create New Offer";
        $services   = Service::active()->get();
        return view('admin.offers.create', compact('pageTitle', 'services'));
    }

    public function save(Request $request, $id)
    {
        $request->validate([
            "offer_name"        => 'required|string|max:40',
            "discount_type"     => 'required|integer|between:1,2',
            "amount"            => 'required|numeric',
            "start_date"        => 'required|date',
            "end_date"          => 'required|date',
            "description"       => 'required|string|max:70'
        ]);

        if ($id == 0) {
            $offer    = new Offer();
            $notify[] = ['success', 'Offer Created Successfully'];
        } else {
            $offer    = Offer::findOrFail($id);
            $notify[] = ['success', 'Offer Updated Successfully'];
        }

        $startDate    = date('Y-m-d', strtotime($request->start_date));
        $endDate      = date('Y-m-d', strtotime($request->end_date));

        $offer->name               = $request->offer_name;
        $offer->discount_type      = $request->discount_type;
        $offer->amount             = $request->amount;
        $offer->start_date         = $startDate;
        $offer->end_date           = $endDate;
        $offer->description        = $request->description;
        $offer->save();

        if ($id == 0) {
            $offer->services()->attach($request->services);
        } else {
            $offer->services()->sync($request->services);
        }

        return redirect()->back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle     = "Edit Offer";
        $offer         = Offer::findOrFail($id);
        $services      = Service::active()->get();
        return view('admin.offers.create', compact('pageTitle', 'offer', 'services'));
    }

    public function status($id)
    {
        return Offer::changeStatus($id);
    }
}
