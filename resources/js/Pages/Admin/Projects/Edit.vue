<template>

    <head>
        <title>Админ панель | Жоба қосу</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Жоба қосу</h1>
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
                            <a :href="route('admin.projects.index')">
                                <i class="fas fa-dashboard"></i>
                                Жоба тізімі
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Жоба қосу
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <div class="container-fluid">
            <div class="card card-primary">
               <searchUser @selectUser="handleUserSelection" />
                <form method="post" @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Жоба аты <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="project.name" name="name" placeholder="Жоба атын енгізініз" />
                            <validation-error :field="'name'" />
                        </div>
                        <div class="form-group">
                            <label for="">Жоба түрі <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="project.project_type" placeholder="Жоба түрін таңдаңыз">
                                <option hidden :value="null">
                                    Жоба түрін таңдаңыз
                                </option>
                                <option v-for="projectType in projectTypes" :value="projectType.id">{{projectType.name}}</option>
                            </select>

                            <validation-error :field="'password'" />
                        </div>

                        <div class="form-group">
                            <label for="">Сипаттама <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="project.description" name="description" placeholder="Сипаттама" />
                            <validation-error :field="'description'" />
                        </div>
                        <div class="form-group">
                            <label for="">Елі <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="project.country" name="country" placeholder="Елі" />
                            <validation-error :field="'country'" />
                        </div>
                        <div class="form-group">
                            <label for="">Аудан <span class="text-danger">*</span></label>
                            <select class="form-control" @change.prevent="search" v-model="project.audan_id" placeholder="Ауданды таңдаңыз">
                                <option hidden :value="null">
                                    Ауданды таңдаңыз
                                </option>
                                <option v-for="audan in audans" :value="audan.id">
                                    {{audan.name}}
                                </option>
                            </select>

                            <validation-error :field="'audan_id'" />
                        </div>
                        <div class="form-group">
                            <label for="">Инвестиция суммасы (₸) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" v-model="project.invest_sum" name="invest_sum" placeholder="Инвестиция суммасы" />
                            <validation-error :field="'invest_sum'" />
                        </div>
                        <div class="form-group">
                            <label for="">Жұмыскерлер саны <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" v-model="project.people_count" name="people_count" placeholder="Инвестиция суммасы" />
                            <validation-error :field="'people_count'" />
                        </div>

                        <div class="h4">Куратор</div>
                            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fa fa-plus"></i> Куратор таңдау
                            </button>
                        <template v-if="project.curator_id">
                            <div class="form-group">
                            <label for="">Куратор аты-жөні <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" v-model="project.curator" name="curator" placeholder="Куратор аты-жөні" disabled />
                            <validation-error :field="'curator'" />
                        </div>
                        <div class="form-group">
                            <label for="">Куратор байланыс номері <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-success" v-model="project.phone" name="phone" placeholder="Байланыс номері" disabled />
                            <validation-error :field="'phone'" />
                        </div>
                        </template>
                        <div class="mb-4">
                            <label for="file" class="form-label">Документ <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                                <!-- Текущее Файл пользователя -->
                                <div v-if="project.file" class="text-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ project.file }}</span>
                                </div>
                                <!-- Предпросмотр загружаемого Файла -->
                                <div v-if="file.file" class="text-success d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ file.file.name }}</span>
                                </div>

                                <div class="input-group">
                                    <input type="file" class="form-control" id="file" name="file" @change="handlefileUpload()" ref="file" />
                                </div>
                                <validation-error :field="'file'" />
                            </div>
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
    import {
        Link,
        Head
    } from "@inertiajs/inertia-vue3";
    import Pagination from "../../../Components/Pagination.vue";
    import ValidationError from "../../../Components/ValidationError.vue";
    import searchUser from "../../../Components/forms/searchUser.vue";

    export default {
        components: {
            AdminLayout,
            Link,
            Pagination,
            ValidationError,
            searchUser,
            Head
        },
        data() {
            return {
                file: {
                    file: "",
                },
            }
        },
        props: ['project', 'audans', 'projectTypes'],
        methods: {
            submit() {
                if (this.file.file) {
                    this.project.file = this.file.file
                }
                this.project['_method'] = 'put';
                this.$inertia.post(
                    route("admin.projects.update", this.project.id),
                    this.project, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () => {
                            console.log("The new contact has been saved");
                            this.file.file = "";
                        }
                    }
                );
            },
            handleUserSelection(user) {
                // Логика обработки выбранного пользователя
                console.log('Выбран пользователь:', user);
                this.project.curator_id = user.id;
                this.project.phone = user.phone;
                this.project.curator = user.fio;
            },
            handlefileUpload() {
                this.file.file = this.$refs.file.files[0];
                if (this.file.file) {
                    this.$refs.file.value = "";
                } else {
                    this.project.file = "";
                    this.file.file = "";
                }
            },
        },
    };

</script>
