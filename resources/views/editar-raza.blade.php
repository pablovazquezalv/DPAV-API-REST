<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Raza</title>
    @vite('resources/css/app.css')  <!-- Usa Vite para cargar los estilos si es necesario -->
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Editar Raza</h1>

    <!-- Verifica si existe un mensaje de error o éxito -->
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulario de edición de raza -->
    <form action="/razas/{{ $raza->id }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT') <!-- Utilizamos PUT para indicar que estamos actualizando -->

        <!-- Campo Nombre -->
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $raza->nombre) }}" 
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('nombre')
                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Actualizar Raza</button>
            <a href="/razas" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
