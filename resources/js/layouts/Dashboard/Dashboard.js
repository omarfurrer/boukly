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
        },
        uploadImportFile(e) {
            const files = e.target.files
            if (files[0] !== undefined) {

                const file = files[0];

                // make sure size is below or equal 3MB
                if (file.size > 1024 * 1024 * 1) {
                    alert('Maximum file size is 1MB');
                    return;
                }

                // make sure type is supported
                if (!['text/plain'].includes(file.type)) {
                    alert('File type must be txt');
                    return;
                }

                const formData = new FormData();
                formData.append('file', file);

                return axios.post(AppHelper.getBaseApiUrl() + 'bookmarks/import', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        alert('File uploaded and bookmarks are being imported.');
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            // Get first key to display first error in errors object
                            let errorsFirstKey = Object.keys(error.response.data.errors)[0];
                            const validationError = error.response.data.errors[errorsFirstKey][0];
                            alert(validationError);
                        }
                    })
                    .then(() => {
                        this.$refs.inputUploadImport.value = '';
                    });
            }
        }
    }

}
