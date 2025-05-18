<script lang="ts" setup>
import { ref } from 'vue';

import { Button } from '@/components/ui/button';

import RichTextBlock from '@/pages/admin/posts/partials/RichTextBlock.vue';
import ImageBlock from '@/pages/admin/posts/partials/ImageBlock.vue';

import { v4 as uuidv4 } from 'uuid';
import draggable from 'vuedraggable';

import { ContentBlock } from '@/types';

const content = defineModel<ContentBlock[]>('content', {
    default: () => [],
});

const activeDialogId = ref<string | null>(null);

// Dio za doavanje novog bloka
const show = ref(false);
let hoverTimeout: any = null;

function openMenu() {
    clearTimeout(hoverTimeout);
    show.value = true;
}

function closeMenu() {
    // Mali delay da korisnik može da pređe sa dugmeta na meni
    hoverTimeout = setTimeout(() => {
        show.value = false;
    }, 100);
}

function addContentBlock(type: ContentBlock['type']) {
    content.value = [
        ...(content.value ?? []),
        {
            id: uuidv4(),
            type: type,
            ...(type === 'paragraph' && { text: '' }),
            ...(type === 'heading' && { text: '' }),
            ...(type === 'image' && { url: '', alt: '' }),
            ...(type === 'video' && { url: '', provider: 'youtube' }),
        } as ContentBlock,
    ];
}

function openDialog(id: string) {
    activeDialogId.value = id;
}

function closeDialog() {
    activeDialogId.value = null;
}

function removeBlock(index: number) {
    content.value = [...content.value.slice(0, index), ...content.value.slice(index + 1)];
}

function moveBlockUp(index: number) {
    if (index > 0) {
        const newContent = [...content.value];
        const temp = newContent[index];
        newContent[index] = newContent[index - 1];
        newContent[index - 1] = temp;
        content.value = newContent;
    }
}

function moveBlockDown(index: number) {
    if (index < content.value.length - 1) {
        const newContent = [...content.value];
        const temp = newContent[index];
        newContent[index] = newContent[index + 1];
        newContent[index + 1] = temp;
        content.value = newContent;
    }
}
</script>

<template>
    <draggable v-model="content" chosen-class="sortable-chosen" class="space-y-4" ghost-class="sortable-ghost" handle=".drag-handle" item-key="id">
        <template #item="{ element: block, index }">
            <div class="group relative mb-4 rounded-lg border bg-white p-4 shadow-sm transition-all duration-200">
                <!--                dugmici za pomeranje gore ili dolje-->
                <div class="absolute top-9 right-3 z-30 flex flex-col gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                    <button
                        :disabled="index === 0"
                        aria-label="Pomeri blok gore"
                        class="text-gray-400 hover:text-gray-600"
                        @click="moveBlockUp(index)"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </button>
                    <button
                        :disabled="index === content.length - 1"
                        aria-label="Pomeri blok dole"
                        class="text-gray-400 hover:text-gray-600"
                        @click="moveBlockDown(index)"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <button
                    aria-label="Promijeni redoslijed"
                    class="drag-handle absolute top-2 left-2 z-30 cursor-move text-gray-400 opacity-0 transition-opacity group-hover:opacity-100 hover:text-gray-600"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </button>

                <button
                    aria-label="Ukloni blok"
                    class="absolute top-2 right-2 z-20 cursor-auto rounded-full bg-red-500/50 p-1 text-white opacity-0 transition-opacity group-hover:opacity-100"
                    type="button"
                    @click="removeBlock(index)"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </button>

                <!-- Paragraph -->
                <div v-if="block.type === 'paragraph'">
                    <RichTextBlock v-if="block.type === 'paragraph'" v-model:content="block.text" />
                </div>

                <div v-if="block.type === 'image'">
                    <ImageBlock
                        v-if="block.type === 'image'"
                        :id="block.id"
                        v-model:alt="block.alt"
                        v-model:url="block.url"
                        :block-data="block"
                        :open="activeDialogId === block.id"
                        @update:open="(val) => (val ? openDialog(block.id) : closeDialog())"
                    />
                </div>

                <div v-if="block.type === 'heading'">
                    <h4 class="text-2xl font-semibold">Heading</h4>
                </div>
            </div>
        </template>
    </draggable>

    <div class="flex w-full justify-end">
        <div class="relative flex">
            <Button
                class="bg-background border-input hover:bg-accent text-foreground ml-4 flex items-center gap-1 rounded-md border px-2 py-1.5 text-sm font-semibold shadow-sm transition"
                @mouseenter="openMenu"
                @mouseleave="closeMenu"
            >
                Dodaj blok
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </Button>
            <div
                v-if="show"
                class="border-border bg-popover animate-in fade-in-0 slide-in-from-right-2 absolute top-0 right-full z-20 flex space-x-2 rounded-md border shadow-lg"
                @mouseenter="openMenu"
                @mouseleave="closeMenu"
            >
                <div
                    class="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center rounded-l-md px-3 py-2 text-sm transition"
                    @click="addContentBlock('paragraph')"
                >
                    Paragraf
                </div>
                <div
                    class="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center px-3 py-2 text-sm transition"
                    @click="addContentBlock('heading')"
                >
                    Naslov
                </div>
                <div
                    class="hover:bg-accent hover:text-accent-foreground flex cursor-pointer items-center rounded-r-md px-3 py-2 text-sm transition"
                    @click="addContentBlock('image')"
                >
                    Slika
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sortable-ghost {
    opacity: 0.5;
    background: #f8fafc;
    border: 2px dashed #94a3b8;
}

.sortable-chosen {
    transform: scale(1.02);
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
</style>
