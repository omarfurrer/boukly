import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const getDefaultState = () => {
    return {
        user: {},
        auth: {}
    }
};

export default new Vuex.Store({
    state: getDefaultState(),
    actions: {
        updateAuth({
            commit
        }, payload) {
            commit('UPDATE_AUTH', payload);
        },
        updateUser({
            commit
        }, payload) {
            commit('UPDATE_USER', payload);
        },
        async logout({
            commit
        }) {
            commit('LOG_OUT');
        },
    },
    mutations: {
        UPDATE_AUTH(state, payload) {
            state.auth = payload;
        },
        UPDATE_USER(state, payload) {
            state.user = payload;
        },
        LOG_OUT(state) {
            // Merge rather than replace so we don't lose observers
            // https://github.com/vuejs/vuex/issues/1118
            Object.assign(state, getDefaultState())
        },
    },
    getters: {
        user: state => state.user,
    },
    plugins: [createPersistedState()]
})
