<template>
    <div>
        <input v-model="filter.fio" class="form-control" placeholder="Іздеу" @input="search" />

        <!-- Таблица пользователей -->
        <table class="table mt-1">
            <tbody>
                <tr v-for="(user, index) in filteredUsers" :key="user.id">
                    <td>{{ index+1 }}</td>
                    <td class="col">{{ user.fio }} - {{user.job_title}}</td>
                    <td>
                        <button v-if="close" class="btn btn-sm btn-success" @click="$emit('selectUser', user)" data-bs-dismiss="modal">Тандау</button>
                        <button v-else class="btn btn-sm btn-success" @click="$emit('selectUser', user)">Тандау</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<script>
    //import Header from '@/components/Header.vue'
    export default {
        props: ['close'],
        data() {
            return {
                filter: {
                    fio: ''
                },
                users: []
            }
        },
        computed: {
            filteredUsers() {
                const search = this.filter.fio.toLowerCase();
                return this.users.filter(user =>
                    user.fio.toLowerCase().includes(search) ||
                    user.job_title.toLowerCase().includes(search)
                );
            }
        },
        methods: {
            search() {
                axios.get(route('admin.userSearch'), {
                        params: {
                            fio: this.filter.fio
                        }
                    })
                    .then(response => {
                        // Если ответ успешен, обновляем filteredUsers
                        this.users = response.data.users
                    })
                    .catch(error => {
                        console.error('Error during search:', error);
                    });
            },
            action(user) {
                console.log('Действие для пользователя:', user);
            }
        },
        mounted(){
            this.search()
        }
    }

</script>

<style lang="scss" scoped>

</style>
