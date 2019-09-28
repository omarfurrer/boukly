@extends('layouts.main')

@section('content')
<div id="landing">
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-3">Boukly.io</h1>
            <p>Boukly.io is a free online application that I created to help solve the problem of organizing bookmarks.
                Sign up, download the chrome extension, start using Boukly and enjoy bookmarking again.
            </p>
            <p>I am always improving Boukly. If you have any questions or want new features added, please contact me using the button below.</p>
            <p><a
                    class="btn btn-primary btn-lg"
                    href="javascript:void(Tawk_API.toggle())"
                    role="button"
                >Contact Me »</a></p>
        </div>
    </div>
    <div class="container">
        <div class="main text-center">
            <h1>Sign Up</h1>
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
                            Go
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="container-fluid example-section">
        <div class="row justify-content-lg-center">
            <div class="col-lg-8">
                <img
                    src="/images/example.png"
                    class="img-fluid mt-5"
                    alt="Responsive image"
                >
            </div>
        </div>
    </div>
    <div class="container mt-5 mb-5">

        <h1 class="text-center">Features</h1>

        <div class="row mt-5">
            <div class="col-md-4">
                <h2>Cloud</h2>
                <p>Boukly keeps your bookmarks stored securely on the cloud so you will never have to lose them
                    again while having the ability to access them from anywhere.</p>
            </div>
            <div class="col-md-4">
                <h2>Organize</h2>
                <p>Boukly tries to organize your bookmarks for you by sorting them into different tags according to the
                    domain</p>
            </div>
            <div class="col-md-4">
                <h2>Adult</h2>
                <p>Boukly will automatically detect if a bookmark has adult content and mark it as private</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <h2>Info</h2>
                <p>Boukly will try to extract as much info from the bookmark as possible and display it on your
                    dashboard</p>
            </div>
            <div class="col-md-4">
                <h2>Tags</h2>
                <p>You can filter your bookmarks by tags and you can even search through the tags themselves</p>
            </div>
            <div class="col-md-4">
                <h2>Broken</h2>
                <p>Boukly will try and identify broken links for you that are no longer working and notify you about
                    them</p>
            </div>
        </div>
    </div>

</div>
@endsection