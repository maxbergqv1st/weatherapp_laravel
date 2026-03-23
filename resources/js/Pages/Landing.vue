<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import axios from 'axios'

const page = usePage()
const city = ref('')
const weather = ref(null)
const loading = ref(false)
const error = ref(null)
const starred = ref(false)

const search = async () => {
    if (!city.value.trim()) return
    loading.value = true
    error.value = null
    try {
        const res = await axios.get('/weather', { params: { city: city.value } })
        weather.value = res.data
        starred.value = false
    } catch (e) {
        error.value = 'Staden hittades inte, försök igen.'
    } finally {
        loading.value = false
    }
}

const toggleStar = async () => {
    if (!page.props.auth.user) {
        router.visit('/login')
        return
    }
    const res = await axios.post('/favorites/toggle', {
        location_id: weather.value.location_id
    })
    starred.value = res.data.starred
}
</script>

<template>
    <component :is="page.props.auth.user ? AuthenticatedLayout : GuestLayout">
        <div class="max-w-xl mx-auto py-12 px-4">

            <!-- Sökruta -->
            <div class="flex gap-2 mb-8">
                <input
                    v-model="city"
                    @keyup.enter="search"
                    type="text"
                    placeholder="Sök stad..."
                    class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <button
                    @click="search"
                    class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600"
                >
                    Sök
                </button>
            </div>

            <!-- Laddar -->
            <p v-if="loading" class="text-gray-400 text-center">Hämtar väder...</p>

            <!-- Fel -->
            <p v-if="error" class="text-red-500 text-center">{{ error }}</p>

            <!-- Väderkort -->
            <div v-if="weather && !loading" class="bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-semibold">
                            {{ weather.location.name }}, {{ weather.location.country }}
                        </h1>
                        <p class="text-gray-400 text-sm">{{ weather.location.localtime }}</p>
                    </div>
                    <button @click="toggleStar" class="text-2xl">
                        {{ starred ? '★' : '☆' }}
                    </button>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <img :src="weather.current.condition.icon" class="w-16 h-16" />
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

        </div>
    </component>
</template>