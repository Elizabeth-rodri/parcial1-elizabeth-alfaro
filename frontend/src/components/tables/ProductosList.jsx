import React, { useState, useEffect } from 'react'
import { getProductos, createProducto, updateProducto, deleteProducto } from '../services/productosService'

const ProductosList = () => {
  const [productos, setProductos] = useState([])
  const [loading, setLoading] = useState(true)
  const [showModal, setShowModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [nombre, setNombre] = useState('')
  const [error, setError] = useState('')

  const loadProductos = async () => {
    try {
      setLoading(true)
      const response = await getProductos()
      setProductos(response.data)
      setError('')
    } catch (error) {
      console.error('Error:', error)
      setError('Error al cargar los productos')
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    loadProductos()
  }, [])

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (!nombre.trim()) {
      alert('El nombre es requerido')
      return
    }

    try {
      if (editing) {
        await updateProducto(editing.id, { nombre })
        alert('Producto actualizado correctamente')
      } else {
        await createProducto({ nombre })
        alert('Producto creado correctamente')
      }
      setShowModal(false)
      setEditing(null)
      setNombre('')
      loadProductos()
    } catch (error) {
      console.error('Error:', error)
      alert(error.response?.data?.message || 'Error al guardar')
    }
  }

  const handleEdit = (producto) => {
    setEditing(producto)
    setNombre(producto.nombre)
    setShowModal(true)
  }

  const handleDelete = async (id) => {
    if (window.confirm('¿Eliminar este producto? (Soft Delete)')) {
      try {
        await deleteProducto(id)
        alert('Producto eliminado correctamente')
        loadProductos()
      } catch (error) {
        console.error('Error:', error)
        alert('Error al eliminar')
      }
    }
  }

  if (loading) {
    return (
      <div className="text-center mt-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Cargando...</span>
        </div>
        <p className="mt-2">Cargando productos...</p>
      </div>
    )
  }

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Productos</h2>
        <button 
          className="btn btn-primary"
          onClick={() => {
            setEditing(null)
            setNombre('')
            setShowModal(true)
          }}
        >
          + Nuevo Producto
        </button>
      </div>

      {error && (
        <div className="alert alert-danger alert-dismissible fade show" role="alert">
          {error}
          <button type="button" className="btn-close" onClick={() => setError('')}></button>
        </div>
      )}

      <div className="table-responsive">
        <table className="table table-striped table-hover table-bordered">
          <thead className="table-dark">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Fecha Creación</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {productos.length === 0 ? (
              <tr>
                <td colSpan="4" className="text-center">No hay productos registrados</td>
              </tr>
            ) : (
              productos.map((producto) => (
                <tr key={producto.id}>
                  <td>{producto.id}</td>
                  <td>{producto.nombre}</td>
                  <td>{producto.created_at ? new Date(producto.created_at).toLocaleDateString() : '-'}</td>
                  <td>
                    <button className="btn btn-warning btn-sm me-2" onClick={() => handleEdit(producto)}>
                      Editar
                    </button>
                    <button className="btn btn-danger btn-sm" onClick={() => handleDelete(producto.id)}>
                      Eliminar
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="modal show d-block" style={{ 
          backgroundColor: 'rgba(0,0,0,0.5)', 
          position: 'fixed', 
          top: 0, 
          left: 0, 
          right: 0, 
          bottom: 0,
          zIndex: 1050
        }} onClick={() => setShowModal(false)}>
          <div className="modal-dialog" style={{ marginTop: '100px' }} onClick={(e) => e.stopPropagation()}>
            <div className="modal-content">
              <div className="modal-header">
                <h5>{editing ? 'Editar Producto' : 'Nuevo Producto'}</h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
              </div>
              <form onSubmit={handleSubmit}>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nombre del Producto</label>
                    <input
                      type="text"
                      className="form-control"
                      value={nombre}
                      onChange={(e) => setNombre(e.target.value)}
                      required
                      autoFocus
                    />
                  </div>
                </div>
                <div className="modal-footer">
                  <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>
                    Cancelar
                  </button>
                  <button type="submit" className="btn btn-primary">
                    {editing ? 'Actualizar' : 'Guardar'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default ProductosList