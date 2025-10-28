<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class ManageCouponsController extends Controller
{
    public function index()
    {
        $pageTitle   = "All Coupons";
        $coupons     = Coupon::searchable(['name'])->paginate(getPaginate());
        $now         = Carbon::now();
        return view('admin.coupons.index', compact('pageTitle', 'coupons', 'now'));
    }

    public function create()
    {
        $pageTitle   = "Create New Coupon";
        return view('admin.coupons.create', compact('pageTitle'));
    }

    public function save(Request $request, $id)
    {

        $request->validate([
            "name"                      => 'required|string|max:40',
            "code"                      => 'required|string|max:20',
            "discount_type"             => 'required|integer|between:1,2',
            "amount"                    => 'required|numeric',
            "start_date"                => 'required|date',
            "end_date"                  => 'required|date',
            "minimum_spend"             => 'required|numeric',
            "usage_limit_per_coupon"    => 'nullable|integer',
            "usage_limit_per_customer"  => 'nullable|integer'

        ]);
        
        if($id == 0){
            $coupon   = new Coupon();
            $notify[] = ['success', 'Coupon Created Successfully'];
        }else{
            $coupon   = Coupon::findOrFail($id);
            $notify[] = ['success', 'Coupon Updated Successfully'];
        }

        $startDate    = date('Y-m-d', strtotime($request->start_date));
        $endDate      = date('Y-m-d', strtotime($request->end_date));

        $coupon->name                       = $request->name;
        $coupon->code                       = $request->code;
        $coupon->discount_type              = $request->discount_type;
        $coupon->amount                     = $request->amount ?? 0;
        $coupon->description                = $request->description;
        $coupon->start_date                 = $startDate;
        $coupon->end_date                   = $endDate;
        $coupon->minimum_spend              = $request->minimum_spend ?? 0;
        $coupon->usage_limit_per_coupon     = $request->usage_limit_per_coupon;
        $coupon->usage_limit_per_user       = $request->usage_limit_per_customer;

        $coupon->save();

        return redirect()->back()->withNotify($notify);
    }

    public function edit($id)
    {
        $coupon    = Coupon::findOrFail($id);
        $pageTitle = "Edit Coupon";
        return view('admin.coupons.create', compact('pageTitle', 'coupon'));
    }

    public function status($id)
	{
		return Coupon::changeStatus($id);
	}
}
