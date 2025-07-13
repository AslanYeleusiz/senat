<template>
  <div>
    <!-- Прогресс успешных проектов -->
    <div class="progress">
      <div
        class="progress-bar bg-success"
        role="progressbar"
        :style="{ width: successfulProgress + '%' }"
        :aria-valuenow="successfulProgress"
        aria-valuemin="0"
        :aria-valuemax="maxValue"
      >{{successful}}</div>
    </div>

    <!-- Прогресс предупредительных проектов -->
    <div class="progress">
      <div
        class="progress-bar bg-warning"
        role="progressbar"
        :style="{ width: warningfulProgress + '%' }"
        :aria-valuenow="warningfulProgress"
        aria-valuemin="0"
        :aria-valuemax="maxValue"
      >{{warningful}}</div>
    </div>

    <!-- Прогресс неуспешных проектов -->
    <div class="progress">
      <div
        class="progress-bar bg-danger"
        role="progressbar"
        :style="{ width: unsuccessfulProgress + '%' }"
        :aria-valuenow="unsuccessfulProgress"
        aria-valuemin="0"
        :aria-valuemax="maxValue"
      >{{unsuccessful}}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    successful: {
      type: Number,
      required: true,
    },
    warningful: {
      type: Number,
      required: true,
    },
    unsuccessful: {
      type: Number,
      required: true,
    },
  },
  computed: {
    maxValue() {
      // Находим максимальное значение среди успешных, предупредительных и неуспешных проектов
      return Math.max(this.successful, this.warningful, this.unsuccessful);
    },
    successfulProgress() {
      // Рассчитываем процент для успешных проектов
      return this.maxValue ? (this.successful / this.maxValue) * 100 : 1;
    },
    warningfulProgress() {
      // Рассчитываем процент для предупредительных проектов
      return this.maxValue ? (this.warningful / this.maxValue) * 100 : 1;
    },
    unsuccessfulProgress() {
      // Рассчитываем процент для неуспешных проектов
      return this.maxValue ? (this.unsuccessful / this.maxValue) * 100 : 1;
    },
  },
};
</script>

<style scoped>
.progress {
  margin-bottom: 10px;
}
</style>
