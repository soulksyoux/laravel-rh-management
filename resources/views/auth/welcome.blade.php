<x-layout-guest page-title="Welcome Page">
    <div class="container mt-5">
        <div class="row justify-center">
            <div class="col">
                <div class="text-center mb-5">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="200px">
                </div>
                <div class="card p-5 text-center">
                    <p>Welcome, <strong> {{ $user->name }} </strong></p>
                    <p>Your account was succesfully created.</p>
                    <p>You can now <a href="{{ route('login') }}">Login</a> to your account.</p>
                </div>
            </div>
            <hr>
        </div>
    </div>
</x-layout-guest>
