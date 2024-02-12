<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\TransactionDetail;

class TransactionDetailController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:transaction-list', ['only' => ['list']]);
        // $this->middleware('permission:transaction-create', ['only' => ['index', 'store']]);
        // $this->middleware('permission:transaction-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:transaction-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
        
    //     if ($request->ajax()) {
            
    //         $query = TransactionDetail::select('*')->get();
    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->make(true);
    //     }

    //     return view('adminpanel.transactions.list');
    // }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            
            $data = TransactionDetail::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('adminpanel.transactions.list');
    }

}
