<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/UI/button.vue';
import Task from '@/Components/UI/Task.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    tasks: {
        type: Array,
        required: true
    }
})

const form = useForm({
    title: null,
    description: null,
    estimation: null,
    status: null,
});

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};
const closeModal = () => {
    form.reset();
    showModal.value = false;
};

const submitform = () => {
    form.post(route('task.store'), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
        onError: (errors) => {
            console.log(errors);
        }
    });


};



</script>

<template>

    <Head title="Tasks" />

    <AuthenticatedLayout dir="rtl">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                تسک‌ها
            </h2>
        </template>

        <div class="p-12 gap-4 flex flex-col">
            <Task v-for="task in tasks" :key="task.id" :task="task">
            </Task>
            <Button label="ساخت تسک جدید" @click="openModal" />
            <Modal :show="showModal" @close="closeModal">
                <div class="flex flex-col gap-4 p-14">
                    <h1 class="text-[20px] font-bold">ساخت تسک جدید</h1>
                    <h1 class="text-[16px] ">عنوان</h1>
                    <input v-model="form.title" type="text" placeholder="عنوان تسک"
                        class="p-2 border border-gray-300 rounded" />
                    <p v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</p>
                    
                    <h1 class="text-[16px] ">توضیحات</h1>
                    <textarea v-model="form.description" placeholder="توضیحات تسک"
                        class="p-2 border border-gray-300 rounded"></textarea>
                    <p v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</p>
                    
                    <h1 class="text-[16px] ">برآورد سختی</h1>
                    <select v-model="form.estimation" class="p-2 border border-gray-300 rounded">
                        <option value="1">1</option>
                        <option value="3">3</option>
                        <option value="5">5</option>
                        <option value="8">8</option>
                        <option value="13">13</option>
                    </select>
                    <p v-if="form.errors.estimation" class="text-red-500 text-sm">{{ form.errors.estimation }}</p>
                    
                    <h1 class="text-[16px] ">وضعیت</h1>
                    <select v-model="form.status" class="p-2 border border-gray-300 rounded">
                        <option value="" disabled>وضعیت تسک</option>
                        <option value="todo">برای انجام</option>
                        <option value="in_progress">در حال انجام</option>
                    </select>
                    <p v-if="form.errors.status" class="text-red-500 text-sm">{{ form.errors.status }}</p>
                    <div class="justify-end inline-flex gap-4">
                        <Button label="انصراف" @click="closeModal" />
                        <Button label="ایجاد تسک" @click="submitform" />
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
