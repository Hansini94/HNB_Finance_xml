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
        $data = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])->where('xml_gen_status', '=', 'N')->where('account_type',$scenario_type)
            ->groupBy('rentity_id', 'id')
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
        $reportInfo = $xmlDoc->createElement('report_info');

        $reportInfo->appendChild($xmlDoc->createElement('rentity_id', $data->rentity_id));
        $reportInfo->appendChild($xmlDoc->createElement('rentity_branch', $data->rentity_branch));
        $reportInfo->appendChild($xmlDoc->createElement('submission_code', $data->submission_code));
        $reportInfo->appendChild($xmlDoc->createElement('report_code', $data->report_code));
        $reportInfo->appendChild($xmlDoc->createElement('entity_reference', $data->entity_reference));
        $reportInfo->appendChild($xmlDoc->createElement('fiu_ref_number', $data->fiu_ref_number));
        $reportInfo->appendChild($xmlDoc->createElement('submission_date', $data->submission_date));
        $reportInfo->appendChild($xmlDoc->createElement('currency_code_local', $data->currency_code_local));

        $root->appendChild($reportInfo);

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

        if($scenario_type == 'Entity')
        {
            $xml_type = 'Entity';
            $trans = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])
            ->where('account_type','Entity')
            ->orderBy('created_at', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Create a <t_from_my_client> element
            $t_from_my_client = $xmlDoc->createElement('t_from_my_client');

            // Iterate over each transaction
            foreach ($trans as $item) {
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
                        // Handle other keys as usual
                        default:
                            $t_from_my_client->appendChild($xmlDoc->createElement($key, $value));
                            break;
                    }
                }
                // Append the <from_entity> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_entity);

                $item->update(['xml_gen_status' => 'Y']);
            }

            // Append the <t_from_my_client> element to the main XML document
            $xmlDoc->appendChild($t_from_my_client);

        }
        else
        {
            $xml_type = 'Person';
            $trans = ScenarioOne::whereBetween('created_at', [$from_date, $to_date])
            ->where('account_type','Person')
            ->orderBy('created_at', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);


            // Create a <t_from_my_client> element
            $t_from_my_client = $xmlDoc->createElement('t_from_my_client');

            // Iterate over each transaction
            foreach ($trans as $item) {
                // Convert the Eloquent model to an array, excluding the metadata fields
                $data = $item->toArray();

                // Create a <from_person> element
                $from_person = $xmlDoc->createElement('from_person');

                // Iterate over each column name and its corresponding value in the $data array
                foreach ($data as $key => $value) {
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
                            $from_person->appendChild($xmlDoc->createElement('birthdate', $value));
                            break;
                        case 'from_person_ssn':
                            $from_person->appendChild($xmlDoc->createElement('ssn', $value));
                            break;
                        case 'from_person_nationality1':
                            $from_person->appendChild($xmlDoc->createElement('nationality1', $value));
                            break;
                        case 'from_person_residence':
                            $from_person->appendChild($xmlDoc->createElement('residence', $value));
                            break;
                        case 'from_person_address_type':
                        case 'from_person_address':
                        case 'from_person_city':
                        case 'from_person_country_code':
                            // Check if addresses element already exists, if not create it
                            if (!$from_person->getElementsByTagName('addresses')->length) {
                                $addresses = $xmlDoc->createElement('addresses');
                                $from_person->appendChild($addresses);
                            }
                            // Append address element to addresses
                            $address = $xmlDoc->createElement('address');
                            $address->appendChild($xmlDoc->createElement($key, $value));
                            $addresses->appendChild($address);
                            break;
                        // Handle other keys as usual
                        default:
                            // $t_from_my_client->appendChild($xmlDoc->createElement($key, $value));
                            break;
                    }
                }
                // Append the <from_person> element to the <t_from_my_client> element
                $t_from_my_client->appendChild($from_person);

                $item->update(['xml_gen_status' => 'Y']);
            }

            // Append the <t_from_my_client> element to the main XML document
            $xmlDoc->appendChild($t_from_my_client);

        }

        // Add report_indicators section
        $reportIndicators = $xmlDoc->createElement('report_indicators');
        // Retrieve report indicators data from the database
        $indicators = ScenarioOne::pluck('report_indicator')->toArray();
        foreach ($indicators as $indicator) {
            $reportIndicators->appendChild($xmlDoc->createElement('indicator', $indicator));
        }
        $root->appendChild($reportIndicators);

        // Save the XML to a file
        $fileName = 'files/xmlfile_' . time() . '.xml';
        $xmlDoc->save(storage_path('app/public/' . $fileName));

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file for Scenario 1 at ' . Carbon::now());
        \LogActivity::addToLogXMLGen('User ' . $user->id . ' created an XML file for Scenario 1 at ' . Carbon::now(),$from_date,$to_date,$xml_type,$xml_gen_status);

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
