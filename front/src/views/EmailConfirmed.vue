<template>
  <v-container
      class="main mb-6"
  >
    <v-card
        class="mx-auto"
        max-width="344"
        :title="mainMessage"
        :subtitle="subtitle"
        append-icon="mdi-check"
    >
      <v-card-text v-if="message">{{ message }}</v-card-text>
      <template v-slot:append>
        <v-icon icon="mdi-check" color="success"></v-icon>
      </template>
    </v-card>
  </v-container>
</template>

<script setup>
import { useRoute } from 'vue-router';
import {ref, onBeforeMount, onMounted} from 'vue'
import axios from 'axios'

const route = useRoute();
const queryParams = route.query;
const mainMessage = ref('')
const subtitle = ref('')
const message = ref('')

console.log(queryParams)

onBeforeMount(async () => {
  const params = {
    expires: queryParams.expires,
    signature: queryParams.signature,
  }
  try {
    await axios.get('http://localhost/api/email/verify/' + queryParams.id + '/' + queryParams.token, { params })
    console.log('success')
    mainMessage.value = 'Success'
    subtitle.value = 'You confirmed your email successfully'
    message.value = 'Now you can add your first offer and wait for a new promotion'
  } catch (error) {
    mainMessage.value = 'Fail'
    subtitle.value = 'Whoops, something went wrong'
  }
})
</script>

<style>
@media (min-width: 1024px) {
  .main {
    width: 100%
  }
}
</style>
