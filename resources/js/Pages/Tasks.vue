<script setup>
import { ref, reactive, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/UI/button.vue';
import Task from '@/Components/UI/Task.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    },
    filters: {
        type: Object,
        required: true
    },
})

const filterForm = reactive({
    status: props.filters?.status ?? '',
    search: props.filters?.search ?? '',
    sort: props.filters?.sort ?? '',
})

watch(
    () => ({ ...filterForm }),
    () => {
        router.get(route('tasks'), {
            status: filterForm.status || null,
            search: filterForm.search || null,
            sort: filterForm.sort || null,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true, // history رو شلوغ نکنه
        })
    },
    { deep: true }
)



const storeform = useForm({
    title: null,
    description: null,
    estimation: null,
    status: null,
});

const isLoading = ref(false);
const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};
const closeModal = () => {
    storeform.reset();
    showModal.value = false;
};

const submitStoreForm = () => {
    isLoading.value = true;
    storeform.post(route('task.store'), {
        onSuccess: () => {
            isLoading.value = false;
            closeModal();
            storeform.reset();
        },
        onError: (errors) => {
            isLoading.value = false;
            console.log(errors);
        }
    });


};


const showEdit = ref(false);

const openEdit = () => {
    showEdit.value = true;
};

const closeEdit = () => {
    showEdit.value = false;
};

const updateform = useForm({
    title: null,
    description: null,
    estimation: null,
    status: null,
    id: null,
});

const submitUpdateForm = () => {
    isLoading.value = true;
    updateform.post(route('task.update', updateform.id), {
        onSuccess: () => {
            isLoading.value = false;
            closeEdit();
            updateform.reset();
        },
        onError: (errors) => {
            isLoading.value = false;
            console.log(errors);
        }
    });

};

const handleEditTask = (task) => {
    updateform.title = task.title;
    updateform.description = task.description;
    updateform.estimation = task.estimation;
    updateform.status = task.status;
    updateform.id = task.id;
    openEdit();
};

const handleStartTask = (taskId) => {
    router.post(route('task.start', taskId), {}, {
        preserveScroll: true,
    });
};

const handleCompleteTask = (taskId) => {
    router.post(route('task.complete', taskId), {}, {
        preserveScroll: true,
    });
};

const handleDeleteTask = (taskId) => {
    isLoading.value = true;
    router.delete(route('task.delete', taskId), {
        onSuccess: () => {
            isLoading.value = false;
            closeEdit();
            updateform.reset();
        },
        onError: (errors) => {
            isLoading.value = false;
            console.log(errors);
        }
    });
};

</script>

<template>

    <Head title="Tasks" />

    <AuthenticatedLayout dir="rtl">
        <template #header>
            <div class="justify-between flex">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    تسک‌ها
                </h2>
                <div class="flex flex-col md:flex-row gap-3 items-start md:items-center">
                    <input v-model="filterForm.search" type="text" placeholder="جستجو در عنوان/توضیحات…"
                        class="p-2 border border-gray-300 rounded w-full md:w-80" />

                    <select v-model="filterForm.status" class="p-2 border border-gray-300 rounded">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="todo">برای انجام</option>
                        <option value="doing">در حال انجام</option>
                        <option value="done">انجام شده</option>
                    </select>

                    <select v-model="filterForm.sort" class="p-2 border border-gray-300 rounded">
                        <option value="newest">جدیدترین</option>
                        <option value="oldest">قدیمی‌ترین</option>
                    </select>
                    <div class="h-16 inline-flex justify-center items-center">
                        <button
                            class="cursor-pointer disabled:cursor-not-allowed py-2 px-4 rounded-[999px] flex justify-center items-center
                                bg-red-100 hover:bg-red-200 focus:bg-red-300 disabled:bg-gray-100
                                text-red-600 hover:text-red-700 focus:text-red-800 disabled:text-gray-400
                                ring-[1.50px] ring-red-300 hover:ring-red-400 focus:ring-red-500 disabled:ring-gray-300"
                            @click="filterForm.status='';filterForm.search='';filterForm.sort=''"
                            :disabled="!filterForm.status && !filterForm.search && !filterForm.sort">
                            حذف فیلترها
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="p-12 gap-4 flex flex-col">
            <Task v-for="task in tasks" :key="task.id" :task="task" @edit-task="handleEditTask(task)"
                @start-task="handleStartTask(task.id)" @complete-task="handleCompleteTask(task.id)"
                @delete-task="handleDeleteTask(task.id)">
            </Task>

            <Modal :show="showEdit" @close="closeEdit">
                <div class="flex flex-col gap-4 p-14">
                    <h1 class="text-[20px] font-bold">ویرایش تسک</h1>
                    <h1 class="text-[16px] ">عنوان</h1>
                    <input v-model="updateform.title" type="text" placeholder="عنوان تسک"
                        class="p-2 border border-gray-300 rounded" />
                    <p v-if="updateform.errors.title" class="text-red-500 text-sm">{{ updateform.errors.title }}</p>

                    <h1 class="text-[16px] ">توضیحات</h1>
                    <textarea v-model="updateform.description" placeholder="توضیحات تسک"
                        class="p-2 border border-gray-300 rounded"></textarea>
                    <p v-if="updateform.errors.description" class="text-red-500 text-sm">{{
                        updateform.errors.description }}</p>

                    <h1 class="text-[16px] ">برآورد سختی</h1>
                    <select v-model="updateform.estimation" class="p-2 border border-gray-300 rounded">
                        <option value="1">1</option>
                        <option value="3">3</option>
                        <option value="5">5</option>
                        <option value="8">8</option>
                        <option value="13">13</option>
                    </select>
                    <p v-if="updateform.errors.estimation" class="text-red-500 text-sm">{{ updateform.errors.estimation
                    }}</p>

                    <h1 class="text-[16px] ">وضعیت</h1>
                    <select v-model="updateform.status" class="p-2 border border-gray-300 rounded">
                        <option value="" disabled>وضعیت تسک</option>
                        <option value="todo">برای انجام</option>
                        <option value="doing">در حال انجام</option>
                    </select>
                    <p v-if="updateform.errors.status" class="text-red-500 text-sm">{{ updateform.errors.status }}</p>
                    <div class="justify-end inline-flex gap-4">
                        <div class="h-16 inline-flex justify-center items-center">
                            <button
                                class="cursor-pointer disabled:cursor-not-allowed py-2 px-4 rounded-[999px] flex justify-center items-center
                                bg-red-100 hover:bg-red-200 focus:bg-red-300 disabled:bg-gray-100
                                text-red-600 hover:text-red-700 focus:text-red-800 disabled:text-gray-400
                                ring-[1.50px] ring-red-300 hover:ring-red-400 focus:ring-red-500 disabled:ring-gray-300"
                                @click="handleDeleteTask(updateform.id)">
                                حذف
                            </button>
                        </div>
                        <Button label="انصراف" @click="closeEdit" :is-disable="isLoading" />
                        <Button label="ذخیره" @click="submitUpdateForm" :is-disable="isLoading" />
                    </div>
                </div>
            </Modal>

            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-transparent">
                <Button label="ساخت تسک جدید" @click="openModal" />
            </div>

            <Modal :show="showModal" @close="closeModal">
                <div class="flex flex-col gap-4 p-14">
                    <h1 class="text-[20px] font-bold">ساخت تسک جدید</h1>
                    <h1 class="text-[16px] ">عنوان</h1>
                    <input v-model="storeform.title" type="text" placeholder="عنوان تسک"
                        class="p-2 border border-gray-300 rounded" />
                    <p v-if="storeform.errors.title" class="text-red-500 text-sm">{{ storeform.errors.title }}</p>

                    <h1 class="text-[16px] ">توضیحات</h1>
                    <textarea v-model="storeform.description" placeholder="توضیحات تسک"
                        class="p-2 border border-gray-300 rounded"></textarea>
                    <p v-if="storeform.errors.description" class="text-red-500 text-sm">{{ storeform.errors.description
                        }}</p>

                    <h1 class="text-[16px] ">برآورد سختی</h1>
                    <select v-model="storeform.estimation" class="p-2 border border-gray-300 rounded">
                        <option value="1">1</option>
                        <option value="3">3</option>
                        <option value="5">5</option>
                        <option value="8">8</option>
                        <option value="13">13</option>
                    </select>
                    <p v-if="storeform.errors.estimation" class="text-red-500 text-sm">{{ storeform.errors.estimation }}
                    </p>

                    <h1 class="text-[16px] ">وضعیت</h1>
                    <select v-model="storeform.status" class="p-2 border border-gray-300 rounded">
                        <option value="" disabled>وضعیت تسک</option>
                        <option value="todo">برای انجام</option>
                        <option value="doing">در حال انجام</option>
                    </select>
                    <p v-if="storeform.errors.status" class="text-red-500 text-sm">{{ storeform.errors.status }}</p>
                    <div class="justify-end inline-flex gap-4">
                        <Button label="انصراف" @click="closeModal" :is-disable="isLoading" />
                        <Button label="ایجاد تسک" @click="submitStoreForm" :is-disable="isLoading" />
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
