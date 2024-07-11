@extends('layouts.app')

@section('content')

    <script>
        function writeToken(token) {
            localStorage.setItem('api_token', token);
            test();
        }

        function test() {
            const token = localStorage.getItem('api_token');

            $.ajax({
                url: '{{ route('user') }}',
                method: 'GET',
                data: {},
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                error: function (error) {
                    console.error('Error logging in: ' + error.message);
                }
            });
        }
    </script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('token'))
                            <script>
                                writeToken("{{ session('token') }}");
                            </script>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
