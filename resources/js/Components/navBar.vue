<template>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a :href="route('admin.index')" class="nav-link">{{__('Басты бет')}}</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <ImageSelect v-model="locale" :options="options" />
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
</template>


<script>
    //import Header from '@/components/Header.vue'
    import ImageSelect from './ImageSelect.vue';
    export default {
        components: {
            //Header,
            ImageSelect,

        },
        data() {
            return {
                locale: window.APP_LOCALE ?? 'kk',
                options: [{
                        value: 'kk',
                        image: '/images/kk.png'
                    },
                    {
                        value: 'ru',
                        image: '/images/ru.png'
                    },
                    {
                        value: 'en',
                        image: '/images/en.png'
                    },
                ],
            }
        },
        methods: {
            async setLocale() {
                try {
                    await axios.post('/set-locale', {
                        locale: this.locale
                    });
                    location.reload(); // Обновим страницу, чтобы применить язык
                } catch (error) {
                    console.error('Error setting locale', error);
                }
            },
        },
        watch: {
            locale() {
                this.setLocale()
            }
        }

    }

</script>
