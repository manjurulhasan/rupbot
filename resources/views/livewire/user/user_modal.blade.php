
<x-modal  on="openUserModal" modal-id="openUserModal" title="{{$user_id ? 'Update' : 'New'}} User" size="lg" :has-button="false" functionCall="submitUser">

        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required">name</label>
                    <input type="text" wire:model="user.name" class="form-control">
                    @error('user.name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required">Email</label>
                    <input type="text" wire:model="user.email" class="form-control">
                    @error('user.email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label {{$user_id ? '' : 'required'}}"> Password</label>
                    <input type="text" wire:model="user.password" class="form-control">
                    @error('user.password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label required"> Role</label>
                    <select class="form-control" wire:model="user.role">
                        @hasanyrole('Super-Admin')
                            <option value="1">Super Admin</option>
                        @endhasanyrole
                        <option value="2">Admin</option>
                        <option value="3">Viewer</option>
                    </select>
                    @error('user.role') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
</x-modal>

