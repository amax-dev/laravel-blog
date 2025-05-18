<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookCheck, BookOpen, Bot, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';


const page = usePage();

const mainNavItems: NavItem[] =  [
    {
        title: 'Kontrolna tabla',
        href: '/admin',
        icon: LayoutGrid,
        isActive: page.url === '/admin',

    },
    {
        title: 'Članci',
        href: '/admin/posts',
        icon: BookCheck,
        isActive: page.url.startsWith('/admin/posts'),
        items: [
            { title: 'Svi članci', href: '/admin/posts' },
            { title: 'Dodaj novi', href: '/admin/posts/create' },
        ],
    },
    {
        title: 'Media fajlovi',
        href: '/admin/media',
        icon: Bot,
        isActive: page.url.startsWith('/admin/media'),
        items: [
            { title: 'Biblioteka', href: '/admin/media' },
            { title: 'Dodaj novi', href: '/admin/media/create' },
        ],

    }
]

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin.dashboard')">
                            <AppLogo/>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
