<template>
<Head>
    <title>Админ панель | {{ forum?.title }} форумы</title>
</Head>

<AdminLayout>
    <template #breadcrumbs>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-user-shield text-primary me-2"></i>
                    {{ forum?.title }} форумы
                </h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a :href="route('admin.index')"><i class="fas fa-dashboard"></i> Басты бет</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a :href="route('admin.forums.index')"><i class="fas fa-dashboard"></i> Форумдар тізімі</a>
                    </li>
                    <li class="breadcrumb-item active">{{ forum?.title }} форумы</li>
                </ol>
            </div>
        </div>
    </template>

    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mr-1 d-inline-block">{{ forum?.title }}</div>
                    <span class="badge" :class="forum?.is_done ? 'bg-success' : 'bg-warning text-dark'">
                        {{ forum?.is_done ? 'Жабылды' : 'Жауабын күтуде' }}
                    </span>
                </div>
                <div class="btn-group mt-3 mt-md-0" v-if="canAction">
                    <Link :href="route('admin.forums.edit', forum)" class="btn btn-outline-primary btn-sm" title="Өзгерту">
                    <i class="fas fa-edit"></i>
                    </Link>
                    <button @click.prevent="deleteData(forum?.id)" class="btn btn-outline-danger btn-sm" title="Жою">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button @click="toggleStatus" class="btn btn-sm" :class="forum?.is_done ? 'btn-outline-warning' : 'btn-outline-success'" :title="forum?.is_done ? 'Форумды қайта ашу' : 'Форумды жабу'">
                        <i :class="forum?.is_done ? 'fas fa-lock text-success' : 'fas fa-lock-open text-warning'"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submitComment()">
                    <div class="mb-3">
                        <textarea v-model="commentText" rows="4" class="form-control" placeholder="Жауап жазу..." :disabled="forum?.is_done"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" :disabled="!commentText.trim()">
                        Жіберу
                    </button>
                </form>
            </div>
        </div>
        <div class="mb-4" v-if="forum?.comments.length">
            <h4 class="mb-3">Жауаптар</h4>
            <CommentNode v-for="comment in rootComments" :key="comment.id" :comment="comment" :is_done="forum?.is_done" :all-comments="forum?.comments" :highlighted-id="highlightedCommentId" />
        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import CommentNode from "./CommentNode.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        AdminLayout,
        Head,
        CommentNode,
        Link,
    },
    props: {
        // forum: Object,
        // canAction: Boolean,
        forumId: Number,
    },
    data() {
        return {
            commentText: '',
            replyingTo: null,
            highlightedCommentId: null,
            forum: null,
            canAction: false,
        };
    },

    created() {
        window.Echo.channel('store_comment')
            .listen('.store_comment', res => {
                this.forum.comments.unshift(res.comment);
            })
        // window.Echo.channel('take_notification_count_' + this.$page.props.user.id)
        //     .listen('.take_notification_count', res => {
        //         console.log(res);
        //     })
        this.loadComments();
    },

    mounted() {
        const hash = window.location.hash;
        if (hash.startsWith('#comment-')) {
            const id = parseInt(hash.replace('#comment-', ''), 10);
            if (!isNaN(id)) {
                this.highlightedCommentId = id;
            }
        }
    },

    computed: {
        rootComments() {
            return this.forum.comments.filter(c => !c.comment_id);
        },
    },
    methods: {
        toggleStatus() {
            Swal.fire({
                title: this.forum.is_done ? 'Форумды қайта ашқыңыз келе ме?' : 'Форумды жабқыңыз келе ме?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: this.forum.is_done ? 'Иә, қайта ашу' : 'Иә, жабу',
                cancelButtonText: 'Жоқ',
            }).then(result => {
                if (result.isConfirmed) {
                    Inertia.put(route('admin.forums.toggleStatus', this.forum.id), {}, {
                        onSuccess: () => {}
                    });
                }
            });
        },
        deleteData(id) {
            Swal.fire({
                title: "Жоюға сенімдісіз бе?",
                text: "Қайтып қалпына келмеуі мүмкін!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Иә, жоямын!",
                cancelButtonText: "Жоқ",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(route('admin.forums.destroy', id))
                }
            });
        },
        async loadComments() {
            await axios.post(`/admin/forums/${this.forumId}/comments`).then(res => {
                this.forum = res.data.forum;
                this.canAction = res.data.canAction;
            });
        },
        submitComment() {
            axios.post(`/admin/forums/${this.forum.id}/commentstore`, {
                comment: this.commentText,
                comment_id: null
            }).then(res => {
                this.forum.comments.unshift(res.data.forumComment);
                this.commentText = '';
            });

            // Inertia.post(route('admin.forums.comment', this.forum.id), {
            //     comment: this.commentText,
            //     comment_id: null
            // }, {
            //     preserveScroll: true,
            //     preserveState: true,
            //     onSuccess: () => {
            //         this.commentText = '';
            //     }
            // });
        }
    }
};
</script>
