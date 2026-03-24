<script setup>
defineProps({
    cities: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['select-city']);
</script>

<template>
    <div class="mb-8 flex flex-wrap gap-2">
        <button
            v-for="city in cities"
            :key="city"
            @click="emit('select-city', city)"
            class="rounded-full border border-gray-200 bg-white px-3 py-1 text-sm text-gray-600 transition hover:border-blue-300 hover:text-blue-600"
        >
            {{ city }}
        </button>
    </div>
</template>
