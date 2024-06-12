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

class DatabaseUpdateController extends Controller
{
    public function index()
    {
        // Define the tables you want to update
        $tables = [
            'scenario_1_trans_details',
            'scenario_2_trans_details',
            'scenario_3_trans_details',
            'scenario_4_trans_details',
            'scenario_5_trans_details',
            'scenario_6_trans_details',
        ];

        try {
            foreach ($tables as $table) {
                // Fetch data from intermediate database for each table where cImportStatus is 'N'
                $records = DB::connection('intermediate_mysql')->table($table)->where('cImportStatus', 'N')->get();

                foreach ($records as $record) {
                    // Convert the record to an array and remove the fields 'dImportDate' and 'cImportStatus'
                    $recordArray = (array) $record;
                    unset($recordArray['id'], $recordArray['dImportDate'], $recordArray['cImportStatus']);

                    // Set the created_date to the current date and time and updated_date to empty
                    $recordArray['created_at'] = date('Y-m-d H:i:s');
                    $recordArray['updated_at'] = null;

                    // Insert new records
                    DB::connection('mysql')->table($table)->insert($recordArray);
                    $insertedId = DB::connection('mysql')->getPdo()->lastInsertId();

                    // Determine the default report code based on the table name
                    $defaultReportCode = in_array($table, ['scenario_1_trans_details', 'scenario_2_trans_details']) ? 'CTR' : 'EFT';

                    // Prepare default values
                    $defaultValues = [
                        'rentity_id' => 96,
                        'rentity_branch' => 'Nawala',
                        'submission_code' => 'E',
                        'report_code' => $defaultReportCode,
                        'entity_reference' => 'reference',
                        'submission_date' => date("Y-m-d"),
                    ];

                    // Set values with defaults if blank
                    $updateData = [
                        'rentity_id' => $record->rentity_id ?: $defaultValues['rentity_id'],
                        'rentity_branch' => $record->rentity_branch ?: $defaultValues['rentity_branch'],
                        'submission_code' => $record->submission_code ?: $defaultValues['submission_code'],
                        'report_code' => $record->report_code ?: $defaultValues['report_code'],
                        'entity_reference' => $record->entity_reference ?: $defaultValues['entity_reference'],
                        'submission_date' => $record->submission_date ?: $defaultValues['submission_date'],
                    ];

                    // Update the datetime (dImportDate) and cImportStatus in the intermediate database for each table
                    DB::connection('mysql')->table($table)->where('id', $insertedId)->update($updateData);

                    // Optionally, update the intermediate database to mark the record as processed
                    DB::connection('intermediate_mysql')->table($table)->where('id', $record->id)->update([
                        'dImportDate' => Carbon::now(),
                        'cImportStatus' => 'Y',
                    ]);
                }
            }

            // Log the creation of the XML
            \Log::info('Data transferred from intermediate db at ' . Carbon::now());

            return response()->json(['message' => 'Databases have been updated successfully.']);

        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error transferring data: ' . $e->getMessage());

            // Return a JSON response with the error message
            return response()->json(['error' => $e->getMessage()]);
        }
    }




}
?>

