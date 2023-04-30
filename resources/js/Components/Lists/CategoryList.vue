<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="my-6 overflow-hidden bg-white rounded-md shadow">
            <table class="w-full text-left border-collapse">
                <thead class="border-b">
                    <tr>
                        <th class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800">
                            Name
                        </th>
                        <th class="px-5 py-3 text-sm font-medium text-gray-100 uppercase bg-indigo-800">
                            Parent Category
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories" :key="category.remoteId" class="hover:bg-gray-200">
                        <td class="px-6 py-4 text-lg text-gray-700 border-b">
                            {{ category.name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 border-b">
                            {{ category.parentId }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Category } from '../../api/index';
import ApiService from '../../api/index';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';


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
    data() {
        return {
            categories: [] as Category[],
        };
    },
    async mounted() {
        await this.fetchCategories();
    },
    methods: {
        async fetchCategories(params: PageParams = {}) {
            try {
                const api = new ApiService();
                const response = await api.sendRequest('categories', 'GET', params);
                this.categories = response.data;
            } catch (error) {
                console.error(error);
            }
        },
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