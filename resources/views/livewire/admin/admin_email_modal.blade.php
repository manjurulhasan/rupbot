
<x-modal on="openAdminEmailModal" modal-id="openAdminEmailModal" title="{{$email_id ? 'Update' : 'New'}} Admin Email" size="lg" :has-button="false" functionCall="submitAdminEmail">

        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required">Email</label>
                    <input type="text" wire:model="email.email" class="form-control">
                    @error('email.email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required"> Status</label>
                    <select class="form-control" wire:model="email.is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('email.is_active') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
</x-modal>

