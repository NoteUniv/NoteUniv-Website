<template>
  <div @keyup.enter="login" class="flex">
    <input
      v-model="studentId"
      class="input rounded-l-full py-2 pl-5 border-gray-400 focus:outline-none"
      type="text"
      placeholder="Entrez votre numéro étudiant"
    />
    <div
      @click="login"
      class="px-3 rounded-r-full flex justify-center items-center bg-white cursor-pointer"
    >
      <ArrowRightIcon size="1.5x" class="text-black" />
    </div>
  </div>
</template>

<script>
import { ArrowRightIcon } from 'vue-feather-icons'

export default {
  components: {
    ArrowRightIcon
  },
  data() {
    return {
      studentId: ''
    }
  },
  methods: {
    login() {
      fetch('/')
        .then(() => console.log('yes'))
        .catch(() => console.log('no'))
    }
  }
}
</script>

<style>
.input {
  width: 380px;
  font-size: 25px;
  color: #110133;
}
</style>
