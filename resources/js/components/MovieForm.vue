<script setup>
import { ref } from 'vue';

const title = ref('');
const description = ref('');
let poster = null;
const message = ref(null);

const saveMovie = async () => {
  try {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('poster', poster);

    const response = await fetch('/api/v1/movies', {
      method: 'POST',
      body: formData,
      headers: {
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      const data = await response.json();
      message.value = { type: 'success', text: 'Movie added successfully' };
      console.log(data);
      // Display a success message or perform other actions
    } else {
      message.value = { type: 'error', text: 'Failed to add movie' };
    }
  } catch (error) {
    message.value = { type: 'error', text: 'Failed to add movie' };
    console.error(error);
    // Handle error, display an error message, etc.
  }
};


const handleFileUpload = (event) => {
  poster = event.target.files[0];
};
</script>

<template>
  <div class="container mt-5">
    <h2>Add New Movie</h2>

        <div v-if="message" :class="['alert', message.type === 'success' ? 'alert-success' : 'alert-danger']">{{ message.text }}</div>


    <form id="movieForm" @submit.prevent="saveMovie" enctype="multipart/form-data">

      <div class="mb-3">
        <label for="title" class="form-label">Title:</label>
        <input type="text" id="title" v-model="title" class="form-control">
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description:</label>
        <textarea id="description" v-model="description" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label for="photo" class="form-label">Photo:</label>
        <input type="file" id="photo" @change="handleFileUpload" class="form-control">
      </div>

      <div>
        <button type="submit" class="btn btn-primary">Add New Film</button>
      </div>
    </form>
  </div>
</template>
