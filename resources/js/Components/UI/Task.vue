<script setup>
defineProps({
    task: {
        type: Object,
        required: true
    }
})
</script>


<template>
    <div class="min-w-100 p-6 bg-white ring-3 ring-gray-400 rounded-xl shadow-md gap-4 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800">{{ task.title }}</h3>
        <p class="text-gray-600">{{ task.description }}</p>
    </div>
</template>