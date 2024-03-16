<template>
  <main>
      <v-card class="mt-10 mx-auto"
              max-width="80%">
        <v-card-title>Profile</v-card-title>
        <v-card-subtitle class="mb-4">This information will be displayed publicly so be careful what you share.</v-card-subtitle>
        <v-form @submit.prevent="">
          <v-responsive
              max-width="344"
              class="ml-4 mr-4"
          >
            <v-text-field
                v-model="user.name"
                label="Name"
                density="compact"
                class="mb-2 mt-2"
                variant="outlined"
            />
          <v-text-field
              v-model="user.email"
              label="Email"
              type="email"
              density="compact"
              class="mb-2 mt-2"
              variant="outlined"
          />
          </v-responsive>
          <v-container >
            <v-btn color="primary" @click="">Save</v-btn>
            <v-btn class="ml-4" color="outline" type="submit">Reset</v-btn>
          </v-container>
        </v-form>
      </v-card>

  </main>
</template>
  
<script setup>
import { ref, onMounted } from 'vue'
import { getAuth } from '@/Repositories/AuthRepository'
const user = ref({name: null, email: null})


onMounted(async () => {

  const response = await getAuth()
  user.value.name = response.data.name
  user.value.email = response.data.email
})
</script>
  
<style>
@media (min-width: 1024px) {
    .main {
        width: 100%
    }
}
</style>
  