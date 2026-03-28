<h1>Editar Marca</h1>

<form action="{{ route('marcas.update', $marca) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="nombre" value="{{ $marca->nombre }}">
    <button>Actualizar</button>
</form>