<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TransactionList from '@/Components/Lists/TransactionList.vue';
</script>

<template>
    <Head title="Transactions" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <TransactionList></TransactionList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
