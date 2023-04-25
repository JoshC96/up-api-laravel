<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center sm:justify-center">
            <table class="w-full text-sm border-separate border-spacing-y-2">
                <thead class="">
                    <tr>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Merchant Name</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tr-class" v-if="transactions?.data" v-for="transaction in transactions.data" :key="transaction.remoteId">
                        <td class="td-class text-center">
                            <span class="rounded-md px-4 py-px text-xs font-semibold uppercase text-green-900 antialiased"
                            :class="transaction.status === 'SETTLED' ? 'bg-green-500/50' : 'bg-orange-500/50'">
                                {{ transaction.status }}
                            </span>
                        </td>
                        <td class="td-class text-center" :class="parseFloat(transaction.amount.value) < 0 ? 'text-red-800' : 'text-green-700'">${{ transaction.amount.value }} {{ transaction.amount.currencyCode }}</td>
                        <td class="td-class text-center">{{ transaction.description }}</td>
                        <td class="td-class text-center">{{ transaction.createdAt ? formatDate(transaction.createdAt) : 'no date' }}</td>
                        <td class="td-class text-center">
                            <SecondaryButton>
                                View
                            </SecondaryButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <PrimaryButton class="ml-4" @click="getPrevPage" :disabled="prevPageKey === null">
                Prev Page
            </PrimaryButton>
            <PrimaryButton class="ml-4" @click="getNextPage" :disabled="nextPageKey === null">
                Next Page
            </PrimaryButton>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue';
import { Transaction } from '../../api/index';
import ApiService from '../../api/index';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { format } from 'date-fns'
import useSWRV from 'swrv';
import { computed } from '@vue/reactivity';


interface PageParams {
    size?: number;
    next?: string;
    prev?: string
}

export default defineComponent({
    components: {
        PrimaryButton,
        SecondaryButton
    },
    setup() {
        const size = ref('25');
        const prevPageKey = ref(null);
        const nextPageKey = ref(null);
        const urlKey = ref('transactions');

        const api = new ApiService();
        const { data: transactions, error } = useSWRV([
            'transactions',
            { size: size.value as any }
        ], api.makeSwrvFetcher());

        watch(urlKey, updatePaginationLinks);

        function updatePaginationLinks() {
            nextPageKey.value = transactions.value.page_links?.next;
            return 'transactions?page[next]=' + nextPageKey.value;
        }

        async function getPrevPage() {
            if (prevPageKey.value !== null) {
                const { data: transactions, error } = useSWRV([
                    `transactions`,
                    { size: size.value as any, prev: prevPageKey.value }
                ], api.makeSwrvFetcher())

                transactions.value = transactions;
                error.value = error;
                nextPageKey.value = transactions.value.data?.page_links?.next;
                prevPageKey.value = transactions.value.data?.page_links?.prev;
            }
        }

        async function getNextPage() {
            if (nextPageKey.value !== null) {
                const { data: transactions, error } = useSWRV([
                    'transactions',
                    { size: size.value as any, next: nextPageKey.value }
                ], api.makeSwrvFetcher());

                console.log(transactions);

                transactions.value = transactions;
                error.value = error;
                nextPageKey.value = transactions.value.data?.page_links?.next;
                prevPageKey.value = transactions.value.data?.page_links?.prev;
            }
        }

        return {
            transactions,
            error,
            size,
            nextPageKey,
            prevPageKey,
            api,
            getPrevPage,
            getNextPage
        }
    },
    methods: {
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