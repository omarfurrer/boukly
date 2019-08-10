export default {
    data() {
        return {
            // Form data
            user: {},
            // Form valid
            isLoginFormValid: false,
            // Form error
            error: this.$route.query.error,
            // Form busy
            isBusy: false,
        }
    },
    computed: {
        emailRules() {
            return [
                v => !!v || 'Email address is required.',
                v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'E-mail must be valid',
            ]
        },
        passwordRules() {
            return [
                v => !!v || 'Password is required.',
            ]
        },
    },
    mounted() {},
    methods: {
        login() {
            this.$refs.loginForm.validate();
            if (!this.isLoginFormValid) return;

            this.isBusy = true;

            const body = {
                username: this.user.email,
                password: this.user.password,
                client_id: AppHelper.getEnv('AUTH_CLIENT_ID'),
                client_secret: AppHelper.getEnv('AUTH_CLIENT_SECRET'),
                grant_type: 'password',
                scope: ''
            };

            return axios.post(AppHelper.getBaseUrl() + 'oauth/token', body).then((res) => {

                    this.$store.dispatch('updateAuth', res);

                    return axios.get(AppHelper.getBaseApiUrl() + 'user').then((res) => {
                        this.$store.dispatch('updateUser', res);
                        this.$router.push('/dashboard');
                    });

                }).catch((error) => {
                    if (error.response.status == 401) {
                        this.error = 'Wrong email or password.';
                    }
                })
                .then(() => {
                    this.isBusy = false;
                });
        }
    }

}
