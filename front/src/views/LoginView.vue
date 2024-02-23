<template>
    <v-sheet class="mx-auto px-6 py-8" width="500">
      <v-form @submit.prevent="onSubmit">
        <v-text-field
          v-model="email"
          v-bind="emailProps"
          label="Email"
          type="email"
          class="mb-2"
          variant="solo-filled"
        />
        <v-text-field
          v-model="password"
          v-bind="passwordProps"
          label="Password"
          type="password"
          class="mb-2"
          variant="solo-filled"
        />
        <v-btn color="primary" type="submit">Login</v-btn>
      </v-form>
    </v-sheet>
  </template>
  
<script setup>
import { ref } from 'vue'
// vee-validate
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/useAuth'
  
const vuetifyConfig = (state) => ({
    props: {
    'error-messages': state.errors,
    },
});

const schema = yup.object({
    email: yup.string().email().required().label('E-mail'),
    password: yup.string().min(6).required(),
});

const { defineField, handleSubmit, setFieldError } = useForm({
validationSchema: schema,
})
const router = useRouter()

  const [email, emailProps] = defineField('email', vuetifyConfig);
  const [password, passwordProps] = defineField('password', vuetifyConfig);
  const [terms, termsProps] = defineField('terms', vuetifyConfig);
  
  const onSubmit = handleSubmit(async () => {
    try {
      const payload = {
        email: email.value,
        password: password.value,
      }
  
      await axios.post('http://localhost/api/login', payload);
      const res = await axios.get('http://localhost/api/users')
      const store = useAuthStore()
      const { login } = store
      console.log(res.data)
      console.log('data z usera')
      login(res.data)
      router.push('/me')
    } catch (error) {
      console.error('Error submitting form:', error)
      setFieldError('password', 'Niepoprawne dane logowania')
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
  