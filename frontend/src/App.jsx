import React from 'react'
import { BrowserRouter, Routes, Route, Link } from 'react-router-dom'
import ProveedoresList from './components/tables/ProveedoresList'
import ProductosList from './components/tables/ProductosList'
import MarcasList from './components/tables/MarcasList'
import 'bootstrap/dist/css/bootstrap.min.css'

function App() {
  return (
    <BrowserRouter>
      <div>
        <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
          <div className="container">
            <Link className="navbar-brand" to="/">Sistema de Gestión</Link>
            <div className="collapse navbar-collapse">
              <ul className="navbar-nav">
                <li className="nav-item">
                  <Link className="nav-link" to="/proveedores">Proveedores</Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/productos">Productos</Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/marcas">Marcas</Link>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        
        <div className="container mt-4">
          <Routes>
            <Route path="/" element={<h2>Bienvenido al Sistema de Gestión</h2>} />
            <Route path="/proveedores" element={<ProveedoresList />} />
            <Route path="/productos" element={<ProductosList />} />
            <Route path="/marcas" element={<MarcasList />} />
          </Routes>
        </div>
      </div>
    </BrowserRouter>
  )
}

export default App