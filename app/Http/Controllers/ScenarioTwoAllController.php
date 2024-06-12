<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use DataTables;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use SimpleXMLElement;

use App\Models\ScenarioTwo;
use App\Models\EmployeeDetail;
use App\Models\LogXMLGenActivity;

class ScenarioTwoAllController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:scenario-two-all-list|scenario-two-all-edit|scenario-two-all-delete', ['only' => ['list']]);
        $this->middleware('permission:scenario-two-all-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:scenario-two-all-delete', ['only' => ['destroy']]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = LogXMLGenActivity::select('*')->where('scenario_no',2)->orderBy('created_at', 'desc')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('excel', function ($row) {
                    $download_url = url('download-excel-two/' . encrypt($row->id));
                    return '<a href="' . $download_url . '">Download Excel</a>';
                })
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/scenario-two-edit/' . encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                 })

                 ->rawColumns(['excel','edit'])
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_two.all.list');
    }

    public function datalist(Request $request, $id)
    {
        $ID = decrypt($id);
        $log_data = LogXMLGenActivity::select('*')->where('id', $ID)->where('scenario_no',2)->first();
        $from_date = $log_data->from_date;
        $to_date = $log_data->to_date;
        $xml_type = $log_data->xml_type;

        if ($request->ajax()) {
            $data = ScenarioTwo::select('*')->where('is_delete', '0')->where('xml_gen_status', 'N')->whereBetween('date_transaction', [$log_data->from_date, $log_data->to_date])->orderBy('id', 'asc')->get(); // xml_gen_status', 'N' => Y
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-scenario-two-all/' . encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                 })
                 ->addColumn('activation', function($row){
                     if ( $row->status == "Y" )
                         $status ='fa fa-check';
                     else
                         $status ='fa fa-remove';
                     $btn = '<a href="changestatus-scenario-two-all/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';
                     return $btn;
                 })
                 ->addColumn('blockscenariotwo', 'adminpanel.generate_xml.scenario_two.all.actionsBlock')
                 ->rawColumns(['edit','activation','blockscenariotwo'])
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_two.all.datalist', ['log_id' => $id , 'from_date' => $from_date , 'to_date' => $to_date , 'xml_type' => $xml_type]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // die(decrypt($id));
        //
        $ID = decrypt($id);
        $info = ScenarioTwo::where('id', '=', $ID)->first();


        return view('adminpanel.generate_xml.scenario_two.all.edit', ['data' => $info]);
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

        $data = ScenarioTwo::find($id);
        $data->update($input);

        $id = $data->id;


        \LogActivity::addToLog('Scenario Two Record updated('.$id.').');

        return redirect()->route('scenario-two-all-list')->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activation(Request $request)
    {
        $data =  ScenarioTwo::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Scenario Two Record deactivated('.$id.').');

            return redirect()->route('scenario-two-all-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Scenario Two Record activated('.$id.').');

            return redirect()->route('scenario-two-all-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ScenarioTwo::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Scenario Two Record deleted('.$id.').');

        return redirect()->route('scenario-two-all-list')
            ->with('success', 'Record deleted successfully.');
    }

    public function generate_xml()
    {
        $scenario_type = request('scenario_type');
        $from_date = request('from_date');
        $to_date = request('to_date');
        $xml_type = '';
        $xml_gen_status=1; //new generation 1 , old data generation 2

        // Retrieve data from the database
        // $data = ScenarioTwo::whereBetween('created_at', [$from_date, $to_date])->where('xml_gen_status', '=', 'N')->where('scenario_type',$scenario_type)
         //   ->groupBy('rentity_id')
           // ->first();

        $data = ScenarioTwo::select('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
        ->whereBetween('date_transaction', [$from_date, $to_date])
        ->where('xml_gen_status', '=', 'N')
        ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
        ->where('scenario_type', $scenario_type)
        ->groupBy('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
        ->first();

        // Check if data exists
        if (!$data) {
            return back()->with('error', 'No records found for the selected date range and scenario type.');
        }

        // Retrieve the current logged-in user
        $user = Auth::user();

        $employee = EmployeeDetail::first();

        // Create the XML document
        $xmlDoc = new \DOMDocument('1.0', 'utf-8');
        $xmlDoc->formatOutput = true;

        // Create the root element
        $root = $xmlDoc->createElement('report');
        $xmlDoc->appendChild($root);

        // Add report_info section
        // $reportInfo = $xmlDoc->createElement('report_info');

        $root->appendChild($xmlDoc->createElement('rentity_id', $data->rentity_id));
        $root->appendChild($xmlDoc->createElement('rentity_branch', $data->rentity_branch));
        $root->appendChild($xmlDoc->createElement('submission_code', $data->submission_code));
        $root->appendChild($xmlDoc->createElement('report_code', $data->report_code));
        $root->appendChild($xmlDoc->createElement('entity_reference', $data->entity_reference));
        $currentDate = date('Y-m-d\TH:i:s'); // Get the current date and time in the format YYYY-MM-DDTHH:MM:SS
        $root->appendChild($xmlDoc->createElement('submission_date', $currentDate));
        if($data->report_code == 'CTR' || $data->report_code == 'EFT' || $data->report_code == 'IFT ')
        {
            $root->appendChild($xmlDoc->createElement('currency_code_local', 'LKR'));
        }
        else
        {
            $root->appendChild($xmlDoc->createElement('currency_code_local', $data->currency_code_local));
        }
        if($data->reason !== null && $data->reason !== '')
        {
            $root->appendChild($xmlDoc->createElement('reason', $data->reason));
        }

        // Add reporting_person section
        $reportingPerson = $xmlDoc->createElement('reporting_person');
        if($user->gender !== null && $user->gender !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('gender', $user->gender));
        }
        if($user->title !== null && $user->title !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('title', $user->title));
        }
        if($user->first_name !== null && $user->first_name !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('first_name', $user->first_name));
        }
        if($user->middle_name !== null && $user->middle_name !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('middle_name', $user->middle_name));
        }
        if($user->prefix !== null && $user->prefix !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('prefix', $user->prefix));
        }
        if($user->last_name !== null && $user->last_name !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('last_name', $user->last_name));
        }
        if($user->birthdate !== null && $user->birthdate !== '')
        {
            $dateofbirth = new DateTime($user->birthdate);
            $formattedDateofBirth = $dateofbirth->format('Y-m-d\TH:i:s');
            $reportingPerson->appendChild($xmlDoc->createElement('birthdate', $formattedDateofBirth));
        }
        if($user->birth_place !== null && $user->birth_place !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('birth_place', $user->birth_place));
        }
        if($user->mothers_name !== null && $user->mothers_name !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('mothers_name', $user->mothers_name));
        }
        if($user->alias !== null && $user->alias !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('alias', $user->alias));
        }
        if($user->ssn !== null && $user->ssn !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('ssn', $user->ssn));
        }
        if($user->passport_number !== null && $user->passport_number !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('passport_number', $user->passport_number));
        }
        if($user->passport_country !== null && $user->passport_country !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('passport_country', $user->passport_country));
        }
        if($user->id_number !== null && $user->id_number !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('id_number', $user->id_number));
        }
        if($user->nationality1 !== null && $user->nationality1 !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('nationality1', $user->nationality1));
        }
        if($user->nationality2 !== null && $user->nationality2 !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('nationality2', $user->nationality2));
        }
        if($user->nationality3 !== null && $user->nationality3 !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('nationality3', $user->nationality3));
        }
        if($user->residence !== null && $user->residence !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('residence', $user->residence));
        }
        if($user->phones !== null && $user->phones !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('phones', $user->phones));
        }
        if($user->address_type !== null && $user->address_type !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('address_type', $user->address_type));
        }
        if($user->address !== null && $user->address !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('address', $user->address));
        }
        if($user->city !== null && $user->city !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('city', $user->city));
        }
        if($user->country_code !== null && $user->country_code !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('country_code', $user->country_code));
        }
        if($user->email !== null && $user->email !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('email', $user->email));
        }
        if($user->occupation !== null && $user->occupation !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('occupation', $user->occupation));
        }
        if($user->employer_name !== null && $user->employer_name !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('employer_name', $employee->employer_name));
        }
        if($user->deceased !== null && $user->deceased !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('deceased', $user->deceased));
        }
        if($user->deceased_date !== null && $user->deceased_date !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('deceased_date', $user->deceased_date));
        }
        if($user->tax_number !== null && $user->tax_number !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('tax_number', $user->tax_number));
        }
        if($user->tax_reg_numebr !== null && $user->tax_reg_numebr !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('tax_reg_numebr', $user->tax_reg_numebr));
        }
        if($user->source_of_wealth !== null && $user->source_of_wealth !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('source_of_wealth', $user->source_of_wealth));
        }
        if($user->comments !== null && $user->comments !== '')
        {
            $reportingPerson->appendChild($xmlDoc->createElement('comments', $user->comments));
        }

        $root->appendChild($reportingPerson);

        if($scenario_type == 'Entity')
        {
            $xml_type = 'Entity';
            $trans = ScenarioTwo::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('scenario_type','Entity')
            ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
            ->orderBy('date_transaction', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Iterate over each transaction
            foreach ($trans as $item) {
                // Create a <t_from_my_client> element
                $transaction = $xmlDoc->createElement('transaction');

                $transaction->appendChild($xmlDoc->createElement('transactionnumber', $item->transactionnumber));
                $transaction->appendChild($xmlDoc->createElement('internal_ref_number', $item->internal_ref_number));
                if($item->transaction_location !== null && $item->transaction_location !== '')
                {
                    $transaction->appendChild($xmlDoc->createElement('transaction_location', $item->transaction_location));
                }
                $transaction->appendChild($xmlDoc->createElement('transaction_description', $item->transaction_description));
                $dateTransaction = new DateTime($item->date_transaction);
                $formattedDateTransaction = $dateTransaction->format('Y-m-d\TH:i:s');
                $transaction->appendChild($xmlDoc->createElement('date_transaction', $formattedDateTransaction));
                $dateValue = new DateTime($item->value_date);
                $formattedDateValue = $dateValue->format('Y-m-d\TH:i:s');
                $transaction->appendChild($xmlDoc->createElement('value_date', $formattedDateValue));
                $transaction->appendChild($xmlDoc->createElement('transmode_code', $item->transmode_code));
                $transaction->appendChild($xmlDoc->createElement('amount_local', $item->amount_local));

                // ******************** from_my_client *******************************

                // Create a <t_from_my_client> element
                $t_from_my_client = $xmlDoc->createElement('t_from_my_client');

                $t_from_my_client->appendChild($xmlDoc->createElement('from_funds_code', $item->from_funds_code));

                // Convert the Eloquent model to an array, excluding the metadata fields
                $data_item = $item->toArray();

                // Create a <from_person> element
                $from_account = $xmlDoc->createElement('from_account');

                if($item->from_account_institution_name !== null && $item->from_account_institution_name !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('institution_name', $item->from_account_institution_name));
                }
                if($item->from_account_swift !== null && $item->from_account_swift !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('swift', $item->from_account_swift));
                }
                if($item->from_account_non_bank_institution !== null && $item->from_account_non_bank_institution !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->from_account_non_bank_institution));
                }
                if($item->from_account_branch !== null && $item->from_account_branch !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('branch', $item->from_account_branch));
                }
                if($item->from_account_number !== null && $item->from_account_number !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_number));
                }
                if($item->from_account_currency_code !== null && $item->from_account_currency_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('currency_code', $item->from_account_currency_code));
                }
                if($item->from_account_personal_account_type !== null && $item->from_account_personal_account_type !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('personal_account_type', $item->from_account_personal_account_type));
                }

                $t_entity = $xmlDoc->createElement('t_entity');

                if($item->from_entity_name !== null && $item->from_entity_name !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('name', $item->from_entity_name));
                }
                if($item->from_entity_incorporation_legal_form !== null && $item->from_entity_incorporation_legal_form !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_legal_form', $item->from_entity_incorporation_legal_form));
                }
                if($item->from_entity_incorporation_number !== null && $item->from_entity_incorporation_number !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_number', $item->from_entity_incorporation_number));
                }
                if($item->from_entity_business !== null && $item->from_entity_business !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('business', $item->from_entity_business));
                }

                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->from_entity_address_type !== null && $item->from_entity_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->from_entity_address_type));
                        }
                        if($item->from_entity_address !== null && $item->from_entity_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->from_entity_address));
                        }
                        if($item->from_entity_address_city !== null && $item->from_entity_address_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->from_entity_address_city));
                        }
                        if($item->from_entity_address_country_code !== null && $item->from_entity_address_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->from_entity_address_country_code));
                        }
                    $addresses->appendChild($address);
                $t_entity->appendChild($addresses);

                if($item->from_entity_incorporation_country_code !== null && $item->from_entity_incorporation_country_code !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_country_code', $item->from_entity_incorporation_country_code));
                }

                $director = DirectorIdDetail::where('entity_id', $item->id)
                ->where('scenario_no', 2)
                ->where('entity_type', 'from')
                ->get();

                foreach ($director as $dr_item)
                {

                    $director_id = $xmlDoc->createElement('director_id');

                    if($dr_item->gender !== null && $dr_item->gender !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('gender', $dr_item->gender));
                    }
                    if($dr_item->title !== null && $dr_item->title !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('title', $dr_item->title));
                    }
                    if($dr_item->first_name !== null && $dr_item->first_name !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('first_name', $dr_item->first_name));
                    }
                    if($dr_item->last_name !== null && $dr_item->last_name !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('last_name', $dr_item->last_name));
                    }
                    if($dr_item->birthdate !== null && $dr_item->birthdate !== '')
                    {
                        $dateofbirth = new DateTime($dr_item->birthdate);
                        $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                        $director_id->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                    }
                    if($dr_item->ssn !== null && $dr_item->ssn !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('ssn', $dr_item->ssn));
                    }
                    if($dr_item->passport_number !== null && $dr_item->passport_number !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('passport_number', $dr_item->passport_number));
                    }
                    if($dr_item->passport_country !== null && $dr_item->passport_country !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('passport_country', $dr_item->passport_country));
                    }
                    if($dr_item->nationality1 !== null && $dr_item->nationality1 !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('nationality1', $dr_item->nationality1));
                    }
                    if($dr_item->residence !== null && $dr_item->residence !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('residence', $dr_item->residence));
                    }
                    $addresses = $xmlDoc->createElement('addresses');
                        $address = $xmlDoc->createElement('address');
                            if($dr_item->address_type !== null && $dr_item->address_type !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('address_type', $dr_item->address_type));
                            }
                            if($dr_item->address !== null && $dr_item->address !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('address', $dr_item->address));
                            }
                            if($dr_item->city !== null && $dr_item->city !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('city', $dr_item->city));
                            }
                            if($dr_item->country_code !== null && $dr_item->country_code !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('country_code', $dr_item->country_code));
                            }
                        $addresses->appendChild($address);
                    $director_id->appendChild($addresses);

                    if($dr_item->occupation !== null && $dr_item->occupation !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('occupation', $dr_item->occupation));
                    }
                    if($dr_item->role !== null && $dr_item->role !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('role', $dr_item->role));
                    }

                    $from_entity->appendChild($director_id);

                }

                $from_account->appendChild($t_entity);

                if($item->from_account_status_code !== null && $item->from_account_status_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('status_code', $item->from_account_status_code));
                }

                // Append the <from_person> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_account);

                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from_my_client->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }


                $transaction->appendChild($t_from_my_client);

                // ******************** to_my_client *******************************

                // Create a <t_to_my_client> element
                $t_to_my_client = $xmlDoc->createElement('t_to_my_client');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }
                // Create a <from_entity> element
                $to_entity = $xmlDoc->createElement('to_entity');

                $to_entity->appendChild($xmlDoc->createElement('name', $item->to_entity_name));
                $to_entity->appendChild($xmlDoc->createElement('incorporation_legal_form', $item->to_entity_incorporation_legal_form));
                $to_entity->appendChild($xmlDoc->createElement('incorporation_number', $item->to_entity_incorporation_number));
                $to_entity->appendChild($xmlDoc->createElement('business', $item->to_entity_business));
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->to_entity_address_type !== null && $item->to_entity_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->to_entity_address_type));
                        }
                        if($item->to_entity_address !== null && $item->to_entity_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->to_entity_address));
                        }
                        if($item->to_entity_address_city !== null && $item->to_entity_address_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->to_entity_address_city));
                        }
                        if($item->to_entity_address_country_code !== null && $item->to_entity_address_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->to_entity_address_country_code));
                        }
                    $addresses->appendChild($address);
                $to_entity->appendChild($addresses);
                $to_entity->appendChild($xmlDoc->createElement('incorporation_country_code', $item->to_entity_incorporation_country_code));
                $directorto = DirectorIdDetail::where('entity_id', $item->id)
                ->where('scenario_no', 2)
                ->where('entity_type', 'to')
                ->get();

                foreach ($directorto as $dr_item_to)
                {

                    $director_id = $xmlDoc->createElement('director_id');

                    if($dr_item_to->gender !== null && $dr_item_to->gender !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('gender', $dr_item_to->gender));
                    }
                    if($dr_item_to->title !== null && $dr_item_to->title !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('title', $dr_item_to->title));
                    }
                    if($dr_item_to->first_name !== null && $dr_item_to->first_name !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('first_name', $dr_item_to->first_name));
                    }
                    if($dr_item_to->last_name !== null && $dr_item_to->last_name !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('last_name', $dr_item_to->last_name));
                    }
                    if($dr_item_to->birthdate !== null && $dr_item_to->birthdate !== '')
                    {
                        $dateofbirth = new DateTime($dr_item_to->birthdate);
                        $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                        $director_id->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                    }
                    if($dr_item_to->ssn !== null && $dr_item_to->ssn !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('ssn', $dr_item_to->ssn));
                    }
                    if($dr_item_to->passport_number !== null && $dr_item_to->passport_number !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('passport_number', $dr_item_to->passport_number));
                    }
                    if($dr_item_to->passport_country !== null && $dr_item_to->passport_country !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('passport_country', $dr_item_to->passport_country));
                    }
                    if($dr_item_to->nationality1 !== null && $dr_item_to->nationality1 !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('nationality1', $dr_item_to->nationality1));
                    }
                    if($dr_item_to->residence !== null && $dr_item_to->residence !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('residence', $dr_item_to->residence));
                    }
                    $addresses = $xmlDoc->createElement('addresses');
                        $address = $xmlDoc->createElement('address');
                            if($dr_item_to->address_type !== null && $dr_item_to->address_type !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('address_type', $dr_item_to->address_type));
                            }
                            if($dr_item_to->address !== null && $dr_item_to->address !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('address', $dr_item_to->address));
                            }
                            if($dr_item_to->city !== null && $dr_item_to->city !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('city', $dr_item_to->city));
                            }
                            if($dr_item_to->country_code !== null && $dr_item_to->country_code !== '')
                            {
                                $address->appendChild($xmlDoc->createElement('country_code', $dr_item_to->country_code));
                            }
                        $addresses->appendChild($address);
                    $director_id->appendChild($addresses);

                    if($dr_item_to->occupation !== null && $dr_item_to->occupation !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('occupation', $dr_item_to->occupation));
                    }
                    if($dr_item_to->role !== null && $dr_item_to->role !== '')
                    {
                        $director_id->appendChild($xmlDoc->createElement('role', $dr_item_to->role));
                    }

                    $to_entity->appendChild($director_id);
                }

                // // Append the <t_entity> element to the <t_to_my_client> element
                $t_to_my_client->appendChild($to_entity);

                if($item->to_country !== null && $item->to_country !== '')
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_country', $item->to_country));
                }

                $transaction->appendChild($t_to_my_client);

                // Append the <transaction> element to the main XML document
                $root->appendChild($transaction);
            }

        }
        else
        {
            $xml_type = 'Person';
            $trans = ScenarioTwo::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('scenario_type','Person')
            ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
            ->orderBy('date_transaction', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Iterate over each transaction
            foreach ($trans as $item) {
                // Create a <t_from_my_client> element
                $transaction = $xmlDoc->createElement('transaction');

                $transaction->appendChild($xmlDoc->createElement('transactionnumber', $item->transactionnumber));
                $transaction->appendChild($xmlDoc->createElement('internal_ref_number', $item->internal_ref_number));
                if($item->transaction_location !== null && $item->transaction_location !== '')
                {
                    $transaction->appendChild($xmlDoc->createElement('transaction_location', $item->transaction_location));
                }
                $transaction->appendChild($xmlDoc->createElement('transaction_description', $item->transaction_description));
                $dateTransaction = new DateTime($item->date_transaction);
                $formattedDateTransaction = $dateTransaction->format('Y-m-d\TH:i:s');
                $transaction->appendChild($xmlDoc->createElement('date_transaction', $formattedDateTransaction));
                $dateValue = new DateTime($item->value_date);
                $formattedDateValue = $dateValue->format('Y-m-d\TH:i:s');
                $transaction->appendChild($xmlDoc->createElement('value_date', $formattedDateValue));
                $transaction->appendChild($xmlDoc->createElement('transmode_code', $item->transmode_code));
                $transaction->appendChild($xmlDoc->createElement('amount_local', $item->amount_local));

                // ******************** from_my_client *******************************

                // Create a <t_from_my_client> element
                $t_from_my_client = $xmlDoc->createElement('t_from_my_client');

                $t_from_my_client->appendChild($xmlDoc->createElement('from_funds_code', $item->from_funds_code));

                // Convert the Eloquent model to an array, excluding the metadata fields
                $data_item = $item->toArray();

                // Create a <from_person> element
                $from_account = $xmlDoc->createElement('from_account');

                $from_account->appendChild($xmlDoc->createElement('institution_name', $item->from_account_institution_name));
                $from_account->appendChild($xmlDoc->createElement('swift', $item->from_account_swift));
                $from_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->from_account_non_bank_institution));
                $from_account->appendChild($xmlDoc->createElement('branch', $item->from_account_branch));
                $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_number));
                $from_account->appendChild($xmlDoc->createElement('currency_code', $item->from_account_currency_code));
                $from_account->appendChild($xmlDoc->createElement('personal_account_type', $item->from_account_personal_account_type));


                $signatory = $xmlDoc->createElement('signatory');

                if($item->from_account_signatory_is_primary !== null && $item->from_account_signatory_is_primary !== '')
                {
                    $signatory->appendChild($xmlDoc->createElement('is_primary', $item->from_account_signatory_is_primary));
                }

                $t_person = $xmlDoc->createElement('t_person');

                if($item->from_account_signatory_gender !== null && $item->from_account_signatory_gender !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('gender', $item->from_account_signatory_gender));
                }
                if($item->from_account_signatory_title !== null && $item->from_account_signatory_title !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('title', $item->from_account_signatory_title));
                }
                if($item->from_account_signatory_first_name !== null && $item->from_account_signatory_first_name !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('first_name', $item->from_account_signatory_first_name));
                }
                if($item->from_account_signatory_last_name !== null && $item->from_account_signatory_last_name !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('last_name', $item->from_account_signatory_last_name));
                }
                if($item->from_account_signatory_birthdate !== null && $item->from_account_signatory_birthdate !== '')
                {
                    $dateofbirth = new DateTime($item->from_account_signatory_birthdate);
                    $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                    $t_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                }
                if($item->from_account_signatory_ssn !== null && $item->from_account_signatory_ssn !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('ssn', $item->from_account_signatory_ssn));
                }
                if($item->from_account_signatory_nationality1 !== null && $item->from_account_signatory_nationality1 !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('nationality1', $item->from_account_signatory_nationality1));
                }
                if($item->from_account_signatory_residence !== null && $item->from_account_signatory_residence !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('residence', $item->from_account_signatory_residence));
                }
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->from_account_signatory_address_type !== null && $item->from_account_signatory_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->from_account_signatory_address_type));
                        }
                        if($item->from_account_signatory_address !== null && $item->from_account_signatory_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->from_account_signatory_address));
                        }
                        if($item->from_account_signatory_city !== null && $item->from_account_signatory_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->from_account_signatory_city));
                        }
                        if($item->from_account_signatory_country_code !== null && $item->from_account_signatory_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->from_account_signatory_country_code));
                        }
                    $addresses->appendChild($address);
                $t_person->appendChild($addresses);

                if($item->from_account_signatory_occupation !== null && $item->from_account_signatory_occupation !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('occupation', $item->from_account_signatory_occupation));
                }

                // Append the <from_person> element to the <t_from_my_client> element
                $signatory->appendChild($t_person);

                if($item->from_account_signatory_role !== null && $item->from_account_signatory_role !== '')
                {
                    $signatory->appendChild($xmlDoc->createElement('role', $item->from_account_signatory_role));
                }

                $from_account->appendChild($signatory);

                if($item->from_account_status_code !== null && $item->from_account_status_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('status_code', $item->from_account_status_code));
                }

                $t_from_my_client->appendChild($from_account);


                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from_my_client->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }

                $transaction->appendChild($t_from_my_client);


                // ******************** to_my_client *******************************

                // Create a <t_to_my_client> element
                $t_to_my_client = $xmlDoc->createElement('t_to_my_client');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }

                $to_person = $xmlDoc->createElement('to_person');

                if($item->to_person_gender !== null && $item->to_person_gender !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('gender', $item->to_person_gender));
                }
                if($item->to_person_title !== null && $item->to_person_title !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('title', $item->to_person_title));
                }
                if($item->to_person_first_name !== null && $item->to_person_first_name !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('first_name', $item->to_person_first_name));
                }
                if($item->to_person_last_name !== null && $item->to_person_last_name !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('last_name', $item->to_person_last_name));
                }
                if($item->to_person_birthdate !== null && $item->to_person_birthdate !== '')
                {
                    $dateofbirth = new DateTime($item->to_person_birthdate);
                    $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                    $to_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                }
                if($item->to_person_ssn !== null && $item->to_person_ssn !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('ssn', $item->to_person_ssn));
                }
                if($item->to_person_nationality1 !== null && $item->to_person_nationality1 !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('nationality1', $item->to_person_nationality1));
                }
                if($item->to_person_residence !== null && $item->to_person_residence !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('residence', $item->to_person_residence));
                }
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->to_person_address_type !== null && $item->to_person_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->to_person_address_type));
                        }
                        if($item->to_person_address !== null && $item->to_person_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->to_person_address));
                        }
                        if($item->to_person_city !== null && $item->to_person_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->to_person_city));
                        }
                        if($item->to_person_country_code !== null && $item->to_person_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->to_person_country_code));
                        }
                    $addresses->appendChild($address);
                $to_person->appendChild($addresses);

                if($item->to_person_occupation !== null && $item->to_person_occupation !== '')
                {
                    $to_person->appendChild($xmlDoc->createElement('occupation', $item->to_person_occupation));

                }
                // // Append the <t_entity> element to the <t_to_my_client> element
                $t_to_my_client->appendChild($to_person);
                if($item->to_country != '' || $item->to_country != null)
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_country', $item->to_country));
                }
                $transaction->appendChild($t_to_my_client);

                // Append the <transaction> element to the main XML document
                $root->appendChild($transaction);
            }

        }

        // Retrieve report indicators data from the database
        // dd($scenario_type);
        // \DB::enableQueryLog();
        $indicators = ScenarioTwo::select('report_indicator')
            ->whereBetween('date_transaction', [$from_date, $to_date])
            ->where('xml_gen_status', '=', 'N')
            ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
            ->where('scenario_type', $scenario_type)
            ->groupBy('report_indicator')
            ->get();
        // dd(\DB::getQueryLog());

        // dd($indicators);

        // Add report_indicators section
        $reportIndicators = $xmlDoc->createElement('report_indicators');

        foreach ($indicators as $indicator) {
            if($indicator->report_indicator != '' || $indicator->report_indicator != null)
            {
                $reportIndicators->appendChild($xmlDoc->createElement('indicator', $indicator->report_indicator));
            }
        }
        $root->appendChild($reportIndicators);

        // $data->update(['xml_gen_status' => 'Y']);

        // Save the XML to a file
        $fileName = 'files/xmlfile_' . time() . '_scenario_two.xml';
        $xmlDoc->save(storage_path('app/public/' . $fileName));
        $scenario_no = 2;

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file for Scenario 2 at ' . Carbon::now());
        \LogActivity::addToLogXMLGen('User ' . $user->id . ' created an XML file for Scenario 2 at ' . Carbon::now(),$from_date,$to_date,$fileName,$xml_type,$scenario_no,$xml_gen_status);

        // Download the XML file
        return response()->download(storage_path('app/public/' . $fileName));
    }

    public function arrayToXml($array, &$xml, $depth = 0) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)){
                    $subnode = $xml->addChild("$key");
                    $this->arrayToXml($value, $subnode, $depth + 1); // Change this line
                } else {
                    $this->arrayToXml($value, $xml, $depth + 1); // Change this line
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
            // Add newline characters based on the depth of the element
            $xml->addChild(str_repeat("\t", $depth + 1));
        }
    }

    public function downloadExcel($id)
    {
        $ID = decrypt($id);
        $log_data = LogXMLGenActivity::findOrFail($ID);

        $excel_data = ScenarioTwo::select('*')
            ->where('is_delete', '0')
            ->where('xml_gen_status', 'N')//need to change for Y
            ->where('scenario_type', $log_data->xml_type)
            ->whereBetween('date_transaction', [$log_data->from_date, $log_data->to_date])
            ->orderBy('id', 'asc')
            ->get();

        $filename = Carbon::now().'-scenario-two.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'scenario_type', 'rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference',
            'submission_date', 'currency_code_local', 'reason', 'transactionnumber', 'internal_ref_number', 'transaction_location',
            'transaction_description', 'date_transaction', 'value_date', 'transmode_code', 'amount_local',
            'from_funds_code', 'from_account_institution_name', 'from_account_swift', 'from_account_non_bank_institution',
            'from_account_branch', 'from_account_number', 'from_account_currency_code', 'from_account_personal_account_type',
            'from_entity_name', 'from_entity_incorporation_legal_form', 'from_entity_incorporation_number', 'from_entity_business',
            'from_entity_address_type', 'from_entity_address', 'from_entity_address_city', 'from_entity_address_country_code',
            'from_entity_incorporation_country_code', 'from_entity_director_gender', 'from_entity_director_title',
            'from_entity_director_first_name', 'from_entity_director_last_name', 'from_entity_director_birthdate',
            'from_entity_director_ssn', 'from_entity_director_nationality1', 'from_entity_director_residence',
            'from_entity_director_address_type', 'from_entity_director_address', 'from_entity_director_city',
            'from_entity_director_country_code', 'from_entity_director_occupation', 'from_entity_director_role',
            'from_account_status_code', 'from_country', 'to_funds_code', 'to_entity_name', 'to_entity_incorporation_legal_form',
            'to_entity_incorporation_number', 'to_entity_business', 'to_entity_address_type', 'to_entity_address', 'to_entity_address_city',
            'to_entity_address_country_code', 'to_entity_incorporation_country_code', 'to_entity_director_gender', 'to_entity_director_title',
            'to_entity_director_first_name', 'to_entity_director_last_name', 'to_entity_director_birthdate', 'to_entity_director_ssn',
            'to_entity_director_nationality1', 'to_entity_director_residence', 'to_entity_director_address_type', 'to_entity_director_address',
            'to_entity_director_city', 'to_entity_director_country_code', 'to_entity_director_occupation', 'to_entity_director_role',
            'to_country', 'report_indicator', 'from_account_signatory_is_primary', 'from_account_signatory_gender',
            'from_account_signatory_title', 'from_account_signatory_first_name', 'from_account_signatory_last_name',
            'from_account_signatory_birthdate', 'from_account_signatory_ssn', 'from_account_signatory_nationality1',
            'from_account_signatory_residence', 'from_account_signatory_address_type', 'from_account_signatory_address',
            'from_account_signatory_city', 'from_account_signatory_country_code', 'from_account_signatory_occupation',
            'from_account_signatory_role', 'to_person_gender', 'to_person_title', 'to_person_first_name', 'to_person_last_name',
            'to_person_birthdate', 'to_person_ssn', 'to_person_nationality1', 'to_person_residence', 'to_person_address_type',
            'to_person_address', 'to_person_city', 'to_person_country_code', 'to_person_occupation'
        ];


        $callback = function() use ($excel_data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($excel_data as $row) {
                fputcsv($file, [
                    $row->scenario_type,
                    $row->rentity_id,
                    $row->rentity_branch,
                    $row->submission_code,
                    $row->report_code,
                    $row->entity_reference,
                    $row->submission_date,
                    $row->currency_code_local,
                    $row->reason,
                    $row->transactionnumber,
                    $row->internal_ref_number,
                    $row->transaction_location,
                    $row->transaction_description,
                    $row->date_transaction,
                    $row->value_date,
                    $row->transmode_code,
                    $row->amount_local,
                    $row->from_funds_code,
                    $row->from_account_institution_name,
                    $row->from_account_swift,
                    $row->from_account_non_bank_institution,
                    $row->from_account_branch, $row->from_account_number,
                    $row->from_account_currency_code, $row->from_account_personal_account_type, $row->from_entity_name, $row->from_entity_incorporation_legal_form,
                    $row->from_entity_incorporation_number, $row->from_entity_business, $row->from_entity_address_type, $row->from_entity_address,
                    $row->from_entity_address_city, $row->from_entity_address_country_code, $row->from_entity_incorporation_country_code,
                    $row->from_entity_director_gender, $row->from_entity_director_title, $row->from_entity_director_first_name, $row->from_entity_director_last_name,
                    $row->from_entity_director_birthdate, $row->from_entity_director_ssn, $row->from_entity_director_nationality1, $row->from_entity_director_residence,
                    $row->from_entity_director_address_type, $row->from_entity_director_address, $row->from_entity_director_city,
                    $row->from_entity_director_country_code, $row->from_entity_director_occupation, $row->from_entity_director_role,
                    $row->from_account_status_code, $row->from_country, $row->to_funds_code, $row->to_entity_name, $row->to_entity_incorporation_legal_form,
                    $row->to_entity_incorporation_number, $row->to_entity_business, $row->to_entity_address_type, $row->to_entity_address, $row->to_entity_city,
                    $row->to_entity_address_country_code, $row->to_entity_incorporation_country_code, $row->to_entity_director_gender, $row->to_entity_director_title,
                    $row->to_entity_director_first_name, $row->to_entity_director_last_name, $row->to_entity_director_birthdate, $row->to_entity_director_ssn,
                    $row->to_entity_director_nationality1, $row->to_entity_director_residence, $row->to_entity_director_address_type, $row->to_entity_director_address,
                    $row->to_entity_director_city, $row->to_entity_director_country_code, $row->to_entity_director_occupation, $row->to_entity_director_role,
                    $row->to_country, $row->report_indicator, $row->from_account_signatory_is_primary, $row->from_account_signatory_gender,
                    $row->from_account_signatory_title, $row->from_account_signatory_first_name, $row->from_account_signatory_last_name,
                    $row->from_account_signatory_birthdate, $row->from_account_signatory_ssn, $row->from_account_signatory_nationality1,
                    $row->from_account_signatory_residence, $row->from_account_signatory_address_type, $row->from_account_signatory_address,
                    $row->from_account_signatory_city, $row->from_account_signatory_country_code, $row->from_account_signatory_occupation,
                    $row->from_account_signatory_role, $row->to_person_gender, $row->to_person_title, $row->to_person_first_name, $row->to_person_last_name,
                    $row->to_person_birthdate, $row->to_person_ssn, $row->to_person_nationality1, $row->to_person_residence, $row->to_person_address_type,
                    $row->to_person_address, $row->to_person_city, $row->to_person_country_code, $row->to_person_occupation
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
