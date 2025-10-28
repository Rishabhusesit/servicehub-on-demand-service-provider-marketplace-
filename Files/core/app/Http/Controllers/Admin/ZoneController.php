<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class ZoneController extends Controller {
    public function city() {
        $pageTitle = "Manage City";
        $cities    = City::searchable(['name'])->withCount(['areas'])->latest()->paginate(getPaginate());
        return view('admin.zone.city.index', compact('pageTitle', 'cities'));
    }

    public function citySave(Request $request, $id = 0) {

        $request->validate([
            'name'            => 'required|max:40|unique:cities,name,' . $id . ',id',
            'delivery_charge' => 'required|numeric|gte:0',
        ]);

        if (!$id) {
            $city     = new City();
            $notify[] = ['success', 'City added successfully'];
        } else {
            $city     = City::findOrFail($id);
            $notify[] = ['success', 'City updated successfully'];
        }

        $slug                  = slug($request->name);
        $city->name            = $request->name;
        $city->delivery_charge = $request->delivery_charge;
        $city->slug            = $slug;
        $city->save();
        return back()->withNotify($notify);
    }

    public function cityStatus($id) {
        return City::changeStatus($id);
    }

    public function area() {
        $pageTitle = "Manage Area";
        $areas     = Area::searchable(['name', 'city:name'])->with(['city'])->latest()->paginate(getPaginate());
        $cities    = City::active()->orderBy('name')->get();
        return view('admin.zone.area.index', compact('pageTitle', 'areas', 'cities'));
    }

    public function createArea($id = null) {
        $pageTitle = $id ? "Update Area" : "Create Area";
        $cities    = City::active()->orderBy('name')->get();
        $area      = null;
        if ($id) {
            $area = Area::findOrFail($id);
        }
        return view('admin.zone.area.create', compact('pageTitle', 'cities', 'area'));
    }

    public function areaSave(Request $request, $id = 0) {
        $request->validate([
            'name'    => 'required|max:40|string',
            'city_id' => 'required|integer|exists:cities,id',
        ]);

        if (!$id) {
            $area     = new Area();
            $notify[] = ['success', 'Area added successfully'];
        } else {
            $area     = Area::findOrFail($id);
            $notify[] = ['success', 'Area updated successfully'];
        }

        $slug          = slug($request->name);
        $area->name    = $request->name;
        $area->slug    = $slug;
        $area->city_id = $request->city_id;
        $area->save();
        return back()->withNotify($notify);
    }

    public function areaStatus($id) {
        return Area::changeStatus($id);
    }
}
