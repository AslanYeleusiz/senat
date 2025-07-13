<template>
    <div v-if="slides.length" class="slider" @mousedown="onDragStart" @mousemove="onDragMove" @mouseup="onDragEnd" @mouseleave="onDragEnd" @touchstart="onDragStart" @touchmove="onDragMove" @touchend="onDragEnd">
        <div class="slider-track" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
            <div v-for="(slide) in slides" class="slide">
                <!-- Показываем только если активен или уже был показан -->
                <div class="slider-block d-block w-100" :style="{backgroundImage: `url(${route('index')}/storage/images/${slide})`}"></div>
            </div>
        </div>
        <!-- Navigation buttons -->
        <button @click.prevent="prevSlide" class="btn slider-button left">
            <img src="../../../public/images/arrow.svg" alt="">
        </button>
        <button @click.prevent="nextSlide" class="btn slider-button right">
            <img src="../../../public/images/arrow.svg" alt="">
        </button>
        <div class="slider-indicators">
            <span v-for="(slide, index) in slides" :key="index" :class="['dot', { active: currentIndex === index }]" @click="goToSlide(index)" />
        </div>
    </div>
    <div v-else></div>
</template>

<script>
    export default {
        props: ['slides'],
        data() {
            return {
                currentIndex: 0,
                autoScrollInterval: null,
                startX: 0,
                isDragging: false,
                dragDelta: 0,
            }
        },
        beforeDestroy() {
            clearInterval(this.autoScrollInterval)
        },
        mounted(){
            this.startAutoScroll()
        },
        methods: {
            nextSlide() {
                this.currentIndex = (this.currentIndex + 1) % this.slides.length
                this.resetAutoScroll()
            },
            prevSlide() {
                this.currentIndex =
                    (this.currentIndex - 1 + this.slides.length) % this.slides.length
                this.resetAutoScroll()
            },
            startAutoScroll() {
                this.autoScrollInterval = setInterval(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.slides.length
                }, 3000)
            },
            resetAutoScroll() {
                clearInterval(this.autoScrollInterval)
                this.startAutoScroll()
            },
            goToSlide(index) {
                this.currentIndex = index
                this.resetAutoScroll()
            },
            // 👇 Drag methods
            onDragStart(event) {
                this.isDragging = true;
                this.startX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
            },
            onDragMove(event) {
                if (!this.isDragging) return;
                const currentX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
                this.dragDelta = currentX - this.startX;
            },
            onDragEnd() {
                if (!this.isDragging) return;
                this.isDragging = false;

                if (this.dragDelta > 50) {
                    this.prevSlide();
                } else if (this.dragDelta < -50) {
                    this.nextSlide();
                }
                this.dragDelta = 0;
            },
        },
    }

</script>

<style scoped>
    .slider {
        position: relative;
        width: 100%;
        max-width: 1440px;
        height: 300px;
        margin: auto;
        overflow: hidden;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        user-select: none;
    }

    .slider-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        height: 100%;
    }
    
    .slider-block {
        height: 500px;
        background-size: cover;
        background-position: 50% 50%;
    }

    .slide {
        min-width: 100%;
        height: 100%;
        background-color: #5E29B9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }

    .slider-button {
        position: absolute;
        top: 50%;
        height: 74px;
        width: 54px;
        transform: translateY(-50%);
        background: #FFFFFFB2;
        font-size: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 0 20px 20px 0;
        user-select: none;
        z-index: 1;
        transition: background 0.2s;
    }
    
    .slider .slider-button img{
        transition: 0.2s;
    }
    .slider-button:hover {
        background: #f0f0f0;
    }
    
    .slider-button.left {
        left: 0px;
    }

    .slider-button.right {
        right: 0px;
        transform: translateY(-50%) scaleX(-1);
    }

    .slider-indicators {
        position: absolute;
        bottom: 15px;
        width: 100%;
        text-align: center;
    }

    .dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        margin: 0 5px;
        background-color:  !transparent;
        border: 1px solid #AABDE1;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .dot.active {
        border: 1px solid #1E63E9;
        background-color: #1E63E9;
        width: 30px;
    }

</style>
