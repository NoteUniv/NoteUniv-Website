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
      <ArrowRightIcon v-if="state === 'idle'" size="1.5x" class="text-black" />
      <LoaderIcon v-if="state === 'loading'" size="1.5x" class="text-black" />
      <XIcon v-if="state === 'error'" size="1.5x" class="text-black" />
      <CheckCircleIcon
        v-if="state === 'success'"
        size="1.5x"
        class="text-black"
      />
    </div>
  </div>
</template>

<script>
import {
  ArrowRightIcon,
  LoaderIcon,
  XIcon,
  CheckCircleIcon
} from 'vue-feather-icons'

export default {
  components: {
    ArrowRightIcon,
    LoaderIcon,
    XIcon,
    CheckCircleIcon
  },
  data() {
    return {
      studentId: '',
      state: 'idle'
    }
  },
  methods: {
    login() {
      this.state = 'loading'
      fetch('/')
        .then(() => {
          this.state = 'success'
        })
        .catch(() => {
          this.state = 'error'
        })
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
