<template>
  <head>
        <title>Админ панель | Аудан қосу</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-map-marked-alt text-primary me-2"></i> Аудан қосу
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
                        <li class="breadcrumb-item">
                            <a :href="route('admin.audans.index')">
                                <i class="fas fa-dashboard"></i>
                                Аудан тізімі
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Аудан қосу
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <div class="container-fluid">
            <div class="card card-primary">
                <form method="post" @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Аты</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="audan.name"
                                name="name"
                                placeholder="Енгізу жолы"
                            />
                            <validation-error :field="'name'" />
                        </div>
                        <div class="form-group">
                            <label for="">Координаты по длине</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="audan.lat"
                                name="lat"
                                placeholder="Енгізу жолы"
                            />
                            <validation-error :field="'lat'" />
                        </div>
                        <div class="form-group">
                            <label for="">Координаты по ширине</label>
                            <input
                                type="text"
                                class="form-control"
                                v-model="audan.lng"
                                name="lng"
                                placeholder="Енгізу жолы"
                            />
                            <validation-error :field="'lng'" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-1">
                            Сақтау
                        </button>
                        <button type="button" class="btn btn-danger" @click.prevent="back()">
                            Артқа
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import { Link, Head } from "@inertiajs/inertia-vue3";
import Pagination from "../../../Components/Pagination.vue";
import ValidationError from "../../../Components/ValidationError.vue";

export default {
    components: {
        AdminLayout,
        Link,
        Pagination,
        ValidationError,
        Head
    },
    data() {
        return {
            audan: {
                name: null,
                lat: 40.7567,
                lng: 68.2843,
            }
        }
    },
    methods: {
        submit() {
            this.$inertia.post(
                route("admin.audans.store"),
                this.audan,
                {
                    onError: () => console.log("An error has occurred"),
                    onSuccess: () =>
                        console.log("The new contact has been saved"),
                }
            );
        },
    },
};
</script>