export default {

    data() {

        return {
            isLeftBarOpened: false,
            tags: [],
            bookmarks: [],
            isGetBookmarksBusy: false,
            page: 1,
            filter: {
                tags: []
            },
            tagSearch: ''
        }

    },
    computed: {
        filteredTags() {
            return this.tags.filter(tag => tag.name.toLowerCase().indexOf(this.tagSearch.toLowerCase()) >= 0);
        }
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
        toggleTagFilter(tag) {
            if (this.isTagFilterSelected(tag)) {
                this.removeTagFilter(tag);
            } else {
                this.addTagFilter(tag);
            }
            this.refreshForFilters();
        },
        addTagFilter(tag) {
            this.filter.tags.push(tag);
        },
        removeTagFilter(tag) {
            this.filter.tags.splice(this.filter.tags.findIndex((el) => {
                return el == tag
            }), 1);
        },
        isTagFilterSelected(tag) {
            return this.filter.tags.findIndex((el) => {
                return el == tag
            }) != -1;
        },
        clearTagFilters() {
            this.filter.tags = [];
            this.refreshForFilters();
        },
        getBookmarks() {
            this.isGetBookmarksBusy = true;
            return axios.get(AppHelper.getBaseApiUrl() + 'user/bookmarks', {
                params: {
                    page: this.page,
                    tags: this.filter.tags
                }
            }).then((res) => {
                const bookmarks = res.bookmarks.data;
                if (bookmarks.length == 0) {
                    return false;
                }
                this.bookmarks.push(...res.bookmarks.data);
                this.page++;
                return true
            }).then(res => {
                this.isGetBookmarksBusy = false;
                return res;
            });

        },
        handleInfiniteScroll($state) {
            this.getBookmarks()
                .then(addedMore => {
                    if (addedMore) {
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });
        },
        refreshForFilters() {
            this.page = 1;
            this.bookmarks = [];
            this.getBookmarks();
        }
    }

}
