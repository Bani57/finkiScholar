<template>
    <form class="ui form" style="width: 75%; margin: 0 auto !important; padding-top: 3em;"
          enctype="multipart/form-data">
        <div class="ui large header">Publish paper</div>
        <div class="three fields">
            <div class="required field">
                <label>Title</label>
                <input type="text" name="title" v-model="title" placeholder="Enter the title of your paper here..."/>
            </div>
            <div class="required field">
                <label>File</label>
                <input type="hidden" name="file" v-model="file"/>
                <input type="file" name="pdfUpload" @change="filesChange($event.target.files)" accept="pdf/*"/>
            </div>
            <div class="required field">
                <label>Topic</label>
                <select class="ui search dropdown" name="topic" v-model="topic"
                        ref="topicDropdown">
                    <option value="">Choose a topic for your paper...</option>
                    <option v-for="topic in allTopics" :value="topic.id">{{topic.name}}</option>
                </select>
            </div>
        </div>
        <div class="required field">
            <label>Abstract</label>
            <textarea name="abstract" v-model="abstract" placeholder="Write an abstract for your paper..."
                      rows="3" style="font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;">
            </textarea>
        </div>
        <div class="field">
            <label>Co-authors</label>
            <select class="ui multiple search selection dropdown" multiple name="coauthors" v-model="coauthors"
                    ref="coauthorsDropdown">
                <option value="">Search here to credit any coauthors...</option>
                <option v-for="author in allAuthors" v-if="currentUser&&author.id!==currentUser.id" :value="author.id">
                    {{author.first_name + " " + author.last_name}}
                </option>
            </select>
        </div>
        <div class="ui small header">Bibliography</div>
        <div class="ui info message" v-if="displayBibliographyMessage">Items: {{bibliography.length}}</div>
        <div class="three fields">
            <div class="field">
                <label>Paper</label>
                <select class="ui search selection dropdown" name="bibliography" v-model="selectedPaper"
                        ref="papersDropdown">
                    <option value="">Find paper to cite...</option>
                    <option v-for="paper in allPapers" :value="paper.id">
                        {{paper.title}}
                    </option>
                </select>
            </div>
            <div class="field">
                <label>Part</label>
                <input type="text" name="part" v-model="part" placeholder="Which pages?"/>
            </div>
            <div class="field">
                <label>Actions</label>
                <div class="ui fluid buttons">
                    <div class="ui blue button" @click="addToBibliography()">Cite</div>
                    <div class="ui button" @click="clearBibliography()">Clear</div>
                </div>
            </div>
        </div>
        <div class="ui fluid buttons">
            <div class="ui blue submit button" @click="publish()">Publish</div>
            <div class="ui clear button" @click="clearFields()">Clear</div>
        </div>
        <div v-if="validationErrors" class="ui visible error message" style="height: 10em; overflow-y: auto;">
            <i class="close icon"></i>
            <div class="header">
                Validation errors
            </div>
            <ul class="list" v-for="errors in Object.values(validationErrors)">
                <li v-for="error in errors">{{error}}</li>
            </ul>
        </div>
    </form>
</template>

<script>
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    export default {
        name: "Publish",
        data() {
            return {
                currentUser: null,
                title: null,
                abstract: null,
                file: null,
                raw_pdf: null,
                topic: null,
                coauthors: [],
                selectedPaper: null,
                part: null,
                bibliographyOnlyPapers: [],
                bibliography: [],
                allTopics: null,
                allAuthors: null,
                allPapers: null,
                validationErrors: null,
            }
        },
        computed: {
            displayBibliographyMessage() {
                if (this.bibliography)
                    return this.bibliography.length > 0;
                else return false;
            }
        },

        methods: {
            filesChange(filesList) {
                let pdfFile = filesList[0];
                this.raw_pdf = pdfFile;
                this.file = pdfFile.name
            },
            addToBibliography() {
                if (this.selectedPaper && !this.bibliographyOnlyPapers.includes(this.selectedPaper)) {
                    this.bibliography.push({'paper_id': this.selectedPaper, 'part': this.part});
                    this.bibliographyOnlyPapers.push(this.selectedPaper);
                }
            },
            clearBibliography() {
                this.bibliography = [];
                this.bibliographyOnlyPapers = [];
            },
            getCurrentUser() {
                let vm = this;
                fetch(`http://localhost:8000/users/current`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting current user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch current user. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.currentUser = data;
                        vm.getCurrentUserRole();
                    } else window.location.href = '/';
                    return data
                })
            },
            getCurrentUserRole() {
                fetch(`http://localhost:8000/users/${this.currentUser.id}/role`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting role of current user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch role of current user. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data !== 0) {
                        window.location.href = '/';
                    }
                    return data
                })
            },
            getTopics() {
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
            getAuthors() {
                let vm = this;
                fetch(`http://localhost:8000/authors`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting authors'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch authors. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.allAuthors = data;
                    }
                    return data
                })
            },
            getPapers() {
                let vm = this;
                fetch(`http://localhost:8000/papers/accepted`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.allPapers = data;
                    }
                    return data
                })
            },
            publish() {
                let vm = this;
                let paper = {
                    title: vm.title,
                    abstract: vm.abstract,
                    file: vm.file,
                    topic: vm.topic,
                    author: vm.currentUser.id,
                    coauthors: vm.coauthors,
                    bibliography: vm.bibliography,
                };
                fetch(`http://localhost:8000/papers`, {
                    method: 'POST',
                    body: JSON.stringify(paper),
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed adding new paper'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed adding new paper.', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data[0] === "Paper successfully published.") {
                        toastr.options.preventDuplicates = true;
                        toastr.success('Paper published.', 'SUCCESS');
                        vm.validation_errors = null;
                        vm.uploadPdf(data[1]);
                    } else {
                        vm.validationErrors = data;
                        toastr.options.preventDuplicates = true;
                        toastr.warning('There were validation errors.', 'WARNING');
                    }
                    return data
                });
            },
            uploadPdf(id) {
                let formData = new FormData();
                formData.append('id', id);
                if (this.raw_pdf) {
                    formData.append('fileToUpload', this.raw_pdf);
                    fetch(`http://localhost:8000/papers/upload`, {
                        method: 'POST',
                        body: formData,
                        credentials: 'include'
                    }).then(response => {
                        if (response.ok) {
                            return response.json()
                        } else {
                            return Promise.reject(new Error('Failed uploading pdf file.'))
                        }
                    }, reason => {
                        toastr.options.preventDuplicates = true;
                        toastr.error('Failed uploading pdf file.', 'ERROR');
                        return Promise.reject(reason)
                    }).then(data => {
                        if (data) {
                            toastr.options.preventDuplicates = true;
                            toastr.success('PDF file uploaded.', 'SUCCESS');
                            window.location.href = '/profile';
                        }
                        return data
                    });
                }
            },
            clearFields() {
                this.title = null;
                this.abstract = null;
                this.file = null;
                this.raw_pdf = null;
                this.topic = null;
                this.coauthors = [];
                this.selectedPaper = null;
                this.part = null;
                this.bibliographyOnlyPapers = [];
                this.bibliography = [];
            }
        },
        mounted() {
            this.getCurrentUser();
            this.getTopics();
            $(this.$refs.topicDropdown).dropdown();
            this.getAuthors();
            $(this.$refs.coauthorsDropdown).dropdown();
            this.getPapers();
            $(this.$refs.papersDropdown).dropdown();
        }
    }
</script>

<style scoped>

</style>