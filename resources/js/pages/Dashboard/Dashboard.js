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
            tagSearch: '',
            infiniteScrollPluginId: +new Date(),
            isPrivateMode: false,
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
            return axios.get(AppHelper.getBaseApiUrl() + 'user/tags', {
                params: {
                    isPrivate: this.isPrivateMode ? 1 : 0
                }
            }).then((res) => {
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
                    tags: this.filter.tags,
                    isPrivate: this.isPrivateMode ? 1 : 0
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
                        // Indicate to the plugin that more data was loaded
                        $state.loaded();
                    } else {
                        // Indicate to the plugin that there is no more data
                        $state.complete();
                    }
                });
        },
        refreshForFilters() {
            this.page = 1;
            this.bookmarks = [];
            // reset the plugin in case $state.complete was called earlier on
            // when the plugin resets, data automaticaly refreshes by calling the handleInfiniteScroll
            this.infiniteScrollPluginId++;
        },
        refreshTags() {
            this.tags = [];
            this.filter.tags = [];
            this.getTags();
        },
        bookmarkImageUrlAlt(event) {
            event.target.src = "/images/placeholder-400x200-secondary.png"
        }
    },
    watch: {
        isPrivateMode: function (val) {
            this.refreshForFilters();
            this.refreshTags();
        },
    }

}
