<template>
  <div class="d-flex align-center flex-column" v-if="offers" v-for="offer in offers" :key="offer.id">
    <div class="text-subtitle-2 mt-2">{{ offer.name }}</div>
    <v-card width="400">
      <template v-slot:title>
        {{ offer.name }}
      </template>

      <template v-slot:subtitle>
        {{ offer.domain }}
      </template>

      <template v-slot:text>
        {{ offer.created_at }}
      </template>
    </v-card>
  </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/useAuth'
import axios from 'axios'

const offers = ref(null)
const store = useAuthStore()
const { user } = store
onMounted(async () => {
    const res = await axios.get('http://localhost/api/offers')
    offers.value = res.data.data
    console.log(offers.value)
})
</script>
  
<style>
@media (min-width: 1024px) {
.main {
    width: 100%
}
}
</style>
  