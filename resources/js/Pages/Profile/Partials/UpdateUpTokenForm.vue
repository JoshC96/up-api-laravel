<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const form = useForm({
    up_bank_token: user.up_bank_token!,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Up Bank Token</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Hi there! To make sure our reports work perfectly with your information, we kindly request that you add your Up Bank API Token.
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Don't worry, your information is safe with us and we value your privacy. Adding the API Token will allow us to provide you with accurate and helpful reports. Thank you!
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update.token'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="uptoken" value="Up API Token" />

                <TextInput
                    id="uptoken"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.up_bank_token"
                    placeholder="up:yeah:abcdefghijklmnopqrstuvwxyz123456789"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.up_bank_token" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
