<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                حذف حساب کاربری
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                پس از حذف حساب، تمامی منابع و داده‌های مربوط به آن به‌صورت دائمی حذف خواهند شد.
                لطفاً قبل از حذف، هر گونه داده‌ای که می‌خواهید نگه دارید را ذخیره کنید.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">حذف حساب</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    آیا از حذف حساب کاربری خود مطمئن هستید؟
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    پس از حذف حساب، تمامی منابع و داده‌های آن به‌صورت دائمی حذف خواهند شد.
                    لطفاً برای تأیید، رمز عبور خود را وارد نمایید تا حذف به‌طور دائم انجام شود.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="رمز عبور"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="رمز عبور"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        انصراف
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        حذف حساب
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
