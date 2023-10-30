<div>
    @section('page-title')
        Dashboard
    @endsection
    @section('header')
        <div class="row g-2 align-items-center">
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-report">
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Create new site
            </a>
        </div>
        </div>
    </div>
    @endsection
        
    @section('content')
        <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sites Status</h3>
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
                    <td>
                        {{ $site->project }}
                    </td>
                    <td>
                        {{ $site->manager }}
                    </td>
                    <td>
                        {{ $site->url }}
                    </td>
                    <td>
                        {{ date('H:m:s', strtotime($site?->last_check)) }}
                    </td>
                    <td>
                        {{ $site->up_at ? date('d/m/y H:m:s', strtotime($site?->up_at)) : '' }}
                    </td>
                    <td>
                        {{ $site->down_at ? date('d/m/y H:m:s', strtotime($site?->down_at)) : '' }}
                    </td>
                    <td>
                        @if($site->status == 0)
                        <span class="badge bg-warning me-1"></span> Down
                        @else
                        <span class="badge bg-success me-1"></span> UP
                        @endif
                    </td>
                    <td class="text-end">

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
                <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                {{ $sites->links() }}
            </div>
            </div>
        </div>
        </div>
    @endsection
</div>