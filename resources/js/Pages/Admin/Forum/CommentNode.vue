<template>
<div class="comment-wrapper" :class="{ 'has-parent': !!comment.comment_id }">
    <div class="comment-box">
        <!-- Комментарий -->
        <div class="card custom" :id="'comment-' + comment.id">
            <div class="card-body py-2 px-3">
                <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                    <img class="avatar" :src="route('index') + '/storage/' + comment.user.avatar" alt="Аватар">
                    <span>{{ comment.user.fio }} · {{ comment.created_at_human }}</span>
                </div>

                <p class="mb-1">{{ comment.comment }}</p>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <button class="btn btn-link btn-sm p-0 me-3" @click="showReply = !showReply" v-if="!comment.is_done">
                        Жауап беру
                    </button>

                    <button v-if="childComments.length" class="btn btn-link btn-sm p-0" @click="showChildren = !showChildren">
                        {{ showChildren ? 'Жауаптарды жасыру' : 'Жауаптарды ашу (' + childComments.length + ')' }}
                    </button>
                </div>

                <div v-if="showReply" class="mt-2">
                    <textarea v-model="replyText" rows="3" class="form-control mb-2" placeholder="Жауап жазыңыз..."></textarea>
                    <button class="btn btn-sm btn-primary" :disabled="!replyText.trim()" @click="submitReply">
                        Жауап қайтару
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Вложенные комментарии -->
    <div v-if="showChildren" class="mt-2">
        <CommentNode v-for="child in childComments" :key="child.id" :comment="child" :all-comments="allComments" />
    </div>
</div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";

export default {
    props: {
        comment: Object,
        allComments: Array,
        is_done: Number,
        highlightedId: Number,
    },
    data() {
        return {
            showReply: false,
            replyText: '',
            showChildren: true
        };
    },
    computed: {
        childComments() {
            return this.allComments.filter(c => c.comment_id === this.comment.id);
        }
    },
    mounted() {
        this.activeComment();
    },
    methods: {
        activeComment() {
            this.$nextTick(() => {
                const el = document.getElementById(`comment-${this.highlightedId}`);
                if (el) {
                    el.classList.add('highlight-comment');

                    setTimeout(() => {
                        el.classList.remove('highlight-comment');
                    }, 1000);
                }
            });
        },
        formatDate(dateStr) {
            return new Date(dateStr).toLocaleString("ru-RU");
        },
        submitReply() {
            axios.post(`/admin/forums/${this.comment.forum_id}/commentstore`, {
                comment: this.replyText,
                comment_id: this.comment.id,
                author_id: this.comment.user.id,
            }).then(res => {
                this.allComments.unshift(res.data.forumComment);
                this.replyText = '';
                this.showReply = false;
                this.showChildren = true;
            });

        },
        // submitReply() {
        //     Inertia.post(route('admin.forums.comment', this.comment.forum_id), {
        //         comment: this.replyText,
        //         comment_id: this.comment.id,
        //         author_id: this.comment.user.id,
        //     }, {
        //         preserveScroll: true,
        //         preserveState: true,
        //         onSuccess: () => {
        //             this.replyText = '';
        //             this.showReply = false;
        //             this.showChildren = true;
        //         }
        //     });
        // }
    }
};
</script>

<style scoped>
.comment-wrapper {
    position: relative;
    margin-left: 0;
}

.comment-wrapper.has-parent {
    margin-left: 20px;
    padding-left: 10px;
    border-left: 0.5px solid #ccc;
}

.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.custom {
    border: 2px solid transparent;
}

.highlight-comment {
    animation: highlightFade 2s ease-in-out;
    border: 2px solid #007bff;
    border-radius: 5px;
}

@keyframes highlightFade {
    0% {
        background-color: #e8f0fe;
    }

    100% {
        background-color: transparent;
    }
}
</style>
