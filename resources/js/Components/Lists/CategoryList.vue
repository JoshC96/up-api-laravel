<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center sm:justify-center">
            <table class="w-full text-sm border-separate border-spacing-y-2">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tr-class" v-for="category in categories" :key="category.remoteId">
                        <td class="td-class text-center">{{ category.name }}</td>
                        <td class="td-class text-center">{{ category.parentId }}</td>
                        <td class="td-class text-center">
                            <SecondaryButton>
                                View
                            </SecondaryButton>
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