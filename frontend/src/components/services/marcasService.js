import api from './api';

export const getMarcas = () => api.get('/marcas');
export const createMarca = (data) => api.post('/marcas', data);
// Nota: La ruta usa {idmarca} como parámetro
export const updateMarca = (id, data) => api.put(`/marcas/${id}`, data);
export const deleteMarca = (id) => api.delete(`/marcas/${id}`);