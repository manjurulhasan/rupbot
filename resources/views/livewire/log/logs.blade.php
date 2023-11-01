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
                                        <x-form.input id="txt_title" wire:model.live="filter.url" placeholder="{{ __('URL') }}" />

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
                                    <th>Up Time</th>
                                    <th>Down Time</th>
                                    <th>Status</th>
                                    <th>Code</th>
                                    <th>Message</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td> {{ $log->url }} </td>
                                        <td> {{ $log->last_check ? date('H:m:s', strtotime($log?->last_check)) :'' }} </td>
                                        <td>{{ $log->up_at ? date('d/m/y H:m:s', strtotime($log?->up_at)) : '' }} </td>
                                        <td> {{ $log->down_at ? date('d/m/y H:m:s', strtotime($log?->down_at)) : '' }} </td>
                                        <td>
                                            @if($log->status == 0)
                                                <span class="badge bg-warning me-1"></span> Down
                                            @else
                                                <span class="badge bg-success me-1"></span> UP
                                            @endif
                                        </td>
                                        <td> {{ $log->code }} </td>
                                        <td> {{ $log->message }} </td>
                                        <td class="text-end">  </td>
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
