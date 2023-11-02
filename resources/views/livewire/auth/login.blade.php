<div>

    <div class="text-center mb-2">
        <a href="/" class="navbar-brand navbar-brand-autodark"><img src="{{asset('assets/img/selise_logo.png')}}" height="60" alt="rupbot"></a>
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Login to your account</h2>
            <p class="h2 text-center mb-4">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ __(session('error')) }}
                    </div>
                @endif
            </p>
            <form wire:submit.prevent="doLogin" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" wire:model="email" class="form-control" placeholder="Enter Email">
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label"> Password </label>
                    <div class="input-group input-group-flat">
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter password"
                            aria-label="Password"
                            aria-describedby="password-addon"
                            wire:model="password"
                        >

                        <button class="btn " type="button" id="password-addon" title="Show password" data-bs-toggle="tooltip">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </button>

                        @error('password')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <span class="form-label-description mb-2">
                                <a href="#">I forgot password</a>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Sign in</button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('footer')
    <script>
        $("#password-addon").on("click",function(){0<$(this).siblings("input").length&&("password"==$(this).siblings("input").attr("type")?$(this).siblings("input").attr("type","input"):$(this).siblings("input").attr("type","password"))});
    </script>
@endpush
