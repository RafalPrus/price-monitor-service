<template>
  <div v-if="offers" v-for="offer in offers" :key="offer.id">
    <BaseOfferCard :offer="offer" />
  </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/useAuth'
import axios from 'axios'
import BaseOfferCard from '../components/Offer/BaseOfferCard.vue'
import { formatDate } from "@/services/DateService.js"

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
  