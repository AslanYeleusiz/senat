<template>

    <head>
        <title>Админ панель | Жоба қосу</title>
    </head>
    <AdminLayout>
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h5 class="modal-title" id="filterModalLabel">Қолданушы іздеу</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Жабу"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Іздеу:</label>
                        <searchUser @selectUser="handleUserSelection" close='true' />
                    </div>
                </div>
            </div>
        </div>
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

                            <validation-error :field="'project_type'" />
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
                            <input type="text" class="form-control" v-model="project.invest_sum" name="invest_sum" v-money3="moneyConfig" placeholder="Инвестиция суммасы" />
                            <validation-error :field="'invest_sum'" />
                        </div>
                        <div class="form-group">
                            <label for="">Жұмыскерлер саны <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" v-model="project.people_count" name="people_count" placeholder="Инвестиция суммасы" />
                            <validation-error :field="'people_count'" />
                        </div>
                        <div class="h4">Куратор</div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fa fa-plus"></i> Куратор таңдау
                        </button>
                        <validation-error :field="'curator_id'" />
                        <template v-if="project.curator_id">
                            <div class="form-group">
                                <label for="">Куратор аты-жөні <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-success" v-model="project.curator" name="curator" placeholder="Куратор аты-жөні" disabled />
                            </div>
                            <div class="form-group">
                                <label for="">Куратор байланыс номері <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-success" v-model="project.phone" name="phone" placeholder="Байланыс номері" disabled />
                            </div>
                        </template>
                        <div class="mb-4 mt-4">
                            <label for="material" class="form-label">Документ <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                                <div v-if="material.file" class="text-success d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ material.file.name }}</span>
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="material" name="material" @change="handleMaterialUpload()" ref="material" />
                                </div>
                                <validation-error :field="'file'" />

                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="material" class="form-label">Суреттер</label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                                <input id="adding" class="form-control" type="file" multiple ref="image" @change="handleImageUpload" accept="image/*" hidden />
                                <div>
                                    <template v-if="form.previews.length">
                                        <div v-for="(preview, index) in form.previews" :key="index" style="display: inline-block; margin: 10px">
                                            <img :src="preview" alt="Preview" style="width: 200px;" />
                                        </div>
                                    </template>
                                    <label for="adding" class="d-inline-flex justify-content-center align-items-center border border-dashed text-center py-5" style="width: 100px;cursor: pointer;">
                                        <div>
                                            <i class="fas fa-plus" style="font-size: 1.2rem; display: block;"></i>
                                            <span style="font-size: 0.9rem;">Қосу</span>
                                        </div>
                                    </label>
                                </div>
                                <validation-error :field="'images'" />
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
                project: {
                    name: null,
                    project_type: null,
                    country: null,
                    audan_id: null,
                    invest_sum: null,
                    people_count: null,
                    description: null,
                    phone: null,
                    curator: null,
                    file: null,
                    images: [],
                },
                moneyConfig: {
                  precision: 0,
                  prefix: '',
                  suffix: ' ₸',
                  thousands: ' ',
                  decimal: ',',
                  disableNegative: true,
                  disableRound: false
                },
                form: {
                    files: [],
                    previews: [],
                },
                material: {
                    file: "",
                },
            }
        },
        props: ['audans', 'projectTypes'],
        methods: {
            submit() {
                if (this.material.file) {
                    console.log(this.material.file)
                    this.project.file = this.material.file
                }

                if (this.form.files.length) {
                    this.project.images = this.form.files
                    console.log(this.project.images)
                }


                this.$inertia.post(
                    route("admin.projects.store"),
                    this.project, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },

            handleMaterialUpload() {
                this.material.file = this.$refs.material.files[0];
                console.log(this.material.file)
                if (this.material.file) {
                    this.$refs.material.value = "";
                } else {
                    this.project.file = "";
                    this.material.file = "";
                }
            },
            handleUserSelection(user) {
                // Логика обработки выбранного пользователя
                console.log('Выбран пользователь:', user);
                this.project.curator_id = user.id;
                this.project.phone = user.phone;
                this.project.curator = user.fio;
            },
            handleImageUpload(e) {
                const files = Array.from(e.target.files);

                files.forEach((file) => {
                    this.form.files.push(file);
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.form.previews.push(event.target.result);
                    };
                    reader.readAsDataURL(file);
                });


                this.$refs.image.value = ""; // сброс input
            },
        },
    };

</script>
