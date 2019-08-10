import StorageHelper from './StorageHelper';

export default {
    getAccessToken() {
        const vuex = StorageHelper.get('vuex');
        return vuex && vuex.auth ? vuex.auth.access_token : '';
    },
    clearSession() {
        StorageHelper.remove('vuex');
    }
}
