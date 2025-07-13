<template>
<head>
    <title>Админ панель | {{ __('Хабарламалар') }}</title>
</head>

<AdminLayout>
    <template #breadcrumbs>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-user-shield text-primary me-2"></i> {{ __('Хабарламалар') }}
                </h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a :href="route('admin.index')">
                            <i class="fas fa-dashboard"></i> Басты бет
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Хабарламалар') }}</li>
                </ol>
            </div>
        </div>
    </template>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">Фильтр по статусу:</label>
                <select v-model="filter.status" @change="search" class="form-select">
                    <option value="all">Все</option>
                    <option value="unread">Непрочитанные</option>
                    <option value="read">Прочитанные</option>
                </select>
            </div>
        </div>

        <div v-if="notifications.length" class="row">
            <div v-for="notification in notifications" :key="'notification-' + notification.id" class="col-12 mb-3">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <!-- Дата уведомления -->
                        <small class="text-muted position-absolute top-0 end-0 p-3">
                            {{ notification.notification.created_at_human }}
                        </small>
                        <!-- Заголовок без ссылки -->
                        <h5 class="fw-semibold text-primary mb-3">
                            {{ formatTitle(notification.notification.title) }}
                        </h5>

                        <!-- Если в тексте есть автор и комментарий -->
                        <template v-if="isStructuredText(notification.notification.text)">
                            <p class="mb-1">
                                <strong>Автор:</strong> {{ extractAuthor(notification.notification.text) }}
                            </p>
                            <p class="mb-2">
                                <strong>Написал:</strong> "{{ extractComment(notification.notification.text) }}"
                            </p>
                        </template>

                        <!-- Иначе обычный текст -->
                        <template v-else>
                            <p class="text-dark mb-2" v-html="notification.notification.text"></p>
                        </template>

                        <!-- Кнопки действий -->
                        <div class="d-flex gap-2 mt-3">
                            <a v-if="extractLink(notification.notification.title)" :href="extractLink(notification.notification.title)" class="btn btn-outline-primary btn-sm" target="_blank">
                                Перейти
                            </a>
                            <button v-if="!notification.read_at" @click="markAsRead(notification.id)" class="btn btn-outline-secondary btn-sm">
                                Пометить как прочитанное
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div v-else class="alert alert-info text-center mt-4">
            Жаңа хабарлама жоқ.
        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {
    Link,
    Head
} from "@inertiajs/inertia-vue3";
export default {
    components: {
        AdminLayout,
        Link,
        Head
    },
    props: ["notifications"],
    data() {
        return {
            filter: {
                status: route().params.status ? route().params.status : 'all',
            },
            loading: 0,
        };
    },
    methods: {
        formatDate(date) {
            if (!date) return '';
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(date).toLocaleString('ru-RU', options);
        },
        async markAsRead(id) {
            try {
                await axios.post(`/admin/notifications/${id}/read`);
                window.location.reload()
            } catch (error) {
                console.error('Ошибка при пометке уведомления как прочитанного:', error);
            }
        },
        formatTitle(rawTitle) {
            const clean = rawTitle.replace(/<a .*<\/a>/, '').trim();
            return clean || 'Уведомление';
        },
        extractLink(rawTitle) {
            const match = rawTitle.match(/<a href=['"]([^'"]+)['"]/);
            return match ? match[1] : null;
        },
        isStructuredText(text) {
            return text.includes(':');
        },
        extractAuthor(text) {
            return text.split(':')[0];
        },
        extractComment(text) {
            return text.split(':').slice(1).join(':').trim();
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
                    this.$inertia.delete(route('admin.notifications.destroy', id))
                }
            });
        },
        removeFilter(key) {
            this.filter[key] = null;
            this.search();
        },
        search() {
            this.loading = 1
            const params = this.clearParams(this.filter);
            this.$inertia.get(route('admin.notifications.index'), params)
        },
    },
    computed: {
        activeFilters() {
            const filters = [];
            if (this.filter.title) filters.push({
                key: 'title',
                value: this.filter.title
            });
            return filters;
        }
    }
};
</script>

<style>
.custom,
.custom:hover,
.custom h5,
.custom h5:hover {
    text-decoration: none !important;
    color: inherit;
}

.custom {
    border: 1px solid transparent;
    transition: border 0.3s ease;
    border-radius: 12px;
}

.custom:hover {
    border: 1px solid #a7a7a7;
}
</style>
