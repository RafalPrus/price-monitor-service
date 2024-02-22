<script setup>
import TheWelcome from '../components/TheWelcome.vue'
</script>

<template>
  <main>
    <TheWelcome />
    <v-checkbox
        v-model="terms"
        v-bind="termsProps"
        label="Do you agree?"
        color="red"
      />
  </main>
</template>
