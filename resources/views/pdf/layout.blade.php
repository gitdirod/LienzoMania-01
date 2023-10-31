<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{--
    <link rel="stylesheet" href="{{ url('css/app.css') }}"> --}}
    {{--
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}" media="all" /> --}}
    {{--

    <link href="/css/build.css" rel="stylesheet"> --}}
    {{--
    <link rel="stylesheet" href="{{ asset('/css/build.css') }}"> --}}


    {{--
    <link rel="stylesheet" href="{{ public_path('styles.css') }}"> --}}
    {{--
    <link rel="stylesheet" href="./styles.css'"> --}}

    @vite('resources/css/app.css')
    {{--
    <base href="http://127.0.0.1/"> --}}

    <link href="/css/build.css" rel="stylesheet">

    {{--
    <link href="../../public/css/app.css" rel="stylesheet"> --}}








    {{-- <style>
        .note {
            background-color: orange;
            border: 2px dashed orangered;
            padding: 20px;
        }

        .page-break {
            page-break-after: always;
        }
    </style> --}}
</head>

<body>
    @include('pdf._header')
    @yield('content')

</body>

</html>