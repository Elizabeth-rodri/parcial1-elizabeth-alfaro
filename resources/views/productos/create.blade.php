<h1>Nuevo Producto</h1>

<form action="{{ route('productos.store') }}" method="POST">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre"><br><br>

    <input type="number" step="0.01" name="precio" placeholder="Precio"><br><br>

    <input type="number" name="stock" placeholder="Stock"><br><br>

    <label>Marca:</label>
    <select name="marca_id">
        @foreach($marcas as $m)
            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
        @endforeach
    </select><br><br>

    

    <label>Proveedor:</label>
    <select name="proveedor_id">
        @foreach($proveedores as $p)
            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
        @endforeach
    </select><br><br>

    <button>Guardar</button>
</form>