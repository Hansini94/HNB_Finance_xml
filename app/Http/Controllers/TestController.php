<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleXMLElement;

class TestController extends Controller
{
    public function index(Request $request)
    {

        echo "hii";
    }

}
?>
