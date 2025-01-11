@extends('admin_layouts.app')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">

                    <div class="card-body">
                        <h5 class="mb-0">Win/Lose Report</h5>
                    </div>
                    <form action="{{ route('admin.report.index') }}" method="GET">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="input-group input-group-static mb-4">
                                    <label for="">PlayerId</label>
                                    <input type="text" class="form-control" name="player_id"
                                        value="{{ request()->player_id }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-static mb-4">
                                    <label for="">StartDate</label>
                                    <input type="datetime-local" class="form-control" name="start_date"
                                        value="{{ request()->get('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-static mb-4">
                                    <label for="">EndDate</label>
                                    <input type="datetime-local" class="form-control" name="end_date"
                                        value="{{ request()->get('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary" id="search" type="submit">Search</button>
                                <a href="{{ route('admin.report.index') }}"
                                    class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Refresh">
                                    <i class="material-icons text-lg mt-0">refresh</i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="users-search">
                        <thead class="table-dark">
                            <tr>
                                <th>Player ID</th>
                                {{-- <th>Player Name</th> --}}
                                <th>Game Code</th>
                                <th>Game Name</th>
                                <th>Game Provider</th>
                                <th>Total Bets</th>
                                <th>Total Bet Amount</th>
                                <th>Total Win Amount</th>
                                <th>Total Net Win</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $data)
                                <tr>
                                    <td>{{ $data->player_id }}</td>
                                    {{-- <td>{{ $data->player_name }}</td> --}}
                                    <td>{{ $data->game_code }}</td>
                                    <td>{{ $data->game_name }}</td>
                                    <td>{{ $data->game_provide_name }}</td>
                                    <td>
                                        @if ($data->total_results == 0)
                                            {{ $data->total_bets }}
                                        @else
                                            {{ $data->total_results }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->total_result_bet_amount == 0)
                                            {{ number_format($data->total_bet_amount, 2) }}
                                        @else
                                            {{ number_format($data->total_result_bet_amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->total_result_bet_amount == 0)
                                            {{ number_format($data->total_win_amount, 2) }}
                                        @else
                                            {{ number_format($data->total_result_win_amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->total_result_net_win == 0)
                                            {{ number_format($data->total_net_win, 2) }}
                                        @else
                                            {{ number_format($data->total_result_net_win, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.game.report.detail', ['player_id' => $data->player_id, 'game_code' => $data->game_code]) }}"
                                            class="btn btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Laravel Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $report->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
    <script>
        if (document.getElementById('users-search')) {
            const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
                searchable: true,
                fixedHeight: false,
                perPage: 10
            });

        };
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
