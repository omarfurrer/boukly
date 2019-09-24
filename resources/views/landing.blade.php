@extends('layouts.main')

@section('content')
<div id="landing">
    <div class="container">

        <div class="main text-center">
            <h1>Boukly.io</h1>
            <h3>Private Bookmarking For Your Needs</h3>
        </div>
        <div class="cta mt-5">
            <form
                method="POST"
                action="{{ route('register') }}"
            >
                @csrf

                <div class="form-group row justify-content-center">
                    <div class="col-md-6">
                        <input
                            placeholder="Email"
                            id="email"
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >

                        @error('email')
                        <span
                            class="invalid-feedback"
                            role="alert"
                        >
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    <div class="col-md-6">
                        <input
                            placeholder="Password"
                            id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="new-password"
                        >

                        @error('password')
                        <span
                            class="invalid-feedback"
                            role="alert"
                        >
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    <div class="col-md-6">
                        <input
                            placeholder="Confirm Password"
                            id="password-confirm"
                            type="password"
                            class="form-control"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        >
                    </div>
                </div>

                <div class="form-group row mb-0 justify-content-center">
                    <div class="col-md-6">
                        <button
                            type="submit"
                            class="btn btn-primary btn-block"
                        >
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="main-features mt-5 text-center">
            <div class="row">
                <div class="col mx-3">
                    {{-- <div class="mx-2"> --}}
                    <h4>Privacy</h5>
                        <h5>With Boukly you can mark your bookmarks as private and they will be hidden in your
                            dashboard
                            untill you provide your password.</h5>
                        {{-- </div> --}}
                </div>
                <div class="col mx-3">
                    <h4>Cloud</h5>
                        <h5>Boukly keeps your bookmarks stored securely on the cloud so you will never have to lose them
                            again while having the ability to access them from anywhere.</h5>
                </div>
                <div class="col mx-3">
                    <h4>Organize</h5>
                        <h5>You can add tags to your bookmarks to organize them. Boukly will also attempt to tag your
                            boomarks for you.</h5>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col mx-3">
                    {{-- <div class="mx-2"> --}}
                    <h4>Privacy</h5>
                        <h5>With Boukly you can mark your bookmarks as private and they will be hidden in your
                            dashboard
                            untill you provide your password.</h5>
                        {{-- </div> --}}
                </div>
                <div class="col mx-3">
                    <h4>Cloud</h5>
                        <h5>Boukly keeps your bookmarks stored securely on the cloud so you will never have to lose them
                            again while having the ability to access them from anywhere.</h5>
                </div>
                <div class="col mx-3">
                    <h4>Organize</h5>
                        <h5>You can add tags to your bookmarks to organize them. Boukly will also attempt to tag your
                            boomarks for you.</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection