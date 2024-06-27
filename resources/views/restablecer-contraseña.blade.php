

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
    <br>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">DPAV</h1>
        <br>
       <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">Restablecer Contraseña</h1>
        <p>{{$user->email}}</p>
        <br>
<form class="max-w-sm mx-auto" method="POST" action="{{ route('restablecerContraseña') }}">
    @csrf

    <p>Hola, para restablecer tu contraseña, llena el siguiente formulario.</p>
<br>
@if ($errors->any())
<div class="mb-5">
    <div class="bg-red-500  text-sm rounded-lg p-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
    <div class="mb-5">
        <input type="hidden" name="email" value="{{ $user->email }}">
      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nueva Contraseña:</label>
      <input type="password"  name="password"  id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
    </div>
    <div class="mb-5">
      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar Contraseña: </label>
      <input type="password" id="password"  name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
    </div>
   
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Restablecer contraseña</button>
  </form>
  
    </div>
    
    
</body>
</html>



