<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import type { MediaFile } from '@/types'

const emit = defineEmits<{
    (e: 'select', image: { id: number; url: string }): void
}>()

const images = ref<MediaFile[]>([])
const loading = ref(false)

async function loadImages() {
    loading.value = true
    const response = await axios.get('/media-manager')
    images.value = response.data.flatMap((item: any) =>
        item.media.map((file: any) => ({
            id: file.id,
            url: file.thumb_url,
        })),
    )
    loading.value = false
}

onMounted(() => {
    loadImages()
})

function handleSelect(image: MediaFile) {
    emit('select', { id: image.id, url: image.url })
}
</script>

<template>
    <div>
        <div v-if="loading" class="text-muted text-sm">Učitavanje...</div>
        <div class="grid grid-cols-3 gap-2">
            <img
                v-for="img in images"
                :key="img.id"
                :src="img.url"
                class="rounded cursor-pointer object-cover h-20 w-full"
                @click="handleSelect(img)"
            />
        </div>
    </div>
</template>
