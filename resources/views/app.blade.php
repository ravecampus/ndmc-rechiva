<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('/css/font-awesome/css/font-awesome.min.css')}}">
    @vite(['resources/sass/app.scss','resources/js/app.js'])
</head>
<body>

        @if (Auth::check())
            @php
            $user_auth_data = [
                'isLoggedin' => true,
                'user' =>  Auth::user(),
            ];
            @endphp
        @else
            @php
            $user_auth_data = [
                'isLoggedin' => false
            ];
            @endphp
        @endif

    <div id="app" class="container-body z-1">
    
    </div>
    <footer class="footer d-flex flex-wrap justify-content-between align-items-center border-top position-fixed z-3">
        <p class="col-md-4 mb-0 text-white">UIM/\V7 - © 2024 Company, Inc</p>

    </footer>
    <!-- <script src="{{ asset('js/pspdfkit.js') }}"></script> -->
    <script>
  
        window.winsdev = JSON.parse(atob('{{ base64_encode(json_encode($user_auth_data)) }}'));
    </script>


</body>
</html>