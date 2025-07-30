var url = '/';

module.exports = {
    getUrl(resource) {
        return url + resource;
    },
    setUrl(value) {
        url = value + '/';
    },
    get(resource, params = {}) {
        return axios.get(url + resource, params)
    },
    show(resource, id) {
        return axios.get(`${url + resource}/${id}`)
    },
    post(resource, data) {
        return axios.post(url + resource, data)
    },
    put(resource, id, data) {
        return axios.put(`${url + resource}/${id}`, data)
    },
    delete(resource, id) {
        return axios.delete(`${url + resource}/${id}`)
    }
}