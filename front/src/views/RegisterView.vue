<template>
  <v-sheet class="mx-auto px-6 py-8" width="500">
    <v-form @submit.prevent="onSubmit">
      <v-text-field 
        v-model="name"
        v-bind="nameProps"
        label="Name"
        class="mb-2"
        variant="solo-filled"
      />
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
      <v-text-field
        v-model="passwordConfirm"
        v-bind="confirmProps"
        label="Password confirmation"
        type="password"
        class="mb-2"
        variant="solo-filled"
      />
  
      <v-btn color="primary" type="submit">Register</v-btn>
      <v-btn color="outline" class="ml-4" @click="resetForm()">Reset</v-btn>
    </v-form>
  </v-sheet>
</template>

<script setup>
  import { ref } from 'vue'
  // vee-validate
  import { useForm } from 'vee-validate';
  import * as yup from 'yup';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import { useAuthStore } from '@/stores/useAuth'

  const vuetifyConfig = (state) => ({
    props: {
      'error-messages': state.errors,
    },
  });

  const schema = yup.object({
    name: yup.string().required().label('Name'),
    email: yup.string().email().required().label('E-mail'),
    password: yup.string().min(6).required(),
    passwordConfirm: yup
      .string()
      .oneOf([yup.ref('password')], 'Passwords must match')
      .required()
      .label('Password confirmation'),
  });

const { defineField, handleSubmit, resetForm, setFieldError } = useForm({
  validationSchema: schema,
});

const [name, nameProps] = defineField('name', vuetifyConfig);
const [email, emailProps] = defineField('email', vuetifyConfig);
const [password, passwordProps] = defineField('password', vuetifyConfig);
const [passwordConfirm, confirmProps] = defineField(
  'passwordConfirm',
  vuetifyConfig
);
const [terms, termsProps] = defineField('terms', vuetifyConfig);
const router = useRouter()

const onSubmit = handleSubmit(async () => {
  try {
    const payload = {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirm.value,
    }

    await axios.post('http://localhost/api/register', payload);
    await axios.post('http://localhost/api/login', {
      email: payload.email,
      password: payload.password
    });
    const { data } = await axios.get('http://localhost/api/users')
    const store = useAuthStore()
    const { login } = store
    login(data)
    router.push('/me')
    
  } catch (error) {
    console.error('Error submitting form:', error)
    setFieldError('passwordConfirm', 'Ups, something went wrong...')
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
