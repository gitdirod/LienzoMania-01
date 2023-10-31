<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <link rel="stylesheet" href="{{ public_path('/css/styles.css') }}">
    {{-- @vite('resources/css/app.css') --}}

    {{--
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    <title>Tu Aplicación</title>
    <!-- Agrega tus hojas de estilo CSS, scripts JavaScript, etc. aquí -->
</head>

<body>
    <header>
        <!-- Aquí puedes colocar la barra de navegación, logotipo, etc. -->
    </header>

    <main>
        @yield('content')
        <!-- Aquí se incluirá el contenido de las vistas hijas -->
    </main>

    <footer>
        <!-- Aquí puedes colocar el pie de página, información de contacto, etc. -->
    </footer>
</body>

</html>