<template>
<head>
    <title>Админ панель | {{ __('Форум') }}</title>
</head>

<AdminLayout>
    <template #breadcrumbs>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-user-shield text-primary me-2"></i> {{ __('Форум') }}
                </h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a :href="route('admin.index')">
                            <i class="fas fa-dashboard"></i> Басты бет
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Форум') }}</li>
                </ol>
            </div>
        </div>
    </template>

    <template #header>
        <div class="buttons d-flex flex-wrap align-items-center gap-2">
            <Link class="btn btn-primary" :href="route('admin.forums.create')">
            <i class="fa fa-plus"></i> {{ __('Қосу') }}
            </Link>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fa fa-filter"></i> Фильтр
            </button>
            <Link class="btn btn-danger" :href="route('admin.forums.index')">
            <i class="fa fa-trash"></i> {{ __('Фильтрді тазалау') }}
            </Link>

            <div v-if="loading" class="spinner-border text-primary ms-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-if="activeFilters.length" class="mt-3 d-flex flex-wrap align-items-center">
            <span class="me-2">Қолданылған фильтрлер:</span>
            <span v-for="(filter, index) in activeFilters" :key="index" class="badge bg-info text-dark me-2 d-flex align-items-center">
                {{ filter.label }}: {{ filter.value }}
                <button type="button" class="btn-close btn-close-white btn-sm ms-2" @click="removeFilter(filter.key)" aria-label="Жою"></button>
            </span>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title" id="filterModalLabel">Фильтр</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Жабу"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="search">
                            <div class="mb-3">
                                <label for="forum" class="form-label">Сұрақ атауы</label>
                                <input type="text" class="form-control" id="forum" placeholder="Енгізу жолы" v-model="filter.title" />
                            </div>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Іздеу</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <div class="container-fluid">
        <div v-if="forums.data.length" class="row">
            <div v-for="(forum, index) in forums.data" :key="'forum-' + forum.id" class="col-12">
                <Link :href="route('admin.forums.show', forum)" class="card shadow-sm rounded-3 custom">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            {{ forum.title }}
                        </h5>
                    </div>
                </div>
                </Link>
            </div>
        </div>

        <div v-else class="alert alert-info text-center mt-4">
            Форум сұрақтары табылмады.
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
    props: ["forums"],
    data() {
        return {
            filter: {
                title: route().params.title ? route().params.title : null,
            },
            loading: 0,
        };
    },
    methods: {
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
        removeFilter(key) {
            this.filter[key] = null;
            this.search();
        },
        search() {
            this.loading = 1
            const params = this.clearParams(this.filter);
            this.$inertia.get(route('admin.forums.index'), params)
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
