<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportVersionTwoController extends Controller
{

    public function getGameReport(Request $request)
{
    // Get data from bet_n_results
    $betData = DB::table('bet_n_results as br')
        ->select(
            'br.player_id',
            DB::raw("NULL as player_name"), // Will be replaced by results if available
            'br.game_code',
            DB::raw("NULL as game_name"), // Will be replaced by results if available
            DB::raw("br.provider_code as game_provide_name"), // Provider from bet_n_results
            DB::raw('COUNT(br.id) as total_bets'),
            DB::raw('ROUND(SUM(br.bet_amount), 2) as total_bet_amount'),
            DB::raw('ROUND(SUM(br.win_amount), 2) as total_win_amount'),
            DB::raw('ROUND(SUM(br.net_win), 2) as total_net_win'),
            DB::raw('0 as total_results'),
            DB::raw('NULL as total_result_bet_amount'),
            DB::raw('NULL as total_result_win_amount'),
            DB::raw('NULL as total_result_net_win')
        )
        ->groupBy('br.player_id', 'br.game_code', 'br.provider_code');

    // Get data from results
    $resultData = DB::table('results as r')
        ->select(
            'r.player_id',
            'r.player_name',
            'r.game_code',
            'r.game_name',
            'r.game_provide_name',
            DB::raw('0 as total_bets'),
            DB::raw('NULL as total_bet_amount'),
            DB::raw('NULL as total_win_amount'),
            DB::raw('NULL as total_net_win'),
            DB::raw('COUNT(r.id) as total_results'),
            DB::raw('ROUND(SUM(r.total_bet_amount), 2) as total_result_bet_amount'),
            DB::raw('ROUND(SUM(r.win_amount), 2) as total_result_win_amount'),
            DB::raw('ROUND(SUM(r.net_win), 2) as total_result_net_win')
        )
        ->groupBy('r.player_id', 'r.game_code', 'r.game_name', 'r.game_provide_name', 'r.player_name');

    // Combine both datasets using UNION
    $report = DB::query()
        ->fromSub(function ($query) use ($betData, $resultData) {
            $query->from($betData)
                  ->unionAll($resultData);
        }, 'combined_data')
        ->select(
            'player_id',
            DB::raw("COALESCE(player_name, player_id) as player_name"), // Use player_name from results if available
            'game_code',
            DB::raw("COALESCE(game_name, game_code) as game_name"), // Use game_name from results if available
            DB::raw("COALESCE(game_provide_name, 'Unknown Provider') as game_provide_name"),
            DB::raw('SUM(total_bets) as total_bets'),
            DB::raw('ROUND(SUM(total_bet_amount), 2) as total_bet_amount'),
            DB::raw('ROUND(SUM(total_win_amount), 2) as total_win_amount'),
            DB::raw('ROUND(SUM(total_net_win), 2) as total_net_win'),
            DB::raw('SUM(total_results) as total_results'),
            DB::raw('ROUND(SUM(total_result_bet_amount), 2) as total_result_bet_amount'),
            DB::raw('ROUND(SUM(total_result_win_amount), 2) as total_result_win_amount'),
            DB::raw('ROUND(SUM(total_result_net_win), 2) as total_result_net_win')
        )
        ->groupBy('player_id', 'game_code', 'game_name', 'game_provide_name', 'player_name')
        ->orderByDesc('total_bets')
        ->get(); // Paginate results

    return response()->json($report);
}

    // public function getGameReport(Request $request)
    // {
    //     // Get data from bet_n_results
    //     $betData = DB::table('bet_n_results as br')
    //         ->select(
    //             'br.player_id',
    //             DB::raw("NULL as player_name"), // Will be filled from results if available
    //             'br.game_code',
    //             DB::raw("NULL as game_name"), // Will be filled from results if available
    //             DB::raw("br.provider_code as game_provide_name"), // Provider stored in bet_n_results
    //             DB::raw('COUNT(br.id) as total_bets'),
    //             DB::raw('SUM(br.bet_amount) as total_bet_amount'),
    //             DB::raw('SUM(br.win_amount) as total_win_amount'),
    //             DB::raw('SUM(br.net_win) as total_net_win'),
    //             DB::raw('0 as total_results'),
    //             DB::raw('NULL as total_result_bet_amount'),
    //             DB::raw('NULL as total_result_win_amount'),
    //             DB::raw('NULL as total_result_net_win')
    //         )
    //         ->groupBy('br.player_id', 'br.game_code', 'br.provider_code');

    //     // Get data from results
    //     $resultData = DB::table('results as r')
    //         ->select(
    //             'r.player_id',
    //             'r.player_name',
    //             'r.game_code',
    //             'r.game_name',
    //             'r.game_provide_name',
    //             DB::raw('0 as total_bets'),
    //             DB::raw('NULL as total_bet_amount'),
    //             DB::raw('NULL as total_win_amount'),
    //             DB::raw('NULL as total_net_win'),
    //             DB::raw('COUNT(r.id) as total_results'),
    //             DB::raw('SUM(r.total_bet_amount) as total_result_bet_amount'),
    //             DB::raw('SUM(r.win_amount) as total_result_win_amount'),
    //             DB::raw('SUM(r.net_win) as total_result_net_win')
    //         )
    //         ->groupBy('r.player_id', 'r.game_code', 'r.game_name', 'r.game_provide_name', 'r.player_name');

    //     // Combine both datasets using UNION
    //     $report = DB::query()
    //         ->fromSub(function ($query) use ($betData, $resultData) {
    //             $query->from($betData)
    //                 ->unionAll($resultData);
    //         }, 'combined_data')
    //         ->select(
    //             'player_id',
    //             DB::raw("COALESCE(player_name, player_id) as player_name"), // Use player_name if available
    //             'game_code',
    //             DB::raw("COALESCE(game_name, game_code) as game_name"), // Use game_name if available
    //             DB::raw("COALESCE(game_provide_name, 'Unknown Provider') as game_provide_name"),
    //             DB::raw('SUM(total_bets) as total_bets'),
    //             DB::raw('SUM(total_bet_amount) as total_bet_amount'),
    //             DB::raw('SUM(total_win_amount) as total_win_amount'),
    //             DB::raw('SUM(total_net_win) as total_net_win'),
    //             DB::raw('SUM(total_results) as total_results'),
    //             DB::raw('SUM(total_result_bet_amount) as total_result_bet_amount'),
    //             DB::raw('SUM(total_result_win_amount) as total_result_win_amount'),
    //             DB::raw('SUM(total_result_net_win) as total_result_net_win')
    //         )
    //         ->groupBy('player_id', 'game_code', 'game_name', 'game_provide_name', 'player_name')
    //         ->orderByDesc('total_bets')
    //         ->get(); // Paginate results

    //     return response()->json($report);
    // }

    // public function getGameReport(Request $request)
    // {
    //     $report = DB::table('bet_n_results as br')
    //         ->leftJoin('results as r', function ($join) {
    //             $join->on('br.game_code', '=', 'r.game_code')
    //                  ->on('br.player_id', '=', 'r.player_id'); // Ensure player_id matches
    //         })
    //         ->select(
    //             'br.player_id',
    //             DB::raw("COALESCE(r.player_name, br.player_id) as player_name"), // Use player_name from results if available, otherwise use player_id
    //             'br.game_code',
    //             DB::raw("COALESCE(r.game_name, br.game_code) as game_name"), // Use game_name from results if available, otherwise fallback
    //             DB::raw("COALESCE(r.game_provide_name, 'Unknown Provider') as game_provide_name"), // Use provider name if available
    //             DB::raw('COUNT(br.id) as total_bets'),
    //             DB::raw('SUM(br.bet_amount) as total_bet_amount'),
    //             DB::raw('SUM(br.win_amount) as total_win_amount'),
    //             DB::raw('SUM(br.net_win) as total_net_win'),
    //             DB::raw('COUNT(r.id) as total_results'),
    //             DB::raw('SUM(r.total_bet_amount) as total_result_bet_amount'),
    //             DB::raw('SUM(r.win_amount) as total_result_win_amount'),
    //             DB::raw('SUM(r.net_win) as total_result_net_win')
    //         )
    //         ->groupBy('br.player_id', 'r.player_name', 'br.game_code', 'r.game_name', 'r.game_provide_name')
    //         ->orderByDesc('total_bets')
    //         ->get();
    //         //->paginate(10); // Paginate results

    //     return response()->json($report);
    // }
    // public function getGameReport(Request $request)
    // {
    //     $report = DB::table('bet_n_results as br')
    //         ->leftJoin('results as r', function ($join) {
    //             $join->on('br.game_code', '=', 'r.game_code')
    //                  ->on('br.player_id', '=', 'r.player_id'); // Ensure player_id matches
    //         })
    //         ->select(
    //             'br.game_code',
    //             'br.player_id',
    //             'r.player_name',
    //             DB::raw('COUNT(br.id) as total_bets'),
    //             DB::raw('SUM(br.bet_amount) as total_bet_amount'),
    //             DB::raw('SUM(br.win_amount) as total_win_amount'),
    //             DB::raw('SUM(br.net_win) as total_net_win'),
    //             'r.game_name',
    //             'r.game_provide_name',
    //             DB::raw('COUNT(r.id) as total_results'),
    //             DB::raw('SUM(r.total_bet_amount) as total_result_bet_amount'),
    //             DB::raw('SUM(r.win_amount) as total_result_win_amount'),
    //             DB::raw('SUM(r.net_win) as total_result_net_win')
    //         )
    //         ->groupBy('br.game_code', 'br.player_id', 'r.player_name', 'r.game_name', 'r.game_provide_name')
    //         ->orderByDesc('total_bets')
    //         ->paginate(10); // Pagination: 10 results per page

    //     return response()->json($report);
    // }
}