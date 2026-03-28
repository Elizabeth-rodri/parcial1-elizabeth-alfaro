<h1>Nuevo Proveedor</h1>

<form action="{{ route('proveedores.store') }}" method="POST">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre"><br>
    <input type="text" name="telefono" placeholder="Teléfono"><br>
    <input type="email" name="correo" placeholder="Correo"><br>

    <button>Guardar</button>
</form>