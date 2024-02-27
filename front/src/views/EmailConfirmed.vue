<template>
  <v-container
      class="main mb-6"
  >
    Hello world
  </v-container>
</template>

<script setup>
import { useRoute } from 'vue-router';
import {ref, onBeforeMount, onMounted} from 'vue'
import axios from 'axios'

const route = useRoute();
const queryParams = route.query;
console.log(queryParams)

onBeforeMount(async () => {
  const params = {
    expires: queryParams.expires,
    signature: queryParams.signature,
  }
  try {
    axios.get('http://localhost/api/email/verify/' + queryParams.id + '/' + queryParams.token, { params })
    console.log('success')
  } catch (er) {
    console.log('errror')
    console.log(er)
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
