<script setup lang="ts">
import type { BreadcrumbItem, Post, Categories, Tag } from '@/types';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import PostForm from '@/pages/admin/posts/partials/PostForm.vue';

const props = defineProps<{
    post: Post;
    categories: Categories[];
    tags: Tag;
}>();




const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Uredi članak',
        href: '/admin/posts/' + props.post?.id + '/edit',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Izmijeni članak" />
        <div class="p-4">
            <h1 class="mb-4 text-lg font-semibold">Izmijeni</h1>
            <PostForm :post="props.post" :categories="categories" :type="'edit'" />
        </div>
    </AppLayout>
</template>
