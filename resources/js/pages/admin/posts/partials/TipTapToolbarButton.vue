<script setup lang="ts">
defineProps<{
    label?: string
    active?: boolean
    disabled?: boolean
    onClick?: () => void
}>()
</script>

<template>
    <button
        type="button"
        class="px-2 py-1 rounded-md border text-sm flex items-center justify-center transition
           focus:outline-none focus:ring-1 ring-offset-1
           disabled:opacity-50 disabled:cursor-not-allowed
           hover:bg-gray-200"
        :class="[
      active ? 'bg-gray-900 text-white border-gray-800' : 'bg-white text-gray-800 border-gray-300',
    ]"
        :disabled="disabled"
        @click="onClick"
    >
        <slot>
            {{ label }}
        </slot>
    </button>
</template>
