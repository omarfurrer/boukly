export default {

    name: 'Dashboard',
    mixins: [],
    props: {},
    components: {

    },
    data() {

        return {}

    },
    computed: {

    },
    mounted() {},
    created() {

    },
    methods: {
        logout() {
            this.$store.dispatch('logout');
            this.$router.push('/login');
        },
        toggleLeftBar() {
            this.$eventHub.$emit('toggle-left-bar');
        }
    }

}
