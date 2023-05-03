<template>
    <div class="w-full px-6 sm:w-1/2 xl:w-1/3 mb-5">
        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">

            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full" v-html="svgIcon"></div>

            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ number }}</h4>
                <div class="text-gray-500">{{ stat }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

defineProps({
    number: String,
    stat: String,
    svgIcon: String
})

</script>