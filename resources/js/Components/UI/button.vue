<script setup>
import { computed } from 'vue'

// props for isDisable to disable and iconStyle to set the style of the icon button
const props = defineProps({
    isDisable: {
        type: Boolean,
        default: false
    },
    label: {
        type: String,
        required: true
    }
})

const emit = defineEmits(['click'])

const onClick = () => emit('click')


</script>


<template>

    <div class="h-16 inline-flex justify-center items-center">
        <button class="cursor-pointer disabled:cursor-not-allowed py-2 px-4 rounded-[999px] flex justify-center items-center
        bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-300 disabled:bg-gray-100
        text-indigo-600 hover:text-indigo-700 focus:text-indigo-800 disabled:text-gray-400
        ring-[1.50px] ring-indigo-300 hover:ring-indigo-400 focus:ring-indigo-500 disabled:ring-gray-300" :class='colorClass'
            :disabled="props.isDisable" @click="onClick">
            {{ label }}
        </button>
    </div>

</template>


<!-- how to use:
<IconButton is-disable icon-style="standard/filled/tonal/outline" @click="yourFunction">
    <svg>...</svg>  // your icon svg here
</IconButton> 
-->