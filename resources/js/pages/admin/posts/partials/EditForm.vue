<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

import ContentBlocks from '@/pages/admin/posts/partials/ContentBlocks.vue';
import ImageDialog from '@/pages/admin/posts/partials/ImageDialog.vue';
import TagSelect from '@/pages/admin/posts/partials/TagSelect.vue';

import { v4 as uuidv4 } from 'uuid';
import { Categories} from '@/types';

import { ContentBlock, MediaItem, PostFormData } from '@/types';
import SlugGenerator from '@/pages/admin/posts/partials/SlugGenerator.vue';
import DataPicker from '@/pages/admin/posts/partials/DataPicker.vue';



const props = defineProps<{
    post?: {
        title: string;
        content: any[];
        featured_image_id: number | null;
        category_id: number;
        tag_id: number;
    };
    categories: Categories[];
    tags?: { id: number; name: string }[];
    type: string;
}>();


type PostForm = Partial<PostFormData> & {
    [key: string]: any; // Index signature za Inertia useForm
};

const form = useForm<PostForm>({
    title: props.post?.title ?? '',
    content: (props.post?.content ?? [
        {
            id: uuidv4(),
            type: 'paragraph',
            text: '',
        },
    ]) as ContentBlock[],
    featured: false,
    featured_image_id: props.post?.featured_image_id ?? undefined,
    category_id: props.post?.category_id ?? undefined,
    tag_id: props.post?.tag_id ?? undefined,
    tags: [],
    slug: '',
    status: 'draft',
    published_at: new Date(),
});

const dialogOpen = ref(false);
const selectedImage = ref<MediaItem>();
const featuredImage = ref<MediaItem>();
function handleSubmit() {
    if (props.type === 'create') {
        form.post(route('admin.posts.store'), {
            onSuccess: () => form.reset(),
        });
    }
}

const slugLoading = ref<boolean>(false);
const loadSlug = () => {
    if (form.title) {
        slugLoading.value = true;
    }
}



watch(selectedImage, (img) => {
    if (img) {
        featuredImage.value = img;
        form.featured_image_id = img.id;
    }
});
</script>

<template>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <!-- Lijeva kolona -->
        <div class="space-y-4 md:col-span-3">

            <input v-model="form.title" @blur="loadSlug" class="w-full border-b p-1 text-3xl font-semibold" placeholder="Naslov" type="text" />

            <p class="text-sm h-3">
            <SlugGenerator
                v-if="form.title"
                :title="form.title"
                :categories="categories"
                :selected_category_id="form.category_id ?? 1"
                v-model:slug="form.slug"
                v-model:slugLoading="slugLoading"
            />
            </p>

            <ContentBlocks v-model:content="form.content" />
        </div>

        <!-- Desna kolona -->
        <div class="space-y-4 md:col-span-1">

            <div class="rounded border p-3">
                <div class="flex items-center gap-x-3">
                    <label for="hs-basic-with-description-unchecked" class="relative inline-block w-11 h-6 cursor-pointer">
                        <input type="checkbox" v-model="form.featured" id="hs-basic-with-description-unchecked" class="peer sr-only">
                        <span class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-blue-600 dark:bg-neutral-700 dark:peer-checked:bg-blue-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                        <span class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                    </label>
                    <label for="hs-basic-with-description-unchecked" class="text-sm text-gray-500 dark:text-neutral-400">Istaknut članak</label>
                </div>
            </div>

            <div class="w-full rounded border p-3">
                <h3 class="mb-2 text-sm font-semibold">Istaknuta slika</h3>
                <div v-if="featuredImage" class="mb-2" @click="dialogOpen = true">
                    <img :src="featuredImage.media[0].thumb_url" alt="" class="aspect-video cursor-pointer rounded object-cover" />
                </div>
                <div v-else class="mb-2" @click="dialogOpen = true">
                    <div class="bg-muted/50 hover:bg-muted aspect-video cursor-pointer rounded object-cover transition-colors">
                        <div class="text-muted-foreground/50 flex h-full w-full flex-col items-center justify-center gap-2">
                            <svg
                                class="h-8 w-8"
                                fill="none"
                                height="24"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                width="24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="3" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>
                            <span class="text-sm font-medium">Odaberi istaknutu sliku</span>
                        </div>
                    </div>
                </div>
                <ImageDialog v-model:open="dialogOpen" v-model:selected="selectedImage" />
            </div>

            <!-- Kategorije -->
            <div class="rounded border p-3">
                <Select v-model="form.category_id">
                    <SelectTrigger>
                        <SelectValue placeholder="Odaberi kategoriju" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id"> {{ cat.name }} </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>

            <div class="rounded border p-3">
                <TagSelect v-model:tags="form.tags!" />
            </div>

            <div class="w-full rounded border p-3">
                <DataPicker v-model:published_at="form.published_at" />
            </div>

            <Button class="btn btn-primary w-full" type="submit" @click="handleSubmit">Sačuvaj</Button>
        </div>
    </div>
</template>
