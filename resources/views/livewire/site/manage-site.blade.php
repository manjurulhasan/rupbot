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
                            <i class="ti ti-plus"></i>
                            Create new site
                        </button>
                        @include('livewire.site._add_new_site')
                        @include('livewire.site._edit_new_site')
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
                            <h3 class="card-title">Manage Sites</h3>
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
                                    <th>Emails</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sites as $key => $site)
                                    <tr wire:key="{{$key+1}}">
                                        <td> {{ $site->project }} </td>
                                        <td> {{ $site->manager }} </td>
                                        <td> {{ $site->url }} </td>
                                        <td>
                                            @foreach($site->contacts as $contact)
                                                {{$contact->email}},
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($site->is_active)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-warning">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            <a class="btn btn-info btn-sm" href='{{route("show.logs", ["site_id" => $site->id])}}' title="Show Logs" >
                                                <i class="ti ti-file"></i>
                                            </a>
                                            <button class="btn btn-success btn-sm" wire:click.prevent="openEditSiteModal({{$site->id}})" title="Edit Site">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" wire:click.prevent="deleteSite({{$site->id}})" title="Delete This Site">
                                                <i class="ti ti-trash"></i>
                                            </button>

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
                                {{ $sites->links() }}
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-notify/>
</div>
