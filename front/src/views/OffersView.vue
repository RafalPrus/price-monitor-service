<template>
    <div class="d-flex align-center flex-column">
        <v-btn
            :color="buttonFormColor"
            class="mb-2"
            @click="triggerForm"
        >
            {{ buttonFormText }}
        </v-btn>
        <BaseOfferForm
            v-if="!hiddenForm"
            class="mb-2"
        />
        <v-container class="d-flex align-center flex-row">
            <v-select
                v-model="sortBy"
                class="mr-6"
                label="Sort by"
                :items="['Created at', 'Name', 'Domain', 'Actual Price']"
            ></v-select>
            <v-select
                v-model="domainFilter"
                label="Domain"
                :items="['wrangler', 'allegro']"
                chips
                clearable
                multiple
            ></v-select>
        </v-container>
        <div v-if="offers" v-for="offer in offers" :key="offer.id">
            <BaseOfferCard :offer="offer" @offer-deleted="getOffers" @offer-added="getOffers" />
        </div>
    </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/useAuth'
import axios from 'axios'
import BaseOfferCard from '../components/Offer/BaseOfferCard.vue'
import BaseOfferForm from '../components/Offer/BaseOfferForm.vue'
import { formatDate } from "@/services/DateService.js"


const offers = ref(null)
const store = useAuthStore()
const { user } = store
const hiddenForm = ref(true)
const buttonFormText = ref('+ Add new offer')
const buttonFormColor = ref('blue')
const sortBy = ref('')
const domainFilter = ref([])

const getOffers = async () => {
    console.log('get offfers...')
    const res = await axios.get('http://localhost/api/offers')
    offers.value = res.data.data
}

onMounted(async () => {
    getOffers()
})

const triggerForm = () => {
    hiddenForm.value = !hiddenForm.value
    if (hiddenForm.value == false) {
        buttonFormText.value = 'Hide form'
        buttonFormColor.value = 'red'
    } else {
        buttonFormText.value = '+ Add new offer'
        buttonFormColor.value = 'blue'
    }

}
</script>
  
<style>
@media (min-width: 1024px) {
    .main {
        width: 100%
    }
}
</style>
  