import { useEffect, useState } from "react";

function Dashboard() {
  const [productos, setProductos] = useState([]);

  useEffect(() => {
    fetch("http://127.0.0.1:8000/api/productos")
      .then(res => res.json())
      .then(data => setProductos(data));
  }, []);

  return (
    <div>
      <h1>Dashboard</h1>

      <h2>Productos</h2>

      <table border="1">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Marca</th>
            <th>Proveedor</th>
          </tr>
        </thead>

        <tbody>
          {productos.map(p => (
            <tr key={p.id}>
              <td>{p.nombre}</td>
              <td>{p.precio}</td>
              <td>{p.stock}</td>
              <td>{p.marca?.nombre}</td>
              <td>{p.proveedor?.nombre}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default Dashboard;