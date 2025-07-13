<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-purple bg-body-tertiary shadow">
        <!-- Brand Logo -->
        <div class="sidebar-brand">
            <Link :href="route('admin.index')" class="brand-link">
            <img src="../../../public/images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle">
            <span class="korinna">Turkistan Invest</span>
            </Link>

        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <div :style="`background: url(${route('index') + '/storage/' + $page.props.user.avatar}); height: 34px; width: 34px; background-size: cover; background-position: center;`" class="img-circle elevation-2">
                    </div>


                </div>
                <div class="info">
                    <Link :href="route('admin.profile.index')" class="d-block">{{__('Жеке кабинет')}}</Link>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <template v-for="(menu_item, index) in menu_items" :key="'menu_item' + index">
                        <li v-if="menu_item.role.includes(currentUserRole)" class="nav-item">
                            <Link :href="route(menu_item.route_name)" class="nav-link position-relative" :class="{ active: menu_item.menu_active.includes(currentRoute) }">
                            <i class="nav-icon fas" :class="menu_item.font"></i>
                            <p>{{ menu_item.name }}</p>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" v-if="menu_item.name == __('Хабарламалар')">
                                {{ ntfs < 100 ? ntfs : ntfs + '+' }}
                            </span>
                            </Link>
                        </li>
                    </template>


                    <li class="nav-item mt-4">
                        <a class="nav-link" :href="route('admin.logout')">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Шығу</p>
                        </a>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
    import {
        Link
    } from "@inertiajs/inertia-vue3";

    export default {
        components: {
            Link,
        },
        data() {
            return {
                menu_items: [{
                        name: this.__('Басты бет'),
                        font: "fa-home",
                        route_name: "admin.home.index",
                        menu_active: ["admin.home"],
                        role: [1, 2, 3, 4, 5, 6],
                    },
                    {
                        name: this.__('Рөлдер тізімі'),
                        font: "fa-user-shield",
                        route_name: "admin.roles.index",
                        menu_active: ["admin.roles"],
                        role: [1, 2],
                    },

                    {
                        name: this.__('Аудандар тізімі'),
                        font: "fa-map-marker-alt",
                        route_name: "admin.audans.index",
                        menu_active: ["admin.audans"],
                        role: [1, 2, 4, 5],
                    },
                    {
                        name: this.__('Жоба түрлері'),
                        font: "fa-bullhorn",
                        route_name: "admin.projectTypes.index",
                        menu_active: ["admin.projectTypes"],
                        role: [1, 2],
                    },

                    {
                        name: this.__('Қолданушылар тізімі'),
                        font: "fa-users",
                        route_name: "admin.users.index",
                        menu_active: ["admin.users"],
                        role: [1, 2, 5],
                    },
                    {
                        name: this.__('Жобалар'),
                        font: "fa-briefcase",
                        route_name: "admin.projects.index",
                        menu_active: ["admin.projects"],
                        role: [1, 2, 3, 4, 6],
                    },
                    {
                        name: this.__('Форум'),
                        font: "fa-comment",
                        route_name: "admin.forums.index",
                        menu_active: ["admin.forums"],
                        role: [1, 2, 3, 4, 5, 6, 7],
                    },
                    {
                        name: this.__('Хабарламалар'),
                        font: "fa-comments",
                        route_name: "admin.notifications.index",
                        menu_active: ["admin.notifications"],
                        role: [1, 2, 3, 4, 5, 6, 7],
                    },
                    {
                        name: this.__('Сұраныстар'),
                        font: "fa-list",
                        route_name: "admin.applications.index",
                        menu_active: ["admin.applications"],
                        role: [1, 7],
                    },
                ],
                ntfs: null,

            }
        },

        created() {
            window.Echo.channel('take_notification_count_' + this.$page.props.user.id)
                .listen('.take_notification_count', res => {
                    this.ntfs = res.ntfs_count_from_event
                })
        },

        mounted() {
            $('[data-widget="treeview"]').each(function() {
                adminlte.Treeview._jQueryInterface.call($(this), "init");
            });
            this.loadNtfs();
        },
        methods: {
            async loadNtfs() {
                const response = await axios.get('/admin/get/ntfs-count');
                this.ntfs = response.data.ntfs_count ? response.data.ntfs_count : null;
            },
        },
        computed: {
            currentRoute() {
                let currentRoute = route().current().split(".");
                currentRoute.pop();
                return currentRoute.join(".");
            },
            currentUserRole() {
                return this.$page.props.user.role_id;
            }
        },

    }

</script>

<style scoped>
    .ntfs {
        margin-left: 5px;
        border-radius: 50%;
        padding: 2px 7px;
    }

</style>
