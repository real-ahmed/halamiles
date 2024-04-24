<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Lib\FormProcessor;

class WithdrawMethodController extends Controller
{
    public function index()

    {

        $pageTitle = 'Withdraw Methods';

        $methods = WithdrawMethod::orderBy('id','desc')->get();

        return view('admin.withdraw.methods.list', compact('pageTitle', 'methods'));

    }


    public function create()

    {

        $pageTitle = 'Edit methd';

        return view('admin.withdraw.methods.create', compact('pageTitle'));

    }


    public function edit($id)

    {

        $pageTitle = 'New Manual Gateway';

        $method = WithdrawMethod::where('id', $id)->firstOrFail();

        return view('admin.withdraw.methods.edit', compact('pageTitle', 'method'));

    }


    public function store(Request $request)

    {

        $request->validate([
            'name'           => 'required|max:60',
            'rate'           => 'required|numeric|gt:0',
            'currency'       => 'required|max:10',
            'symbol'         => 'required|max:10',
            'min_limit'      => 'required|numeric|gt:0',
            'max_limit'      => 'required|numeric|gt:'.$request->min_limit,
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'instruction'    => 'required|max:64000',
            'field_name.*'   => 'sometimes|required'
        ],[
            'field_name.*.required'=>'All field is required',
        ]);




        if ($request->has('field_name')) {
            for ($a = 0; $a < count($request->field_name); $a++) {
                $arr = array();
                $arr['field_name'] = strtolower(str_replace(' ', '_', trim($request->field_name[$a])));
                $arr['field_level'] = trim($request->field_name[$a]);
                $arr['type'] = $request->type[$a];
                $arr['validation'] = $request->validation[$a];
                $inputForm[$arr['field_name']] = $arr;
            }
        }

        $method = new WithdrawMethod();

        $method->name           = $request->name;

        $method->input_form     = json_encode($inputForm);

        $method->note           = $request->instruction;

        $method->currency       = $request->currency;

        $method->symbol = $request->symbol;

        $method->min_amount     = $request->min_limit;

        $method->max_amount     = $request->max_limit;

        $method->fixed_charge   = $request->fixed_charge;

        $method->percent_charge = $request->percent_charge;

        $method->rate           = $request->rate;

        $method->status = 1;

        $method->save();


        $notify[] = ['success', $method->name . ' Manual gateway has been added.'];
        return redirect()->route('admin.withdraw-method.index')->withNotify($notify);

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|max:60',
            'rate'           => 'required|numeric|gt:0',
            'currency'       => 'required|max:10',
            'symbol'         => 'required|max:10',
            'min_limit'      => 'required|numeric|gt:0',
            'max_limit'      => 'required|numeric|gt:'.$request->min_limit,
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|between:0,100',
            'instruction'    => 'required|max:64000',
            'field_name.*'   => 'sometimes|required'
        ],[
            'field_name.*.required' => 'All field is required',
        ]);
    
        $method = WithdrawMethod::where('id', $id)->firstOrFail();
    
        $inputForm = [];
    
        if ($request->has('field_name')) {
            for ($a = 0; $a < count($request->field_name); $a++) {
                $arr = array();
                $arr['field_name'] = strtolower(str_replace(' ', '_', trim($request->field_name[$a])));
                $arr['field_level'] = trim($request->field_name[$a]);
                $arr['type'] = $request->type[$a];
                $arr['validation'] = $request->validation[$a];
                $inputForm[$arr['field_name']] = $arr;
            }
        }
    
        $method->name = $request->name;
        $method->input_form = json_encode($inputForm);
        $method->note = $request->instruction;
        $method->currency = $request->currency;
        $method->symbol = $request->symbol;
        $method->min_amount = $request->min_limit;
        $method->max_amount = $request->max_limit;
        $method->fixed_charge = $request->fixed_charge;
        $method->percent_charge = $request->percent_charge;
        $method->rate = $request->rate;
    
        $method->save();
    
        $notify[] = ['success', $method->name . ' Manual gateway has been updated.'];
        return redirect()->route('admin.withdraw-method.index')->withNotify($notify);
    }


    public function activate($id)

    {

        $method = WithdrawMethod::where('id', $id)->firstOrFail();

        $method->status = 1;

        $method->save();

        $notify[] = ['success', $method->name . ' enabled successfully'];

        return back()->withNotify($notify);

    }



    public function deactivate($id)

    {

        $method = WithdrawMethod::where('id', $id)->firstOrFail();

        $method->status = 0;

        $method->save();

        $notify[] = ['success', $method->name . ' disabled successfully'];

        return back()->withNotify($notify);

    }



}
