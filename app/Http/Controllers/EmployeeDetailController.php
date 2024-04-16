<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\EmployeeDetail;

class EmployeeDetailController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:transaction-list', ['only' => ['list']]);
        $this->middleware('permission:employee|employee-edit', ['only' => ['index']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:transaction-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = EmployeeDetail::select('*')->first();
       

        return view('adminpanel.employee.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $id = $request->id;
        $input = $request->all();

        $data = EmployeeDetail::find($id);
        $data->update($input);

        $id = $data->id;


        \LogActivity::addToLog('Employee Detail Record updated('.$id.').');

        $user = EmployeeDetail::select('*')->first();
       

        return view('adminpanel.employee.edit', compact('user'));
    }

}
