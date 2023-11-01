<div>
    @section('page-title')
        Dashboard
    @endsection
    @section('header')

    @endsection
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" wire:click="openNewSiteModal" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Create new site
                        </button>
                        @include('livewire.site._add_new_site')
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="page-body">
       <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sites Status</h3>
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
                                        <x-form.input id="txt_title" wire:model.live="filter.site_name" placeholder="{{ __('Project') }}" />

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Manager</th>
                                    <th>Domain</th>
                                    <th>Check at</th>
                                    <th>Up Time</th>
                                    <th>Down Time</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sites as $site)
                                    <tr>
                                        <td> {{ $site->project }} </td>
                                        <td> {{ $site->manager }} </td>
                                        <td> {{ $site->url }} </td>
                                        <td> {{ $site->last_check ? date('H:m:s', strtotime($site?->last_check)) :'' }} </td>
                                        <td>{{ $site->up_at ? date('d/m/y H:m:s', strtotime($site?->up_at)) : '' }} </td>
                                        <td> {{ $site->down_at ? date('d/m/y H:m:s', strtotime($site?->down_at)) : '' }} </td>
                                        <td>
                                            @if($site->status == 0)
                                                <span class="badge bg-warning me-1"></span> Down
                                            @else
                                                <span class="badge bg-success me-1"></span> UP
                                            @endif
                                        </td>
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
                                {{ $sites->links() }}
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
       </div>
   </div>



</div>
