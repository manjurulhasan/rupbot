<div>
    @section('page-title')
        Profile
    @endsection
    @section('header')

    @endsection

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Profile</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <div>
                                    <input type="text" class="form-control" wire:model="user.name" aria-describedby="name" placeholder="Enter Name">
                                    @error('user.name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email address</label>
                                <div>
                                    <input type="email" class="form-control" wire:model="user.email"  aria-describedby="email" placeholder="Enter email">
                                    @error('user.email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div>
                                    <input type="password" class="form-control" wire:model="user.password" placeholder="Password">
                                    @error('user.password') <span class="text-danger">{{ $message }}</span> @enderror
                                    <small class="form-hint">
                                        Your password must be 8-20 characters long, contain letters and numbers, and must not contain emoji.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="button" wire:click.prevent="updateProfile" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <x-notify/>
</div>
