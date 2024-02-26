<template>
    <v-sheet class="mx-auto px-6 py-8" width="500">
      <v-form @submit.prevent="onSubmit">
        <v-text-field 
          v-model="name"
          v-bind="nameProps"
          label="Offer name"
          class="mb-2"
          variant="solo-filled"
        />
        <v-text-field
          v-model="url"
          v-bind="urlProps"
          label="Offer link"
          class="mb-2"
          variant="solo-filled"
        />
        <v-switch
         label="Activate monitoring?"
          v-model="isActive"
          v-bind="isActiveProps"
          color="success"></v-switch>
        <v-btn color="primary" type="submit" :disabled="urlError" >Add offer</v-btn>
      </v-form>
    </v-sheet>
</template>

<script setup>
import { ref, watch } from 'vue'
// vee-validate
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/useAuth'

const emit = defineEmits(['offer-added'])
const urlError = ref(false)
const vuetifyConfig = (state) => ({
    props: {
    'error-messages': state.errors,
    },
});

const schema = yup.object({
    name: yup.string().required().label('Offer name'),
    url: yup.string().url().required().label('Offer link'),
    isActive: yup.string().required().label('Activate monitoring?')
});

const { defineField, handleSubmit, resetForm, setFieldError } = useForm({
    validationSchema: schema,
});

const [name, nameProps] = defineField('name', vuetifyConfig);
const [url, urlProps] = defineField('url', vuetifyConfig);
const [isActive, isActiveProps] = defineField('isActive', vuetifyConfig);
const [terms, termsProps] = defineField('terms', vuetifyConfig);
const router = useRouter()

watch(url, () => {
    urlError.value = false
})
  
const onSubmit = handleSubmit(async () => {
    try {
        const payload = {
        name: name.value,
        url: url.value,
        is_active: isActive.value,
    }
        await axios.post('http://localhost/api/offers', payload);
        emit('offer-added')
        resetForm()
    } catch (error) {
        console.error('Error submitting form:', error)
        setFieldError('url', "Ups, you can't add offer to this shop")
        urlError.value = true
    }
})
</script>

<style>
@media (min-width: 1024px) {
    .register {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
}
</style>
