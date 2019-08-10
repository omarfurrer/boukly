export default {
    getEnv(key, defaultValue) {
        return (typeof _ENV[key] === 'undefined') ? defaultValue : _ENV[key];
    },
    getBaseUrl() {
        return this.getEnv('BASE_URL');
    },
    getBaseApiUrl() {
        return this.getEnv('BASE_URL') + this.getEnv('API_URL');
    }
}
