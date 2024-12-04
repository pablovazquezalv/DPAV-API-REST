<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Perro</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Crear Nuevo Perro</h1>

    <form action="/api/crearPerro" method="POST" class="space-y-6">
        @csrf
        <!-- Campo Nombre -->
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Color -->
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <input type="text" id="color" name="color" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Edad -->
        <div>
            <label for="edad" class="block text-sm font-medium text-gray-700">Edad</label>
            <input type="number" id="edad" name="edad" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Sexo -->
        <div>
            <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
            <select id="sexo" name="sexo" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="Macho">Macho</option>
                <option value="Hembra">Hembra</option>
            </select>
        </div>

        <!-- Campo Peso -->
        <div>
            <label for="peso" class="block text-sm font-medium text-gray-700">Peso</label>
            <input type="number" id="peso" name="peso" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Tamaño -->
        <div>
            <label for="tamaño" class="block text-sm font-medium text-gray-700">Tamaño</label>
            <select id="tamaño" name="tamaño" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="Pequeño">Pequeño</option>
                <option value="Mediano">Mediano</option>
                <option value="Grande">Grande</option>
            </select>
        </div>

        <!-- Campo Fecha de Nacimiento -->
        <div>
            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Raza -->
        <div>
            <label for="id_raza" class="block text-sm font-medium text-gray-700">Raza</label>
            <select id="id_raza" name="id_raza" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <!-- Opciones dinámicas desde el controlador -->
                @foreach ($razas as $raza)
                    <option value="{{ $raza->id }}">{{ $raza->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Crear Perro</button>
            <a href="/perros" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
