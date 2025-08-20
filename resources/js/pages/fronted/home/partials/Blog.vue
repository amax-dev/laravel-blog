<script setup lang="ts">

import { Link } from '@inertiajs/vue3';
import { PostShow } from '@/types';

defineProps<{
    other_posts: PostShow[],
}>()

</script>

<template>

    <!-- Card Blog -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">The Blog</h2>
            <p class="mt-1 text-gray-600">See how game-changing companies are making the most of every engagement with Preline.</p>
        </div>
        <!-- End Title -->

        <!-- Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card -->
            <Link v-for="(post, i) in other_posts" :key="i" class="group flex flex-col h-full border border-gray-200 hover:border-transparent hover:shadow-lg focus:outline-hidden focus:border-transparent focus:shadow-lg transition duration-300 rounded-xl p-5"
                :href="'/'+post.category.slug+'/'+post.slug"
            >
                <div class="aspect-w-16 aspect-h-11">
                    <img class="w-full object-cover rounded-xl" :src="post.image.url" alt="Blog Image">
                </div>
                <div class="my-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        {{post.title}}
                    </h3>
                    <p class="mt-5 text-gray-600">
                        {{ post.published_at}}
                    </p>
                </div>
                <div class="mt-auto flex items-center gap-x-3">
                    <img class="size-8 rounded-full" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="Avatar">
                    <div>
                        <h5 class="text-sm text-gray-800">Autor: {{post.author.name}}</h5>
                    </div>
                </div>
            </Link>
            <!-- End Card -->


        </div>
        <!-- End Grid -->

        <!-- Card -->
        <div class="mt-12 text-center">
            <a class="py-3 px-4 inline-flex items-center gap-x-1 text-sm font-medium rounded-full border border-gray-200 bg-white text-blue-600 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" href="#">
                Read more
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Card Blog -->
</template>

