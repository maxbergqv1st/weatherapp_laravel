<script setup>
defineProps({
    weather: {
        type: Object,
        required: true,
    },
    starred: {
        type: Boolean,
        default: false,
    },
    showFavorite: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['toggle-favorite']);
</script>

<template>
    <div class="rounded-2xl bg-white p-6 shadow">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-semibold">
                    {{ weather.location.name }}, {{ weather.location.country }}
                </h1>
                <p class="text-sm text-gray-400">{{ weather.location.localtime }}</p>
            </div>

            <button
                v-if="showFavorite"
                @click="emit('toggle-favorite')"
                class="text-2xl"
                type="button"
            >
                {{ starred ? '★' : '☆' }}
            </button>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <img :src="weather.current.condition.icon" class="h-16 w-16" />
            <div>
                <p class="text-5xl font-bold">{{ weather.current.temp_c }}°C</p>
                <p class="text-gray-500">{{ weather.current.condition.text }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-3 gap-4 text-center text-sm text-gray-500">
            <div>
                <p class="font-medium text-gray-700">{{ weather.current.humidity }}%</p>
                <p>Luftfuktighet</p>
            </div>
            <div>
                <p class="font-medium text-gray-700">{{ weather.current.wind_kph }} km/h</p>
                <p>Vind</p>
            </div>
            <div>
                <p class="font-medium text-gray-700">{{ weather.current.feelslike_c }}°C</p>
                <p>Känns som</p>
            </div>
        </div>
    </div>
</template>
