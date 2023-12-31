<div>
    @section('page-title')
        Logs
    @endsection
    @section('header')

    @endsection

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck mb-2">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header py-2">
                            <h3 class="card-title">Sites Info</h3>
                        </div>
                        <div class="card-body border-bottom p-0">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Project</td> <td> {{ $info->project }} </td>
                                    <td>Status</td>
                                    <td>
                                        @if($info->is_active)
                                            @if($info->status == 0)
                                                <span class="badge bg-warning me-1"> <i class="ti ti-arrow-narrow-down"></i>Down</span>
                                            @elseif($info->status == 2)
                                                <span class="badge bg-indigo me-1"></span>
                                            @else
                                                <span class="badge bg-success me-1"> <i class="ti ti-arrow-narrow-up"></i>UP </span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning me-1"><i class="ti ti-player-pause"></i>
                                        @endif
                                    </td>
                                    <td>Manager</td> <td> {{ $info->manager }} </td>
                                    <td>Check At</td> <td> {{ $info->last_check ? Carbon\Carbon::parse($info->last_check)->format('d/m/y H:i:s') : 'Not scanned yet'}} </td>
                                </tr>
                                <tr>
                                    <td>Up At</td> <td> {{ $info->up_at ?  Carbon\Carbon::parse($info->up_at)->format('d/m/y H:i:s') : '' }} </td>
                                    <td>Down At</td><td> {{ $info->down_at ? Carbon\Carbon::parse($info->down_at)->format('d/m/y H:i:s') : ''}} </td>
                                    <td>Emails</td>
                                    <td colspan="3">
                                        @foreach($info->contacts as $contact)
                                            {{$contact->email}},
                                        @endforeach
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-deck">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header py-2">
                            <h3 class="card-title">Sites Logs</h3>
                        </div>
                        <div class="card-body border-bottom py-2">
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
                                    <div class="d-flex">
                                    <x-form.input id="datepicker-range" wire:model.live="filter.dates"
                                                  class="form-control flatpickr-input active py-1"
                                                   :error="$errors->first('dates')" placeholder="Date"
                                                  autocomplete="off" />
                                    <button wire:click.prevent="download" class="btn btn-success btn-sm ms-2 py-0">Download Logs</button>
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
                                        <td> {{ $log->last_check ? Carbon\Carbon::parse($log->last_check)->format('d/m/y H:i:s') : '' }} </td>
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

</div>
@push('footer')
    <script>
        $("#datepicker-range").flatpickr(
            {
                mode: "range",
                allowInput:true,
                maxDate: "today",
                dateFormat: "Y-m-d"
            });
</script>
@endpush
