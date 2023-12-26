<x-modal  on="openNewSiteModal" modal-id="openNewSiteModal" title="New Site" size="lg" :has-button="false" functionCall="addNewSite">

        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required">Project</label>
                    <input type="text" wire:model="site.project" class="form-control">
                    @error('site.project') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required">Manager</label>
                    <input type="text" wire:model="site.manager" class="form-control">
                    @error('site.manager') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <label class="form-label required">Project URL</label>
                    <input type="text" wire:model="site.url" class="form-control">
                    @error('site.url') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            @foreach($emails as $key => $email)
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="text" wire:model="emails.{{$key}}.email" class="form-control">
                        @error("emails.$key.email") <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                @if($loop->last)
                    <div class="col-lg-12 mb-2">
                        <button type="button" wire:click.prevent="addEmail" class="btn btn-link" {{ $disabled ? 'disabled' : '' }}>{{ __('Add Another Email') }}</button>
                    </div>
                @endif
            @endforeach
        </div>
</x-modal>

