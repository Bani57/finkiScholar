<template>
    <div id="profile">
        <div class="ui grid" v-if="currentUserRole===0 && papers" style="margin: auto;">
            <div class="four wide column">
                <div class="ui card" style="width: 100%;">
                    <div class="content">
                        <div class="header">{{currentUser ? currentUser.first_name + " " + currentUser.last_name : ''}}
                        </div>
                    </div>
                    <div class="image" style="height: 391.75px; width: 100%;">
                        <img :src="profilePictureSrc" alt="" style="height: 391.75px; width: 100%;"/>
                    </div>
                    <div class="content" v-if="currentUser && currentUser.description">
                        <pre class="description">{{currentUser ? currentUser.description : ''}}</pre>
                    </div>
                    <div v-if="currentUserRole===0" class="extra content">
                        <div class="header">
                            Organization
                        </div>
                        <div id='organization' class="left aligned description">
                            <div>
                                <i v-if="author && author.organization_type===0" class="book icon"></i>
                                <i v-else-if="author" class="briefcase icon"></i>
                                {{author ? author.organization_name : ''}}
                            </div>
                            <div>
                                <i :class="author ? author.organization_country.toLowerCase() : ''" class="flag"></i>
                                {{author ? author.organization_country : ''}}
                            </div>
                            <div>
                                <i class="address book icon"></i>
                                {{author ? author.organization_address : ''}}
                            </div>
                            <div v-if="author && author.organization_email">
                                <i class="at icon"></i>
                                {{author.organization_email}}
                            </div>
                            <div v-if="author && author.organization_telephone">
                                <i class="phone icon"></i>
                                {{author.organization_telephone}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eight wide column">
                <div class="ui header">Submitted papers</div>
                <div class="ui relaxed divided list"
                     style="overflow-y: auto; height: 42em">
                    <div v-for="(paper, index) in papers" class="item">
                        <i class="large middle aligned icon" :class="paperStatusToIcon(paper.status)"></i>
                        <div class="content" style="padding-left: 1em; padding-right: 1em; text-align: left">
                            <div class="header">
                                {{paper.title}}
                            </div>
                            <div v-if="topics" class="meta">Topic: {{topics[index]}}</div>
                            <div v-if="coauthors" class="description">Authors:
                                {{coauthors[index]}}
                            </div>
                            <div class="description" v-if="paper.created_at">Date published:
                                {{moment(paper.created_at).format('DD MMMM YYYY')}}
                            </div>
                            <div class="description">Status: {{paperStatusToText(paper.status)}}
                            </div>
                            <div v-if="citations && paper.status===1" class="extra" style="text-align: right">
                                <i class="right quote icon"></i>
                                {{citations[index]}}
                            </div>
                            <a v-if="reviews&&reviewers" @click="showReviewsModal(index)" class="ui left aligned link">View
                                reviews</a>
                        </div>
                    </div>
                </div>
                <router-link to="/publish-page" class="ui huge fluid blue button" style="margin-top: 4em;">Publish paper
                </router-link>
                <div v-if="reviews&&reviewers&&reviews[review_index]&&reviewers[review_index]" class="ui large modal"
                     ref="reviewsModal">
                    <i class="close icon"></i>
                    <div class="header">Reviews</div>
                    <div class="content">
                        <div class="ui relaxed divided list">
                            <div class="item" v-for="(reviewer,index) in reviewers[review_index]">
                                <i class="large middle aligned icon"
                                   :class="paperStatusToIcon(reviews[review_index][index].passed)"></i>
                                <div class="review content">
                                    <div class="header">
                                        <i class="user icon"></i>
                                        {{reviewer.first_name + " " + reviewer.last_name}}
                                    </div>
                                    <div class="meta">
                                        <i class="at icon"></i>
                                        <a :href="'mailto:'+reviewer.email">{{reviewer.email}}</a>
                                    </div>
                                    <div v-if="reviews[review_index][index].comment" class="description">
                                        <i class="comment outline icon"></i>
                                        <pre style="display: inline;">{{reviews[review_index][index].comment}}</pre>
                                    </div>
                                    <div class="extra">
                                        <div class="ui star rating" :data-rating="reviews[review_index][index].score"
                                             data-max-rating="5" ref="scoreRating"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="four wide column">
                <div class="ui card" style="width: 100%;">
                    <div class="content">
                        <div class="header">Statistics</div>
                    </div>
                    <div id="stats" class="content" style="text-align: left">
                        <div>Total number of published papers: {{totalPapers}}</div>
                        <div>Favourite topic: {{favouriteTopic}}</div>
                        <div>Total citations: {{totalCitations}}</div>
                        <div>Average citations per paper: {{averageCitations.toFixed(2)}}</div>
                        <div>h-index: {{h_index}}</div>
                        <div>i10-index: {{i10_index}}</div>
                        <div>Paper acceptance rate: {{acceptanceRate.toFixed(2)}}%</div>
                        <div>Average review score: {{averageReviewScore.toFixed(2)}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="pendingReviews" class="ui grid" style="margin: auto;">
            <div class="four wide column">
                <div class="ui card" style="width: 100%;">
                    <div class="content">
                        <div class="header">{{currentUser ? currentUser.first_name + " " + currentUser.last_name : ''}}
                        </div>
                    </div>
                    <div class="image" style="height: 391.75px; width: 100%;">
                        <img :src="profilePictureSrc" alt="" style="height: 391.75px; width: 100%;"/>
                    </div>
                    <div class="content" v-if="currentUser && currentUser.description">
                        <pre class="description">{{currentUser ? currentUser.description : ''}}</pre>
                    </div>
                </div>
            </div>
            <div class="twelve wide column">
                <div class="ui header">Pending reviews</div>
                <div class="ui relaxed divided list"
                     style="overflow-y: auto; height: 42em">
                    <div v-for="(paper, index) in pendingReviews" class="item">
                        <i class="large middle aligned icon" :class="paperStatusToIcon(paper.review.passed)"></i>
                        <div class="content" style="padding-left: 1em; padding-right: 1em; text-align: left">
                            <div class="header">
                                {{paper.title}}
                            </div>
                            <div class="meta" v-if="leadAuthors">Lead author: {{leadAuthors[index].first_name + " " +
                                leadAuthors[index].last_name}}
                            </div>
                            <div class="description">Abstract:
                                <pre style="font-style: italic">{{paper.abstract}}</pre>
                            </div>
                            <div class="description" v-if="paper.created_at">Date published:
                                {{moment(paper.created_at).format('DD MMMM YYYY')}}
                            </div>
                            <a v-if="leadAuthors" class="ui link"
                               :href="getPaperPdfLink(paper.id,paper.file)" style="display: block">Read
                                paper</a>
                            <a v-if="pendingReviews" @click="showUpdateReviewModal(index)" class="ui link"
                               style="display: block">Update review</a>
                        </div>
                    </div>
                </div>
                <div v-if="pendingReviews" class="ui medium modal"
                     ref="updateReviewModal">
                    <i class="close icon"></i>
                    <div class="header">Update review</div>
                    <div class="content">
                        <form class="ui form">
                            <div class="two fields">
                                <div class="required four wide field">
                                    <label>Score</label>
                                    <div class="ui huge star rating" ref="updateScoreRating"
                                         style="margin-top: 0.5em;"></div>
                                </div>
                                <div class="twelve wide field">
                                    <label>Comment</label>
                                    <input type="text" name="title" v-model="reviewComment"
                                           placeholder="Give feedback to the authors..."/>
                                </div>
                            </div>
                            <div class="required field">
                                <label>Verdict</label>
                                <div class="ui buttons">
                                    <div @click="setAsAccepted()" class="ui green button"
                                         :class="{active: reviewPassed===1}">
                                        Accept
                                    </div>
                                    <div class="or"></div>
                                    <div @click="setAsRejected()" class="ui red button"
                                         :class="{active: reviewPassed===-1}">
                                        Reject
                                    </div>
                                </div>
                            </div>
                            <div class="ui large blue button" @click="updateReview()">Update</div>
                        </form>
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
        name: "Profile",
        data() {
            return {
                currentUser: null,
                currentUserRole: null,
                profilePictureSrc: null,
                author: null,
                papers: null,
                topics: null,
                coauthors: null,
                citations: null,
                reviews: null,
                reviewers: null,
                review_index: null,
                papersToUpdate: [],
                pendingReviews: null,
                updateReviewIndex: null,
                reviewScore: 0,
                reviewComment: null,
                reviewPassed: 0,
                leadAuthors: null,
                moment: moment,
            }
        },
        computed: {
            totalPapers() {
                if (this.papers) {
                    return this.papers.length;
                } else return 0;
            },
            favouriteTopic() {
                if (this.topics) {
                    let counts = {};
                    for (var topic in this.topics) {
                        topic = this.topics[topic];
                        if (counts[topic])
                            counts[topic]++;
                        else
                            counts[topic] = 1;
                    }
                    let max_count = Object.values(counts).reduce((a, b) => Math.max(a, b));
                    let max_topic = Object.values(counts).indexOf(max_count);
                    return Object.keys(counts)[max_topic];
                } else
                    return 'N/A';
            },
            totalCitations() {
                if (this.citations) {
                    return this.citations.reduce((a, b) => a + b, 0);
                } else return 0;
            },
            averageCitations() {
                if (this.citations) {
                    return this.totalCitations / this.numAcceptedPapers;
                } else return 0;
            },
            h_index() {
                if (this.citations) {
                    let sorted_citations = this.citations.slice();
                    sorted_citations.sort((a, b) => b - a);
                    let minimums = [];
                    for (var i = 0; i < sorted_citations.length; i++)
                        minimums.push(Math.min(i, sorted_citations[i]));
                    return minimums.reduce((a, b) => Math.max(a, b));


                } else return 0;
            },
            i10_index() {
                if (this.citations)
                    return this.citations.reduce(function (a, b) {
                        if (b >= 10) return a + 1; else return a;
                    }, 0);
                else return 0;
            },
            numAcceptedPapers() {
                if (this.papers) {
                    let num_accepted = 0;
                    for (var paper in this.papers) {
                        paper = this.papers[paper];
                        if (paper.status === 1)
                            num_accepted++;
                    }
                    return num_accepted;
                } else return 0;
            },
            acceptanceRate() {
                if (this.papers) {
                    return 100 * this.numAcceptedPapers / this.totalPapers;
                } else return 0;
            },
            averageReviewScore() {
                if (this.reviews) {
                    let review_scores = [];
                    for (var review in this.reviews) {
                        review = this.reviews[review];
                        review_scores.push(review[0].score);
                        review_scores.push(review[1].score);
                        review_scores.push(review[2].score);
                    }
                    return review_scores.reduce((a, b) => a + b, 0) / review_scores.length;
                } else return 0;
            }

        },
        methods: {
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
                        vm.getProfilePictureSrc();
                    }
                    else{
                        window.location.href = '/';
                    }
                    return data
                })
            },
            getProfilePictureSrc() {
                if (this.currentUser.picture) {
                    let pictureUniqueName = this.currentUser.username + this.currentUser.picture;
                    this.profilePictureSrc = '../../images/profile_pictures/' + pictureUniqueName;
                } else
                    this.profilePictureSrc = '../../images/default-profile-picture.png';
            },
            getCurrentUserRole() {
                let vm = this;
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
                    vm.currentUserRole = data;
                    if (data === 0) {
                        vm.getAuthorInfo();
                        vm.getPapers();
                    } else {
                        vm.getPendingReviews();
                        vm.getLeadAuthors();
                    }
                    return data
                })
            },
            getAuthorInfo() {
                let vm = this;
                fetch(`http://localhost:8000/users/${this.currentUser.id}/author-info`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting author info of current user'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch author info current user. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        vm.author = data;
                    return data
                })
            },
            getPapers() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/papers`, {
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
                        vm.papers = data;
                        vm.getTopics();
                        vm.getCoAuthors();
                        vm.getCitations();
                        vm.getReviews();
                    }
                    return data
                })
            },
            getTopics() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/topics`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting topics of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch topics of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        vm.topics = data;
                    return data
                });
            },
            getCoAuthors() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/authors`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting co-authors of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch co-authors of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        vm.coauthors = data;
                    return data
                });
            },
            getCitations() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/citations`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting citations of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch citations of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        vm.citations = data;
                    return data
                });
            },
            getReviews() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/reviews`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting reviews of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch reviews of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.reviews = data;
                        vm.getReviewers();
                    }
                    return data
                });
            },
            getReviewers() {
                let vm = this;
                fetch(`http://localhost:8000/authors/${this.currentUser.id}/reviewers`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting reviewers of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch reviewers of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.reviewers = data;
                        vm.checkPapersStatus();
                    }
                    return data
                });
            },
            checkPapersStatus() {
                let papers_to_update = [];
                for (var paper in this.papers) {
                    if (this.papers[paper].status !== 0)
                        continue;
                    let paper_id = this.papers[paper].id;
                    let reviews = this.reviews[paper];
                    let num_passed = 0;
                    let num_rejected = 0;
                    for (var review in reviews) {
                        review = reviews[review];
                        if (review.passed === 1)
                            num_passed++;
                        if (review.passed === -1)
                            num_rejected++;
                    }
                    if (num_passed >= 2)
                        papers_to_update.push({'paper_id': paper_id, 'status': 1});
                    else if (num_rejected >= 2)
                        papers_to_update.push({'paper_id': paper_id, 'status': -1});
                }
                this.papersToUpdate = papers_to_update;
                this.updatePapers();
            },
            updatePapers() {
                if (this.papersToUpdate.length > 0) {
                    let vm = this;
                    fetch(`http://localhost:8000/papers/update`, {
                        method: 'PATCH',
                        body: JSON.stringify({'papersToUpdate': this.papersToUpdate}),
                        credentials: 'include'
                    }).then(response => {
                        if (response.ok) {
                            return response.json()
                        } else {
                            return Promise.reject(new Error('Failed updating papers'))
                        }
                    }, reason => {
                        toastr.options.preventDuplicates = true;
                        toastr.error('Failed updating papers.', 'ERROR');
                        return Promise.reject(reason)
                    }).then(data => {
                        if (data) {
                            toastr.options.preventDuplicates = true;
                            toastr.success('Status of papers updated.', 'SUCCESS');
                            vm.getPapers();
                        }
                        return data
                    });
                }
            },
            getPendingReviews() {
                let vm = this;
                fetch(`http://localhost:8000/reviewers/${this.currentUser.id}/reviews`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting pending reviews'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch pending reviews. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        vm.pendingReviews = data;
                    }
                    return data
                });
            },
            getLeadAuthors() {
                let vm = this;
                fetch(`http://localhost:8000/reviewers/${this.currentUser.id}/authors`, {
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed getting lead authors of papers'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Unable to fetch lead authors of papers. Try reloading', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data)
                        vm.leadAuthors = data;
                    return data
                });
            },
            setAsAccepted() {
                this.reviewPassed = 1;
            },
            setAsRejected() {
                this.reviewPassed = -1;
            },
            updateReview() {
                let vm = this;
                let review = {
                    reviewer_id: vm.pendingReviews[vm.updateReviewIndex].review.reviewer_id,
                    paper_id: vm.pendingReviews[vm.updateReviewIndex].review.paper_id,
                    score: vm.reviewScore,
                    comment: vm.reviewComment,
                    passed: vm.reviewPassed,
                };
                console.log(review);
                fetch(`http://localhost:8000/reviews`, {
                    method: 'PUT',
                    body: JSON.stringify(review),
                    credentials: 'include'
                }).then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        return Promise.reject(new Error('Failed updating review'))
                    }
                }, reason => {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Failed updating review.', 'ERROR');
                    return Promise.reject(reason)
                }).then(data => {
                    if (data) {
                        toastr.options.preventDuplicates = true;
                        toastr.success('Review updated.', 'SUCCESS');
                        $(this.$refs.updateReviewModal).modal('hide');
                        vm.getPendingReviews();
                    }
                    return data
                });
            },
            getPaperPdfLink(id, filename) {
                if (filename)
                    return '../../papers_files/' + id + filename;
                else return '';
            },
            paperStatusToIcon(status) {
                if (status === 1)
                    return 'green check';
                else if (status === 0)
                    return 'yellow question';
                else
                    return 'red x';

            },
            paperStatusToText(status) {
                if (status === 1)
                    return 'Accepted';
                else if (status === 0)
                    return 'Pending peer review';
                else
                    return 'Rejected';

            },
            showReviewsModal(index) {
                this.review_index = index;
                $(this.$refs.scoreRating).rating({
                    initialRating: 0,
                    maxRating: 5,
                    interactive: false,
                });
                $(this.$refs.reviewsModal).modal('hide');
                $(this.$refs.reviewsModal).modal({
                    inline: true,
                    closable: true,
                }).modal('show');
            },
            showUpdateReviewModal(index) {
                let vm = this;
                this.updateReviewIndex = index;
                this.reviewScore = this.pendingReviews[index].review.score;
                this.reviewComment = this.pendingReviews[index].review.comment;
                this.reviewPassed = this.pendingReviews[index].review.passed;
                $(this.$refs.updateScoreRating).rating({
                    initialRating: vm.pendingReviews[index].review.score,
                    maxRating: 5,
                    interactive: true,
                    onRate: function (rating) {
                        vm.reviewScore = rating;
                    },
                });
                $(this.$refs.updateReviewModal).modal('hide');
                $(this.$refs.updateReviewModal).modal({
                    inline: true,
                    closable: true,
                }).modal('show');
            },
        },
        mounted() {
            this.getCurrentUser();
        },
        beforeDestroy: function () {
            $(this.$refs.reviewsModal).modal('hide');
            $(this.$refs.updateReviewModal).modal('hide');
        }

    }
</script>

<style scoped>
    pre {
        margin: 0 !important;
        font-family: Lato, 'Helvetica Neue', Arial, Helvetica, sans-serif;
    }

    #profile {
        height: 100%;
    }

    #organization div {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    #stats div {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .review div {
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>