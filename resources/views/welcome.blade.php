<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>

    <title>UaiProva - Repositório de provas da UNIFEI</title>

    <!-- Material Design -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
    <v-app id="app">
        <main-app></main-app>
    </v-app>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

<style>
    .v-enter,
    .v-leave-to {
        opacity: 0;
    }

    .v-enter {
        transform: translate3d(0, -20px, 0);
    }

    .v-leave-to {
        transform: translate3d(0, 20px, 0);
    }

    .v-enter-active,
    .v-leave-active {
        transition: all 0.3s;
    }
</style>