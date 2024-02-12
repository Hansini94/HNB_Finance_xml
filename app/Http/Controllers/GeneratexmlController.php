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

class GeneratexmlController extends Controller
{
    public function index() {
        $trans_data = TransactionDetail::first();
        $currenttime = Carbon\Carbon::now();
        $current_date =  $currenttime->toDateTimeString();

        return response()->view('generate_xml.xml', [
            'trans_data' => $trans_data
        ])
        ->withHeaders([
            'Content-Type' => 'text/xml',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename='.$currenttime.'.xml',
            'Content-Transfer-Encoding' => 'binary',
        ]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            
            $data = TransactionDetail::select('*')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('adminpanel.generate_xml.list');
    }
}
