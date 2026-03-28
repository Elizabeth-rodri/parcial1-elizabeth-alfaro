import React, { useState, useEffect } from "react";

const Dashboard = () => {
  // Estados
  const [marcas, setMarcas] = useState([]);
  const [proveedores, setProveedores] = useState([]);
  const [productos, setProductos] = useState([]);

  const [nombreMarca, setNombreMarca] = useState("");
  const [nombreProveedor, setNombreProveedor] = useState("");
  const [nombreProducto, setNombreProducto] = useState("");

  // Token de autenticación si tus rutas están protegidas
  const token = "TU_TOKEN_AQUI"; 
  const headers = {
    "Content-Type": "application/json",
    Authorization: `Bearer ${token}`,
  };

  // URLs de tu API
  const apiMarcas = "http://localhost:8000/api/catalogos/marcas";
  const apiProveedores = "http://localhost:8000/api/catalogos/proveedores";
  const apiProductos = "http://localhost:8000/api/productos";

  // ===================== FETCH =====================
  const fetchMarcas = () =>
    fetch(apiMarcas, { headers })
      .then(res => res.json())
      .then(data => setMarcas(data.data || []))
      .catch(err => console.error(err));

  const fetchProveedores = () =>
    fetch(apiProveedores, { headers })
      .then(res => res.json())
      .then(data => setProveedores(data.data || []))
      .catch(err => console.error(err));

  const fetchProductos = () =>
    fetch(apiProductos, { headers })
      .then(res => res.json())
      .then(data => setProductos(data.data || []))
      .catch(err => console.error(err));

  // Cargar datos al iniciar
  useEffect(() => {
    fetchMarcas();
    fetchProveedores();
    fetchProductos();
  }, []);

  // ===================== AGREGAR =====================
  const agregarMarca = () => {
    if (!nombreMarca) return;
    fetch(apiMarcas, {
      method: "POST",
      headers,
      body: JSON.stringify({ nombre: nombreMarca }),
    })
      .then(() => {
        setNombreMarca("");
        fetchMarcas();
      })
      .catch(err => console.error(err));
  };

  const agregarProveedor = () => {
    if (!nombreProveedor) return;
    fetch(apiProveedores, {
      method: "POST",
      headers,
      body: JSON.stringify({ nombre: nombreProveedor }),
    })
      .then(() => {
        setNombreProveedor("");
        fetchProveedores();
      })
      .catch(err => console.error(err));
  };

  const agregarProducto = () => {
    if (!nombreProducto) return;
    fetch(apiProductos, {
      method: "POST",
      headers,
      body: JSON.stringify({ nombre: nombreProducto }),
    })
      .then(() => {
        setNombreProducto("");
        fetchProductos();
      })
      .catch(err => console.error(err));
  };

  // ===================== ACTUALIZAR =====================
  const actualizarItem = (url, id, nombre, fetchFunc) => {
    const nuevoNombre = prompt("Nuevo nombre:", nombre);
    if (!nuevoNombre) return;
    fetch(`${url}/${id}`, {
      method: "PUT",
      headers,
      body: JSON.stringify({ nombre: nuevoNombre }),
    })
      .then(() => fetchFunc())
      .catch(err => console.error(err));
  };

  // ===================== ELIMINAR =====================
  const eliminarItem = (url, id, fetchFunc) => {
    if (!window.confirm("¿Estás seguro de eliminar este item?")) return;
    fetch(`${url}/${id}`, { method: "DELETE", headers })
      .then(() => fetchFunc())
      .catch(err => console.error(err));
  };

  // ===================== RENDER =====================
  return (
    <div style={{ padding: "20px" }}>
      <h1>Dashboard CRUD</h1>

      {/* MARCAS */}
      <section>
        <h2>Marcas</h2>
        <input
          placeholder="Nueva Marca"
          value={nombreMarca}
          onChange={e => setNombreMarca(e.target.value)}
        />
        <button onClick={agregarMarca}>Agregar</button>
        <ul>
          {Array.isArray(marcas) &&
            marcas.map(m => (
              <li key={m.id}>
                {m.nombre}{" "}
                <button
                  onClick={() =>
                    actualizarItem(apiMarcas, m.id, m.nombre, fetchMarcas)
                  }
                >
                  Editar
                </button>{" "}
                <button
                  onClick={() => eliminarItem(apiMarcas, m.id, fetchMarcas)}
                >
                  Eliminar
                </button>
              </li>
            ))}
        </ul>
      </section>

      {/* PROVEEDORES */}
      <section>
        <h2>Proveedores</h2>
        <input
          placeholder="Nuevo Proveedor"
          value={nombreProveedor}
          onChange={e => setNombreProveedor(e.target.value)}
        />
        <button onClick={agregarProveedor}>Agregar</button>
        <ul>
          {Array.isArray(proveedores) &&
            proveedores.map(p => (
              <li key={p.id}>
                {p.nombre}{" "}
                <button
                  onClick={() =>
                    actualizarItem(
                      apiProveedores,
                      p.id,
                      p.nombre,
                      fetchProveedores
                    )
                  }
                >
                  Editar
                </button>{" "}
                <button
                  onClick={() =>
                    eliminarItem(apiProveedores, p.id, fetchProveedores)
                  }
                >
                  Eliminar
                </button>
              </li>
            ))}
        </ul>
      </section>

      {/* PRODUCTOS */}
      <section>
        <h2>Productos</h2>
        <input
          placeholder="Nuevo Producto"
          value={nombreProducto}
          onChange={e => setNombreProducto(e.target.value)}
        />
        <button onClick={agregarProducto}>Agregar</button>
        <ul>
          {Array.isArray(productos) &&
            productos.map(pr => (
              <li key={pr.id}>
                {pr.nombre}{" "}
                <button
                  onClick={() =>
                    actualizarItem(apiProductos, pr.id, pr.nombre, fetchProductos)
                  }
                >
                  Editar
                </button>{" "}
                <button
                  onClick={() =>
                    eliminarItem(apiProductos, pr.id, fetchProductos)
                  }
                >
                  Eliminar
                </button>
              </li>
            ))}
        </ul>
      </section>
    </div>
  );
};

export default Dashboard;