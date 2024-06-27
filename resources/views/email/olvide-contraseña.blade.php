<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Document</title>
</head>
<body>
    
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">DPAV</h1>
    <br>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">Restablecer Contraseña</h1>

    <p class="text-center">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <a href="{{$url}}">        Restablecer contraseña</a></button>
    <p class="text-center">Si no ha solicitado restablecer la contraseña, por favor ignore este mensaje.</p>
    <p class="text-center">Gracias!</p>

</body>
</html>