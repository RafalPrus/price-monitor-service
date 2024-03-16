<template>
    <div class="d-flex align-center flex-column mb-2">
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
              <div>
                Monitoring since: {{ formatDate(offer.created_at) }}
              </div>
            </template>
            <template v-slot:actions>
              <v-container class="m-0 p-0">
              <v-row>
                <v-col cols="12" sm="12" class="mb-2">
                  <v-btn :href="offer.url" target="_blank">
                    Take me to the offer
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="2">
                  <v-btn color="blue">
                    Edit
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="2">
                  <v-btn color="red" @click="deleteOffer(offer)">
                    Delete
                  </v-btn>
                </v-col>
              </v-row>
              </v-container>



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