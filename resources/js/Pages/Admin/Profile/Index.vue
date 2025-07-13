<template>

    <head>
        <title>Админ панель | Жеке кабинет</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-user text-primary me-2"></i> Жеке кабинет
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
                            Жеке кабинет
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <div class="container mt-3">
            <div class="row align-items-center border rounded shadow p-4 bg-white">

                <!-- Правая часть: Аватар -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="position-relative rounded p-3 text-center avatar-container d-flex flex-column align-items-center gap-2">
                        <div class="avatar-wrapper">
                            <img :src="route('index') + '/storage/' + user.avatar" alt="Аватар" class="rounded-circle img-thumbnail mb-2 avatar-img" style="width: 150px; height: 150px; object-fit: cover;">
                            <button type="button" class="btn avatar-overlay" @click="$refs.avatar.click()">
                                <i class="fas fa-camera fa-2x text-white"></i>
                            </button>
                        </div>
                        
                        <input ref="avatar" type="file" accept="image/*" class="d-none" @change="handleImageUpload">

                        <!-- Имя и должность -->
                        <div>
                            <h5 class="mb-1">{{ user.fio }}</h5>
                            <p class="mb-0 text-muted">{{ user.job_title }}</p>
                        </div>
                    </div>
                </div>

                <!-- Левая часть: Информация о пользователе -->
                <div class="col-md-8">
                    <p>
                        <i class="fas fa-phone-alt me-1 text-muted"></i>
                        <strong>Телефон:</strong> {{ user.phone }}
                    </p>

                    <p>
                        <i class="fas fa-envelope me-1 text-muted"></i>
                        <strong>Почта:</strong> {{ user.mail || 'Не указана' }}
                    </p>

                    <p>
                        <i class="fas fa-id-card me-1 text-muted"></i>
                        <strong>ИИН:</strong> {{ user.iin }}
                    </p>

                    <p>
                        <i class="fas fa-user-tag me-1 text-muted"></i>
                        <strong>Роль:</strong> {{ user.role.name }}
                    </p>

                    <p>
                        <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                        <strong>Аудан:</strong> {{ user.audan.name }}
                    </p>


                    <!-- Ссылки -->
                    <div class="mt-3">
                        <!-- Автобиография -->
                        <template v-if="user.autobiography_link">
                            <div class="position-relative d-inline-block me-3">
                                <!-- Ссылка на файл -->
                                <a :href="route('index') + '/storage/' + user.autobiography_link" class="btn btn-outline-success btn-sm" target="_blank">
                                    Автобиография
                                </a>

                                <!-- Кнопка удаления — круглая -->
                                <button class="btn btn-danger btn-sm position-absolute " style="top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; padding: 0;" @click="removedoc(1)" title="Жою">
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Кнопка + скрытый input -->
                        <template v-else>
                            <button class="btn btn-primary btn-sm me-3" @click="$refs.autobiography.click()">
                                Добавить автобиографию
                            </button>

                            <input ref="autobiography" type="file" accept=".pdf,.doc,.docx" class="d-none" @change="handleautobiographyUpload">
                        </template>

                        <!-- Сертификат -->
                        <template v-if="user.certificate_link">
                            <div class="position-relative d-inline-block me-3">
                                <!-- Ссылка на файл -->
                                <a :href="route('index') + '/storage/' + user.certificate_link" class="btn btn-outline-success btn-sm" target="_blank">
                                    Сертификат
                                </a>

                                <!-- Кнопка удаления — круглая -->
                                <button class="btn btn-danger btn-sm position-absolute" style="top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; padding: 0;" @click="removedoc(2)" title="Жою">
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </button>
                            </div>
                        </template>


                        <template v-else>
                            <button class="btn btn-primary btn-sm me-2" @click="$refs.certificate.click()">
                                Добавить сертификат
                            </button>

                            <input ref="certificate" type="file" class="d-none" @change="handleCertificateUpload" accept=".pdf,.doc,.docx" />
                        </template>


                        <!-- Документ -->
                        <template v-if="user.document_link">
                            <div class="position-relative d-inline-block me-2">
                                <!-- Ссылка на файл -->
                                <a :href="route('index') + '/storage/' + user.document_link" class="btn btn-outline-success btn-sm" target="_blank">
                                    Документ
                                </a>

                                <!-- Кнопка удаления — круглая -->
                                <button class="btn btn-danger btn-sm position-absolute" style="top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; padding: 0;" @click="removedoc(3)" title="Жою">
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </button>
                            </div>
                        </template>


                        <template v-else>
                            <button class="btn btn-primary btn-sm" @click="$refs.document.click()">
                                Добавить документ
                            </button>

                            <input ref="document" type="file" class="d-none" @change="handleDocumentUpload" accept=".pdf,.doc,.docx" />
                        </template>

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
        props: ["user"],
        data() {
            return {
                loading: 0,
            };
        },
        methods: {
            addDocument(type) {
                switch (type) {
                    case 'autobiography':
                        alert('Открыть форму загрузки автобиографии')
                        break
                    case 'certificate':
                        alert('Открыть форму загрузки сертификата')
                        break
                    case 'document':
                        alert('Открыть форму загрузки документа')
                        break
                }
            },
            handleautobiographyUpload() {
                this.user.autobiography = this.$refs.autobiography.files[0];
                this.$inertia.post(
                    route("admin.profile.uploadautobiography", this.user.id),
                    this.user, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },
            handleCertificateUpload() {
                this.user.certificate = this.$refs.certificate.files[0];
                this.$inertia.post(
                    route("admin.profile.uploadcertificate", this.user.id),
                    this.user, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },
            handleDocumentUpload() {
                this.user.document = this.$refs.document.files[0];
                this.$inertia.post(
                    route("admin.profile.uploaddocument", this.user.id),
                    this.user, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },
            handleImageUpload() {
                this.user.avatar = this.$refs.avatar.files[0];
                this.$inertia.post(
                    route("admin.profile.uploadavatar", this.user.id),
                    this.user, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },
            
            removedoc(a) {
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
                        switch (a) {
                            case 1:
                                return this.$inertia.delete(route('admin.profile.deleteautobiography'));
                            case 2:
                                return this.$inertia.delete(route('admin.profile.deletecertificate'));
                            case 3:
                                return this.$inertia.delete(route('admin.profile.deletedocument'));
                        }

                    }
                });
            },



        },
    };

</script>

<style scoped>
    .avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .avatar-wrapper:hover .avatar-overlay {
        opacity: 1;
    }

</style>
