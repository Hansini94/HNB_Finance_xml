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
        set_time_limit(0); // Unlimited execution time
        $cronLogId = null; // ID of the cron log
        $startTime = Carbon::now();

        $cronLogId = DB::connection('mysql')->table('cron_logs')->insertGetId([
            'cron_name' => 'Database Update Cron',
            'start_time' => $startTime,
            'status' => 'in_progress',
            'created_at' => $startTime,
            'updated_at' => $startTime,
        ]);

        $tableMapping = [
            'S0011' => 'scenario_1_trans_details',
            'S0022' => 'scenario_2_trans_details',
            'S0033' => 'scenario_3_trans_details',
            'S0044' => 'scenario_4_trans_details',
            'S0055' => 'scenario_5_trans_details',
            'S0066' => 'scenario_6_trans_details',
        ];

        $scenarioNumbers = [
            'S0011' => 1,
            'S0022' => 2,
            'S0033' => 3,
            'S0044' => 4,
            'S0055' => 5,
            'S0066' => 6,
        ];

        $serverName = 'etlshragls.hnbfinance.lk,4795';
        $connectionOptions = [
            'Database' => 'GOAML',
            'Uid' => 'TG_GoAML_CN',
            'PWD' => 'TgGe3K3e#%24Cn0Kee7',
        ];

        try {
            foreach ($tableMapping as $intermediateTable => $finalTable) {
                DB::transaction(function () use ($serverName, $connectionOptions, $intermediateTable, $finalTable, $scenarioNumbers) {
                    $conn = sqlsrv_connect($serverName, $connectionOptions);

                    if (!$conn) {
                        throw new \Exception('Connection failed: ' . print_r(sqlsrv_errors(), true));
                    }

                    $sql = "SELECT * FROM $intermediateTable WHERE xml_gen_status = 0";
                    $stmt = sqlsrv_query($conn, $sql);

                    if ($stmt === false) {
                        throw new \Exception('Query execution failed: ' . print_r(sqlsrv_errors(), true));
                    }

                    while ($record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        if (!isset($record['ID'])) {
                            Log::warning("Record from $intermediateTable missing 'ID' field", $record);
                            continue;
                        }

                        $recordArray = $record;
                        unset($recordArray['ID'], $recordArray['updated_at'], $recordArray['xml_gen_status']);
                        $recordArray = array_map(function ($value) {
                            return is_string($value) ? trim($value) : $value;
                        }, $recordArray);

                        $defaultReportCode = in_array($finalTable, ['scenario_1_trans_details', 'scenario_2_trans_details']) ? 'CTR' : 'EFT';
                        $defaultValues = [
                            'rentity_id' => 96,
                            'rentity_branch' => 'Nawala',
                            'submission_code' => 'E',
                            'report_code' => $defaultReportCode,
                            'entity_reference' => 'reference',
                            'submission_date' => date('Y-m-d'),
                            'status' => 'Y',
                        ];

                        $recordArray = array_merge(
                            $defaultValues,
                            array_filter($recordArray, function ($value) {
                                return $value !== null && $value !== '';
                            })
                        );

                        DB::connection('mysql')->table($finalTable)->insert($recordArray);
                        $insertedId = DB::connection('mysql')->getPdo()->lastInsertId();

                        // Determine the related table (signatory_details or director_details)
                        $tableToInsert = $record['scenario_type'] === 'Person' ? 'signatory_details' : 'director_details';

                        // Get the scenario number from the mapping
                        $scenarioNo = $scenarioNumbers[$intermediateTable] ?? null;

                        // Fetch related records from SQL Server based on entity_id and scenario_no
                        $relatedSql = "SELECT * FROM $tableToInsert WHERE entity_id = ? AND is_delete = 0 AND scenario_no = ?";
                        $relatedStmt = sqlsrv_query($conn, $relatedSql, [$record['ID'], $scenarioNo]);

                        if ($relatedStmt === false) {
                            throw new \Exception('Related query execution failed: ' . print_r(sqlsrv_errors(), true));
                        }

                        while ($relatedRecord = sqlsrv_fetch_array($relatedStmt, SQLSRV_FETCH_ASSOC)) {
                            $relatedRecordArray = $relatedRecord;
                            $relatedRecordArray['entity_id'] = $insertedId;
                            $rec_id = $relatedRecordArray['ID'];

                            unset($relatedRecordArray['ID']);
                            $relatedRecordArray = array_map(function ($value) {
                                return is_string($value) ? trim($value) : $value;
                            }, $relatedRecordArray);

                            // Insert related record and check success
                            $inserted = DB::connection('mysql')->table($tableToInsert)->insert($relatedRecordArray);

                            if (!$inserted) {
                                throw new \Exception("Failed to insert data into $tableToInsert for record ID: {$rec_id}");
                            }

                            $lastInsertedId = DB::connection('mysql')->getPdo()->lastInsertId();
                            Log::info("Inserted into $tableToInsert with ID $lastInsertedId: " . json_encode($relatedRecordArray));

                            // Update the status of the inserted record
                            DB::connection('mysql')
                                ->table($tableToInsert)
                                ->where('id', $rec_id)
                                ->update(['status' => 1]);
                        }

                        // Update the main record
                        DB::connection('mysql')
                            ->table($finalTable)
                            ->where('id', $insertedId)
                            ->update([
                                'created_at' => Carbon::now(),
                                'status' => 'Y',
                            ]);

                        // Update the intermediate table
                        $updateSql = "UPDATE $intermediateTable SET updated_at = ?, xml_gen_status = ? WHERE ID = ?";
                        $params = [Carbon::now(), '1', $record['ID']];
                        sqlsrv_query($conn, $updateSql, $params);

                        // Free the related statement resource
                        if (is_resource($relatedStmt)) {
                            sqlsrv_free_stmt($relatedStmt);
                        }
                    }

                    // Free the main statement resource
                    if (is_resource($stmt)) {
                        sqlsrv_free_stmt($stmt);
                    }

                    sqlsrv_close($conn);
                });
            }

            DB::connection('mysql')->table('cron_logs')
                ->where('id', $cronLogId)
                ->update([
                    'end_time' => Carbon::now(),
                    'status' => 'success', // or 'failed' if there's an error
                    'updated_at' => Carbon::now(),
                ]);

            Log::info('Data transferred successfully from intermediate database at ' . Carbon::now());
            return response()->json(['message' => 'Databases have been updated successfully.']);
        } catch (\Exception $e) {
            DB::connection('mysql')->table('cron_logs')
                ->where('id', $cronLogId)
                ->update([
                    'end_time' => Carbon::now(),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at' => Carbon::now(),
                ]);
            Log::error('Error transferring data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
?>
