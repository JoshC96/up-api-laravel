<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AccountList from '@/Components/Lists/AccountList.vue';
</script>

<template>
    <Head title="Accounts" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <AccountList></AccountList>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
