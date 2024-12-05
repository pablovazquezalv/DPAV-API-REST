<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Razas</title>
    @vite('resources/css/app.css')
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, form) {
            event.preventDefault(); // Evita el envío automático del formulario
            if (confirm('¿Estás seguro de que deseas eliminar esta raza? Esta acción no se puede deshacer.')) {
                form.submit(); // Envía el formulario si el usuario confirma
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Mensajes de éxito/error -->
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

    <x-navbar />

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Listado de Razas</h1>

        <!-- Botón para agregar nueva raza -->
        <a href="/raza" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg mb-6 hover:bg-blue-700">
            Agregar Nueva Raza
        </a>

        <!-- Tabla de razas -->
        <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($razas as $raza)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $raza->nombre }}</td>
                        <td class="px-4 py-2">
                            <!-- Enlace para editar -->
                            <a href="/razas/{{ $raza->id }}" class="text-blue-500 hover:underline">Editar</a> |

                            <!-- Botón para eliminar -->
                            <form action="/razas/{{ $raza->id }}" method="POST" class="inline" onsubmit="confirmarEliminacion(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-center">No hay razas registradas actualmente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
