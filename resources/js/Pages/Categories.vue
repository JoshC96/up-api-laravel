<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CategoryList from '@/Components/Lists/CategoryList.vue';
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <CategoryList></CategoryList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
