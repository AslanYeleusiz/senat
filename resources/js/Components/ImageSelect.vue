<template>
  <div class="relative w-64 p-2">
    <div @click="toggleDropdown" class="">
      <div class="">
        <img :src="selectedOption.image" alt="" class="" />
      </div>
    </div>

    <div v-if="isOpen" class="absolute z-10 w-full bg-white border mt-1 rounded-md shadow">
      <div
        v-for="option in options"
        :key="option.value"
        @click="selectOption(option)"
        class="p-1 items"
      >
        <img :src="option.image" alt="" class="w-6 h-6" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageSelect',
  props: {
    options: Array,
    modelValue: [String, Number, Object],
  },
  data() {
    return {
      isOpen: false,
    };
  },
  computed: {
    selectedOption() {
      return (
        this.options.find((opt) => opt.value === this.modelValue) ||
        this.options[0]
      );
    },
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen;
    },
    selectOption(option) {
      this.$emit('update:modelValue', option.value);
      this.isOpen = false;
    },
  },
};
</script>

<style scoped>
    img {
        width: 32px;
        height: 24px;
    }
    .absolute {
        position: absolute;
        right: 10px;
        z-index: 1;
    }
    .items {
        transition: 0.2s;
        border: 1px solid #fff;
    }
    .items:hover {
        cursor: pointer;
        background: #f1f1f1;
        border: 1px solid #808080;
    }
/* Optional: style improvements */
</style>
