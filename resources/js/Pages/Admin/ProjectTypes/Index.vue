<template>

    <head>
        <title>Админ панель | Жоба түрі</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-bullhorn text-primary me-2"></i> Жоба түрі тізімі
                    </h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a :href="route('admin.index')">
                                <i class="fas fa-dashboard"></i>
                                Басты бет
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Жоба түрі тізімі
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <template #header>
            <div class="buttons d-flex align-items-center">
                <Link class="btn btn-primary mr-2" :href="route('admin.projectTypes.create')">
                <i class="fa fa-plus"></i> Қосу
                </Link>
                <button type="button" class="btn btn-secondary mr-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fa fa-filter"></i> Фильтр
                </button>
                <Link class="btn btn-danger" :href="route('admin.projectTypes.index')">
                <i class="fa fa-trash"></i> Фильтрді тазалау
                </Link>
                <div v-if="loading" class="spinner-border text-primary mx-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div v-if="activeFilters.length" class="mt-2 d-flex flex-wrap align-items-center">
                <span class="me-2">Қолданылған фильтрлер:</span>
                <span v-for="(filter, index) in activeFilters" :key="index" class="badge bg-info text-dark me-2 d-flex align-items-center">
                    {{ filter.label }}: {{ filter.value }}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" @click="removeFilter(filter.key)" aria-label="Жою"></button>
                </span>
            </div>

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
                                    <label for="projectType" class="form-label">Рөл атауы</label>
                                    <input type="text" class="form-control" id="projectType" placeholder="Енгізу жолы" v-model="filter.name">
                                </div>

                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Іздеу</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="container-fluid">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">№</th>
                                    <th scope="col">Аты</th>
                                    <th scope="col">Әрекет</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(projectType, index) in projectTypes" :key="'projectType' + projectType.id">
                                    <td>
                                        {{
                                projectType.from
                                    ? projectType.from + index
                                    : index + 1
                            }}
                                    </td>
                                    <td>{{ projectType.name }}</td>
                                    <td class="col-md-2">
                                        <div class="btn-group btn-group-sm">
                                            <Link :href="route('admin.projectTypes.edit', projectType)" class="btn btn-primary" title="Өзгерту">
                                            <i class="fas fa-edit"></i>
                                            </Link>

                                            <button @click.prevent="deleteData(projectType.id)" class="btn btn-danger" title="Жою">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
        props: ["projectTypes"],
        data() {
            return {
                filter: {
                    name: route().params.name ? route().params.name : null,
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
                        this.$inertia.delete(route('admin.projectTypes.destroy', id))
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
                this.$inertia.get(route('admin.projectTypes.index'), params)
            },
        },
        computed: {
            activeFilters() {
                const filters = [];
                if (this.filter.name) filters.push({
                    key: 'name',
                    value: this.filter.name
                });
                return filters;
            }
        }
    };

</script>