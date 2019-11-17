<template>
  <div class="container">
    <h1>Note Univ</h1>
    <p>
      Votre nouvel espace pour retrouver vos notes plus facilement et savoir si
      vous passez votre semestre !
    </p>

    <TextInput></TextInput>

    <small>
      Ce service est proposé par Erosya pour tous les étudiants de l'IUT de
      Haguenau.
    </small>
  </div>
</template>

<script>
import TextInput from '@/components/TextInput'

export default {
  components: {
    TextInput
  }
}
</script>

<style>
.container {
  @apply min-h-screen flex flex-col justify-center items-center text-center mx-auto;
}
</style>
