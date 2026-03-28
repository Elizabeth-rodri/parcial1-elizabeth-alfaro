<h1>Editar Producto</h1>

<form action="{{ route('productos.update', $producto) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="nombre" value="{{ $producto->nombre }}"><br><br>

    <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}"><br><br>

    <input type="number" name="stock" value="{{ $producto->stock }}"><br><br>

    <label>Marca:</label>
    <select name="marca_id">
        @foreach($marcas as $m)
            <option value="{{ $m->id }}" {{ $producto->marca_id == $m->id ? 'selected' : '' }}>
                {{ $m->nombre }}
            </option>
        @endforeach
    </select><br><br>

    

    <label>Proveedor:</label>
    <select name="proveedor_id">
        @foreach($proveedores as $p)
            <option value="{{ $p->id }}" {{ $producto->proveedor_id == $p->id ? 'selected' : '' }}>
                {{ $p->nombre }}
            </option>
        @endforeach
    </select><br><br>

    <button>Actualizar</button>
</form>