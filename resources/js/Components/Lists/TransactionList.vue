<template>
    <div class="mt-6">
        <h2 class="text-xl font-semibold leading-tight text-gray-700">Transactions</h2>

        <div class="flex flex-col mt-3 sm:flex-row">
            <div class="flex">
                <div class="relative">
                    <select
                        v-model="size"
                        :selected="size"
                        class="block w-full h-full px-4 py-2 pr-8 leading-tight text-gray-700 bg-white border border-gray-400 rounded-l appearance-none focus:outline-none focus:bg-white focus:border-gray-500">
                        <option disabled>Page Size</option>
                        <option>5</option>
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                    </select>
                </div>

                <div class="relative">
                    <select
                        class="block w-full h-full px-4 py-2 pr-8 leading-tight text-gray-700 bg-white border-t border-b border-r border-gray-400 rounded-r appearance-none sm:rounded-r-none sm:border-r-0 focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-500">
                        <option>All</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex">
                <vue-tailwind-datepicker 
                    placeholder="Date From"
                    as-single 
                    v-model="dateFromValue"
                    :formatter="dateFormatter" />
            </div>
            <div class="flex">
                <vue-tailwind-datepicker 
                    placeholder="Date To"
                    as-single 
                    v-model="dateToValue"
                    :formatter="dateFormatter" />
            </div>
            <div class="relative block mt-2 sm:mt-0">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-gray-500 fill-current">
                        <path
                            d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z" />
                    </svg>
                </span>

                <input placeholder="Search"
                    class="block w-full py-2 pl-8 pr-6 text-sm text-gray-700 placeholder-gray-400 bg-white border border-b border-gray-400 rounded-l rounded-r appearance-none sm:rounded-l-none focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
            </div>
        </div>

        <div class="px-4 py-4 -mx-4 overflow-x-auto sm:-mx-8 sm:px-8">
            <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                                Description
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                                Amount
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                                Status
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                                Created at
                            </th>
                            <th
                                class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
                                Settings
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="transactions" v-for="transaction in transactions" :key="transaction.remoteId">
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <div class="flex items-center">
                                    <!-- <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-full h-full rounded-full" src="" alt="profile pic" />
                                    </div> -->
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-nowrap">
                                            {{ transaction.description }}
                                            <div class="text-sm leading-5 text-gray-500">
                                                {{ transaction.message }}
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-nowrap">
                            <td class="td-class text-center"
                                :class="parseFloat(transaction.amount.value) < 0 ? 'text-red-800' : 'text-green-700'">
                                ${{ transaction.amount.value }} {{ transaction.amount.currencyCode }}
                            </td>
                            </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight"
                                    :class="transaction.status === 'SETTLED' ? 'text-green-900' : 'text-orange-900'">
                                    <span aria-hidden class="absolute inset-0 opacity-50 rounded-full"
                                        :class="transaction.status === 'SETTLED' ? 'bg-green-500/50' : 'bg-orange-500/50'"></span>
                                    <span class="relative">{{ transaction.status }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-nowrap">
                                    {{ transaction.createdAt ? formatDate(transaction.createdAt) : 'no date' }}
                                </p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <div class="flex justify-around">
                                    <span class="text-yellow-500 flex justify-center">
                                        <a href="#" class="mx-2 px-2 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <form method="POST">
                                            <button class="mx-2 px-2 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-700"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex flex-col items-center px-5 py-5 bg-white border-t xs:flex-row xs:justify-between">
                    <span class="text-xs text-gray-900 xs:text-sm">Showing {{ transactions?.length }} results</span>

                    <div class="inline-flex mt-2 xs:mt-0">
                        <button @click="getPrevPage"
                            class="px-4 py-2 text-sm font-semibold text-gray-800 bg-gray-300 rounded-l hover:bg-gray-400">
                            Prev
                        </button>
                        <button @click="getNextPage"
                            class="px-4 py-2 text-sm font-semibold text-gray-800 bg-gray-300 rounded-r hover:bg-gray-400">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue';
import { Transaction } from '../../api/index';
import ApiService from '../../api/index';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { format } from 'date-fns';
import VueTailwindDatepicker from 'vue-tailwind-datepicker';


interface PageParams {
    size?: number;
    next?: string;
    prev?: string
}

export default defineComponent({
    components: {
        PrimaryButton,
        SecondaryButton,
        VueTailwindDatepicker
    },
    setup() {
        const size = ref('20');
        const prevPageKey = ref(null);
        const nextPageKey = ref(null);
        const urlKey = ref('transactions');
        const api = new ApiService();
        const transactions = ref<Transaction[]>();
        const dateFromValue = ref();
        const dateToValue = ref();
        const dateFormatter = ref({
            date: 'DD MMM YYYY',
            month: 'MMM'
        })

        async function getTransactions() {
            let transactionData = await api.sendRequest('transactions', 'GET', { 
                    size: size.value as any, 
                    ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateTo': dateToValue.value[0] } : {}),
                    ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateFrom': dateFromValue.value[0] } : {}),
                });
            transactions.value = transactionData.data;
            nextPageKey.value = transactionData.page_links?.next;
            prevPageKey.value = transactionData.page_links?.prev;
        }

        async function getPrevPage() {
            if (prevPageKey.value !== null) {
                let transactionData = await api.sendRequest('transactions', 'GET', { 
                        size: size.value as any, 
                        prev: prevPageKey.value, 
                        ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateTo': dateToValue.value[0] } : {}),
                        ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateFrom': dateFromValue.value[0] } : {}),
                    })

                transactions.value = transactionData.data;
                nextPageKey.value = transactionData.page_links?.next;
                prevPageKey.value = transactionData.page_links?.prev;
            }
        }

        async function getNextPage() {
            if (nextPageKey.value !== null) {
                let transactionData = await api.sendRequest('transactions', 'GET', {
                        size: size.value as any,
                        next: nextPageKey.value,
                        ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateTo': dateToValue.value[0] } : {}),
                        ...((dateFromValue.value && typeof dateFromValue.value[0] !== 'undefined') ? { 'dateFrom': dateFromValue.value[0] } : {}),
                    })

                transactions.value = transactionData.data;
                nextPageKey.value = transactionData.page_links?.next;
                prevPageKey.value = transactionData.page_links?.prev;
            }
        }

        watch(size, () => {
            getTransactions();
        });

        watch(dateFromValue, () => {
            console.log(dateFromValue.value)
            getTransactions();
        });

        watch(dateToValue, () => {
            console.log(dateToValue.value)
            getTransactions();
        });

        return {
            transactions,
            size,
            dateToValue,
            dateFromValue,
            dateFormatter,
            nextPageKey,
            prevPageKey,
            api,
            getTransactions,
            getPrevPage,
            getNextPage
        }
    },
    async mounted() {
        await this.getTransactions();
    },
    methods: {
        formatDate(date: string) {
            return format(new Date(date), 'hh:mmaa E d-MMM-yy')
        }
    },
});
</script>