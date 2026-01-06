<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const flash = computed(() => page.props.flash)

const visible = ref(false)

watch(
  flash,
  (val) => {
    if (!val?.message) return
    visible.value = true
    setTimeout(() => (visible.value = false), 3000)
  },
  { immediate: true }
)

const classes = computed(() => {
  if (!flash.value) return ''
  return flash.value.type === 'success'
    ? 'bg-green-100 text-green-700 ring-green-300'
    : 'bg-red-100 text-red-700 ring-red-300'
})
</script>

<template>
  <div
    v-if="visible && flash?.message"
    class="fixed top-4 left-1/2 -translate-x-1/2 z-50 rounded-xl shadow"
  >
    <div :class="classes" class="px-4 py-3 rounded-xl">
      {{ flash.message }}
    </div>
  </div>
</template>
