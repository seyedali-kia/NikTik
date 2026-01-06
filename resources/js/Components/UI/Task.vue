<script setup>
import Button from './button.vue';

defineProps({
    task: {
        type: Object,
        required: true
    },
    showMarkAsDone: {
        type: Boolean,
        default: true
    },
    showEdit: {
        type: Boolean,
        default: true
    }
});


</script>


<template>
    <div class="min-w-100 p-6 bg-white ring-3 ring-gray-400 rounded-xl shadow-md gap-4 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800">{{ task.title }}</h3>
        <p class="text-gray-600">{{ task.description }}</p>
        <div class="flex justify-between items-center">
            <div class="w-48 flex justify-between items-center ">
                <span class="text-sm text-gray-500">برآورد: {{ task.estimation ?? '-' }} </span>
                <span :class="{
                    'bg-yellow-200 text-yellow-800': task.status === 'todo',
                    'bg-blue-200 text-blue-800': task.status === 'doing',
                    'bg-green-200 text-green-800': task.status === 'done',
                }" class="px-3 py-1 rounded-full text-sm font-medium">
                    {{ task.status === 'todo' ? 'انجام نشده' : task.status === 'doing' ? 'در حال انجام' : 'انجام شده' }}
                </span>
            </div>
            <div class="flex gap-4">
                <Button v-if="showEdit && task.status !== 'done'" label="ویرایش تسک"
                    @click="$emit('edit-task', task)" />
                <button v-else
                    class="cursor-pointer disabled:cursor-not-allowed py-2 px-4 rounded-[999px] flex justify-center items-center
                                bg-red-100 hover:bg-red-200 focus:bg-red-300 disabled:bg-gray-100
                                text-red-600 hover:text-red-700 focus:text-red-800 disabled:text-gray-400
                                ring-[1.50px] ring-red-300 hover:ring-red-400 focus:ring-red-500 disabled:ring-gray-300"
                    @click="$emit('delete-task', task.id)">
                    حذف
                </button>
                <Button v-if="showMarkAsDone && task.status === 'doing'" label="انجام شد"
                    @click="$emit('complete-task', task.id)" />
                <Button v-else-if="showMarkAsDone && task.status === 'todo'" label="شروع"
                    @click="$emit('start-task', task.id)" />
            </div>

        </div>
    </div>
</template>