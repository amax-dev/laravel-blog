<script setup lang="ts">
import MediaGrid from '@/components/media/MediaGrid.vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem} from '@/types';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bibliteka',
        href: '/admin/media',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Biblioteka" />
    <div class="p-4">
        <h1 class="text-xl font-semibold mb-4">Upravljanje media fajlovima</h1>
        <MediaGrid/>
    </div>
    </AppLayout>
</template>
