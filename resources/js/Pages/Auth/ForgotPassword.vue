<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        
        <h1
            class="text-xl font-semibold leading-tight text-gray-800 text-center mb-4"
        >
            فراموشی رمز عبور
        </h1>

        <div class="mb-4 text-sm text-gray-600">
            رمز عبورتو فراموش کردی؟ اشکالی نداره!
ایمیلت رو وارد کن تا لینک بازیابی برات بفرستیم و بتونی یه رمز جدید بذاری.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="ایمیل" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    ارسال ایمیل بازنشانی رمز
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
