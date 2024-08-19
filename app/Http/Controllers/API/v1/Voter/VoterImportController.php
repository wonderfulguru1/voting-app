<?php

namespace App\Http\Controllers\API\v1\Voter;

use Exception;
use App\Imports\VoterImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Voter;
use App\VoterTemplate;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

class VoterImportController extends Controller
{
    public function import(Request $request)
    {

        try {
            DB::table('voter_templates')->truncate();
            Excel::import(new VoterImport, $request->file('file'), null, ExcelType::XLSX);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex], 401);
        }


        return response()->json(['message' => 'Data Imported Successfully'], 200);
    }

    public function loadData(Request $request)
    {

        try {
           Voter::where('election_id', Util::getCurrentElection())->delete();

                $sourceData = VoterTemplate::all();
                foreach ($sourceData as $data) {
                    Voter::create([
                        'name' => $data->name_of_member,
                        'student_id' => $data->nigam_no,
                        'course' => 'main_team',
                        'password' => 'default',
                        'election_id' => Util::getCurrentElection()
                    ]);
                }
            
        } catch (Exception $ex) {
            return response()->json(['message' => $ex], 401);
        }


        return response()->json(['message' => 'Data Imported Successfully'], 200);
    }
}
