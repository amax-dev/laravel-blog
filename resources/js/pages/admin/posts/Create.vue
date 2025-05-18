<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3';
import PostForm from '@/pages/admin/posts/partials/PostForm.vue';
import type { BreadcrumbItem, Categories } from '@/types';



defineProps<{
    categories: Categories[],
}>()


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Novi članak',
        href: '/admin/posts/create',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="kreiraj" />
        <div class="p-4">
            <h1 class="text-lg font-semibold mb-4">Dodaj novi članak</h1>
            <PostForm :categories="categories" :type="'create'"/>
        </div>

    </AppLayout>

</template>
