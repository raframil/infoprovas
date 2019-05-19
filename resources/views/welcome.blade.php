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
        <navbar></navbar>

        <v-content class="mx-4 my-4">

            <router-view></router-view>

        </v-content>

        <footer-component></footer-component>
    </v-app>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>