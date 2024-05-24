<script setup>
import { ref, onMounted } from 'vue';

let movies = ref([]);

const fetchMovies = () => {
  const token = localStorage.getItem('jwt_token'); // Replace with your actual JWT token
  fetch('/api/v1/movies/', {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Failed to fetch movies');
    }
    return response.json();
  })
  .then(data => {
    movies.value = data.movies; // Update movies array with the fetched data
  })
  .catch(error => {
    console.error(error);
  });
};

const getPosterUrl = (poster) => {
  // If poster is already a full URL, return it as is
  if (poster.startsWith('http')) {
    return poster;
  }else
  // Remove the '/api/v1/poster/' prefix from the poster path
  return poster.replace('/api/v1/posters/', '');
};

onMounted(() => {
  fetchMovies();
});
</script>

<template>
  <div class="container mt-5">
    <h2>Courses</h2>
    <div class="row">
      <div v-for="movie in movies" :key="movie.id" class="col-md-4 mb-4">
        <div class="card">
          <img :src="getPosterUrl(movie.poster)" class="card-img-top" style="height:250px;" alt="Movie Poster">
          <div class="card-body">
            <h5 class="card-title">{{ movie.title }}</h5>
            <p class="card-text">{{ movie.description }} </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card {
  width: 18rem;
}
</style>
