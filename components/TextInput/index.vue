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
      <transition name="fade" mode="out-in">
        <ArrowRightIcon
          key="idle"
          v-if="state === 'idle'"
          size="1.5x"
          class="text-black"
        />
        <LoaderIcon
          key="loading"
          v-if="state === 'loading'"
          size="1.5x"
          class="text-black"
        />
        <XIcon
          key="error"
          v-if="state === 'error'"
          size="1.5x"
          class="text-black"
        />
        <CheckCircleIcon
          key="success"
          v-if="state === 'success'"
          size="1.5x"
          class="text-black"
        />
      </transition>
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
    async login() {
      if (this.state === 'loading') return
      this.state = 'loading'

      try {
        const response = await fetch('/')

        console.log(response)
        this.state = 'success'
      } catch (error) {
        this.state = 'error'
      }
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
