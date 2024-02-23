<template>
    <v-container
        class="main mb-6"
    >
        <v-row
        align="start"
        no-gutters
        style="height: 150px;"
        >
        <v-col v-for="offer in offers" :key="offer.id">
            <v-sheet class="pa-2 ma-2">
                {{ offer.name }}
            </v-sheet>
            <v-sheet class="pa-2 ma-2">
                {{ offer.url }}
            </v-sheet>
            <v-sheet class="pa-2 ma-2">
                {{ user.created_at }}
            </v-sheet>
            <v-sheet class="pa-2 ma-2">
                {{ offer.domain }}
            </v-sheet>
        </v-col>
        </v-row>
    </v-container>
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
    offers = res.data
})
</script>
  
<style>
@media (min-width: 1024px) {
.main {
    width: 100%
}
}
</style>
  