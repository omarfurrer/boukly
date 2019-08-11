export default {

    data() {

        return {
            isLeftBarOpened: false,
            tags: [
                // {
                //         id: 1,
                //         name: 'youtube.com'
                //     },
                //     {
                //         id: 2,
                //         name: 'microsoft.com'
                //     },
                //     {
                //         id: 3,
                //         name: 'yahoo.com'
                //     },
                //     {
                //         id: 4,
                //         name: 'laravel.com'
                //     },
                //     {
                //         id: 5,
                //         name: 'work'
                //     },
                //     {
                //         id: 6,
                //         name: 'videos'
                //     },
                //     {
                //         id: 7,
                //         name: 'games'
                //     },
                //     {
                //         id: 8,
                //         name: 'Teacher'
                //     },
            ],
            bookmarks: [],
            isGetBookmarksBusy: false,
            page: 1
        }

    },
    computed: {


    },
    created() {
        this.$eventHub.$on('open-left-bar', this.openLeftBar);
        this.$eventHub.$on('close-left-bar', this.closeLeftBar);
        this.$eventHub.$on('toggle-left-bar', this.toggleLeftBar);
        this.getTags();
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
        },
        getTags() {
            return axios.get(AppHelper.getBaseApiUrl() + 'user/tags').then((res) => {
                this.tags = res.tags;
            });
        },
        getBookmarks($state) {
            this.isGetBookmarksBusy = true;
            return axios.get(AppHelper.getBaseApiUrl() + 'user/bookmarks', {
                params: {
                    page: this.page
                }
            }).then((res) => {
                const bookmarks = res.bookmarks.data;
                if (bookmarks.length == 0) {
                    $state.complete();
                    return;
                }
                this.bookmarks.push(...res.bookmarks.data);
                this.page++;
                $state.loaded();
            }).then(res => {
                this.isGetBookmarksBusy = false;
            });

        }
    }

}
