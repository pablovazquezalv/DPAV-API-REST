<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Raza</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Crear Nueva Raza</h1>

    <form action="/api/crearRaza" method="POST" class="space-y-6">
        @csrf

        <!-- Campo Nombre -->
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

     

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Crear Raza</button>
            <a href="/razas" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
