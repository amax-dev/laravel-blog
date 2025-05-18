<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { Progress } from '@/components/ui/progress'
import axios from 'axios';

const fileInput = ref<HTMLInputElement | null>(null)
const file = ref<File | null>(null)
const uploadProgress = ref(0)


const emit = defineEmits<{
    (e: 'uploaded', item: any): void
}>()

const form = useForm({
    title: '',
    description: '',
    file: null as File | null,
})

const URL = window.URL;
function triggerFileInput() {
    fileInput.value?.click()
}

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement
    if (target.files?.length) {
        file.value = target.files[0]
        form.file = file.value
        form.title = file.value.name
    }
}

function handleDrop(e: DragEvent) {
    e.preventDefault()
    if (e.dataTransfer?.files?.length) {
        file.value = e.dataTransfer.files[0]
        form.file = file.value
        form.title = file.value.name
    }
}

const previewUrl = computed(() => {
    if (file.value && file.value.type.startsWith('image/')) {
        return URL.createObjectURL(file.value)
    }
    return null
})

const isVideo = computed(() => file.value?.type.startsWith('video/'))

function handleUpload() {
    if (!file.value) return

    uploadProgress.value = 0

    form.post(route('admin.media.store'), {
        onSuccess: async () => {
            const { data } = await axios.get(route('media.latest')) // ruta vrati poslednji fajl
            // console.log(data);
            emit('uploaded', data) // emituj direktno fajl
            form.reset()
            file.value = null
            uploadProgress.value = 0
        },
        onProgress: (event) => {
            if (event?.total && event.loaded) {
                uploadProgress.value = Math.round((event.loaded / event.total) * 100)
            }
        }
    })
}
</script>

<template>
    <div class="p-6 border rounded-lg shadow-sm space-y-4">
        <!-- Drop zone -->
        <div
            class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:bg-gray-50 transition"
            @click="triggerFileInput"
            @dragover.prevent
            @drop="handleDrop"
        >
            <p class="text-gray-500">
                Klikni za odabir fajla ili prevuci fajl ovdje
            </p>
            <p class="text-sm text-gray-400 mt-1">
                Podržani formati: jpg, png, pdf, mp4...
            </p>
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                @change="handleFileChange"
                accept="image/*,video/*,application/pdf"
            />
        </div>

        <!-- Preview -->
        <div v-if="file" class="mt-4 space-y-2">
<!--            <div class="text-sm text-gray-600">-->
<!--                Odabrani fajl: <strong>{{ file.name }}</strong>-->
<!--            </div>-->

            <div v-if="previewUrl" class="flex space-x-5">
                <div class="w-1/4">
                    <img :src="previewUrl" alt="Preview" class="w-full max-h-64 object-contain rounded-md border" />
                </div>
                <div class="w-3/4">
                    <!-- Title -->
                    <div class="space-y-2 mb-3">
                        <Label for="title">Naslov</Label>
                        <Input
                            v-model="form.title"
                            id="title"
                            :class="{ 'border-red-500': form.errors.title }"
                        />
                        <div v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 mb-3">
                        <Label for="description">Opis</Label>
                        <Textarea
                            v-model="form.description"
                            id="description"
                            rows="3"
                            :class="{ 'border-red-500': form.errors.description }"
                        />
                        <div v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <!-- Progress bar -->
                        <div class="w-2/3">
                            <Progress :value="uploadProgress" />
                            <p class="text-sm text-gray-500 mt-1">Učitavanje: {{ uploadProgress }}%</p>
                        </div>
                        <!-- Submit -->
                        <Button
                            @click="handleUpload"
                            :disabled="!file || form.processing"
                            class=""
                        >
                            {{ form.processing ? 'Učitavanje...' : 'Upload' }}
                        </Button>
                    </div>



                </div>

            </div>

            <div v-else-if="isVideo">
                <video controls class="w-full max-h-64 rounded border">
                    <source :src="URL.createObjectURL(file)" type="video/mp4" />
                    Tvoj pretraživač ne podržava video tag.
                </video>
            </div>
        </div>






    </div>
</template>
