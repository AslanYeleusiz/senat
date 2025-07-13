<template>

    <head>
        <title>Админ панель | Қолданушы қосу</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Қолданушы қосу</h1>
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
                            <a :href="route('admin.users.index')">
                                <i class="fas fa-dashboard"></i>
                                Қолданушы тізімі
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Қолданушы қосу
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
                            <label for="">Фамилия, аты-жөні <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="user.fio" name="fio" placeholder="Жолдас Қанат Толқынұлы" />
                            <validation-error :field="'fio'" />
                        </div>
                        <div class="form-group">
                            <label for="">Лауазым <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="user.job_title" name="job_title" placeholder="Басқарма басшысы" />
                            <validation-error :field="'job_title'" />
                        </div>

                        <div class="form-group">
                            <label for="">Телефон нөмірі <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="user.phone" name="phone" placeholder="+7(7__) ___ __-__" v-mask="'+7(7##) ### ##-##'" />
                            <validation-error :field="'phone'" />
                        </div>
                        <div class="form-group">
                            <label for="">Почта</label>
                            <input type="email" class="form-control" v-model="user.mail" name="mail" placeholder="example@gmail.com" />
                            <validation-error :field="'mail'" />
                        </div>
                        <div class="form-group">
                            <label for="">ИИН <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="user.iin" name="iin" v-mask="'############'" placeholder="000000000000" />
                            <validation-error :field="'iin'" />
                        </div>
                        <div class="form-group">
                            <label for="">Құпия сөз <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" v-model="user.real_password" name="real_password" placeholder="********" />
                            <validation-error :field="'real_password'" />
                        </div>
                        <div class="form-group">
                            <label for="">Аудан <span class="text-danger">*</span></label>
                            <select class="form-control" @change.prevent="search" v-model="user.audan_id" placeholder="Ауданды таңдаңыз">
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
                            <label for="">Рөлі <span class="text-danger">*</span></label>
                            <select class="form-control" @change.prevent="search" v-model="user.role_id" placeholder="Рөлді таңдаңыз">
                                <option hidden :value="null">
                                    Рөлді таңдаңыз
                                </option>
                                <option v-for="role in roles" :value="role.id">
                                    {{role.name}}
                                </option>
                            </select>

                            <validation-error :field="'role_id'" />
                        </div>
                        <label class="form-label h3 mt-5">Қосымша материал</label>
                        <div class="form-group">
                            <label for="image" class="form-label">Аватар</label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                               <!-- Текущее изображение пользователя -->
                                <img v-if="user.avatar && !image.file" :src="route('index') + '/storage/' + user.avatar" class="img-thumbnail mb-2" alt="User Avatar" style="max-width: 300px;" />
                                <!-- Предпросмотр загружаемого изображения -->
                                <img v-show="image.preview" :src="image.preview" class="img-thumbnail mb-2" alt="Preview" style="max-width: 300px;" />

                                <!-- Загрузка файла -->
                                <div class="input-group">
                                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png" @change="handleImageUpload()" ref="image" />
                                </div>
                            </div>
                            <validation-error :field="'avatar'" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">АВТОБИОГРАФИЯ</label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                               <!-- Текущее Файл пользователя -->
                                <div v-if="user.autobiography_link" class="text-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ user.autobiography_link }}</span>
                                </div>
                                <div v-if="autobiography.file" class="text-success d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ autobiography.file.name }}</span>
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="autobiography" name="autobiography" @change="handleautobiographyUpload()" ref="autobiography" />
                                </div>
                                <validation-error :field="'autobiography'" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Паспорт</label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                               <div v-if="user.certificate_link" class="text-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ user.certificate_link }}</span>
                                </div>
                                <div v-if="certificate.file" class="text-success d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ certificate.file.name }}</span>
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="certificate" name="certificate" @change="handlecertificateUpload()" ref="certificate" />
                                </div>
                                <validation-error :field="'certificate'" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Қосымша документ</label>
                            <div class="border rounded p-3 bg-light d-flex flex-column gap-2">
                               <div v-if="user.document_link" class="text-primary d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ user.document_link }}</span>
                                </div>
                                <div v-if="document.file" class="text-success d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt fs-5"></i>
                                    <span>{{ document.file.name }}</span>
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="document" name="document" @change="handledocumentUpload()" ref="document" />
                                </div>
                                <validation-error :field="'document'" />
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

    export default {
        components: {
            AdminLayout,
            Link,
            Pagination,
            ValidationError,
            Head
        },
        props: ['user', 'roles', 'audans'],
        data() {
            return {
                autobiography: {
                    file: "",
                    filename: "",
                },
                certificate: {
                    file: "",
                    filename: "",
                },
                document: {
                    file: "",
                    filename: "",
                },
                
                image: {
                    file: "",
                    preview: "",
                },
            }
        },
        methods: {
            submit() {
                if (this.autobiography.file) {
                    this.user.autobiography = this.autobiography.file
                }
                if (this.certificate.file) {
                    this.user.certificate = this.certificate.file
                }
                if (this.document.file) {
                    this.user.document = this.document.file
                }
                if (this.image.file) {
                    this.user.avatar = this.image.file
                }
                this.user['_method'] = 'put';
                this.$inertia.post(
                    route("admin.users.update", this.user.id),
                    this.user, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },



            handleImageUpload() {
                this.image.file = this.$refs.image.files[0];
                if (this.image.file) {
                    const reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.image.preview = reader.result;
                    };
                    reader.readAsDataURL(this.image.file);
                    this.$refs.image.value = "";
                } else {
                    this.user.avatar = "";
                    this.image.file = "";
                    this.image.preview = "";
                }
            },
            handleautobiographyUpload() {
                this.autobiography.file = this.$refs.autobiography.files[0];
                if (this.autobiography.file) {
                    this.autobiography.filename = this.autobiography.file.name;
                    const reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.autobiography.preview = reader.result;
                    };
                    reader.readAsDataURL(this.autobiography.file);
                    this.$refs.autobiography.value = "";
                } else {
                    this.user.autobiography = "";
                    this.autobiography.file = "";
                    this.autobiography.filname = "";
                }
            },
            handlecertificateUpload() {
                this.certificate.file = this.$refs.certificate.files[0];
                if (this.certificate.file) {
                    this.certificate.filename = this.certificate.file.name;
                    const reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.certificate.preview = reader.result;
                    };
                    reader.readAsDataURL(this.certificate.file);
                    this.$refs.certificate.value = "";
                } else {
                    this.user.certificate = "";
                    this.certificate.file = "";
                    this.certificate.filname = "";
                }
            },
            handledocumentUpload() {
                this.document.file = this.$refs.document.files[0];
                if (this.document.file) {
                    this.document.filename = this.document.file.name;
                    const reader = new FileReader();
                    reader.onloadend = (file) => {
                        this.document.preview = reader.result;
                    };
                    reader.readAsDataURL(this.document.file);
                    this.$refs.document.value = "";
                } else {
                    this.user.document = "";
                    this.document.file = "";
                    this.document.filname = "";
                }
            },

        },
    };

</script>
