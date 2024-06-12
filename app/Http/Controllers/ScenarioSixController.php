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

use App\Models\ScenarioSix;
use App\Models\EmployeeDetail;
use App\Models\LogXMLGenActivity;
use App\Models\DirectorIdDetail;
use App\Models\SignatoryDetail;

class ScenarioSixController extends Controller
{
    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = ScenarioSix::select('*')->where('is_delete', '0')->where('status', 'Y')->where('xml_gen_status', 'N')->orderBy('id', 'DESC')->get();
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('adminpanel.generate_xml.scenario_six.list');
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
        // dd($scenario_type);
        // Retrieve data from the database
        // $data = ScenarioSix::whereBetween('created_at', [$from_date, $to_date])->where('xml_gen_status', '=', 'N')->where('scenario_type',$scenario_type)
         //   ->groupBy('rentity_id')
           // ->first();

        $data = ScenarioSix::select('rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'submission_date', 'currency_code_local')
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
            $trans = ScenarioSix::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('scenario_type','Entity')
            ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
            ->orderBy('date_transaction', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Iterate over each transaction
            foreach ($trans as $item) {
                // Create a <t_from> element
                $transaction = $xmlDoc->createElement('transaction');

                $transaction->appendChild($xmlDoc->createElement('transactionnumber', $item->transactionnumber));
                $transaction->appendChild($xmlDoc->createElement('internal_ref_number', $item->internal_ref_number));
                if($item->transaction_location !== null && $item->transaction_location !== '')
                {
                    //in goAML it gives need location if transmode_code is branch
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

                // Create a <t_from> element
                $t_from = $xmlDoc->createElement('t_from');

                $t_from->appendChild($xmlDoc->createElement('from_funds_code', $item->from_funds_code));

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
                if($item->from_account_account !== null && $item->from_account_account !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_account));
                }
                if($item->from_account_currency_code !== null && $item->from_account_currency_code !== '')
                {
                    $from_account->appendChild($xmlDoc->createElement('currency_code', $item->from_account_currency_code));
                }

                // Append the <from_person> element to the <t_from> element
                $t_from->appendChild($from_account);

                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }

                $transaction->appendChild($t_from);

                // ******************** to_my_client *******************************

                // Create a <t_to_my_client> element
                $t_to_my_client = $xmlDoc->createElement('t_to_my_client');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }

                // Create a <from_person> element
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
                if($item->to_account_branch !== null && $item->to_account_branch !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('branch', $item->to_account_branch));
                }
                if($item->to_account_account !== null && $item->to_account_account !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('account', $item->to_account_account));
                }
                if($item->to_account_currency_code !== null && $item->to_account_currency_code !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('currency_code', $item->to_account_currency_code));
                }
                if($item->to_account_personal_account_type !== null && $item->to_account_personal_account_type !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('personal_account_type', $item->to_account_personal_account_type));
                }

                $t_entity = $xmlDoc->createElement('t_entity');

                $t_entity->appendChild($xmlDoc->createElement('name', $item->to_account_name));
                $t_entity->appendChild($xmlDoc->createElement('incorporation_legal_form', $item->to_account_incorporation_legal_form));
                $t_entity->appendChild($xmlDoc->createElement('incorporation_number', $item->to_account_incorporation_number));
                $t_entity->appendChild($xmlDoc->createElement('business', $item->to_account_business));
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->to_account_address_type !== null && $item->to_account_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->to_account_address_type));
                        }
                        if($item->to_account_address !== null && $item->to_account_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->to_account_address));
                        }
                        if($item->to_account_city !== null && $item->to_account_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->to_account_city));
                        }
                        if($item->to_account_country_code !== null && $item->to_account_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->to_account_country_code));
                        }
                    $addresses->appendChild($address);
                $t_entity->appendChild($addresses);
                $t_entity->appendChild($xmlDoc->createElement('incorporation_country_code', $item->to_account_incorporation_country_code));

                $director_id = $xmlDoc->createElement('director_id');

                if($item->to_account_director_gender !== null && $item->to_account_director_gender !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('gender', $item->to_account_director_gender));
                }
                if($item->to_account_director_title !== null && $item->to_account_director_title !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('title', $item->to_account_director_title));
                }
                if($item->to_account_director_first_name !== null && $item->to_account_director_first_name !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('first_name', $item->to_account_director_first_name));
                }
                if($item->to_account_director_last_name !== null && $item->to_account_director_last_name !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('last_name', $item->to_account_director_last_name));
                }
                if($item->to_account_director_birthdate !== null && $item->to_account_director_birthdate !== '')
                {
                    $dateofbirth = new DateTime($item->to_account_director_birthdate);
                    $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                    $director_id->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                }
                if($item->to_account_director_ssn !== null && $item->to_account_director_ssn !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('ssn', $item->to_account_director_ssn));
                }
                if($item->to_account_director_nationality1 !== null && $item->to_account_director_nationality1 !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('nationality1', $item->to_account_director_nationality1));
                }
                if($item->to_account_director_residence !== null && $item->to_account_director_residence !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('residence', $item->to_account_director_residence));
                }
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->to_account_director_address_type !== null && $item->to_account_director_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->to_account_director_address_type));
                        }
                        if($item->to_account_director_address !== null && $item->to_account_director_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->to_account_director_address));
                        }
                        if($item->to_account_director_city !== null && $item->to_account_director_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->to_account_director_city));
                        }
                        if($item->to_account_director_country_code !== null && $item->to_account_director_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->to_account_director_country_code));
                        }
                    $addresses->appendChild($address);
                $director_id->appendChild($addresses);

                if($item->to_account_director_occupation !== null && $item->to_account_director_occupation !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('occupation', $item->to_account_director_occupation));
                }
                if($item->to_account_director_role !== null && $item->to_account_director_role !== '')
                {
                    $director_id->appendChild($xmlDoc->createElement('role', $item->to_account_director_role));
                }

                $t_entity->appendChild($director_id);

                if($item->status_code !== null && $item->status_code !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('status_code', $item->status_code));
                }

                // // Append the <t_entity> element to the <t_to_my_client> element
                $t_to_my_client->appendChild($to_account);

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
            $trans = ScenarioSix::whereBetween('date_transaction', [$from_date, $to_date])
            ->where('scenario_type','Person')
            ->where('is_delete', '=', 0)
            ->where('status', '=', 'Y')
            ->orderBy('date_transaction', 'asc')
            ->get()
            ->except(['rentity_id', 'rentity_branch', 'submission_code', 'report_code', 'entity_reference', 'fiu_ref_number', 'submission_date', 'currency_code_local','report_indicator']);

            // Iterate over each transaction
            foreach ($trans as $item) {
                // Create a <t_from> element
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

                // Create a <t_from> element
                $t_from = $xmlDoc->createElement('t_from');

                $t_from->appendChild($xmlDoc->createElement('from_funds_code', $item->from_funds_code));

                // Convert the Eloquent model to an array, excluding the metadata fields
                $data_item = $item->toArray();

                // Create a <from_person> element
                $from_account = $xmlDoc->createElement('from_account');

                $from_account->appendChild($xmlDoc->createElement('institution_name', $item->from_account_institution_name));
                $from_account->appendChild($xmlDoc->createElement('swift', $item->from_account_swift));
                $from_account->appendChild($xmlDoc->createElement('non_bank_institution', $item->from_account_non_bank_institution));
                $from_account->appendChild($xmlDoc->createElement('account', $item->from_account_account));
                $from_account->appendChild($xmlDoc->createElement('currency_code', $item->from_account_currency_code));


                $t_from->appendChild($from_account);


                if($item->from_country !== null && $item->from_country !== '')
                {
                    $t_from->appendChild($xmlDoc->createElement('from_country', $item->from_country));
                }

                $transaction->appendChild($t_from);


                // ******************** to_my_client *******************************

                // Create a <t_to_my_client> element
                $t_to_my_client = $xmlDoc->createElement('t_to_my_client');

                if($item->to_funds_code !== null && $item->to_funds_code !== '')
                {
                    $t_to_my_client->appendChild($xmlDoc->createElement('to_funds_code', $item->to_funds_code));
                }

                // Create a <from_person> element
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
                if($item->to_account_branch !== null && $item->to_account_branch !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('branch', $item->to_account_branch));
                }
                if($item->to_account_account !== null && $item->to_account_account !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('account', $item->to_account_account));
                }
                if($item->to_account_currency_code !== null && $item->to_account_currency_code !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('currency_code', $item->to_account_currency_code));
                }
                if($item->to_account_personal_account_type !== null && $item->to_account_personal_account_type !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('personal_account_type', $item->to_account_personal_account_type));
                }

                $signatory = $xmlDoc->createElement('signatory');

                if($item->to_account_signatory_is_primary !== null && $item->to_account_signatory_is_primary !== '')
                {
                    $signatory->appendChild($xmlDoc->createElement('is_primary', $item->to_account_signatory_is_primary));
                }

                $t_person = $xmlDoc->createElement('t_person');

                if($item->to_account_signatory_gender !== null && $item->to_account_signatory_gender !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('gender', $item->to_account_signatory_gender));
                }
                if($item->to_account_signatory_title !== null && $item->to_account_signatory_title !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('title', $item->to_account_signatory_title));
                }
                if($item->to_account_signatory_first_name !== null && $item->to_account_signatory_first_name !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('first_name', $item->to_account_signatory_first_name));
                }
                if($item->to_account_signatory_last_name !== null && $item->to_account_signatory_last_name !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('last_name', $item->to_account_signatory_last_name));
                }
                if($item->to_account_signatory_birthdate !== null && $item->to_account_signatory_birthdate !== '')
                {
                    $dateofbirth = new DateTime($item->to_account_signatory_birthdate);
                    $formattedDateofbirth = $dateofbirth->format('Y-m-d\TH:i:s');
                    $t_person->appendChild($xmlDoc->createElement('birthdate', $formattedDateofbirth));
                }
                if($item->to_account_signatory_ssn !== null && $item->to_account_signatory_ssn !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('ssn', $item->to_account_signatory_ssn));
                }
                if($item->to_account_signatory_nationality1 !== null && $item->to_account_signatory_nationality1 !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('nationality1', $item->to_account_signatory_nationality1));
                }
                if($item->to_account_signatory_residence !== null && $item->to_account_signatory_residence !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('residence', $item->to_account_signatory_residence));
                }
                $addresses = $xmlDoc->createElement('addresses');
                    $address = $xmlDoc->createElement('address');
                        if($item->to_account_signatory_address_type !== null && $item->to_account_signatory_address_type !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address_type', $item->to_account_signatory_address_type));
                        }
                        if($item->to_account_signatory_address !== null && $item->to_account_signatory_address !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('address', $item->to_account_signatory_address));
                        }
                        if($item->to_account_signatory_city !== null && $item->to_account_signatory_city !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('city', $item->to_account_signatory_city));
                        }
                        if($item->to_account_signatory_country_code !== null && $item->to_account_signatory_country_code !== '')
                        {
                            $address->appendChild($xmlDoc->createElement('country_code', $item->to_account_signatory_country_code));
                        }
                    $addresses->appendChild($address);
                $t_person->appendChild($addresses);

                if($item->to_account_signatory_occupation !== null && $item->to_account_signatory_occupation !== '')
                {
                    $t_person->appendChild($xmlDoc->createElement('occupation', $item->to_account_signatory_occupation));
                }

                // Append the <from_person> element to the <t_from_my_client> element
                $signatory->appendChild($t_person);

                if($item->to_account_signatory_role !== null && $item->to_account_signatory_role !== '')
                {
                    $signatory->appendChild($xmlDoc->createElement('role', $item->to_account_signatory_role));
                }

                $to_account->appendChild($signatory);

                if($item->status_code !== null && $item->status_code !== '')
                {
                    $to_account->appendChild($xmlDoc->createElement('status_code', $item->status_code));
                }

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

        // Retrieve report indicators data from the database
        // dd($scenario_type);
        // \DB::enableQueryLog();
        $indicators = ScenarioSix::select('report_indicator')
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
        $fileName = 'files/xmlfile_' . time() . '_scenario_six.xml';
        $xmlDoc->save(storage_path('app/public/' . $fileName));
        $scenario_no = 6;

        // Log the creation of the XML
        \Log::info('User ' . $user->id . ' created an XML file for Scenario 6 at ' . Carbon::now());
        \LogActivity::addToLogXMLGen('User ' . $user->id . ' created an XML file for Scenario 6 at ' . Carbon::now(),$from_date,$to_date,$fileName,$xml_type,$scenario_no,$xml_gen_status);

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
