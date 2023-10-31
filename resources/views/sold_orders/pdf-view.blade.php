@extends('layouts.app')
{{--
<link rel="stylesheet" href="{{ public_path('css/app.css') }}"> --}}
{{-- <style>
    /* Estilos globales para el PDF */
    @font-face {
        font-family: "poppins-regular";
        src: local("Poppins Regular"), local('Poppins-Regular');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        src: local('Roboto Light'), local('Roboto-Light'), url(https://fonts.gstatic.com/s/roboto/v20/KFOlCnqEu92Fr1MmSU5vAw.ttf) format('truetype');
    }

    .pdf-container {
        border: 1px solid #000;
        padding: 10px;
        font-family: 'Roboto';
        /* font-family: poppins-regular; */
    }

    .bg-slate-700 {
        background: #333;
        color: #fff;
        padding: 0 4rem;
        text-align: center;
        /* font-family: poppins-light; */
    }
</style> --}}

@section('content')


<!-- Contenido del PDF -->
<div class="pdf-container">
    <h1 class="bg-slate-700">Detalles de la factura </h1>
    <p class="">Número de factura: DIegos </p>
    <!-- Agrega más detalles de la factura según tus necesidades -->
</div>
@endsection