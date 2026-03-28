<h1>Editar Proveedor</h1>

<form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="nombre" value="{{ $proveedor->nombre }}"><br>
    <input type="text" name="telefono" value="{{ $proveedor->telefono }}"><br>
    <input type="email" name="correo" value="{{ $proveedor->correo }}"><br>

    <button>Actualizar</button>
</form>