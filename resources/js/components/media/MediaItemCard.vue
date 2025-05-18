<script setup lang="ts">
import type { MediaItem } from '@/types'
defineProps<{
    item: MediaItem
}>()
</script>

<template>
    <div class="cursor-pointer border rounded hover:ring-2 ring-primary overflow-hidden">
        <img :src="item.media[0].thumb_url ?? item.media[0].url" class="object-cover w-full h-32" :alt="item.title"/>
        <div class="p-2 text-sm truncate">{{ item.title }}</div>
    </div>
</template>

