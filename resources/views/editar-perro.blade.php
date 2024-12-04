<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Editar Perro</h1>

    <form action="/perros/{{ $perro->id }}/edit" method="POST" class="space-y-6">
        @csrf
        @method('PUT') <!-- Método PUT para actualización -->

        <!-- Campo Nombre -->
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ $perro->nombre }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Color -->
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <input type="text" id="color" name="color" value="{{ $perro->color }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Edad -->
        <div>
            <label for="edad" class="block text-sm font-medium text-gray-700">Edad</label>
            <input type="number" id="edad" name="edad" value="{{ $perro->edad }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Campo Sexo -->
        <div>
            <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
            <select id="sexo" name="sexo" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="macho" {{ $perro->sexo == 'macho' ? 'selected' : '' }}>Macho</option>
                <option value="hembra" {{ $perro->sexo == 'hembra' ? 'selected' : '' }}>Hembra</option>
            </select>
        </div>

        <!-- Campo Peso -->
        <div>
            <label for="peso" class="block text-sm font-medium text-gray-700">Peso</label>
            <input type="number" id="peso" name="peso" value="{{ $perro->peso }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Actualizar Perro</button>
            <a href="/perros" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
