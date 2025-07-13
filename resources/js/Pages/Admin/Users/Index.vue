<template>

    <head>
        <title>Админ панель | Қолданушылар</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-users text-primary me-2"></i> Қолданушылар тізімі
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
                            Қолданушылар тізімі
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <template #header>
            <div class="buttons d-flex align-items-center">
                <Link class="btn btn-primary mr-2" :href="route('admin.users.create')">
                <i class="fa fa-plus"></i> Қосу
                </Link>
                <Link class="btn btn-danger" :href="route('admin.users.index')">
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
        </template>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card" style="">
                        <div class="card-header bg-secondary">
                            <h5 class="modal-title" id="filterModalLabel">Фильтр</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="search">
                                <div class="mb-1">
                                    <label class="form-label">Рөл атауы</label>
                                    <input v-model="filter.fio" class="form-control" placeholder="Енгізу жолы" @keyup.enter="search" />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label">ИИН арқылы іздеу</label>
                                    <input v-model="filter.iin" class="form-control" placeholder="Енгізу жолы" @keyup.enter="search" />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label">Почта арқылы іздеу</label>
                                    <input v-model="filter.mail" class="form-control" placeholder="Енгізу жолы" @keyup.enter="search" />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label">Телефон номері арқылы іздеу</label>
                                    <input v-model="filter.phone" class="form-control" placeholder="Енгізу жолы" @keyup.enter="search" />
                                </div>


                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Іздеу</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4" v-for="user in users.data" :key="'user' + user.id">
                                    <div class="card text-center p-3 w-100 shadow-sm">
                                        <img :src="route('index') + '/storage/' + user.avatar" alt="Аватар" class="rounded-circle mx-auto mb-3 img-thumbnail" style="width: 250px; height: 250px; object-fit: cover;" />
                                        <div>
                                            <h5 class="mb-1">{{ user.fio }}</h5>
                                            <p class="mb-2 text-muted">{{ user.job_title }}</p>

                                            <!-- Действия -->
                                            <div class="btn-group btn-group-sm justify-content-center">
                                                <Link :href="route('admin.users.edit', user)" class="btn btn-primary" title="Өзгерту">
                                                <i class="fas fa-edit"></i>
                                                </Link>
                                                <button @click.prevent="deleteData(user.id)" class="btn btn-danger" title="Жою">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="px-3 py-2">
                            <Pagination :links="users.links" />
                        </div>
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
    import Pagination from "../../../Components/Pagination.vue";
    export default {
        components: {
            AdminLayout,
            Link,
            Pagination,
            Head
        },
        props: ["users"],
        data() {
            return {
                filter: {
                    fio: route().params.fio ? route().params.fio : null,
                    iin: route().params.iin ? route().params.iin : null,
                    mail: route().params.mail ? route().params.mail : null,
                    phone: route().params.phone ? route().params.phone : null,
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
                        this.$inertia.delete(route('admin.users.destroy', id))
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
                this.$inertia.get(route('admin.users.index'), params)
            },
        },
        computed: {
            activeFilters() {
                const filters = [];
                if (this.filter.fio) filters.push({
                    key: 'fio',
                    label: 'ФИО',
                    value: this.filter.fio
                });
                if (this.filter.iin) filters.push({
                    key: 'iin',
                    label: 'ИИН',
                    value: this.filter.iin
                });
                if (this.filter.mail) filters.push({
                    key: 'mail',
                    label: 'Почта',
                    value: this.filter.mail
                });
                if (this.filter.phone) filters.push({
                    key: 'phone',
                    label: 'Телефон',
                    value: this.filter.phone
                });
                return filters;
            }
        }
    };

</script>
