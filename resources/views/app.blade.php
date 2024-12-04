<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación</title>
    @vite('resources/css/app.css')
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, form) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente
            if (confirm('¿Estás seguro de que deseas eliminar este perro? Esta acción no se puede deshacer.')) {
                form.submit(); // Envía el formulario si el usuario confirma
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    @if (session('success'))
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-500 text-white p-4 rounded mb-4">
        {{ session('error') }}
    </div>
@endif


    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Listado de Perros</h1>
    
        <!-- Botón para crear nuevo perro -->
        <a href="/perro" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg mb-6 hover:bg-blue-700">Agregar Nuevo Perro</a>
    
        <!-- Tabla de perros -->
        <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Color</th>
                    <th class="px-4 py-2 text-left">Edad</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perros as $perro)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $perro->nombre }}</td>
                        <td class="px-4 py-2">{{ $perro->color }}</td>
                        <td class="px-4 py-2">{{ $perro->edad }}</td>
                        <td class="px-4 py-2">
                            <a href="/perros/{{ $perro->id }}" class="text-blue-500 hover:underline">Editar</a> |
                            <form action="/perros/{{ $perro->id }}" method="POST" class="inline" onsubmit="confirmarEliminacion(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                            </form>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center">No hay perros registrados actualmente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</body>
</html>
