<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseUpdateController extends Controller
{
    public function index()
    {
        // Define the mapping of intermediate tables to your database tables
        $tableMapping = [
            'S0011' => 'scenario_1_trans_details',
            'S0022' => 'scenario_2_trans_details',
            'S0033' => 'scenario_3_trans_details',
            'S0044' => 'scenario_4_trans_details',
            'S0055' => 'scenario_5_trans_details',
            'S0066' => 'scenario_6_trans_details'
        ];

        // SQL Server connection options
        $serverName = "etlshragls.hnbfinance.lk,4795";
        $connectionOptions = array(
            "Database" => "GOAML",
            "Uid" => "TG_GoAML_CN",
            "PWD" => "TgGe3K3e#%24Cn0Kee7"
        );

        // Begin a database transaction
        DB::beginTransaction();
        try {
            foreach ($tableMapping as $intermediateTable => $finalTable) {
                // Establish the SQL Server connection
                $conn = sqlsrv_connect($serverName, $connectionOptions);

                if (!$conn) {
                    throw new \Exception("Connection failed: " . print_r(sqlsrv_errors(), true));
                }

                // Fetch records from SQL Server
                $sql = "SELECT * FROM $intermediateTable WHERE xml_gen_status = 0";
                $stmt = sqlsrv_query($conn, $sql);

                if ($stmt === false) {
                    throw new \Exception("Query execution failed: " . print_r(sqlsrv_errors(), true));
                }

                while ($record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    if (!isset($record['ID'])) {
                        Log::warning("Record from $intermediateTable missing 'ID' field", $record);
                        continue;
                    }

                    // Prepare the record data for insertion into MySQL
                    $recordArray = $record;

                    // Remove unnecessary fields
                    unset($recordArray['ID'], $recordArray['updated_at'], $recordArray['xml_gen_status']);

                    // Trim whitespace from all string values
                    $recordArray = array_map(function($value) {
                        return is_string($value) ? trim($value) : $value;
                    }, $recordArray);

                    // Default values
                    $defaultReportCode = in_array($finalTable, ['scenario_1_trans_details', 'scenario_2_trans_details']) ? 'CTR' : 'EFT';
                    $defaultValues = [
                        'rentity_id' => 96,
                        'rentity_branch' => 'Nawala',
                        'submission_code' => 'E',
                        'report_code' => $defaultReportCode,
                        'entity_reference' => 'reference',
                        'submission_date' => date("Y-m-d"),
                        'status' => 'Y',
                    ];

                    $recordArray = array_merge($defaultValues, array_filter($recordArray, function($value) {
                        return $value !== null && $value !== '';
                    }));

                    // Insert into MySQL
                    DB::connection('mysql')->table($finalTable)->insert($recordArray);
                    $insertedId = DB::connection('mysql')->getPdo()->lastInsertId();

                    // Update the inserted record
                    DB::connection('mysql')->table($finalTable)->where('id', $insertedId)->update([
                        'created_at' => Carbon::now(),
                        'status' => 'Y'
                    ]);
                    $current_date = Carbon::now();
                    $status = 1;

                    // Update the intermediate SQL Server table
                    $updateSql = "UPDATE $intermediateTable SET updated_at = ?, xml_gen_status = ? WHERE ID = ?";
                    $params = [Carbon::now(), '1', $record['ID']];
                    sqlsrv_query($conn, $updateSql, $params);
                }

                // Free the statement and close the connection
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
            }

            DB::commit();
            Log::info('Data transferred from intermediate db at ' . Carbon::now());
            return response()->json(['message' => 'Databases have been updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error transferring data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}

?>


