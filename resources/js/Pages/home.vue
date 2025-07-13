<template>
    <layout>
        <section class="main-section position-relative overflow-hidden">
            <!-- Фоновое видео -->
            <video class="video-bg position-absolute top-0 start-0 w-100 h-100 object-fit-cover" autoplay muted loop playsinline>
                <source src="https://turkistaninvest.com//wp-content//uploads//2024//06//fin-sait-2.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            
            <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title" id="requestModalLabel">
                            <i class="bi bi-send"></i> Оставить заявку
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Цель -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" v-model="apps.name" class="custom-control-input" id="customSwitch1" true-value=1 false-value=0 />
                                <label class="custom-control-label" for="customSwitch1">Email расталған</label>
                            </div>
                            <validation-error :field="'name'" />
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">
                            📄 СФОРМИРОВАТЬ ПАКЕТ
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- Затемнение -->
            <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>

            <!-- Контейнер всего содержимого, включая шапку -->
            <div class="position-relative z-1" style="min-height: 100vh;">
                <Header />

                <!-- Центрированный контент -->
                <div class="container d-flex align-items-center justify-content-center text-white" style="min-height: calc(100vh - 80px);">
                    <div class="container d-flex align-items-center justify-content-center text-white" style="min-height: calc(100vh - 80px);">
                        <div class="row w-100 align-items-center text-center text-md-start">

                            <!-- Левая часть -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h1 class="display-4 fw-bold">ИНВЕСТИРУЙТЕ В ТУРКЕСТАНСКУЮ ОБЛАСТЬ</h1>
                                <button type="button" class="btn btn-warning btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#requestModal">
                                    Оставить заявку
                                </button>
                            </div>

                            <!-- Центр: большая стрелка -->
                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                <i class="fas fa-arrow-right fa-4x text-white"></i>
                            </div>

                            <!-- Правая часть: факты в виде кнопок -->
                            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center text-end gap-3">
                                <a href="#" class="custom-pill-btn d-flex align-items-center gap-2">
                                    <i class="fas fa-users fa-lg text-primary"></i>
                                    <div class="text-start">
                                        <div class="fw-bold mb-0">№1</div>
                                        <small>В республике по численности населения</small>
                                    </div>
                                </a>

                                <a href="#" class="custom-pill-btn d-flex align-items-center gap-2">
                                    <i class="fas fa-apple-alt fa-lg text-primary"></i>
                                    <div class="text-start">
                                        <div class="fw-bold mb-0">№1</div>
                                        <small>Безопасность пищевых продуктов в странах СНГ</small>
                                    </div>
                                </a>

                                <a href="#" class="custom-pill-btn d-flex align-items-center gap-2">
                                    <i class="fas fa-industry fa-lg text-primary"></i>
                                    <div class="text-start">
                                        <div class="fw-bold mb-0">№1</div>
                                        <small>В мире по добыче урана</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>
    </layout>
</template>



<script>
    import layout from '@/Layouts/layout.vue'
    import Header from '@/Components/Home/Header.vue'

    export default {
        data() {
            return {
                apps: {
                    name: null
                }
            }
        },
        
        components: {
            layout,
            Header,
        },
    }

</script>

<style scoped>
    .video-bg {
        object-fit: cover;
        z-index: 0;
    }

    .overlay {
        background: rgba(0, 0, 0, 0.5);
        /* Затемнение видео */
        z-index: 1;
    }

    .custom-pill-btn {
        width: 100%;
        max-width: 380px;
        background-color: rgba(255, 255, 255, 0.6);
        /* Прозрачный белый фон */
        color: #000;
        border: 2px solid #0d6efd;
        border-radius: 50px;
        padding: 1.5rem 2rem;
        /* Больше отступов */
        text-decoration: none;
        transition: all 0.3s ease;
        min-height: 130px;
        /* Увеличенный размер */
        font-size: 1.1rem;
    }

    .custom-pill-btn:hover {
        background-color: rgba(255, 255, 255, 0.8);
        color: #0d6efd;
        border-color: #0d6efd;
        text-decoration: none;
    }

</style>
