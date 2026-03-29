import React, { useState, useEffect } from 'react'
import { getProveedores, createProveedor, updateProveedor, deleteProveedor } from '../services/proveedoresService'

const ProveedoresList = () => {
  const [proveedores, setProveedores] = useState([])
  const [loading, setLoading] = useState(true)
  const [showModal, setShowModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [nombre, setNombre] = useState('')
  const [error, setError] = useState('')

  // Cargar proveedores desde la API
  const loadProveedores = async () => {
    try {
      setLoading(true)
      const response = await getProveedores()
      // La API devuelve { data: [...] }
      setProveedores(response.data.data || response.data)
      setError('')
    } catch (error) {
      console.error('Error al cargar proveedores:', error)
      setError('Error al cargar los proveedores. Verifica que el backend esté corriendo.')
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    loadProveedores()
  }, [])

  // Crear o actualizar proveedor
  const handleSubmit = async (e) => {
    e.preventDefault()
    if (!nombre.trim()) {
      alert('El nombre es requerido')
      return
    }

    try {
      if (editing) {
        // Actualizar proveedor existente
        await updateProveedor(editing.id, { nombre })
        alert('Proveedor actualizado correctamente')
      } else {
        // Crear nuevo proveedor
        await createProveedor({ nombre })
        alert('Proveedor creado correctamente')
      }
      setShowModal(false)
      setEditing(null)
      setNombre('')
      loadProveedores() // Recargar la lista
    } catch (error) {
      console.error('Error al guardar:', error)
      alert(error.response?.data?.message || 'Error al guardar el proveedor')
    }
  }

  const handleEdit = (proveedor) => {
    setEditing(proveedor)
    setNombre(proveedor.nombre)
    setShowModal(true)
  }

  const handleDelete = async (id) => {
    if (window.confirm('¿Estás seguro de eliminar este proveedor? (Soft Delete)')) {
      try {
        await deleteProveedor(id)
        alert('Proveedor eliminado correctamente')
        loadProveedores() // Recargar la lista
      } catch (error) {
        console.error('Error al eliminar:', error)
        alert(error.response?.data?.message || 'Error al eliminar el proveedor')
      }
    }
  }

  if (loading) {
    return (
      <div className="text-center mt-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Cargando...</span>
        </div>
        <p className="mt-2">Cargando proveedores...</p>
      </div>
    )
  }

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Proveedores</h2>
        <button 
          className="btn btn-primary"
          onClick={() => {
            setEditing(null)
            setNombre('')
            setShowModal(true)
          }}
        >
          + Nuevo Proveedor
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
              <th style={{ width: '80px' }}>ID</th>
              <th>Nombre</th>
              <th style={{ width: '150px' }}>Fecha Creación</th>
              <th style={{ width: '150px' }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {proveedores.length === 0 ? (
              <tr>
                <td colSpan="4" className="text-center">No hay proveedores registrados</td>
              </tr>
            ) : (
              proveedores.map((proveedor) => (
                <tr key={proveedor.id}>
                  <td>{proveedor.id}</td>
                  <td>{proveedor.nombre}</td>
                  <td>{proveedor.created_at ? new Date(proveedor.created_at).toLocaleDateString() : '-'}</td>
                  <td>
                    <button 
                      className="btn btn-warning btn-sm me-2"
                      onClick={() => handleEdit(proveedor)}
                    >
                      Editar
                    </button>
                    <button 
                      className="btn btn-danger btn-sm"
                      onClick={() => handleDelete(proveedor.id)}
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Modal para crear/editar */}
      {showModal && (
        <div 
          className="modal show d-block" 
          style={{ 
            backgroundColor: 'rgba(0,0,0,0.5)', 
            position: 'fixed', 
            top: 0, 
            left: 0, 
            right: 0, 
            bottom: 0,
            zIndex: 1050
          }}
          onClick={() => setShowModal(false)}
        >
          <div className="modal-dialog" style={{ marginTop: '100px' }} onClick={(e) => e.stopPropagation()}>
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">
                  {editing ? 'Editar Proveedor' : 'Nuevo Proveedor'}
                </h5>
                <button 
                  type="button" 
                  className="btn-close" 
                  onClick={() => setShowModal(false)}
                ></button>
              </div>
              <form onSubmit={handleSubmit}>
                <div className="modal-body">
                  <div className="mb-3">
                    <label htmlFor="nombre" className="form-label">
                      Nombre del Proveedor
                    </label>
                    <input
                      type="text"
                      className="form-control"
                      id="nombre"
                      value={nombre}
                      onChange={(e) => setNombre(e.target.value)}
                      placeholder="Ingrese el nombre del proveedor"
                      autoFocus
                      required
                    />
                  </div>
                </div>
                <div className="modal-footer">
                  <button 
                    type="button" 
                    className="btn btn-secondary"
                    onClick={() => setShowModal(false)}
                  >
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

export default ProveedoresList