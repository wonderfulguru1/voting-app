<?php

namespace App\Http\Controllers\API\v1\Nominee;

use Exception;
use App\Imports\NomineeImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Nominee;
use App\NomineeTemplate;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

class NomineeImportController extends Controller
{
    public function import(Request $request)
    {

        try {
            DB::table('nominee_templates')->truncate();
            Excel::import(new NomineeImport, $request->file('file'), null, ExcelType::XLSX);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex], 401);
        }


        return response()->json(['message' => 'Data Imported Successfully'], 200);
    }

    public function loadData(Request $request)
    {

        try {
            Nominee::where('election_id', Util::getCurrentElection())->delete();

                $sourceData = NomineeTemplate::all();
                foreach ($sourceData as $data) {
                    Nominee::create([
                        'name' => $data->name_of_member,
                        'student_id' => $data->nigam_no,
                        'course' => 'main_team',
                        'password' => 'default',
                        'election_id' => Util::getCurrentElection(),
                        'position_id' => 7,
                        'partylist_id' => 4
                    ]);
                }
            
        } catch (Exception $ex) {
            return response()->json(['message' => $ex], 401);
        }


        return response()->json(['message' => 'Data Imported Successfully'], 200);
    }
}
