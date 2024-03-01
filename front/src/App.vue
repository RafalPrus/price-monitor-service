
<template>
  <header>
    <div class="wrapper">
      <img alt="Vue logo" class="logo" src="@/assets/logo.jpg" width="125" height="125" />
      <HelloWorld msg="Hunt your promo!" />
    </div>
    <div class="wrapper">
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink v-if="!store.isAuthenticated" to="/register">Register</RouterLink>
        <RouterLink v-if="!store.isAuthenticated" to="/login">Login</RouterLink>
        <RouterLink v-if="store.isAuthenticated" to="/offers">Offers</RouterLink>
        <RouterLink v-if="store.isAuthenticated" to="/me">Me</RouterLink>
        <RouterLink v-if="store.isAuthenticated" @click.prevent="handleClick" to="/logout">Logout</RouterLink>
      </nav>
    </div>
  </header>

  <RouterView />
</template>

<script setup>

import { useAuthStore } from '@/stores/useAuth'
import HelloWorld from './components/HelloWorld.vue'
import { logout } from '@/Repositories/AuthRepository'
import { useRouter } from "vue-router";

const store = useAuthStore()
const router = useRouter()

const handleClick = async () => {
   await logout()
   router.push('/login')
}
</script>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: flex-start stretch;
    justify-content: space-between;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 0;
  }
}
</style>
