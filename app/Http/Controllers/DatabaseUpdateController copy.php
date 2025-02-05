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
                // Add the remaining mappings here
            ];

            DB::beginTransaction();
            try {
                foreach ($tableMapping as $intermediateTable => $finalTable) {
                    $records = DB::connection('sqlsrv')->table($intermediateTable)->where('xml_gen_status', 0)->get();

                    foreach ($records as $record) {
                        if (!isset($record->ID)) {
                            Log::warning("Record from $intermediateTable missing 'id' field", (array)$record);
                            continue;
                        }

                        $recordArray = (array) $record;

                        unset($recordArray['ID'], $recordArray['updated_at'], $recordArray['xml_gen_status']);

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

                        DB::connection('mysql')->table($finalTable)->insert($recordArray);
                        $insertedId = DB::connection('mysql')->getPdo()->lastInsertId();

                        DB::connection('mysql')->table($finalTable)->where('id', $insertedId)->update([
                            'created_at' => Carbon::now(),
                            'status' => 'Y'
                        ]);

                        DB::connection('sqlsrv')->table($intermediateTable)->where('ID', $record->ID)->update([
                            'updated_at' => Carbon::now(),
                            'xml_gen_status' => '1',
                        ]);
                    }
                }
                // dd("test");

                DB::commit();
                Log::info('Data transferred from intermediate db at ' . Carbon::now());
                return response()->json(['message' => 'Databases have been updated successfully.']);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error transferring data: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()]);
            }

        }

        public function testSqlSrv()
        {
            try {
                // Query to test connection
                $results = DB::connection('sqlsrv')->select("SELECT TOP 10 * FROM S0011");
                return response()->json($results);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }
?>


