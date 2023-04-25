<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center sm:justify-center">
            <table class="w-full text-sm border-separate border-spacing-y-2">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Balance</th>
                        <th>Ownership</th>
                        <th>Type</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tr-class" v-if="accounts?.data" v-for="account in accounts.data" :key="account.remoteId">
                        <td class="td-class text-center">{{ account.displayName }}</td>
                        <td class="td-class text-center">${{ account.balance.value }} {{ account.balance.currencyCode }}</td>
                        <td class="td-class text-center" :class="account.ownershipType === 'JOINT' ? 'text-yellow-600' : ''">{{ account.ownershipType }}</td>
                        <td class="td-class text-center">{{ account.type }}</td>
                        <td class="td-class text-center">{{ formatDate(account.createdAt) }}</td>
                        <td class="td-class text-center">
                            <SecondaryButton>
                                View
                            </SecondaryButton>
                        </td>
                    </tr>
                    <tr v-if="error">
                        <td class="text-center"> {{ error }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import ApiService from '../../api/index';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { format } from 'date-fns';
import useSWRV from 'swrv';

interface PageParams {
    size?: string;
    next?: string;
    prev?: string
}

export default defineComponent({
    components: {
        PrimaryButton,
        SecondaryButton
    },
    setup() {
        const size = ref('100');
        const nextPageKey = ref(null);
        const prevPageKey = ref(null);
        const api = new ApiService();
        const { data: accounts, error } = useSWRV([
            'accounts',
            { size: size.value as any }
        ], api.makeSwrvFetcher());

        return {
            accounts,
            error,
            size,
            nextPageKey,
            prevPageKey,
            api
        }
    },
    methods: {
        async getPrevPage() {
            if (this.prevPageKey !== null) {
                const { data: accounts, error } = useSWRV([
                    'accounts',
                    { size: this.size as any, prev: this.prevPageKey }
                ], this.api.makeSwrvFetcher());

                this.accounts = accounts;
                this.error = error;
            }
        },
        async getNextPage() {
            if (this.nextPageKey !== null) {
                const { data: accounts, error } = useSWRV([
                    'accounts', 
                    { size: this.size as any, next: this.nextPageKey } 
                ], this.api.makeSwrvFetcher());

                this.accounts = accounts;
                this.error = error;
            }
        },
        formatDate(date: string) {
            return format(new Date(date), 'hh:mmaa E d-MMM-yy')
        }
    },
});
</script>

<style scoped>
@tailwind base;
@tailwind components;
@tailwind utilities;

.td-class {
  @apply px-4 py-3 bg-gray-200 first:rounded-t-lg last:rounded-b-lg sm:first:rounded-t-none sm:last:rounded-b-none sm:first:rounded-tl-lg sm:first:rounded-bl-lg sm:last:rounded-tr-lg sm:last:rounded-br-lg
} 

.tr-class {
  @apply flex flex-col mb-4 sm:table-row
}

.suspended-text {
  @apply text-gray-500
}

</style>