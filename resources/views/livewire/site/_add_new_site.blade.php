<x-modal  on="openNewSiteModal" modal-id="modalBooking" title="New Site" size="lg" :has-button="false" functionCall="addNewSite">

        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <input type="text" wire:model="site.project" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Manager</label>
                    <input type="text" wire:model="site.manager" class="form-control">
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <label class="form-label">Site URL</label>
                    <textarea wire:model="site.url" class="form-control" rows="1"></textarea>
                </div>
            </div>
        </div>
</x-modal>

