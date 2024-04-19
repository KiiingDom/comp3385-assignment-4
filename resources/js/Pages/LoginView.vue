<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

let email = ref('');
let password = ref('');
let error = ref(null);
let message = ref(null);

const router = useRouter();

const login = async () => {
  try {
    const response = await fetch('/api/v1/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    });

    if (response.ok) {
      const { token } = await response.json();
      localStorage.setItem('token', token);
      router.push('/'); // Redirect to the home page
      message.value = { type: 'success', text: 'Login successful!' };
      error.value = null; // Reset error message
      // Redirect to a different page upon successful login
    } else {
      const errorMessage = await response.text();
      throw new Error(errorMessage);
    }
  } catch (error) {
    console.error(error);
    message.value = { type: 'error', text: 'Failed to login. Please check your credentials and try again.' };
    // Reset success message
  }
};
</script>

<template>
<div class="container mt-5">
  <h2>Login</h2>

  <form @submit.prevent="login" class="mt-3">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" v-model="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" v-model="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>

  <div v-if="error" class="mt-3 alert alert-danger">{{ error }}</div>
  <div v-if="message" :class="['mt-3', 'alert', 'alert-' + message.type]">{{ message.text }}</div>
</div>
</template>
