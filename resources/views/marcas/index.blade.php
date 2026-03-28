<h1>Marcas</h1>

<a href="{{ route('marcas.create') }}">➕ Nueva Marca</a>

<table border="1">
@foreach($marcas as $m)
<tr>
    <td>{{ $m->id }}</td>
    <td>{{ $m->nombre }}</td>
    <td>
        <a href="{{ route('marcas.edit', $m) }}">Editar</a>

        <form action="{{ route('marcas.destroy', $m) }}" method="POST">
            @csrf
            @method('DELETE')
            <button>Eliminar</button>
        </form>
    </td>
</tr>
@endforeach
</table>