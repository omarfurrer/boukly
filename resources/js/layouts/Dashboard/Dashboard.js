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
        }
    }

}
