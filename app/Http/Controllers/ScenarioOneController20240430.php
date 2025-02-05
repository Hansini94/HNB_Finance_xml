<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use DateTime;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleXMLElement;

use App\Models\ScenarioOne;
use App\Models\EmployeeDetail;
use App\Models\LogXMLGenActivity;

class ScenarioOneController extends Controller
{
    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = ScenarioOne::select('*')->where('is_delete', '0')->where('status', 'Y')->where('xml_gen_status', 'N')->orderBy('id', 'DESC')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_one.list');
    }

    public function fetchLastDetail(Request $request)
    {
        $scenarioType = $request->get('scenario_type');

        $lastDetail = LogXMLGenActivity::where('xml_type', $scenarioType)->where('xml_gen_status',1)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json($lastDetail);
    }


    public function generate_xml()
    {
        $scenario_type = request('scenario_type');
        $from_date = request('from_date');
        $to_date = request('to_date');
        $xml_type = '';
        $xml_gen_status=1; //new generation 1 , old data generation 2

        // Retrieve data from the database
       // $data = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])->where('xml_gen_status', '=', 'N')->where('account_type',$scenario_type)
         //   ->groupBy('rentity_id')
           // ->first();

      $data = ScenarioOne::select('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
      ->whereBetween('date_transaction', [$from_date, $to_date])
      ->where('xml_gen_status', '=', 'N')
      ->where('account_type', $scenario_type)
      ->groupBy('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
      ->first();

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
        else
        {
            $xml_type = 'Person';
            $trans = ScenarioOne::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('account_type','Person')
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
                $from_person = $xmlDoc->createElement('from_person');

                // Check if addresses element already exists, if not create it
                if (!$from_person->getElementsByTagName('addresses')->length) {
                    $addresses = $xmlDoc->createElement('addresses');
                    $from_person->appendChild($addresses);
                }

                // Create a single address element
                $address = $xmlDoc->createElement('address');

                // Iterate over each column name and its corresponding value in the $data array
                foreach ($data_item as $key => $value) {
                    // Handle special cases for nested elements
                    switch ($key) {
                        case 'from_person_gender':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('gender', $value));
                            }
                            break;
                        case 'from_person_title':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('title', $value));
                            }
                            break;
                        case 'from_person_first_name':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('first_name', $value));
                            }
                            break;
                        case 'from_person_last_name':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('last_name', $value));
                            }
                            break;
                        case 'from_person_birthdate':
                            if($value !== null && $value !== '')
                            {
                                $dateofbirth = new DateTime($value);
                                $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                                $from_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                            }
                            break;
                        case 'from_person_ssn':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('ssn', $value));
                            }
                            break;
                        case 'from_person_nationality1':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('nationality1', $value));
                            }
                            break;
                        case 'from_person_residence':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('residence', $value));
                            }
                            break;
                        case 'from_person_address_type':
                        case 'from_person_address':
                        case 'from_person_city':
                        case 'from_person_country_code':
                            // Append address detail to the single address element
                            if($value !== null && $value !== '')
                            {
                                $address->appendChild($xmlDoc->createElement(str_replace('from_person_', '', $key), $value));
                            }
                            // Append the single address element to addresses
                            $addresses->appendChild($address);
                            break;
                        case 'from_person_occupation':
                            if($value !== null && $value !== '')
                            {
                                $from_person->appendChild($xmlDoc->createElement('occupation', $value));
                            }
                            break;
                    }

                }


                // Append the <from_person> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_person);

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
                $to_account = $xmlDoc->createElement('to_account');

                $to_account->appendChild($xmlDoc->createElement('institution_name', $item->to_account_institution_name));
                $to_account->appendChild($xmlDoc->createElement('swift', $item->to_swift));
                $to_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->to_non_bank_institution));
                $to_account->appendChild($xmlDoc->createElement('branch', $item->to_branch));
                $to_account->appendChild($xmlDoc->createElement('account', $item->to_account));
                $to_account->appendChild($xmlDoc->createElement('currency_code', $item->to_currency_code));
                $to_account->appendChild($xmlDoc->createElement('personal_account_type', $item->to_personal_account_type));

                $signatory = $xmlDoc->createElement('signatory');
                // // Iterate over each column name and its corresponding value in the $data array
                // Create a <t_person> element
                $t_person = $xmlDoc->createElement('t_person');

                // Check if addresses element already exists, if not create it
                if (!$from_person->getElementsByTagName('addresses')->length) {
                    $addresses = $xmlDoc->createElement('addresses');
                    $t_person->appendChild($addresses);
                }

                // Create a single address element
                $address = $xmlDoc->createElement('address');

                foreach ($data_item as $key => $value) {
                //     // Handle special cases for nested elements
                    switch ($key) {
                        case 'to_signatory_is_primary':
                            if($value !== null && $value !== '')
                            {
                                $signatory->appendChild($xmlDoc->createElement('is_primary', $value));
                            }
                            break;
                        // After personal_account_type, add the director_id section
                        case 'to_signatory_gender':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('gender', $value));
                            }
                            break;
                        case 'to_signatory_title':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('title', $value));
                            }
                            break;
                        case 'to_signatory_first_name':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('first_name', $value));
                            }
                            break;
                        case 'to_signatory_last_name':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('last_name', $value));
                            }
                            break;
                        case 'to_signatory_birthdate':
                            if($value !== null && $value !== '')
                            {
                                $dateofbirth = new DateTime($value);
                                $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                                $t_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                            }
                            break;
                        case 'to_signatory_ssn':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('ssn', $value));
                            }
                            break;
                        case 'to_signatory_nationality1':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('nationality1', $value));
                            }
                            break;
                        case 'to_signatory_residence':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('residence', $value));
                            }
                            break;
                        case 'to_signatory_address_type':
                        case 'to_signatory_address':
                        case 'to_signatory_city':
                        case 'to_signatory_country_code':
                            // Append address detail to the single address element
                            if($value !== null && $value !== '')
                            {
                                $address->appendChild($xmlDoc->createElement(str_replace('to_signatory_', '', $key), $value));
                            }
                            // Append the single address element to addresses
                            $addresses->appendChild($address);
                            break;
                        case 'to_signatory_occupation':
                            if($value !== null && $value !== '')
                            {
                                $t_person->appendChild($xmlDoc->createElement('occupation', $value));
                                $signatory->appendChild($t_person);
                            }
                            break;

                    }
                }
                if($item->to_signatory_role != '' || $item->to_signatory_role != null)
                {
                    $signatory->appendChild($xmlDoc->createElement('role', $item->to_signatory_role));
                }

                $signatory->appendChild($t_person);
                $to_account->appendChild($signatory);

                if($item->to_status_code != '' || $item->to_status_code != null)
                {
                    $to_account->appendChild($xmlDoc->createElement('status_code', $item->to_status_code));
                }
                // // Append the <t_entity> element to the <t_to_my_client> element
                $t_to_my_client->appendChild($to_account);
                if($item->to_country != '' || $item->to_country != null)
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_country', $item->to_country));
                }


                $transaction->appendChild($t_to_my_client);

                // Append the <transaction> element to the main XML document
                $root->appendChild($transaction);
            }

        }
        // Add report_indicators section
        $reportIndicators = $xmlDoc->createElement('report_indicators');
        // Retrieve report indicators data from the database
        $indicators = ScenarioOne::pluck('report_indicator')->toArray();
        foreach ($indicators as $indicator) {
            if($indicator != '' || $indicator != null)
            {
                $reportIndicators->appendChild($xmlDoc->createElement('indicator', $indicator));
            }
        }
        $root->appendChild($reportIndicators);

        // $data->update(['xml_gen_status' => 'Y']);

        // Save the XML to a file
        $fileName = 'files/xmlfile_' . time() . '.xml';
        $xmlDoc->save(storage_path('app/public/' . $fileName));

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file for Scenario 1 at ' . Carbon::now());
        // \LogActivity::addToLogXMLGen('User ' . $user->id . ' created an XML file for Scenario 1 at ' . Carbon::now(),$from_date,$to_date,$xml_type,$xml_gen_status);

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


}
