<h1>Lista de Proveedores</h1>

<a href="{{ route('proveedores.create') }}">➕ Nuevo Proveedor</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Acciones</th>
    </tr>

    @foreach($proveedores as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->nombre }}</td>
        <td>{{ $p->telefono }}</td>
        <td>{{ $p->correo }}</td>
        <td>
            <a href="{{ route('proveedores.edit', $p) }}">✏️ Editar</a>

            <form action="{{ route('proveedores.destroy', $p) }}" method="POST">
                @csrf
                @method('DELETE')
                <button>🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>