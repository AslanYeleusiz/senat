<template>
<head>
    <title>Жоба № {{project.id}}</title>
</head>
<AdminLayout>
    <template #breadcrumbs>
        <div class="row mb-2">
            <div class="col-sm-6">

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
                        Жоба № {{project.id}}
                    </li>
                </ol>
            </div>
        </div>
    </template>
    <!-- Модальное окно Оставить заявку на проект -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="requestModalLabel">
                        <i class="fas fa-paper-plane"></i> Оставить заявку на проект
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" @submit.prevent="applicationStore()">
                    <div class="modal-body">
                        <!-- Цель -->
                        <div class="form-group">
                            <label for="">📅 <strong>Цель встречи:</strong></label>
                            <input type="text" class="form-control" v-model="application.goal" placeholder="Согласование земельного участка" />
                        </div>

                        <!-- Кому -->
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label">Кому:</label>
                                <input v-model="application.user_id" style="opacity: 0; user-select: none;" required />
                                <div v-if="application.user_id" class="form-control bg-light border-success d-flex justify-content-between">
                                    <div>{{application.userfio}} - {{application.userjob}}</div>
                                    <a href="#" class="text-danger" @click="clearFilterUser(1)">Өшіру</a>
                                </div>
                                <searchUser v-else @selectUser="actionapp" />
                            </div>
                        </div>

                        <!-- Инвестор -->
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label">Инвестор:</label>
                                <input class="form-control" v-model="application.investor" placeholder="Енгізу жолы" required />
                            </div>
                        </div>

                        <!-- Прикрепленные документы -->
                        <div class="mb-3">
                            <p class="mb-1">📎 <strong>Прикреплён:</strong></p>
                            <ul class="mb-0">
                                <li>Паспорт проекта</li>
                                <li v-if="file.file">Биография инвестора</li>
                                <li v-if="file.cert">Личные данные</li>
                                <li v-if="file.other">Другие документы</li>
                                <li>Список участников</li>
                            </ul>
                            <div class="d-flex flex-row justify-content-between mt-3 gap-3">
                                <div>
                                    <label for="file" class="form-label">Автобиография</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control form-control-sm" id="file" name="file" @change="handlefileUpload()" ref="file" />
                                    </div>
                                </div>

                                <div>
                                    <label for="cert" class="form-label">Личные данные</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control form-control-sm" id="cert" name="cert" @change="handlecertUpload()" ref="cert" />
                                    </div>
                                </div>

                                <div>
                                    <label for="other" class="form-label">Другие документы</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control form-control-sm" id="other" name="other" @change="handleotherUpload()" ref="other" />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Таблица участников -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ФИО - Должность</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Кому: {{application.userfio ? application.userfio + ' - ' + application.userjob : '...'}}</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Куратор: {{project.curator ? project.curator.fio + ' - ' + project.curator.job_title  : '...'}}</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Инвестор: {{application.investor ? application.investor : '...'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            📄 СФОРМИРОВАТЬ ПАКЕТ
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Модальное окно Добавить этап к проекту -->
    <div class="modal fade" id="etapModal" tabindex="-1" aria-labelledby="etapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="etapModalLabel">
                        <i class="fas fa-paper-plane"></i> Добавить этап к проекту
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" @submit.prevent="etapStore">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Тақырып (модуль) атауы</label>
                            <input type="text" class="form-control" v-model="newetap.name" name="name" placeholder="Тақырып" required />
                        </div>
                        <div class="form-group">
                            <label for="">Басталу күні</label>
                            <input type="date" class="form-control" v-model="newetap.date" name="date" placeholder="Тақырып" required />
                        </div>
                        <div class="form-group">
                            <label for="">Жауапкер тағайындау</label>
                            <input v-model="newetap.user_id" style="opacity: 0; user-select: none;" required />
                            <div v-if="newetap.user_id" class="form-control bg-light border-success d-flex justify-content-between">
                                <div>{{newetap.userfio}} - {{newetap.userjob}}</div>
                                <a href="#" class="text-danger" @click="clearFilterUser(3)">Өшіру</a>
                            </div>
                            <searchUser v-else @selectUser="action" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Сохранить
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Модальное окно Добавить вопрос к проекту -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="questionModalLabel">
                        <i class="fas fa-paper-plane"></i> Добавить вопрос к проекту
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" @submit.prevent="questionStore">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Сұрақ тақырыбы</label>
                            <input type="text" class="form-control" v-model="question.title" name="title" placeholder="Тақырып" required />
                        </div>
                        <div class="form-group">
                            <label for="">Сұрақ тексті</label>
                            <input type="text" class="form-control" v-model="question.question" name="question" placeholder="Тақырып" required />
                        </div>
                        <div class="form-group">
                            <label for="">Мерзім</label>
                            <input type="date" class="form-control" v-model="question.limit_date" name="limit_date" placeholder="Тақырып" required />
                        </div>
                        <div class="form-group">
                            <label for="">Жауапкер тағайындау</label>
                            <input v-model="question.user_id" style="opacity: 0; user-select: none;" required />
                            <div v-if="question.user_id" class="form-control bg-light border-success d-flex justify-content-between">
                                <div>{{question.userfio}} - {{question.userjob}}</div>
                                <a href="#" class="text-danger" @click="clearFilterUser(4)">Өшіру</a>
                            </div>
                            <searchUser v-else @selectUser="actionttt" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Сохранить
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card border-0 shadow-lg rounded-4 ">
            <div class="card-header bg-primary text-white rounded-top-4 py-3 px-4">
                <h3 class="fw-bold mb-0"><i class="fas fa-briefcase me-2"></i>Жоба № {{project.id}} - {{ project.name }}</h3>
            </div>

            <div class="card-body px-4 py-4">
                <div class="row">
                    <div class="col-md-4">
                        <Cstcarousel :slides="project.images"></Cstcarousel>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-1">
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-globe me-2 text-primary"></i>Страна
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.country }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-city me-2 text-primary"></i>Аудан
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.audan.name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-project-diagram me-2 text-primary"></i>Тип проекта
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.project_type.name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-dollar-sign me-2 text-primary"></i>Инвест. сумма
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.invest_sum.toLocaleString() }} ₸</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-users me-2 text-primary"></i>Кол-во людей
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.people_count }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-white shadow-sm h-100">
                                    <h6 class="text-secondary mb-2">
                                        <i class="fas fa-user-tie me-2 text-primary"></i>Куратор
                                    </h6>
                                    <p class="mb-0 fw-semibold fs-6">{{ project.curator?.fio }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- PDF-документ -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="text-muted"><i class="fas fa-file-pdf me-1"></i>Документы проекта</h6>
                    <div class="d-flex align-items-center">
                        <a :href="'/storage/files/' + project.file" target="_blank" class="btn btn-outline-danger">
                            <i class="fas fa-download me-1"></i> Скачать Документ
                        </a>
                        <a :href="route('createPasport', project.id)" class="btn btn-outline-success ml-2">
                            <i class="fas fa-download me-1"></i> Создать паспорт проекта
                        </a>
                        <!-- Кнопка -->
                        <button type="button" class="btn btn-outline-primary ml-2" data-bs-toggle="modal" data-bs-target="#requestModal">
                            <i class="fas fa-plus me-1"></i> Оставить заявку
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <!-- Карточка с этапами -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-success bg-opacity-75 text-white rounded-top-4 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Этапы проекта</h5>
                    <button type="button" class="btn text-white ml-2" data-bs-toggle="modal" data-bs-target="#etapModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body px-4 py-4">
                <ul class="list-group list-group-flush">
                    <li v-for="stage in project.stages" class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span v-if="stage.colorStatus.status == 1" class="me-2">🟢</span>
                            <span v-if="stage.colorStatus.status == 2" class="me-2">🟡</span>
                            <span v-if="stage.colorStatus.status == 3" class="me-2">🔴</span>
                            <div>
                                <strong>{{stage.name}}:</strong> <br>{{stage.textDate}} | {{stage.user.fio}} - {{stage.user.job_title}}
                                <span v-if="stage.status != 0" class="text-success ms-1">✅ {{stage.comment}}</span>
                            </div>
                        </div>
                        <button v-if="stage.status == 0" class="btn btn-success btn-sm" title="Отметить как выполнено" @click="confirmStage(stage.id)">
                            <i class="fas fa-check"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <!-- Карточка вопросов -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark bg-opacity-75 text-white rounded-top-4 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Вопросы инвесторов</h5>
                    <button type="button" class="btn text-white ml-2" data-bs-toggle="modal" data-bs-target="#questionModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body px-4 py-4">
                <!-- Вопрос 1 -->
                <div v-for="(qs, index) in project.questions" class="mb-4 p-3 border-start border-4 border-dark bg-light rounded">
                    <div :id="'question-' + qs.id">
                        <h6 class="mb-2">🔽 <strong>Вопрос {{index + 1}}:</strong></h6>
                        <p class="mb-1"><strong>Суть:</strong> {{qs.title}}</p>
                        <p class="mb-1"><strong>Текст вопроса:</strong> {{qs.question}}</p>
                        <p class="mb-1"><strong>Ответственный:</strong> {{ qs.user.fio }}</p>
                        <p class="mb-1"><strong>Срок:</strong> {{qs.limit_date}}</p>
                        <p v-if="qs.answer" class="mb-1"><strong>Ответ:</strong> {{qs.answer}} <span class="text-success">✅</span></p>
                        <p v-if="qs.answer" class="mb-0"><strong>Дата ответа:</strong> {{qs.answer_date}} | <strong>Исполнитель:</strong> {{qs.answer_user.name}}</p>
                    </div>
                </div>

                <!-- Кнопка: Новый вопрос -->
                <!-- <div class="text-end">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-plus-circle me-1"></i> Новый вопрос
                        </button>
                    </div> -->
            </div>
        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import Cstcarousel from "../../../Components/Cstcarousel.vue";
import searchUser from "../../../Components/forms/searchUser.vue";
import {
    nextTick
} from 'vue'
export default {
    components: {
        AdminLayout,
        searchUser,
        Cstcarousel,
    },
    props: ["project", "audans", "user"],
    data() {
        return {
            loading: 0,
            newetap: {
                name: null,
                date: null,
                user_id: null,
                userfio: null,
                userjob: null,
                status: null,
            },
            application: {
                goal: null,
                user_id: null,
                userfio: null,
                userjob: null,
                investor: null,
            },
            file: {
                file: null,
                cert: null,
                other: null,
            },
            question: {
                project_id: this.project.id,
                title: null,
                question: null,
                limit_date: null,
                project_name: this.project.name,
            },
            filter: {
                fio: ''
            },
            users: []

        };
    },
    methods: {
        handlefileUpload() {
            this.file.file = this.$refs.file.files[0];
        },
        handlecertUpload() {
            this.file.cert = this.$refs.cert.files[0];
        },
        handleotherUpload() {
            this.file.other = this.$refs.other.files[0];
        },

        confirmStage(id) {
            Swal.fire({
                title: "Тапсырманың орындалғанын растаңыз!",
                text: "Кураторға мәтін қалдыра аласыз!",
                icon: "info",
                input: "text",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Иә, жоямын!",
                cancelButtonText: "Жоқ",
                showLoaderOnConfirm: true,
                preConfirm: async (value) => {
                    try {
                        const response = await this.$inertia.post(route('admin.stageConfirm', id), {
                            value: value,
                            status: 1 // или другое логическое значение, если нужно
                        }, {
                            preserveState: true,
                            preserveScroll: true,
                            onSuccess: () => {
                                console.log("The stage has been confirmed");
                            },
                            onError: (errors) => {
                                Swal.showValidationMessage(`Қате: ${JSON.stringify(errors)}`);
                            }
                        });

                        return response;
                    } catch (error) {
                        Swal.showValidationMessage(`Сұраныс орындалмады: ${error}`);
                    }
                }
            });
        },
        applicationStore() {
            this.$inertia.post(
                route("admin.applicationStore"), {
                    name: this.application.goal,
                    project_id: this.project.id,
                    send_from: this.project.curator_id,
                    send_to: this.application.user_id,
                    investor: this.application.investor,
                    userjob: this.application.userjob,
                    userfio: this.application.userfio,
                    user_id: this.user.id,
                    autobiography_link: this.file.file,
                    certificate_link: this.file.cert,
                    other_link: this.file.other,
                }, {
                    onSuccess: () => {
                        console.log("The new contact has been saved")
                    }
                }
            );
        },
        etapStore() {
            this.$inertia.post(
                route("admin.projects.stagestore", this.project.id),
                this.newetap, {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        console.log("The new contact has been saved")
                    }
                }
            );
        },
        questionStore() {
            this.$inertia.post(
                route("admin.questions.store"),
                this.question, {
                    preserveScroll: true,
                    preserveState: true,
                    onError: () => console.log("An error has occurred"),
                    onSuccess: () =>
                        console.log("The new contact has been saved"),
                }
            );

        },
        clearFilterUser(e) {
            switch (e) {
                case 1: {
                    this.application.user_id = null
                    this.application.userfio = null
                    this.application.userjob = null
                    break;
                }
                case 3: {
                    this.newetap.user_id = null
                    this.newetap.userfio = null
                    this.newetap.userjob = null
                }
                case 4: {
                    this.question.user_id = null
                    this.question.userfio = null
                    this.question.userjob = null
                }
            }
            this.filter.fio = ''
        },
        action(user) {
            this.newetap.user_id = user.id
            this.newetap.userfio = user.fio
            this.newetap.userjob = user.job_title
        },
        actionapp(user) {
            this.application.user_id = user.id
            this.application.userfio = user.fio
            this.application.userjob = user.job_title
        },
        actionttt(user) {
            this.question.user_id = user.id
            this.question.userfio = user.fio
            this.question.userjob = user.job_title
        },
    },
}
</script>
