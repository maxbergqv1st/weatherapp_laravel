<script setup>
import { onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import WeatherCard from '@/Components/Weather/WeatherCard.vue'
import WeatherCardSkeleton from '@/Components/Weather/WeatherCardSkeleton.vue'
import WeatherQuickCities from '@/Components/Weather/WeatherQuickCities.vue'
import WeatherSearchBar from '@/Components/Weather/WeatherSearchBar.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import axios from 'axios'

const page = usePage()
const defaultCity = 'Halmstad, Sweden'
const quickCities = ['Halmstad, Sweden', 'Stockholm, Sweden', 'Gothenburg, Sweden', 'Malmo, Sweden']
const city = ref(defaultCity)
const weather = ref(null)
const loading = ref(false)
const error = ref(null)
const starred = ref(false)

const search = async (query = city.value) => {
    const normalizedQuery = query.trim()

    if (!normalizedQuery) return

    loading.value = true
    error.value = null

    try {
        const res = await axios.get('/weather', { params: { city: normalizedQuery } })
        weather.value = res.data
        city.value = normalizedQuery
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

onMounted(() => {
    search(defaultCity)
})
</script>

<template>
    <component
        :is="page.props.auth.user ? AuthenticatedLayout : GuestLayout"
        v-bind="page.props.auth.user ? {} : { contained: false }"
    >
        <div class="max-w-xl mx-auto py-12 px-4">
            <WeatherSearchBar
                v-model="city"
                @search="search"
            />

            <WeatherQuickCities
                :cities="quickCities"
                @select-city="search"
            />

            <div class="min-h-[22rem]">
                <WeatherCardSkeleton v-if="loading" />

                <p v-else-if="error" class="pt-10 text-center text-red-500">{{ error }}</p>

                <WeatherCard
                    v-else-if="weather"
                    :weather="weather"
                    :starred="starred"
                    :show-favorite="true"
                    @toggle-favorite="toggleStar"
                />
            </div>
        </div>
    </component>
</template>
