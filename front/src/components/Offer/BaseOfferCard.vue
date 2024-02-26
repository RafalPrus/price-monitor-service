<template>
    <div class="d-flex align-center flex-column">
        <div class="text-subtitle-2 mt-2">{{ offer.name }}</div>
        <v-card width="600">
            <template v-slot:title>
            {{ offer.name }}
            </template>
        
            <template v-slot:subtitle>
            {{ offer.domain }}
            </template>
        
            <template v-slot:text>
                <div v-if="offer.price_actual">
                    <div class="mb-2">Actual price: <span class="text-purple-accent-3">{{ offer.price_actual.price }}</span></div>
                    <div>Last Update: {{ formatDate(offer.price_actual.created_at) }}</div>
                </div>
                Monitoring since: {{ formatDate(offer.created_at) }}
            </template>
            <template v-slot:actions>
            <v-btn color="blue">
                Edit
            </v-btn>
        
            <v-btn color="red" @click="deleteOffer(offer)">
                Delete
            </v-btn>
            </template>
        </v-card>
    </div>
</template>
<script setup>
import { formatDate } from "@/services/DateService.js"
import axios from 'axios'

defineProps({
  offer: {
    required: true
  }
})

const emit = defineEmits(['offer-deleted'])

const deleteOffer = async (offer) => {
    try {
        const res = await axios.delete('http://localhost/api/offers/' + offer.id)
        emit('offer-deleted')
    } catch {

    }
}
</script>