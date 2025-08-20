<script setup lang="ts">

import { Head } from '@inertiajs/vue3'
import MainLayout from '@/layouts/MainLayout.vue';
import Header from '@/pages/fronted/home/partials/Header.vue';
import Blog from '@/pages/fronted/home/partials/Blog.vue';
import Nav from '@/pages/fronted/home/partials/Nav.vue';
import {  PostShow } from '@/types';

defineProps<{
    featured_post: PostShow,
    other_posts: PostShow[],
}>()


</script>

<template>

    <MainLayout>
        <Head title="Home" />


        <Nav/>

        <Header :featured_post="featured_post" />

        <Blog :other_posts="other_posts"/>

    </MainLayout>
</template>
