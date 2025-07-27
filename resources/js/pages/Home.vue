<template>
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Welcome to the Presentation App</h1>
        <p class="mb-4">This is a simple Inertia.js + Vue.js page.</p>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-xl font-semibold mb-2">Current Time</h2>
            <p>{{ currentTime }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const currentTime = ref(new Date().toLocaleString());

onMounted(() => {
    // Update time every second
    setInterval(() => {
        currentTime.value = new Date().toLocaleString();
    }, 1000);
});
</script>
