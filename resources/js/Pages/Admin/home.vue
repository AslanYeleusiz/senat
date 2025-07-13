<template>
    <AdminLayout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{project_count}}</h3>

                            <p>Жоба саны</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{totalInvest.toLocaleString()}} ₸</h3>

                            <p>Инвестиция суммасы</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{investor_count}}</h3>

                            <p>Инвестор саны</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{project_end_count}}</h3>

                            <p>Орындалған жобалар саны</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row g-4 mb-4">
                <!-- Круговая диаграмма -->
                <div class="col-md-4">
                    <div class="card shadow rounded-4 h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📊 Статус проектов</h5>
                        </div>
                        <div class="card-body">
                            <Pie :data="pieData" :options="options" />
                        </div>
                    </div>
                </div>
                <div class="col-md-8 overflow-hidden">
                    <div class="card shadow rounded-4 h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📊 Карта Облости</h5>
                        </div>
                        <div class="card-body">
                            <cstleaflet :markers="audans" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Гистограмма -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Аудандар рейтингі</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th @click="sortBy('name')" style="cursor:pointer;">
                                                Аудандар
                                                <span v-if="sortKey === 'name'"> {{ sortDirection === 'asc' ? '↑' : '↓' }} </span>
                                            </th>
                                            <th @click="sortBy('invest_sum')" style="cursor:pointer;">
                                                Инвестиция
                                                <span v-if="sortKey === 'invest_sum'"> {{ sortDirection === 'asc' ? '↑' : '↓' }} </span>
                                            </th>
                                            <th @click="sortBy('percent_invest')" style="cursor:pointer;">
                                                % Жалпы жоба мөлшері
                                                <span v-if="sortKey === 'percent_invest'"> {{ sortDirection === 'asc' ? '↑' : '↓' }} </span>
                                            </th>
                                            <th @click="sortBy('kpd')" style="cursor:pointer;">
                                                ЖҰМЫС-КПД
                                                <span v-if="sortKey === 'kpd'"> {{ sortDirection === 'asc' ? '↑' : '↓' }} </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in sortedAudans" :key="index">
                                            <td>{{ item.name }}</td>
                                            <td>{{ formatNumber(item.invest_sum) }} ₸</td>
                                            <td>{{ item.percent_invest }}%</td>
                                            <td>
                                                <div class="progress" style="height: 12px;">
                                                    <div class="progress-bar bg-info" role="progressbar" :style="{ width: item.kpd + '%' }" :aria-valuenow="item.percent_invest" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>


<script>
    import AdminLayout from "../../Layouts/AdminLayout.vue";
    import cstleaflet from "../../Components/cstleaflet.vue";
    export default {
        components: {
            AdminLayout,
            cstleaflet
        },
        props: ['audans', 'project_count', 'investor_count', 'project_end_count', 'totalInvest', 'active_project_count', 'freeze_project_count'],
        data() {
            return {
                sortKey: 'percent_invest',
      sortDirection: 'desc',
                pieData: {
                    labels: ['Успешные', 'Открытые', 'Замороженные'],
                    datasets: [{
                        backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                        data: [this.project_end_count, this.active_project_count, this.freeze_project_count],
                    }, ],
                }
            }
        },
        methods: {
            sortBy(key) {
                if (this.sortKey === key) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc'
                } else {
                    this.sortKey = key
                    this.sortDirection = 'asc'
                }
            },
            formatNumber(number) {
                return number ? number.toLocaleString() : 0
            }
        },
        computed: {
            sortedAudans() {
                return [...this.audans].sort((a, b) => {
                    const modifier = this.sortDirection === 'asc' ? 1 : -1
                    if (a[this.sortKey] < b[this.sortKey]) return -1 * modifier
                    if (a[this.sortKey] > b[this.sortKey]) return 1 * modifier
                    return 0
                })
            }
        },
    }

</script>

<script setup>
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        ArcElement,
        BarElement,
        CategoryScale,
        LinearScale,
    } from 'chart.js';
    import {
        Pie,
    } from 'vue-chartjs';

    // Регистрация компонентов Chart.js
    ChartJS.register(Title, Tooltip, Legend, ArcElement, BarElement, CategoryScale, LinearScale)




    const options = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
        },
    }

</script>
