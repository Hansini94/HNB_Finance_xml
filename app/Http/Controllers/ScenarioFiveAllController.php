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

use App\Models\ScenarioFive;
use App\Models\EmployeeDetail;
use App\Models\LogXMLGenActivity;

class ScenarioFiveAllController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:scenario-five-all-list|scenario-five-all-edit|scenario-five-all-delete', ['only' => ['list']]);
        $this->middleware('permission:scenario-five-all-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:scenario-five-all-delete', ['only' => ['destroy']]);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = LogXMLGenActivity::select('*')->where('scenario_no',5)->orderBy('created_at', 'desc')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('excel', function ($row) {
                    $download_url = url('download-excel-five/' . encrypt($row->id));
                    return '<a href="' . $download_url . '">Download Excel</a>';
                })
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/scenario-five-edit/' . encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                 })

                 ->rawColumns(['excel','edit'])
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_five.all.list');
    }

    public function datalist(Request $request, $id)
    {
        $ID = decrypt($id);
        $log_data = LogXMLGenActivity::select('*')->where('id', $ID)->where('scenario_no',5)->first();
        $from_date = $log_data->from_date;
        $to_date = $log_data->to_date;
        $xml_type = $log_data->xml_type;

        if ($request->ajax()) {
            $data = ScenarioFive::select('*')->where('is_delete', '0')->where('xml_gen_status', 'N')->whereBetween('date_transaction', [$log_data->from_date, $log_data->to_date])->orderBy('id', 'asc')->get(); // xml_gen_status', 'N' => Y
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-scenario-five-all/' . encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                 })
                 ->addColumn('activation', function($row){
                     if ( $row->status == "Y" )
                         $status ='fa fa-check';
                     else
                         $status ='fa fa-remove';
                     $btn = '<a href="changestatus-scenario-five-all/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';
                     return $btn;
                 })
                 ->addColumn('blockscenariofive', 'adminpanel.generate_xml.scenario_five.all.actionsBlock')
                 ->rawColumns(['edit','activation','blockscenariofive'])
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_five.all.datalist', ['log_id' => $id , 'from_date' => $from_date , 'to_date' => $to_date , 'xml_type' => $xml_type]);
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
        $info = ScenarioFive::where('id', '=', $ID)->first();


        return view('adminpanel.generate_xml.scenario_five.all.edit', ['data' => $info]);
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

        $data = ScenarioFive::find($id);
        $data->update($input);

        $id = $data->id;


        \LogActivity::addToLog('Scenario Five Record updated('.$id.').');

        return redirect()->route('scenario-five-all-list')->with('success', 'Record updated successfully.');
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
        $data =  ScenarioFive::find($request->id);

        if ( $data->status == "Y" ) {

            $data->status = 'N';
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Scenario Five Record deactivated('.$id.').');

            return redirect()->route('scenario-five-all-list')
            ->with('success', 'Record deactivate successfully.');

        } else {

            $data->status = "Y";
            $data->save();
            $id = $data->id;

            \LogActivity::addToLog('Scenario Five Record activated('.$id.').');

            return redirect()->route('scenario-five-all-list')
            ->with('success', 'Record activate successfully.');
        }

    }

    public function block(Request $request)
    {
        $request->validate([
            // 'status' => 'required'
        ]);

        $data =  ScenarioFive::find($request->id);
        $data->is_delete = 1;
        $data->save();
        $id = $data->id;

        \LogActivity::addToLog('Scenario Five Record deleted('.$id.').');

        return redirect()->route('scenario-five-all-list')
            ->with('success', 'Record deleted successfully.');
    }

    public function generate_xml()
    {
        $scenario_type = request('scenario_type');
        $from_date = request('from_date');
        $to_date = request('to_date');
        $xml_type = '';
        $xml_gen_status=1; //new generation 1 , old data generation 2
        // dd($scenario_type);
        // Retrieve data from the database
        // $data = ScenarioFive::whereBetween('created_at', [$from_date, $to_date])->where('xml_gen_status', '=', 'N')->where('scenario_type',$scenario_type)
         //   ->groupBy('rentity_id')
           // ->first();

        $data = ScenarioFive::select('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
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
            $trans = ScenarioFive::whereBetween('date_transaction', [$from_date, $to_date])
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
                $transaction->appendChild($xmlDoc->createElement('transaction_location', $item->transaction_location));
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
                if($item->from_account_account !== null && $item->from_account_account !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_account));
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

                if($item->from_account_name !== null && $item->from_account_name !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('name', $item->from_account_name));
                }
                if($item->from_account_incorporation_legal_form !== null && $item->from_account_incorporation_legal_form !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_legal_form', $item->from_account_incorporation_legal_form));
                }
                if($item->from_account_incorporation_number !== null && $item->from_account_incorporation_number !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_number', $item->from_account_incorporation_number));
                }
                if($item->from_account_business !== null && $item->from_account_business !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('business', $item->from_account_business));
                }

                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->from_account_address_type !== null && $item->from_account_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->from_account_address_type));
                        }
                        if($item->from_account_address !== null && $item->from_account_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->from_account_address));
                        }
                        if($item->from_account_city !== null && $item->from_account_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->from_account_city));
                        }
                        if($item->from_account_country_code !== null && $item->from_account_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->from_account_country_code));
                        }
                    $addresses->appendChild($address);
                $t_entity->appendChild($addresses);

                if($item->from_account_incorporation_country_code !== null && $item->from_account_incorporation_country_code !== '')
                {
                    $t_entity->appendChild($xmlDoc->createElement('incorporation_country_code', $item->from_account_incorporation_country_code));
                }

                $director_id = $xmlDoc->createElement('director_id');

                if($item->from_account_director_gender !== null && $item->from_account_director_gender !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('gender', $item->from_account_director_gender));
                }
                if($item->from_account_director_title !== null && $item->from_account_director_title !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('title', $item->from_account_director_title));
                }
                if($item->from_account_director_first_name !== null && $item->from_account_director_first_name !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('first_name', $item->from_account_director_first_name));
                }
                if($item->from_account_director_last_name !== null && $item->from_account_director_last_name !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('last_name', $item->from_account_director_last_name));
                }
                if($item->from_account_director_birthdate !== null && $item->from_account_director_birthdate !== '')
                {
                    $dateofbirth = new DateTime($item->from_account_director_birthdate);
                    $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                    $director_id->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                }
                if($item->from_account_director_ssn !== null && $item->from_account_director_ssn !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('ssn', $item->from_account_director_ssn));
                }
                if($item->from_account_director_passport_number !== null && $item->from_account_director_passport_number !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('passport_number', $item->from_account_director_passport_number));
                }
                if($item->from_account_director_passport_country !== null && $item->from_account_director_passport_country !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('passport_country', $item->from_account_director_passport_country));
                }
                if($item->from_account_director_nationality1 !== null && $item->from_account_director_nationality1 !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('nationality1', $item->from_account_director_nationality1));
                }
                if($item->from_account_director_residence !== null && $item->from_account_director_residence !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('residence', $item->from_account_director_residence));
                }
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->from_account_director_address_type !== null && $item->from_account_director_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->from_account_director_address_type));
                        }
                        if($item->from_account_director_address !== null && $item->from_account_director_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->from_account_director_address));
                        }
                        if($item->from_account_director_city !== null && $item->from_account_director_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->from_account_director_city));
                        }
                        if($item->from_account_director_country_code !== null && $item->from_account_director_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->from_account_director_country_code));
                        }
                    $addresses->appendChild($address);
                $director_id->appendChild($addresses);

                if($item->from_account_director_occupation !== null && $item->from_account_director_occupation !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('occupation', $item->from_account_director_occupation));
                }
                if($item->from_account_director_role !== null && $item->from_account_director_role !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('role', $item->from_account_director_role));
                }

                $from_account->appendChild($t_entity);

                if($item->status_code !== null && $item->status_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('status_code', $item->status_code));
                }

                // Append the <from_person> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_account);

                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from_my_client->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }

                $transaction->appendChild($t_from_my_client);

                // ******************** to_my_client *******************************

                // Create a <t_to> element
                $t_to = $xmlDoc->createElement('t_to');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }
                // Create a <from_entity> element
                $to_account = $xmlDoc->createElement('to_account');

                $to_account->appendChild($xmlDoc->createElement('institution_name', $item->to_account_institution_name));
                $to_account->appendChild($xmlDoc->createElement('swift', $item->to_account_swift));
                $to_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->to_account_non_bank_institution));
                $to_account->appendChild($xmlDoc->createElement('account', $item->to_account_account));
                $to_account->appendChild($xmlDoc->createElement('currency_code', $item->to_account_currency_code));

                // // Append the <t_entity> element to the <t_to> element
                $t_to->appendChild($to_account);

                if($item->to_country !== null && $item->to_country !== '')
                {
                    $t_to->appendChild($xmlDoc->createElement('to_country', $item->to_country));
                }

                $transaction->appendChild($t_to);

                // Append the <transaction> element to the main XML document
                $root->appendChild($transaction);
            }

        }
        else
        {
            $xml_type = 'Person';
            $trans = ScenarioFive::whereBetween('date_transaction', [$from_date, $to_date])
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
                $transaction->appendChild($xmlDoc->createElement('transaction_location', $item->transaction_location));
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
                $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_account));
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

                if($item->status_code !== null && $item->status_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('status_code', $item->status_code));
                }

                $t_from_my_client->appendChild($from_account);


                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from_my_client->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }

                $transaction->appendChild($t_from_my_client);


                // ******************** to_my_client *******************************

                // Create a <t_to> element
                $t_to = $xmlDoc->createElement('t_to');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }

                $to_account = $xmlDoc->createElement('to_account');

                if($item->to_account_institution_name !== null && $item->to_account_institution_name !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('institution_name', $item->to_account_institution_name));
                }
                if($item->to_account_swift !== null && $item->to_account_swift !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('swift', $item->to_account_swift));
                }
                if($item->to_account_non_bank_institution !== null && $item->to_account_non_bank_institution !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->to_account_non_bank_institution));
                }
                if($item->to_account_account !== null && $item->to_account_account !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('account', $item->to_account_account));
                }
                if($item->to_account_currency_code !== null && $item->to_account_currency_code !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('currency_code', $item->to_account_currency_code));
                }
                // // Append the <t_entity> element to the <t_to> element
                $t_to->appendChild($to_account);
                if($item->to_country != '' || $item->to_country != null)
                {
                    $t_to->appendChild($xmlDoc->createElement('to_country', $item->to_country));
                }
                $transaction->appendChild($t_to);

                // Append the <transaction> element to the main XML document
                $root->appendChild($transaction);
            }

        }

        // Retrieve report indicators data from the database
        // dd($scenario_type);
        // \DB::enableQueryLog();
        $indicators = ScenarioFive::select('report_indicator')
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
        $fileName = 'files/xmlfile_' . time() . '_scenario_five.xml';
        $xmlDoc->save(storage_path('app/public/' . $fileName));
        $scenario_no = 5;

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file for Scenario 5 at ' . Carbon::now());
        \LogActivity::addToLogXMLGen('User ' . $user->id . ' created an XML file for Scenario 5 at ' . Carbon::now(),$from_date,$to_date,$fileName,$xml_type,$scenario_no,$xml_gen_status);

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

        $excel_data = ScenarioFive::select('*')
            ->where('is_delete', '0')
            ->where('xml_gen_status', 'N')//need to change for Y
            ->where('scenario_type', $log_data->xml_type)
            ->whereBetween('date_transaction', [$log_data->from_date, $log_data->to_date])
            ->orderBy('id', 'asc')
            ->get();

        $filename = Carbon::now().'-scenario-five.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'scenario_type', 'rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference',
            'submission_date', 'currency_code_local', 'transactionnumber', 'internal_ref_number', 'transaction_location',
            'transaction_description', 'date_transaction', 'value_date', 'transmode_code', 'amount_local',
            'from_funds_code', 'from_account_institution_name', 'from_account_swift', 'from_account_non_bank_institution',
            'from_account_branch', 'from_account_account', 'from_account_currency_code', 'from_account_personal_account_type',
            'from_account_name', 'from_account_incorporation_legal_form', 'from_account_incorporation_number',
            'from_account_business', 'from_account_address_type', 'from_account_address', 'from_account_city',
            'from_account_country_code', 'from_account_incorporation_country_code', 'from_account_director_gender',
            'from_account_director_title', 'from_account_director_first_name', 'from_account_director_last_name',
            'from_account_director_birthdate', 'from_account_director_ssn', 'from_account_director_passport_number',
            'from_account_director_passport_country', 'from_account_director_nationality1', 'from_account_director_residence',
            'from_account_director_address_type', 'from_account_director_address', 'from_account_director_city',
            'from_account_director_country_code', 'from_account_director_occupation', 'from_account_director_role',
            'status_code', 'from_country', 'to_funds_code', 'to_account_institution_name', 'to_account_swift',
            'to_account_non_bank_institution', 'to_account_account', 'to_account_currency_code', 'to_country',
            'from_account_signatory_is_primary', 'from_account_signatory_gender', 'from_account_signatory_title',
            'from_account_signatory_first_name', 'from_account_signatory_last_name', 'from_account_signatory_birthdate',
            'from_account_signatory_ssn', 'from_account_signatory_nationality1', 'from_account_signatory_residence',
            'from_account_signatory_address_type', 'from_account_signatory_address', 'from_account_signatory_city',
            'from_account_signatory_country_code', 'from_account_signatory_occupation', 'from_account_signatory_role',
            'report_indicator'
        ];


        $callback = function() use ($excel_data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($excel_data as $row) {
                fputcsv($file, [
                    $row->scenario_type, $row->rentity_id, $row->rentity_branch, $row->submission_code, $row->report_code,
                    $row->entity_reference, $row->submission_date, $row->currency_code_local, $row->transactionnumber,
                    $row->internal_ref_number, $row->transaction_location, $row->transaction_description, $row->date_transaction,
                    $row->value_date, $row->transmode_code, $row->amount_local, $row->from_funds_code, $row->from_account_institution_name,
                    $row->from_account_swift, $row->from_account_non_bank_institution, $row->from_account_branch,
                    $row->from_account_account, $row->from_account_currency_code, $row->from_account_personal_account_type,
                    $row->from_account_name, $row->from_account_incorporation_legal_form, $row->from_account_incorporation_number,
                    $row->from_account_business, $row->from_account_address_type, $row->from_account_address, $row->from_account_city,
                    $row->from_account_country_code, $row->from_account_incorporation_country_code, $row->from_account_director_gender,
                    $row->from_account_director_title, $row->from_account_director_first_name, $row->from_account_director_last_name,
                    $row->from_account_director_birthdate, $row->from_account_director_ssn, $row->from_account_director_passport_number,
                    $row->from_account_director_passport_country, $row->from_account_director_nationality1, $row->from_account_director_residence,
                    $row->from_account_director_address_type, $row->from_account_director_address, $row->from_account_director_city,
                    $row->from_account_director_country_code, $row->from_account_director_occupation, $row->from_account_director_role,
                    $row->status_code, $row->from_country, $row->to_funds_code, $row->to_account_institution_name, $row->to_account_swift,
                    $row->to_account_non_bank_institution, $row->to_account_account, $row->to_account_currency_code, $row->to_country,
                    $row->from_account_signatory_is_primary, $row->from_account_signatory_gender, $row->from_account_signatory_title,
                    $row->from_account_signatory_first_name, $row->from_account_signatory_last_name, $row->from_account_signatory_birthdate,
                    $row->from_account_signatory_ssn, $row->from_account_signatory_nationality1, $row->from_account_signatory_residence,
                    $row->from_account_signatory_address_type, $row->from_account_signatory_address, $row->from_account_signatory_city,
                    $row->from_account_signatory_country_code, $row->from_account_signatory_occupation, $row->from_account_signatory_role,
                    $row->report_indicator
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
