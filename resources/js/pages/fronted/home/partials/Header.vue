<script setup lang="ts">


import { PostShow } from '@/types';
import { Link } from '@inertiajs/vue3';

defineProps<{
    featured_post: PostShow,
}>()

</script>

<template>
    <!-- Hero -->


    <div class="h-screen w-full mx-auto">

        <div class="h-[85vh] relative">


            <img class="bg-gray-100 absolute inset-0 size-full object-cover" :src="featured_post.image.url" :alt="featured_post.image.alt">

            <div class="absolute inset-0 bg-black/30"></div>

            <div class="relative z-10 size-full max-w-xl lg:max-w-4xl  mx-auto flex flex-col justify-center items-center">



                <h1 class="mx-4 mt-4 font-semibold text-4xl sm:text-4xl lg:text-5xl lg:mt-auto text-gray-200">
                    <span class="text-shadow-xs">{{featured_post.title}}</span>
                </h1>

                <!-- Button Group -->
                <div class="mt-5 md:mt-10 mb-5">
                    <div class="flex flex-wrap justify-center gap-5">
                        <Link class="text-sm md:text-base text-white underline underline-offset-4 hover:text-white/80 focus:outline-hidden focus:text-white/80"
                              :href="'/'+featured_post.category.slug">
                            kategorija: {{featured_post.category.name}}
                        </Link>

                        <span class="text-sm md:text-base text-white underline underline-offset-4 hover:text-white/80 focus:outline-hidden focus:text-white/80">
                            autor: {{featured_post.author.name}}
                        </span>
                        <span class="text-sm md:text-base text-white underline underline-offset-4 hover:text-white/80 focus:outline-hidden focus:text-white/80">
                            objavljeno {{featured_post.published_at}}
                        </span>
                    </div>
                </div>
                <!-- End Button Group -->
            </div>
        </div>
    </div>
    <!-- End Hero -->
</template>
