<template>
    <div id="archive">
        <div style="width: 75%; margin: auto; padding-top: 2em;">
            <div v-show="ancestors.length" class="ui huge breadcrumb" style="float: left">
                <i class="archive icon"></i>
                <template v-for="ancestor in ancestors">
                    <i class="right angle icon divider"></i>
                    <a v-if="ancestor.id!==selectedTopic" class="section" @click="selectAncestor(ancestor.id)">{{ancestor.name}}</a>
                    <div v-else class="section">{{ancestor.name}}</div>
                </template>
            </div>
            <div class="ui huge header" style="padding-top: 1em;">Choose topic</div>
            <div v-show="subtopics.length" class="ui info message"
                 style="text-align: left; overflow-y: auto; height: 8em;">
                <div class="header">Sub-topics</div>
                <ul class="ui list">
                    <li class="item" v-for="subtopic in subtopics">
                        <a @click="selectAncestor(subtopic.id)">{{subtopic.name}}</a>
                    </li>
                </ul>
            </div>
            <select class="ui fluid search selection dropdown" ref="topicsDropdown" v-model="selectedTopic">
                <option value="">Find something you are interested in...</option>
                <option :value="topic.id" v-for="topic in allTopics">{{topic.name}}</option>
            </select>
            <div v-show="papers.length" style="padding-top: 2em;">
                <div class="ui huge header">Papers</div>
                <div class="ui relaxed divided list" style="overflow-y: auto; height: 25em">
                    <div v-for="(paper, index) in papers" class="item">
                        <i class="large middle aligned file pdf icon"></i>
                        <div class="paper content" style="padding-left: 1em; padding-right: 1em; text-align: left">
                            <div class="header">
                                {{paper.title}}
                            </div>
                            <div class="description">
                                <i class="users icon"></i>{{authors[index]}}
                            </div>
                            <div class="description" v-if="paper.created_at">
                                <i class="calendar icon"></i>{{moment(paper.created_at).format('DD MMMM YYYY')}}
                            </div>
                            <div class="extra">
                                <i class="paperclip icon"></i><a :href="getLinkToPdfFile(paper.id, paper.file)"
                                                                 target="_blank">File</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    $(document).ready(function () {
        $('.no-sticky-animation').on('mousedown', function (event) {
            event.preventDefault()
        });
    });

    export default {
        name: "Archive",
        data() {
            return {
                allTopics: [],
                selectedTopic: null,
                ancestors: [],
                subtopics: [],
                papers: [],
                authors: [],
                moment: moment,
            }
        },
        methods: {
            checkIfUserLoggedIn() {
                let vm = this;
                fetch(`http://localhost:8000/users/check`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed checking whether any user is logged in'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed checking whether any user is logged in. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (!data) {
                        window.location.href = '/';
                    } else
                        vm.getAllTopics();
                    return data
                })
            },
            getAllTopics() {
                let vm = this;
                fetch(`http://localhost:8000/topics`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting topics'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch topics. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.allTopics = data;
                    }
                    return data
                })
            },
            getSubTopics() {
                let vm = this;
                fetch(`http://localhost:8000/topics/${this.selectedTopic}/children`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting child topics'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch child topics. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.subtopics = data;
                    }
                    return data
                })
            },
            getAncestors() {
                let vm = this;
                fetch(`http://localhost:8000/topics/${this.selectedTopic}/ancestors`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting ancestors of topic'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch ancestors of topic. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.ancestors = data;
                    }
                    return data
                })
            },
            selectAncestor(id) {
                this.selectedTopic = id;
                this.getSubTopics();
                this.getAncestors();
                this.getPapers();
                this.getAuthors();
            },
            getPapers() {
                let vm = this;
                fetch(`http://localhost:8000/topics/${this.selectedTopic}/papers`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting papers about topic'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch papers about topic. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.papers = data;
                    }
                    return data
                })
            },
            getAuthors() {
                let vm = this;
                fetch(`http://localhost:8000/topics/${this.selectedTopic}/authors`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting papers about topic'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch papers about topic. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.authors = data;
                    }
                    return data
                })
            },
            getLinkToPdfFile(id, filename) {
                return '../../papers_files/' + id + filename;
            }
        },
        mounted() {
            this.checkIfUserLoggedIn();
            let vm = this;
            $(this.$refs.topicsDropdown).dropdown({
                onChange: function () {
                    vm.getSubTopics();
                    vm.getAncestors();
                    vm.getPapers();
                    vm.getAuthors();
                }
            });
        }
    }
</script>

<style scoped>
    #archive {
        height: 100%;
    }

    .paper.content div {
        padding-top: 0.25em;
        padding-bottom: 0.25em;
    }
</style>