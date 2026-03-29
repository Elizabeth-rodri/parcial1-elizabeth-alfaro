import React, { useState, useEffect } from 'react'
import { getMarcas, createMarca, updateMarca, deleteMarca } from '../services/marcasService'

const MarcasList = () => {
  const [marcas, setMarcas] = useState([])
  const [loading, setLoading] = useState(true)
  const [showModal, setShowModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [nombre, setNombre] = useState('')
  const [error, setError] = useState('')

  const loadMarcas = async () => {
    try {
      setLoading(true)
      const response = await getMarcas()
      setMarcas(response.data.data || response.data)
      setError('')
    } catch (error) {
      console.error('Error:', error)
      setError('Error al cargar las marcas')
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    loadMarcas()
  }, [])

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (!nombre.trim()) {
      alert('El nombre es requerido')
      return
    }

    try {
      if (editing) {
        await updateMarca(editing.id, { nombre })
        alert('Marca actualizada correctamente')
      } else {
        await createMarca({ nombre })
        alert('Marca creada correctamente')
      }
      setShowModal(false)
      setEditing(null)
      setNombre('')
      loadMarcas()
    } catch (error) {
      console.error('Error:', error)
      alert(error.response?.data?.message || 'Error al guardar')
    }
  }

  const handleEdit = (marca) => {
    setEditing(marca)
    setNombre(marca.nombre)
    setShowModal(true)
  }

  const handleDelete = async (id) => {
    if (window.confirm('¿Eliminar esta marca? (Soft Delete)')) {
      try {
        await deleteMarca(id)
        alert('Marca eliminada correctamente')
        loadMarcas()
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
        <p className="mt-2">Cargando marcas...</p>
      </div>
    )
  }

  return (
    <div>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Marcas</h2>
        <button 
          className="btn btn-primary"
          onClick={() => {
            setEditing(null)
            setNombre('')
            setShowModal(true)
          }}
        >
          + Nueva Marca
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
            {marcas.length === 0 ? (
              <tr>
                <td colSpan="4" className="text-center">No hay marcas registradas</td>
              </tr>
            ) : (
              marcas.map((marca) => (
                <tr key={marca.id}>
                  <td>{marca.id}</td>
                  <td>{marca.nombre}</td>
                  <td>{marca.created_at ? new Date(marca.created_at).toLocaleDateString() : '-'}</td>
                  <td>
                    <button className="btn btn-warning btn-sm me-2" onClick={() => handleEdit(marca)}>
                      Editar
                    </button>
                    <button className="btn btn-danger btn-sm" onClick={() => handleDelete(marca.id)}>
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
                <h5>{editing ? 'Editar Marca' : 'Nueva Marca'}</h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)}></button>
              </div>
              <form onSubmit={handleSubmit}>
                <div className="modal-body">
                  <div className="mb-3">
                    <label className="form-label">Nombre de la Marca</label>
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

export default MarcasList