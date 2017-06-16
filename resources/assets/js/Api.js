export default {

    prefix : 'api/v1/',

    get(url, data) {
        return axios.get( this.prefix + url, data )
    },

    post(url, data) {
        return axios.post( this.prefix + url, data )
    },

    patch(url, data) {
        return axios.patch( this.prefix + url, data )
    },

    put(url, data) {
        return axios.put( this.prefix + url, data )
    },

    delete(url, data) {
        return axios.delete( this.prefix + url, data )
    },
}