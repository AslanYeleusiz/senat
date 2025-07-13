<template>

    <head>
        <title>Админ панель | Сұраныстар</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-user-shield text-primary me-2"></i> Сұраныстар тізімі
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
                            Сұраныстар тізімі
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <template #header>
            <div class="buttons d-flex align-items-center">
                <button type="button" class="btn btn-secondary mr-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fa fa-filter"></i> Фильтр
                </button>
                <Link class="btn btn-danger" :href="route('admin.applications.index')">
                <i class="fa fa-trash"></i> Фильтрді тазалау
                </Link>
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
                                    <label for="application" class="form-label">Рөл атауы</label>
                                    <input type="text" class="form-control" id="application" placeholder="Енгізу жолы" v-model="filter.name">
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
                                    <th scope="col">Кездесу мақсаты</th>
                                    <th scope="col">Проект</th>
                                    <th scope="col">Кімге</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col">Әрекет</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(application, index) in applications.data" :key="'application' + application.id">
                                    <td>
                                        {{
                                application.from
                                    ? application.from + index
                                    : index + 1
                            }}
                                    </td>
                                    <td>{{ application.name }}</td>
                                    <td>{{ application.project.name }}</td>
                                    <td>{{ application.send_user.fio }}</td>
                                    <td>
                                        <span class="badge badge-success" :class="{
                                                    'badge-warning':
                                                        application.status ==
                                                        0,
                                                    'badge-danger':
                                                        application.status ==
                                                        1,
                                                    'badge-success':
                                                        application.status ==
                                                        2,
                                                    
                                                }">
                                            {{
                                                    getStatusText(
                                                        application.status
                                                    )
                                                }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <Link :href="route('admin.applications.edit', application)" class="btn btn-outline-primary" title="Көру">
                                            <i class="fas fa-comment"></i>
                                            </Link>

                                            <button @click.prevent="deleteData(application.id)" class="btn btn-outline-danger" title="Жою">
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
        props: ["applications"],
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
                        this.$inertia.delete(route('admin.applications.destroy', id))
                    }
                });
            },
            removeFilter(key) {
                this.filter[key] = null;
                this.search();
            },
            getStatusText(status) {
                if (status == 0) {
                    return "Қаралмады";
                }
                if (status == 1) {
                    return "Қабылданбады";
                }
                if (status == 2) {
                    return "Қабылданды";
                }
                return "Анықталмаған";
            },
            search() {
                this.loading = 1
                const params = this.clearParams(this.filter);
                this.$inertia.get(route('admin.applications.index'), params)
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
