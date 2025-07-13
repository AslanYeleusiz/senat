<template>

    <head>
        <title>Админ панель | Жобалар</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-briefcase text-primary me-2"></i> Жобалар тізімі
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
                            Жобалар тізімі
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <template #header>
            <div class="buttons d-flex align-items-center">
                <Link class="btn btn-primary mr-2" :href="route('admin.projects.create')">
                <i class="fa fa-plus"></i> Қосу
                </Link>
                <button type="button" class="btn btn-secondary mr-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fa fa-filter"></i> Фильтр
                </button>
                <Link class="btn btn-danger" :href="route('admin.projects.index')">
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
                        <div class="modal-body pb-4">
                            <form @submit.prevent="search">
                                <div class="form-group">
                                    <label for="project" class="form-label">Ел арқылы іздеу</label>
                                    <input v-model="filter.country" type="text" class="form-control" placeholder="Елі" />
                                </div>
                                <div class="form-group">
                                    <label for="project" class="form-label">Аудан арқыды іздеу</label>
                                    <select v-model="filter.audan_id" class="form-select">
                                        <option hidden :value="null">Аудан таңдаңыз</option>
                                        <option v-for="audan in audans" :key="audan.id" :value="audan.id">
                                            {{ audan.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="project" class="form-label">Жоба түрі арқыды іздеу</label>
                                    <select v-model="filter.project_type" class="form-select">
                                        <option hidden :value="null">Жоба түрін таңдаңыз</option>
                                        <option v-for="projectType in projectTypes" :value="projectType.id">{{projectType.name}}</option>
                                    </select>
                                </div>


                                <button type="submit" class="btn w-100 btn-primary" data-bs-dismiss="modal"><i class="fas fa-search"></i> Іздеу</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4 col-lg-6" v-for="(project, index) in projects.data" :key="'project' + project.id">
                    <div class="card border-0 shadow">
                        <div class="card-img-top" :style="{
       backgroundImage: `url(${route('index')}/storage/images/${project.images[0]})`,
       backgroundPosition: '50% 10%',
       backgroundSize: 'cover',
       width: '100%',
       height: '250px'
     }" alt="...">
                        </div>

                        <div class="card-body p-3">
                            <ul class="list-unstyled mb-3">
                                <li class="mb-2">
                                    <i class="fas fa-project-diagram text-primary me-2"></i>
                                    <strong>Жоба аты:</strong> {{ project.name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-flag text-success me-2"></i>
                                    <strong>Елі:</strong> {{ project.country }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <strong>Аудан:</strong> {{ project.audan.name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-layer-group text-info me-2"></i>
                                    <strong>Жоба түрі:</strong> {{ project.project_type.name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-dollar-sign text-warning me-2"></i>
                                    <strong>Суммасы:</strong> {{ project.invest_sum.toLocaleString() }} ₸
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user-tie text-secondary me-2"></i>
                                    <strong>Куратор:</strong> {{ project.curator }}
                                </li>
                            </ul>


                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 py-2 border-top mt-3 pt-3">
                                <div>
                                    <span class="text-muted"><i class="fas fa-info-circle me-1"></i>Жоба әрекеттері</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <Link :href="route('admin.projects.show', project)" class="btn btn-sm btn-outline-info" title="Жобаны көру">
                                    <i class="fas fa-eye me-1"></i> Жобаны көру
                                    </Link>

                                    <Link :href="route('admin.projects.edit', project)" class="btn btn-sm btn-outline-primary" title="Өзгерту">
                                    <i class="fas fa-pencil-alt me-1"></i> Өзгерту
                                    </Link>

                                    <button @click.prevent="deleteData(project.id, project.is_archived)" class="btn btn-sm" :class="project.is_archived ? 'btn-outline-success' : 'btn-outline-danger'" :title="project.is_archived ? 'Архивтан қайтару' : 'Архивқа салу'">
                                        <i class="fas me-1" :class="project.is_archived ? 'fa-redo-alt' : 'fa-archive'"></i>
                                        {{ project.is_archived ? 'Архивтан қайтару' : 'Архивқа салу' }}
                                    </button>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="px-3 py-2">
                <Pagination :links="projects.links" />
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
        props: ["projects", "audans", "projectTypes"],
        data() {
            return {
                filter: {
                    country: route().params.country ? route().params.country : null,
                    audan_id: route().params.audan_id ? route().params.audan_id : null,
                    project_type: route().params.project_type ? route().params.project_type : null
                },
                loading: 0,
            };
        },
        methods: {
            deleteData(id, status) {
                Swal.fire({
                    title: status ? 'Архивтен қайтаруға сенімдісіз бе?' : 'Архивке салуға сенімдісіз бе?',
                    text: status ? '' : 'Архивтағы жобалар басқалар қолданушыларға көрінбейді!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Иә",
                    cancelButtonText: "Жоқ",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$inertia.delete(route('admin.projects.destroy', id))
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
                this.$inertia.get(route('admin.projects.index'), params)
            },
        },
        computed: {
            activeFilters() {
                const filters = [];
                if (this.filter.country) filters.push({
                    key: 'country',
                    value: this.filter.country
                });
                if (this.filter.audan_id) filters.push({
                    key: 'audan_id',
                    value: this.filter.audan_id
                });
                if (this.filter.project_type) filters.push({
                    key: 'project_type',
                    label: 'Жоба түрі ID',
                    value: this.filter.project_type
                });

                return filters;
            }
        }
    };

</script>
