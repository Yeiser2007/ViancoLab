// api.js
import axios from 'axios';

let baseUrl = '/';

export default {
  setBaseUrl(url) {
    baseUrl = url.endsWith('/') ? url : url + '/';
  },
  get(resource, params = {}) {
    return axios.get(baseUrl + resource, { params });
  },
  show(resource, id) {
    return axios.get(`${baseUrl + resource}/${id}`);
  },
  post(resource, data) {
    return axios.post(baseUrl + resource, data);
  },
  put(resource, id, data) {
    return axios.put(`${baseUrl + resource}/${id}`, data);
  },
  patch(resource, id, data) {
    return axios.patch(`${baseUrl + resource}/${id}`, data);
  },
  delete(resource, id) {
    return axios.delete(`${baseUrl + resource}/${id}`);
  }
};