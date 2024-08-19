<?php

namespace App\Http\Controllers\API\v1\Election;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util;
use DB;

class ResultController extends Controller
{

    private $ONGOING_STATUS =  2;
    public function __invoke(Request $request)
    {

        $result = $this->getResult(Util::getCurrentElection());
        return response()->json(
            $result
        );
    }

    public function finalResult($id)
    {
        $result = $this->getResult($id);
        $getTotalVotes = $this->getTotalVotes($id);

        $position = \App\Position::where('election_id', $id)->get();
       // $nominee = \App\Nominee::where('election_id', $id)->get();

        $nominee = DB::table('nominee')
            ->select('nominee.*', DB::raw('COUNT(result.voter_id) as total_votes'))
            ->leftJoin('result', 'result.nominee_id', '=', 'nominee.id')
            ->where('nominee.election_id', $id)
            ->groupBy('nominee.id')
            ->get();
        $partylist = \App\Partylist::where('election_id', $id)->get();
        return response()->json([
            'result' => $result,
            'position' => $position,
            'nominee' => $nominee,
            'votes' => $getTotalVotes
        ]);
    }

    public function OngoingResult()
    {
        $id = $this->getOngoingElection();
        if ($id) {
            $result = $this->getResult($id);
            $position = \App\Position::where('election_id', $id)->get();
            $getTotalVotes = $this->getTotalVotes($id);

            $nominee =  DB::table('nominee')
            ->select('nominee.*', DB::raw('COUNT(result.voter_id) as total_votes'))
            ->leftJoin('result', 'result.nominee_id', '=', 'nominee.id')
            ->where('nominee.election_id', $id)
            ->groupBy('nominee.id')
            ->orderBy('total_votes', 'desc') // Order by total_votes in descending order
            ->get();
            $partylist = \App\Partylist::where('election_id', $id)->get();
            return response()->json([
                'result' => $result,
                'position' => $position,
                'nominee' => $nominee,
                'votes' => $getTotalVotes
            ]);
        }
        return response()->json([
            'result' => [],
            'position' => [],
            'nominee' => []
        ]);
    }


    private function getResult($id)
    {
        return \App\Result::select(DB::raw('position_id,nominee_id,count(*) as votes'))
            ->groupBy('position_id', 'nominee_id')
            ->where('election_id', $id)
            ->orderBy('votes', 'DESC')
            ->get();
    }

    
    private function getTotalVotes($id)
    {
        return \App\Result::select(DB::raw('position_id, count(*) as votes'))
            ->groupBy('position_id')
            ->where('election_id', $id)
            ->orderBy('votes', 'DESC')
            ->get();
    }

    private function getOngoingElection()
    {
        $result = \App\Election::select(DB::raw('id'))
            ->where('status',  $this->ONGOING_STATUS)
            ->first();
        return $result->id;
    }
}
