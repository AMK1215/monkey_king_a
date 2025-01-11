@extends('admin_layouts.app')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">

                    <div class="card-body">
                        <h5 class="mb-0">Win/Lose Details
                            <a href="{{ url('/admin/game-report') }}" class="btn btn-secondary mb-3">Back to Report</a>
                            <span></span>
                        </h5>
                    </div>
                    {{-- <form action="{{ route('admin.report.detail', $playerId) }}" method="GET">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="input-group input-group-static mb-4">
                                    <label for="">Product Type</label>
                                    <select name="product_type_id" id="" class="form-control">
                                        <option value="" disabled>Select Product type</option>
                                        @foreach ($productTypes as $type)
                                            <option value="{{ $type->provider_name }}">{{ $type->provider_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-static mb-4">
                                    <label for="">Start Date</label>
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
                                <a href="{{ route('admin.report.detail', $playerId) }}"
                                    class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Refresh">
                                    <i class="material-icons text-lg mt-0">refresh</i>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary" id="search" type="submit">Search</button>
                                <a href="{{ route('admin.report.detail', $playerId) }}"
                                    class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Refresh">
                                    <i class="material-icons text-lg mt-0">refresh</i>
                                </a>
                            </div>
                        </div>
                    </form> --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="users-search">
                        <thead class="table-dark">
                            <tr>
                                <th>Player ID</th>
                                <th>Player Name</th>
                                <th>Game Code</th>
                                <th>Game Name</th>
                                <th>Game Provider</th>
                                <th>Bet Amount</th>
                                <th>Win Amount</th>
                                <th>Net Win</th>
                                <th>Total Bet Amount</th>
                                <th>Result Win Amount</th>
                                <th>Result Net Win</th>
                                <th>Bet Time</th>
                                <th>Result Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $data)
                                <tr>
                                    <td>{{ $data->player_id }}</td>
                                    <td>{{ $data->player_name }}</td>
                                    <td>{{ $data->game_code }}</td>
                                    <td>{{ $data->game_name }}</td>
                                    <td>{{ $data->game_provide_name }}</td>
                                    <td>{{ number_format($data->bet_amount, 2) }}</td>
                                    <td>{{ number_format($data->win_amount, 2) }}</td>
                                    <td>{{ number_format($data->net_win, 2) }}</td>
                                    <td>{{ number_format($data->total_bet_amount, 2) }}</td>
                                    <td>{{ number_format($data->result_win_amount, 2) }}</td>
                                    <td>{{ number_format($data->result_net_win, 2) }}</td>
                                    <td>{{ $data->bet_time }}</td>
                                    <td>{{ $data->result_time }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                perPage: 7
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
