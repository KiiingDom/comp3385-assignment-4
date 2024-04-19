<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const credentials = ref({
    email: '',
    password: ''
  });
let error = ref(null);
let message = ref(null);

const router = useRouter();

function login() {
  fetch('/api/v1/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(credentials.value)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Login failed');
    }
    return response.json();
  })
  .then(data => {
    if (data.access_token) {
      // Store the token in localStorage or cookies
      localStorage.setItem('jwt_token', data.access_token);
      message.value = { type: 'success', text: 'Login successful! \nPlease wait to view the movies section.' }; // Set success message
      setTimeout(() => {
        router.push('/movies').then (() => {
          window.location.reload();
        }); // Redirect after setting message
      }, 3000); // Redirect after 1 second
    } else {
      // Handle any additional error messages if you have a specific format
      message.value = { type: 'error', text: 'Failed to login, please check your credentials.' };
    }
  })
  .catch(error => {
    message.value = { type: 'error', text: error.message }; // Set error message
  });
}
</script>

<template>
<div class="container mt-5">
  <h2>Login</h2>

  <form @submit.prevent="login" class="mt-3">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" v-model="credentials.email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" v-model="credentials.password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-bottom:10px;">Login</button>
  </form>

  <div v-if="error" class="mt-3 alert alert-danger">{{ error }}</div>
  <div v-if="message" :class="['alert', message.type === 'success' ? 'alert-success' : 'alert-danger']">{{ message.text }}</div>
</div>
</template>
