<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';

import { Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import MediaUpload from '@/components/media/MediaUpload.vue';
import MediaGrid from '@/components/media/MediaGrid.vue';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { MediaFile, MediaItem } from '@/types';
import { Input } from '@/components/ui/input';

// defineModel zamjenjuje emit
// const model = defineModel<MediaItem | null>('selected');
const open = defineModel<boolean>('open');
const url = defineModel<string>('url');
const alt = defineModel<string>('alt');
const props = defineProps<{
    id: string,
    blockData?: { // Dodajte novi prop
        url?: string
        alt?: string
    }
}>()

const mediaItems = ref<any[]>([]);

// fetch iz posebne JSON rute
async function fetchMedia() {
    const response = await axios.get(route('media.list'));
    mediaItems.value = response.data;
}

// kad se modal otvori, pokupi podatke


const selected = ref<MediaItem>();

// odabir slike
function handleSelect(item: MediaItem) {
    selected.value = item;
    alt.value = selected.value?.title;
}

function handleUploaded(item: MediaItem) {
    selected.value = item;
    alt.value = selected.value?.title;
}

function addToPost() {
    // console.log(selected.value)
    // model.value = selected.value;

    open.value = false;

    url.value = selected.value?.media[0]?.post_img_url;
    selected.value = undefined;
}

function openDialog(){
    open.value = true;


}

watch(open, async (val) => {
    if (val) {
        await fetchMedia()

        // console.log(props.blockData)

        // Pronađi medij koji odgovara URL-u iz bloka
        if (props.blockData?.url) {
            selected.value = mediaItems.value.find(item =>
                item.media?.some((media: MediaFile)=> media.post_img_url === props.blockData?.url)
            )
        }
    }
})





</script>

<template>


    <Dialog v-model:open="open">
        <DialogContent class="my-auto max-h-10/12 min-h-10/12 sm:max-w-11/12 pointer-events-auto" :inert="false">
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
                                <MediaGrid :media="mediaItems" @select="handleSelect" ref="mediaGrid"/>
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
                        <Input class="my-2" v-model="alt" />
                    </div>
                    <div v-else class="text-sm text-gray-400 italic">Nijedna slika nije odabrana.</div>
                    <Button v-if="selected" :size="'sm'" @click="addToPost">Dodaj sliku u artikl</Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>

    <div class="w-full">
        <div class="bg-muted/50 hover:bg-muted aspect-video cursor-pointer rounded object-cover transition-colors">
            <div v-if="url" class="mb-2" @click="openDialog()">
                <img :src="url" alt="" class="aspect-video cursor-pointer rounded object-cover" />
                <p class="text-sm font-light italic">{{ alt }}</p>
            </div>

            <div v-else @click="openDialog()" class="text-muted-foreground/50 flex h-full w-full flex-col items-center justify-center gap-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="h-8 w-8"
                >
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                    <circle cx="9" cy="9" r="2" />
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                </svg>
                <span class="text-sm font-medium">Odaberi sliku za ovaj blok</span>
            </div>
        </div>
    </div>
</template>
