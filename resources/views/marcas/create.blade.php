<h1>Crear Marca</h1>

<a href="{{ route('marcas.index') }}">⬅️ Volver</a>

<br><br>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('marcas.store') }}" method="POST">
    @csrf

    <label>Nombre de la marca:</label><br>
    <input type="text" name="nombre" value="{{ old('nombre') }}"><br><br>

    <button type="submit">Guardar</button>
</form>