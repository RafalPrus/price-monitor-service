<template>
    <v-container
        class="main mb-6"
    >
        <v-row
        align="start"
        no-gutters
        style="height: 150px;"
        >
        <v-col v-for="n in 8">
            <v-sheet class="pa-2 ma-2">
            {{ user.name }}
            </v-sheet>
            <v-sheet class="pa-2 ma-2">
                {{ user.email }}
            </v-sheet>
            <v-sheet class="pa-2 ma-2">
                {{ user.created_at }}
            </v-sheet>
        </v-col>
        </v-row>
    </v-container>
</template>
  
<script setup>
import { useAuthStore } from '@/stores/useAuth'
  
const store = useAuthStore()
const { user } = store
</script>
  
<style>
@media (min-width: 1024px) {
    .main {
        width: 100%
    }
}
</style>
  