<!DOCTYPE html>
<html>

<head>
    <title>Factura</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Carga las clases de Tailwind CSS -->
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Factura</h1>
        <!-- Aquí puedes mostrar la información de la factura utilizando la variable $invoice -->
        {{-- <p>Número de factura: {{ $invoice->invoice_number }}</p> --}}
        {{-- <p>Cliente: {{ $invoice->customer->name }}</p> --}}

        <p>Número de factura: Diego</p>
        <p>Cliente: Ing de casa</p>
        <!-- Agrega más detalles de la factura según tu modelo -->
    </div>
</body>

</html>