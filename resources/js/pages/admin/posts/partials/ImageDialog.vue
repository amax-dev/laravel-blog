<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';

import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogClose, DialogDescription } from '@/components/ui/dialog';
import MediaUpload from '@/components/media/MediaUpload.vue';
import MediaGrid from '@/components/media/MediaGrid.vue';
import { Button } from '@/components/ui/button';
import { Tabs, TabsList, TabsContent, TabsTrigger } from '@/components/ui/tabs';
import { MediaItem } from '@/types';

// defineModel zamjenjuje emit
const model = defineModel<MediaItem | null>('selected');
const open = defineModel<boolean>('open');

const mediaItems = ref<any[]>([]);


// fetch iz posebne JSON rute
async function fetchMedia() {
    const response = await axios.get(route('media.list'));
    mediaItems.value = response.data;
}

// kad se modal otvori, pokupi podatke
watch(open, (val) => {
    if (val) fetchMedia();
});

const selected = ref<MediaItem | null | undefined>(undefined);

// odabir slike
function handleSelect(item: MediaItem) {
    selected.value = item;
}

function handleUploaded(item: MediaItem) {
    selected.value = item;
}

function attachToPost() {
    model.value = selected.value;
    open.value = false;
}

watch(model, (val) => {
    // console.log('model changed in ImageDialog:', val);
    selected.value = val; // sinhronizuj lokalni selected sa model vrednošću
}, { immediate: true });


</script>

<template>
    <!--    <Button @click="open=true">Izaberi</Button>-->
    <Dialog v-model:open="open">
        <DialogContent class="my-auto max-h-10/12 min-h-10/12 sm:max-w-11/12">
            <DialogHeader>
                <DialogTitle>Izaberi sliku</DialogTitle>
                <DialogDescription>Odaberi jednu od postojećih slika ili dodaj novu.</DialogDescription>
                <DialogClose />
            </DialogHeader>

            <div class="flex w-full">
                <div class="w-3/4">
                    <Tabs default-value="upload" class="flex h-full flex-1 flex-col">
                        <TabsList class="mb-4">
                            <TabsTrigger value="upload">Otpremi novu</TabsTrigger>
                            <TabsTrigger value="select">Izaberi sliku</TabsTrigger>
                        </TabsList>

                        <TabsContent value="upload" class="flex-1 overflow-hidden">
                            <div class="h-[60vh] overflow-y-auto p-3">
                                <MediaUpload @uploaded="handleUploaded" />
                            </div>
                        </TabsContent>

                        <TabsContent value="select" class="flex-1 overflow-hidden" @openAutoFocus="fetchMedia">
                            <div class="h-[60vh] overflow-y-auto p-3">
                                <MediaGrid :media="mediaItems" @select="handleSelect" />
                            </div>
                        </TabsContent>
                    </Tabs>
                </div>
                <div class="w-1/4 border-l pl-6">
                    <h4 class="mb-2 text-sm font-medium">Odabrana slika:</h4>

                    <div v-if="selected?.media[0]?.thumb_url">
                        <img :src="selected?.media[0]?.thumb_url" class="max-h-40 max-w-full rounded object-contain" alt="Odabrana slika" />
                        <p class="mt-2 text-xs text-gray-500">{{ selected.id }} {{ selected.title }}</p>
                        <p class="mt-2 text-xs text-gray-500">Veličina: {{ selected.media[0].human_size }}</p>
                        <p class="mt-2 text-xs text-gray-500">Dimenzije: {{ selected.media[0].width }} x {{ selected.media[0].height }} px</p>
                    </div>
                    <div v-else class="text-sm text-gray-400 italic">Nijedna slika nije odabrana.</div>
                    <Button v-if="selected" :size="'sm'" @click="attachToPost">Dodaj istaknutu sliku u artikl</Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
