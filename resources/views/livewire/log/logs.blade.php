<div>
    @section('page-title')
        Logs
    @endsection
    @section('header')

    @endsection

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sites Logs</h3>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-muted">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <x-form.select id="perPage" wire:model.live="perPage">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </x-form.select>

                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-muted">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <x-form.input id="txt_title" wire:model.live.debounce.500ms="filter.url" placeholder="{{ __('URL') }}" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Check at</th>
                                    <th>Resolved at</th>
                                    <th>Incident started at</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Code</th>
                                    <th>Root cause</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td> {{ $log->url }} </td>
                                        <td> {{ $log->last_check ? Carbon\Carbon::parse($log?->last_check)->format('d/m/y H:i:s') : '' }} </td>
                                        <td> {{ $log->up_at ? Carbon\Carbon::parse($log?->up_at)->format('d/m/y H:i:s') : '' }} </td>
                                        <td> {{ $log->down_at ? Carbon\Carbon::parse($log?->down_at)->format('d/m/y H:i:s') : '' }} </td>
                                        <td> {{ $log->duration }}</td>
                                        <td>
                                            @if($log->status == 0)
                                                <span class="badge bg-warning me-1"></span><i class="ti ti-arrow-narrow-down"></i>Down
                                            @else
                                                <span class="badge bg-success me-1"></span><i class="ti ti-arrow-narrow-up"></i>UP
                                            @endif
                                        </td>
                                        <td> {{ $log->code }} </td>
                                        <td> {{ $log->message }} </td>
                                        <td class="text-end">
                                            <a class="btn btn-info btn-sm" href='{{route("show.logs", ["site_id" => $log->site_id])}}' title="Show Logs"> <i class="ti ti-file"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No Record Found!') }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer d-flex align-items-center">

                            <ul class="pagination m-0 ms-auto">
                                {{ $logs->links() }}
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
