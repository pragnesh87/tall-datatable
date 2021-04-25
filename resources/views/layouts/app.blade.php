<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>{{ config('app.name') }}</title>
    @stack('styles')
    <script src="{{ asset('js/app.js') }}"></script>
    @livewireStyles
</head>

<body>

    {{ $slot }}

    @livewireScripts
    @stack('scripts')
</body>

</html>
