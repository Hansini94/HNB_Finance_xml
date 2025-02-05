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

use App\Models\ScenarioOne;
use App\Models\EmployeeDetail;

class ScenarioOneController extends Controller
{
    //
    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = ScenarioOne::select('*')->where('is_delete', '0')->where('status', 'Y')->orderBy('id', 'DESC')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_one.list');
    }


    public function generate_xml()
    {

 // Create the XML structure
 $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><report></report>");




        $from_date = request('from_date');
        $to_date = request('to_date');

        // Retrieve data from the database
        $data = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])
            ->groupBy('rentity_id', 'id')
            ->first();

            // dd($data);

        $trans = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])
            ->orderBy('created_at', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);


        $employee = EmployeeDetail::first();
        // dd($employee);

        // Retrieve the current logged-in user
        $user = Auth::user();

        // Create the XML structure
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><report></report>");

        // Add static information
        $reportInfo = $xml->addChild('report_info');
        $reportInfo->addChild('rentity_id', $data->rentity_id);
        $reportInfo->addChild('rentity_branch', $data->rentity_branch);
        $reportInfo->addChild('submission_code', $data->submission_code);
        $reportInfo->addChild('report_code', $data->report_code);
        $reportInfo->addChild('entity_reference', $data->entity_reference);
        $reportInfo->addChild('fiu_ref_number', $data->fiu_ref_number);
        $reportInfo->addChild('submission_date', $data->submission_date);
        $reportInfo->addChild('currency_code_local', $data->currency_code_local);

        // Add reporting person details
        $reportingPerson = $xml->addChild('reporting_person');
        $reportingPerson->addChild('gender', $user->gender);
        $reportingPerson->addChild('title', $user->title);
        $reportingPerson->addChild('first_name', $user->first_name);
        $reportingPerson->addChild('middle_name', $user->middle_name);
        $reportingPerson->addChild('prefix', $user->prefix);
        $reportingPerson->addChild('last_name', $user->last_name);
        $reportingPerson->addChild('birthdate', $user->birthdate);
        $reportingPerson->addChild('birth_place', $user->birth_place);
        $reportingPerson->addChild('mothers_name', $user->mothers_name);
        $reportingPerson->addChild('alias', $user->alias);
        $reportingPerson->addChild('ssn', $user->ssn);
        $reportingPerson->addChild('passport_number', $user->passport_number);
        $reportingPerson->addChild('passport_country', $user->passport_country);
        $reportingPerson->addChild('id_number', $user->id_number);
        $reportingPerson->addChild('nationality1', $user->nationality1);
        $reportingPerson->addChild('nationality2', $user->nationality2);
        $reportingPerson->addChild('nationality3', $user->nationality3);
        $reportingPerson->addChild('residence', $user->residence);
        $reportingPerson->addChild('phones', $user->phones);
        $reportingPerson->addChild('address_type', $user->address_type);
        $reportingPerson->addChild('address', $user->address);
        $reportingPerson->addChild('city', $user->city);
        $reportingPerson->addChild('country_code', $user->country_code);
        $reportingPerson->addChild('email', $user->email);
        $reportingPerson->addChild('occupation', $user->occupation);
        $reportingPerson->addChild('employer_name', $employee->employer_name);

        // Add reporting employee details
        $employer_address_id = $xml->addChild('employer_address_id');
        $employer_address_id->addChild('address_type', $employee->address_type);
        $employer_address_id->addChild('address', $employee->address);
        $employer_address_id->addChild('town', $employee->town);
        $employer_address_id->addChild('city', $employee->city);
        $employer_address_id->addChild('zip', $employee->zip);
        $employer_address_id->addChild('country_code', $employee->country_code);
        $employer_address_id->addChild('state', $employee->state);
        $employer_address_id->addChild('comments', $employee->comments);

        $employer_phone_id = $xml->addChild('employer_phone_id');
        $employer_phone_id->addChild('tph_contact_type', $employee->tph_contact_type);
        $employer_phone_id->addChild('tph_communication_type', $employee->tph_communication_type);
        $employer_phone_id->addChild('tph_number', $employee->tph_number);
        $employer_phone_id->addChild('tph_extension', $employee->tph_extension);
        $employer_phone_id->addChild('comments', $employee->employer_phone_id_comments);

        $identification = $xml->addChild('identification');
        $identification->addChild('type', $employee->identification_type);
        $identification->addChild('number', $employee->identification_number);
        $identification->addChild('issue_date', $employee->identification_issue_date);
        $identification->addChild('issued_by', $employee->identification_issued_by);
        $identification->addChild('issue_country', $employee->identification_issue_country);
        $identification->addChild('comments', $employee->identification_comments);

        $reportingPerson->addChild('deceased', $user->deceased);
        $reportingPerson->addChild('deceased_date', $user->deceased_date);
        $reportingPerson->addChild('tax_number', $user->tax_number);
        $reportingPerson->addChild('tax_reg_numebr', $user->tax_reg_numebr);
        $reportingPerson->addChild('source_of_wealth', $user->source_of_wealth);
        $reportingPerson->addChild('comments', $user->comments);

        // Add other user details as needed

        // Add transaction details
        $transactions = $xml->addChild('transactions');
        foreach ($trans as $item) {
            $transaction = $transactions->addChild('transaction');
            // Add transaction details from $item
            $transaction->addChild('transaction_number', $item->transaction_number);
            // Add other transaction details as needed
        }

        // Add report indicators
        $reportIndicators = $xml->addChild('report_indicators');
        // Retrieve report indicators data from the database
        $indicators = ScenarioOne::pluck('report_indicator')->toArray();
        foreach ($indicators as $indicator) {
            $reportIndicators->addChild('indicator', $indicator);
        }

        // Save the XML to a file
        $fileName = 'files/xmlfile_' . time() . '.xml';
        $xml->asXML(storage_path('app/public/' . $fileName));

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file at ' . Carbon::now());

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

    // public function arrayToXml($array, &$xml) {
    //     foreach ($array as $key => $value) {
    //         if (is_array($value)) {
    //             if (!is_numeric($key)){
    //                 $subnode = $xml->addChild("$key");
    //                 $this->arrayToXml($value, $subnode); // Change this line
    //             } else {
    //                 $this->arrayToXml($value, $xml); // Change this line
    //             }
    //         } else {
    //             $xml->addChild("$key", htmlspecialchars("$value"));
    //         }
    //     }
    // }





    public function all_list(Request $request)
    {
        if ($request->ajax()) {
            $data = ScenarioOne::select('*')->where('is_delete', '0')->where('status', 'Y')->orderBy('id', 'asc')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $edit_url = url('/edit-scenario-one/' . encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                 })
                 ->addColumn('activation', function($row){
                     if ( $row->status == "Y" )
                         $status ='fa fa-check';
                     else
                         $status ='fa fa-remove';
                     $btn = '<a href="changestatus-scenario-one/'.$row->id.'/'.$row->cEnable.'"><i class="'.$status.'"></i></a>';
                     return $btn;
                 })
                 ->addColumn('blockscenarioone', 'adminpanel.generate_xml.scenario_one.actionsBlock')
                 ->rawColumns(['edit','activation','blockscenarioone'])
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_one.all_list');
    }
}


        // Add transaction details
        $transactions = $xmlDoc->createElement('transactions');

    // dd($trans);
    foreach ($trans as $item) {
        // Convert the Eloquent model to an array, excluding the metadata fields
        $data = $item->toArray();

        // Create a <transaction> element
        $transaction = $xmlDoc->createElement('transaction');

        // Iterate over each column name and its corresponding value in the $data array
        foreach ($data as $key => $value) {
            // Create a new element with the column name as the tag name (key)
            // and the corresponding value from the database as the tag value
            $transaction->appendChild($xmlDoc->createElement($key, $value));
        }

        // Append the <transaction> element to the <transactions> element
        $transactions->appendChild($transaction);
}

$root->appendChild($transactions);

///////20240429
// Add reporting_person section
$reportingPerson = $xmlDoc->createElement('reporting_person');
$reportingPerson->appendChild($xmlDoc->createElement('gender', $user->gender));
$reportingPerson->appendChild($xmlDoc->createElement('title', $user->title));
$reportingPerson->appendChild($xmlDoc->createElement('first_name', $user->first_name));
$reportingPerson->appendChild($xmlDoc->createElement('middle_name', $user->middle_name));
$reportingPerson->appendChild($xmlDoc->createElement('prefix', $user->prefix));
$reportingPerson->appendChild($xmlDoc->createElement('last_name', $user->last_name));
$reportingPerson->appendChild($xmlDoc->createElement('birthdate', $user->birthdate));
$reportingPerson->appendChild($xmlDoc->createElement('birth_place', $user->birth_place));
$reportingPerson->appendChild($xmlDoc->createElement('mothers_name', $user->mothers_name));
$reportingPerson->appendChild($xmlDoc->createElement('alias', $user->alias));
$reportingPerson->appendChild($xmlDoc->createElement('ssn', $user->ssn));
$reportingPerson->appendChild($xmlDoc->createElement('passport_number', $user->passport_number));
$reportingPerson->appendChild($xmlDoc->createElement('passport_country', $user->passport_country));
$reportingPerson->appendChild($xmlDoc->createElement('id_number', $user->id_number));
$reportingPerson->appendChild($xmlDoc->createElement('nationality1', $user->nationality1));
$reportingPerson->appendChild($xmlDoc->createElement('nationality2', $user->nationality2));
$reportingPerson->appendChild($xmlDoc->createElement('nationality3', $user->nationality3));
$reportingPerson->appendChild($xmlDoc->createElement('residence', $user->residence));
$reportingPerson->appendChild($xmlDoc->createElement('phones', $user->phones));
$reportingPerson->appendChild($xmlDoc->createElement('address_type', $user->address_type));
$reportingPerson->appendChild($xmlDoc->createElement('address', $user->address));
$reportingPerson->appendChild($xmlDoc->createElement('city', $user->city));
$reportingPerson->appendChild($xmlDoc->createElement('country_code', $user->country_code));
$reportingPerson->appendChild($xmlDoc->createElement('email', $user->email));
$reportingPerson->appendChild($xmlDoc->createElement('occupation', $user->occupation));

$reportingPerson->appendChild($xmlDoc->createElement('employer_name', $employee->employer_name));

$reportingPerson->appendChild($xmlDoc->createElement('deceased', $user->deceased));
$reportingPerson->appendChild($xmlDoc->createElement('deceased_date', $user->deceased_date));
$reportingPerson->appendChild($xmlDoc->createElement('tax_number', $user->tax_number));
$reportingPerson->appendChild($xmlDoc->createElement('tax_reg_numebr', $user->tax_reg_numebr));
$reportingPerson->appendChild($xmlDoc->createElement('source_of_wealth', $user->source_of_wealth));
$reportingPerson->appendChild($xmlDoc->createElement('comments', $user->comments));

$root->appendChild($reportingPerson);


// Add employer_address_id section
$employer_address_id = $xmlDoc->createElement('employer_address_id');
$employer_address_id->appendChild($xmlDoc->createElement('address_type', $employee->address_type));
$employer_address_id->appendChild($xmlDoc->createElement('address', $employee->address));
$employer_address_id->appendChild($xmlDoc->createElement('town', $employee->town));
$employer_address_id->appendChild($xmlDoc->createElement('city', $employee->city));
$employer_address_id->appendChild($xmlDoc->createElement('zip', $employee->zip));
$employer_address_id->appendChild($xmlDoc->createElement('country_code', $employee->country_code));
$employer_address_id->appendChild($xmlDoc->createElement('state', $employee->state));
$employer_address_id->appendChild($xmlDoc->createElement('comments', $employee->comments));

$root->appendChild($employer_address_id);

// Add employer_phone_id section
$employer_phone_id = $xmlDoc->createElement('employer_phone_id');
$employer_phone_id->appendChild($xmlDoc->createElement('tph_contact_type', $employee->tph_contact_type));
$employer_phone_id->appendChild($xmlDoc->createElement('tph_communication_type', $employee->tph_communication_type));
$employer_phone_id->appendChild($xmlDoc->createElement('tph_number', $employee->tph_number));
$employer_phone_id->appendChild($xmlDoc->createElement('tph_extension', $employee->tph_extension));
$employer_phone_id->appendChild($xmlDoc->createElement('comments', $employee->employer_phone_id_comments));

$root->appendChild($employer_phone_id);

// Add identification section
$identification = $xmlDoc->createElement('identification');
$identification->appendChild($xmlDoc->createElement('type', $employee->identification_type));
$identification->appendChild($xmlDoc->createElement('number', $employee->identification_number));
$identification->appendChild($xmlDoc->createElement('issue_date', $employee->identification_issue_date));
$identification->appendChild($xmlDoc->createElement('issued_by', $employee->identification_issued_by));
$identification->appendChild($xmlDoc->createElement('issue_country', $employee->identification_issue_country));
$identification->appendChild($xmlDoc->createElement('comments', $employee->identification_comments));

$root->appendChild($identification);

//////////
// Iterate over each column name and its corresponding value in the $data array
foreach ($data_item as $key => $value) {
    // Handle special cases for nested elements
    switch ($key) {
        case 'from_person_gender':
            $from_person->appendChild($xmlDoc->createElement('gender', $value));
            break;
        case 'from_person_title':
            $from_person->appendChild($xmlDoc->createElement('title', $value));
            break;
        case 'from_person_first_name':
            $from_person->appendChild($xmlDoc->createElement('first_name', $value));
            break;
        case 'from_person_last_name':
            $from_person->appendChild($xmlDoc->createElement('last_name', $value));
            break;
        case 'from_person_birthdate':
            $dateofbirth = new DateTime($value);
            $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
            $from_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
            break;
        case 'from_person_ssn':
            $from_person->appendChild($xmlDoc->createElement('ssn', $value));
            break;
        case 'from_person_nationality1':
            if($value !== null)
            {
                $from_person->appendChild($xmlDoc->createElement('nationality1', $value));
            }
            break;
        case 'from_person_residence':
            $from_person->appendChild($xmlDoc->createElement('residence', $value));
            break;
        case 'from_person_address_type':
        case 'from_person_address':
        case 'from_person_city':
        case 'from_person_country_code':
            // Append address detail to the single address element
            $address->appendChild($xmlDoc->createElement(str_replace('from_person_', '', $key), $value));
            // Append the single address element to addresses
            $addresses->appendChild($address);
            break;
        case 'from_person_occupation':
            $from_person->appendChild($xmlDoc->createElement('occupation', $value));
            break;
    }

}


/////////////////////////////////////////


if($scenario_type == 'Entity')
        {
            $xml_type = 'Entity';
            $trans = ScenarioOne::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('account_type','Entity')
            ->orderBy('date_transaction', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Iterate over each transaction
            foreach ($trans as $item) {
                $transaction->appendChild($xmlDoc->createElement('transactionnumber', $data->transactionnumber));
                $transaction->appendChild($xmlDoc->createElement('internal_ref_number', $data->internal_ref_number));
                $transaction->appendChild($xmlDoc->createElement('transaction_location', $data->transaction_location));
                $transaction->appendChild($xmlDoc->createElement('transaction_description', $data->transaction_description));
                $transaction->appendChild($xmlDoc->createElement('date_transaction', $data->date_transaction));
                $transaction->appendChild($xmlDoc->createElement('value_date', $data->value_date));
                $transaction->appendChild($xmlDoc->createElement('transmode_code', $data->transmode_code));
                $transaction->appendChild($xmlDoc->createElement('amount_local', $data->amount_local));

                // ******************** from_my_client *******************************
                // $root->appendChild($reportInfo);
                // Create a <t_from_my_client> element
                $t_from_my_client = $xmlDoc->createElement('t_from_my_client');

                $t_from_my_client->appendChild($xmlDoc->createElement('from_funds_code', $data->from_funds_code));

                // Convert the Eloquent model to an array, excluding the metadata fields
                $data = $item->toArray();

                // Create a <from_entity> element
                $from_entity = $xmlDoc->createElement('from_entity');

                // Iterate over each column name and its corresponding value in the $data array
                foreach ($data as $key => $value) {
                    // Handle special cases for nested elements
                    switch ($key) {
                        case 'from_entity_name':
                            $from_entity->appendChild($xmlDoc->createElement('name', $value));
                            break;
                        case 'from_entity_incorporation_legal_form':
                            $from_entity->appendChild($xmlDoc->createElement('incorporation_legal_form', $value));
                            break;
                        case 'from_entity_incorporation_number':
                            $from_entity->appendChild($xmlDoc->createElement('incorporation_number', $value));
                            break;
                        case 'from_entity_business':
                            $from_entity->appendChild($xmlDoc->createElement('business', $value));
                            break;
                        case 'from_entity_address_type':
                        case 'from_entity_address':
                        case 'from_entity_address_city':
                        case 'from_entity_address_country_code':
                            // Check if addresses element already exists, if not create it
                            if (!$from_entity->getElementsByTagName('addresses')->length) {
                                $addresses = $xmlDoc->createElement('addresses');
                                $from_entity->appendChild($addresses);
                            }
                            // Append address element to addresses
                            $address = $xmlDoc->createElement('address');
                            $address->appendChild($xmlDoc->createElement($key, $value));
                            $addresses->appendChild($address);
                            break;
                        case 'from_entity_incorporation_country_code':
                            $from_entity->appendChild($xmlDoc->createElement('incorporation_country_code', $value));
                            break;
                        // After incorporation_country_code, add the director_id section
                        case 'from_entity_director_gender':
                        case 'from_entity_director_title':
                        case 'from_entity_director_first_name':
                        case 'from_entity_director_last_name':
                        case 'from_entity_director_birthdate':
                        case 'from_entity_director_ssn':
                        case 'from_entity_director_passport_number':
                        case 'from_entity_director_passport_country':
                        case 'from_entity_director_nationality1':
                        case 'from_entity_director_residence':
                        case 'from_entity_director_address_type':
                        case 'from_entity_director_address':
                        case 'from_entity_director_city':
                        case 'from_entity_director_country_code':
                        case 'from_entity_director_occupation':
                        case 'from_entity_director_role':
                            // Create a <director_id> element
                            $director_id = $xmlDoc->createElement('director_id');
                            // Append director details
                            $director_id->appendChild($xmlDoc->createElement('gender', $value));
                            $director_id->appendChild($xmlDoc->createElement('title', $value));
                            $director_id->appendChild($xmlDoc->createElement('first_name', $value));
                            $director_id->appendChild($xmlDoc->createElement('last_name', $value));
                            $director_id->appendChild($xmlDoc->createElement('birthdate', $value));
                            $director_id->appendChild($xmlDoc->createElement('ssn', $value));
                            $director_id->appendChild($xmlDoc->createElement('passport_number', $value));
                            $director_id->appendChild($xmlDoc->createElement('passport_country', $value));
                            $director_id->appendChild($xmlDoc->createElement('nationality1', $value));
                            $director_id->appendChild($xmlDoc->createElement('residence', $value));
                            // Check if addresses element already exists, if not create it
                            if (!$from_entity->getElementsByTagName('addresses')->length) {
                                $addresses = $xmlDoc->createElement('addresses');
                                $from_entity->appendChild($addresses);
                            }
                            // Append address element to addresses
                            $address = $xmlDoc->createElement('address');
                            $address->appendChild($xmlDoc->createElement($key, $value));
                            $addresses->appendChild($address);
                            $director_id->appendChild($addresses);
                            $director_id->appendChild($xmlDoc->createElement('occupation', $value));
                            $director_id->appendChild($xmlDoc->createElement('role', $value));
                            // Add other director details...
                            // Append director_id to from_entity
                            $from_entity->appendChild($director_id);
                            break;
                        default:
                            $t_from_my_client->appendChild($xmlDoc->createElement($key, $value));
                            break;
                    }
                }
                // Append the <from_entity> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_entity);

                // ******************** to_my_client *******************************

                // Create a <t_to_my_client> element
                $t_to_my_client = $xmlDoc->createElement('t_to_my_client');

                $t_to_my_client->appendChild($xmlDoc->createElement('to_funds_code', $data->to_funds_code));

                // Convert the Eloquent model to an array, excluding the metadata fields
                $data = $item->toArray();

                // Create a <from_entity> element
                $to_account = $xmlDoc->createElement('to_account');

                $to_account->appendChild($xmlDoc->createElement('institution_name', $data->to_account_institution_name));
                $to_account->appendChild($xmlDoc->createElement('swift', $data->to_swift));
                $to_account->appendChild($xmlDoc->createElement('non_bank_institution', $data->to_non_bank_institution));
                $to_account->appendChild($xmlDoc->createElement('branch', $data->to_branch));
                $to_account->appendChild($xmlDoc->createElement('account', $data->to_account));
                $to_account->appendChild($xmlDoc->createElement('currency_code', $data->to_currency_code));
                $to_account->appendChild($xmlDoc->createElement('personal_account_type', $data->to_personal_account_type));

                $to_account = $xmlDoc->createElement('t_entity');
                // Iterate over each column name and its corresponding value in the $data array
                foreach ($data as $key => $value) {
                    // Handle special cases for nested elements
                    switch ($key) {
                        case 'to_entity_name':
                            $to_account->appendChild($xmlDoc->createElement('name', $value));
                            break;
                        case 'to_entity_incorporation_legal_form':
                            $to_account->appendChild($xmlDoc->createElement('incorporation_legal_form', $value));
                            break;
                        case 'to_entity_incorporation_number':
                            $to_account->appendChild($xmlDoc->createElement('incorporation_number', $value));
                            break;
                        case 'to_entity_business':
                            $to_account->appendChild($xmlDoc->createElement('business', $value));
                            break;
                        case 'to_entity_address_type':
                        case 'to_entity_address':
                        case 'to_entity_city':
                        case 'to_entity_country_code':
                            // Check if addresses element already exists, if not create it
                            if (!$t_entity->getElementsByTagName('addresses')->length) {
                                $addresses = $xmlDoc->createElement('addresses');
                                $t_entity->appendChild($addresses);
                            }
                            // Append address element to addresses
                            $address = $xmlDoc->createElement('address');
                            $address->appendChild($xmlDoc->createElement($key, $value));
                            $addresses->appendChild($address);
                            break;
                        case 'to_entity_incorporation_country_code':
                            $to_account->appendChild($xmlDoc->createElement('incorporation_country_code', $value));
                            break;
                        // After personal_account_type, add the director_id section
                        case 'to_entity_director_gender':
                        case 'to_entity_director_title':
                        case 'to_entity_director_first_name':
                        case 'to_entity_director_last_name':
                        case 'to_entity_director_birthdate':
                        case 'to_entity_director_ssn':
                        case 'to_entity_director_passport_number':
                        case 'to_entity_director_passport_country':
                        case 'to_entity_director_nationality1':
                        case 'to_entity_director_residence':
                        case 'to_entity_director_address_type':
                        case 'to_entity_director_address':
                        case 'to_entity_director_city':
                        case 'to_entity_director_country_code':
                        case 'to_entity_director_occupation':
                        case 'to_entity_director_role':
                            // Create a <director_id> element
                            $director_id = $xmlDoc->createElement('director_id');
                            // Append director details
                            $director_id->appendChild($xmlDoc->createElement('gender', $value));
                            $director_id->appendChild($xmlDoc->createElement('title', $value));
                            $director_id->appendChild($xmlDoc->createElement('first_name', $value));
                            $director_id->appendChild($xmlDoc->createElement('last_name', $value));
                            $director_id->appendChild($xmlDoc->createElement('birthdate', $value));
                            $director_id->appendChild($xmlDoc->createElement('ssn', $value));
                            $director_id->appendChild($xmlDoc->createElement('passport_number', $value));
                            $director_id->appendChild($xmlDoc->createElement('passport_country', $value));
                            $director_id->appendChild($xmlDoc->createElement('nationality1', $value));
                            $director_id->appendChild($xmlDoc->createElement('residence', $value));
                            // Check if addresses element already exists, if not create it
                            if (!$to_account->getElementsByTagName('addresses')->length) {
                                $addresses = $xmlDoc->createElement('addresses');
                                $to_account->appendChild($addresses);
                            }
                            // Append address element to addresses
                            $address = $xmlDoc->createElement('address');
                            $address->appendChild($xmlDoc->createElement($key, $value));
                            $addresses->appendChild($address);
                            $director_id->appendChild($addresses);
                            $director_id->appendChild($xmlDoc->createElement('occupation', $value));
                            $director_id->appendChild($xmlDoc->createElement('role', $value));
                            // Add other director details...
                            // Append director_id to from_entity
                            $to_account->appendChild($director_id);
                            break;
                        default:
                            $t_to_my_client->appendChild($xmlDoc->createElement($key, $value));
                            break;
                    }
                }
                // Append the <t_entity> element to the <t_to_my_client> element
                $t_to_my_client->appendChild($t_entity);

                $item->update(['xml_gen_status' => 'Y']);
            }

        }
