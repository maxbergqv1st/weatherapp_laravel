<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'search']);

const updateValue = (event) => {
    emit('update:modelValue', event.target.value);
};

const submitSearch = () => {
    emit('search');
};
</script>

<template>
    <div class="mb-8 flex gap-2">
        <input
            :value="modelValue"
            @input="updateValue"
            @keyup.enter="submitSearch"
            type="text"
            placeholder="Sök stad..."
            class="flex-1 rounded-lg border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <button
            @click="submitSearch"
            class="rounded-lg bg-blue-500 px-5 py-2 text-white hover:bg-blue-600"
        >
            Sök
        </button>
    </div>
</template>
