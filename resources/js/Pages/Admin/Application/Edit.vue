<template>

    <head>
        <title>Админ панель | Сқранысты көру</title>
    </head>
    <AdminLayout>
        <template #breadcrumbs>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-user-shield text-primary me-2"></i> Сұранысты көру
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
                            <a :href="route('admin.applications.index')">
                                <i class="fas fa-dashboard"></i>
                                Сқраныс тізімі
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Сұранысты көру
                        </li>
                    </ol>
                </div>
            </div>
        </template>
        <div class="container-fluid">
            <div class="card card-primary">
                <form method="post" @submit.prevent="submit">
                    <div class="card-body">
                        <Cstcarousel :slides="application['project'].images"></Cstcarousel>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">{{ application['project']['name'] }}</h5>
                            </div>
                            <div class="card-body">
                               <p class="card-text">
                                    <i class="fas fa-user-tie"></i> Кому: {{ application['send_user']['fio'] }} - {{application['send_user']['job_title']}}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-user"></i> Инвестор: {{ application['investor'] }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt"></i> Күні: {{ application['date'] }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-clock"></i> Уақыты: {{ application['time'] }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-info-circle"></i> Жоба сипаттамасы: {{ application['project']['description'] }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-globe"></i> Ел: {{ application['project']['country'] }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-dollar-sign"></i> Инвестиция сомасы: {{ application['project']['invest_sum'] }}
                                </p>
                                
                                <p class="card-text">
                                    <i class="fas fa-user-tie"></i> Куратор: {{ application['project']['curator'] }}
                                </p>
                                
                                <p class="card-text">
                                    <i class="fas fa-phone-alt"></i> Телефон: {{ application['project']['phone'] }}
                                </p>
                            </div>

                            <ul class="list-group list-group-flush d-flex flex-column flex-sm-row flex-wrap">
                                <li class="list-group-item flex-fill mb-2 mb-sm-0">
                                    <a :href="application.document_link" class="btn btn-primary w-100" download>Жоба паспорты</a>
                                </li>
                                <li class="list-group-item flex-fill mb-2 mb-sm-0">
                                    <a :href="'/storage/files/'+application.file_link" class="btn btn-primary w-100" download>Жоба документі</a>
                                </li>
                                <li v-if="application.autobiography_link" class="list-group-item flex-fill mb-2 mb-sm-0">
                                    <a :href="'/storage/files/'+application.autobiography_link" class="btn btn-primary w-100" download>Инвестор Автобиографиясы</a>
                                </li>
                                <li v-if="application.certificate_link" class="list-group-item flex-fill mb-2 mb-sm-0">
                                    <a :href="'/storage/files/'+application.certificate_link" class="btn btn-primary w-100" download>Жеке доументі</a>
                                </li>
                                <li v-if="application.other_link" class="list-group-item flex-fill mb-2 mb-sm-0">
                                    <a :href="'/storage/files/'+application.other_link" class="btn btn-primary w-100" download>Өзге құжат</a>
                                </li>
                            </ul>

                            <div class="card-footer">
                                <small class="text-muted">Кому: {{ application['send_user']['fio'] }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="">Әрекет</label>
                    </div>
                    <div class="form-group">
                        <select
                            class="form-control"
                            @change.prevent="search"
                            v-model="application.status"
                            placeholder="Белсенді"
                        >
                            <option :value="0">
                                Қаралмады
                            </option>
                            <option :value="1">
                                Қабылданбады
                            </option>
                            <option :value="2">
                                Қабылданды
                            </option>
                            
                        </select>
                        
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
    import Cstcarousel from "../../../Components/Cstcarousel.vue";

    export default {
        components: {
            AdminLayout,
            Link,
            Pagination,
            ValidationError,
            Cstcarousel,
            Head
        },
        props: ["application"],
        methods: {
            submit() {
                this.$inertia.put(
                    route("admin.applications.update", this.application.id),
                    this.application, {
                        onError: () => console.log("An error has occurred"),
                        onSuccess: () =>
                            console.log("The new contact has been saved"),
                    }
                );
            },
        },
    };

</script>
