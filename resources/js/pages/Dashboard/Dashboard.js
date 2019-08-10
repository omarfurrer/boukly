export default {

    data() {

        return {
            isLeftBarOpened: false,
            tags: [{
                    id: 1,
                    name: 'youtube.com'
                },
                {
                    id: 2,
                    name: 'microsoft.com'
                },
                {
                    id: 3,
                    name: 'yahoo.com'
                },
                {
                    id: 4,
                    name: 'laravel.com'
                },
                {
                    id: 5,
                    name: 'work'
                },
                {
                    id: 6,
                    name: 'videos'
                },
                {
                    id: 7,
                    name: 'games'
                },
                {
                    id: 8,
                    name: 'Teacher'
                },
            ]
        }

    },
    computed: {


    },
    created() {
        this.$eventHub.$on('open-left-bar', this.openLeftBar);
        this.$eventHub.$on('close-left-bar', this.closeLeftBar);
        this.$eventHub.$on('toggle-left-bar', this.toggleLeftBar);
    },
    mounted() {},
    methods: {
        openLeftBar() {
            this.isLeftBarOpened = true;
        },
        closeLeftBar() {
            this.isLeftBarOpened = false;
        },
        toggleLeftBar() {
            this.isLeftBarOpened = !this.isLeftBarOpened;
        },
        leftBarOpened(isOpened) {
            this.isLeftBarOpened = isOpened;
        }
    }

}
