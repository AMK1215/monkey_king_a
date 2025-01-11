<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportVersionTwoController extends Controller
{
    public function getGameReport(Request $request)
    {
        $report = DB::table('bet_n_results as br')
            ->leftJoin('results as r', function ($join) {
                $join->on('br.game_code', '=', 'r.game_code')
                     ->on('br.player_id', '=', 'r.player_id'); // Ensure player_id matches
            })
            ->select(
                'br.game_code',
                'br.player_id',
                'r.player_name',
                DB::raw('COUNT(br.id) as total_bets'),
                DB::raw('SUM(br.bet_amount) as total_bet_amount'),
                DB::raw('SUM(br.win_amount) as total_win_amount'),
                DB::raw('SUM(br.net_win) as total_net_win'),
                'r.game_name',
                'r.game_provide_name',
                DB::raw('COUNT(r.id) as total_results'),
                DB::raw('SUM(r.total_bet_amount) as total_result_bet_amount'),
                DB::raw('SUM(r.win_amount) as total_result_win_amount'),
                DB::raw('SUM(r.net_win) as total_result_net_win')
            )
            ->groupBy('br.game_code', 'br.player_id', 'r.player_name', 'r.game_name', 'r.game_provide_name')
            ->orderByDesc('total_bets')
            ->paginate(10); // Pagination: 10 results per page

        return response()->json($report);
    }
}