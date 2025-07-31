import axios from 'axios';

// Configuración inicial
const ApiService = {
  _baseUrl: '/',
  _axiosInstance: axios.create(),

  init(baseURL = '/') {
    this._baseUrl = baseURL.endsWith('/') ? baseURL : `${baseURL}/`;
    this._axiosInstance = axios.create({
      baseURL: this._baseUrl,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    // Interceptores (opcional pero recomendado)
    this._setupInterceptors();
  },

  _setupInterceptors() {
    // Interceptor de solicitudes
    this._axiosInstance.interceptors.request.use(
      config => {
        // Puedes añadir lógica común aquí (como tokens)
        return config;
      },
      error => {
        return Promise.reject(error);
      }
    );

    // Interceptor de respuestas
    this._axiosInstance.interceptors.response.use(
      response => response,
      error => {
        // Manejo centralizado de errores
        console.error('API Error:', error);
        return Promise.reject(error);
      }
    );
  },

  setBaseUrl(url) {
    this.init(url);
  },

  get(resource, config = {}) {
    return this._axiosInstance.get(resource, config);
  },

  post(resource, data, config = {}) {
    return this._axiosInstance.post(resource, data, config);
  },

  put(resource, id, data, config = {}) {
    return this._axiosInstance.put(`${resource}/${id}`, data, config);
  },

  patch(resource, id, data, config = {}) {
    return this._axiosInstance.patch(`${resource}/${id}`, data, config);
  },

  delete(resource, id, config = {}) {
    return this._axiosInstance.delete(`${resource}/${id}`, config);
  },

  customRequest(config) {
    return this._axiosInstance(config);
  }
};

// Inicialización por defecto
ApiService.init();

export default ApiService;