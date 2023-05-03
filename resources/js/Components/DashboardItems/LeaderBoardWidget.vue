<template>
    <div class="w-full px-6 sm:w-1/2 xl:w-1/3 mb-5">
        <div v-for="(item, index) in items" :key="index"
            class="flex items-center px-5 py-6 bg-white shadow-sm">
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full text-white min-w-[50px] text-center"> {{ item.transaction_count }} </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ formatCurrency(item.total_amount) }}</h4>
                <div class="text-gray-500 capitalize">{{ index.replace(/-/g, ' ') }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { formatCurrency } from '@/formatters';

interface Item {
    total_amount: number;
    transaction_count: number;
}

defineProps({
    items: Array<Item>,
})

</script>