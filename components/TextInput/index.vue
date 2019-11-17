<template>
  <div>
    <div @keyup.enter="login" class="flex">
      <input
        v-model="studentId"
        :class="
          `input rounded-l-full py-2 pl-5 border-gray-400 focus:outline-none bg-${state}`
        "
        type="text"
        placeholder="Entrez votre numéro étudiant"
      />
      <div
        @click="login"
        :class="
          `px-3 rounded-r-full flex justify-center items-center bg-white cursor-pointer bg-${state}`
        "
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
          <CheckIcon
            key="success"
            v-if="state === 'success'"
            size="1.5x"
            class="text-black"
          />
        </transition>
      </div>
    </div>

    <p
      v-if="state === 'error' || state === 'success'"
      :class="`flex items-center mt-2 ${state}`"
    >
      <CheckIcon
        v-if="state === 'success'"
        :class="`text-${state}`"
        size="1x"
      />
      <XIcon v-if="state === 'error'" :class="`text-${state}`" size="1x" />
      <span :class="`ml-2 text-${state}`">{{ messages[state] }}</span>
    </p>
  </div>
</template>

<script>
import { ArrowRightIcon, LoaderIcon, XIcon, CheckIcon } from 'vue-feather-icons'

export default {
  components: {
    ArrowRightIcon,
    LoaderIcon,
    XIcon,
    CheckIcon
  },
  data() {
    return {
      studentId: '',
      state: 'idle',
      messages: {
        error: "Ce numéro n'existe pas",
        success: 'Ce numéro existe'
      }
    }
  },
  watch: {
    studentId(value) {
      if (value === '') {
        this.state = 'idle'
      }
    }
  },
  methods: {
    async login() {
      if (this.state === 'loading') return
      this.state = 'loading'

      try {
        const response = await fetch(
          'https://jsonplaceholder.typicode.com/users/1'
        )
        const data = await response.json()
        this.$store.commit('setUsername', data.id)
        this.state = 'success'

        this.$router.push('/marks')
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
