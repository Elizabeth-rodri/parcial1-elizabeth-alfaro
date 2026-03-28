<h1>Listado de Productos</h1>

<a href="{{ route('productos.create') }}">➕ Nuevo Producto</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Marca</th>
    
        <th>Proveedor</th>
        <th>Acciones</th>
    </tr>

    @foreach($productos as $producto)
    <tr>
        <td>{{ $producto->id }}</td>
        <td>{{ $producto->nombre }}</td>
        <td>${{ $producto->precio }}</td>
        <td>{{ $producto->stock }}</td>

        <!-- Relaciones -->
        <td>{{ $producto->marca->nombre ?? 'Sin marca' }}</td>
        
        <td>{{ $producto->proveedor->nombre ?? 'Sin proveedor' }}</td>

        <td>
            <a href="{{ route('productos.edit', $producto->id) }}">✏️ Editar</a>

            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('¿Eliminar este producto?')">
                    🗑️ Eliminar
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>