import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    items?: { title: string; href: string}[];
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface MediaItem  {
    id: number | undefined;
    title?: string;
    media: {
        id: number;
        url: string;
        thumb_url: string;
        human_size: string;
        post_img_url: string;
        width: string;
        height: string;
    }[];
}

export interface MediaFile {
    id: number;
    url: string;
    thumb_url: string | null;
    post_img_url?: string | null;
    mime_type?: string;
    extension?: string;
    size?: number;
    human_size?: string;
    file_name?: string;
    collection_name?: string;
    width?: string;
    height?: string;
}

export interface PostFormData {
    title: string;
    slug: string;
    content: ContentBlock[];
    featured_image_id: number;
    category_id: number;
    tag_id: number;
    tabs: [];
    featured: boolean;
    author_id: number;
    categories?: number[];
    tags?: {
        id?: number
        name: string
    }[];
    status?: string;
    publish_date?: string;
    publish_time?: string;
}

export interface Post extends PostFormData{
    id: number;
    author: {
        id: number;
        name: string;
    }
    published: boolean;
    published_at: string;
    category: {id: number, name: string, slug: string};
    tags: {id: number, name: string, slug: string}[];
    featured_image: {
        id: number;
        url: string;
        thumb_url: string;
        human_size: string;
        post_img_url: string;
        width: string;
        height: string;
    }
}

export interface PostPagination extends Post{
    data: Post[];
    current_page: number;
    from: number;
    to: number;
    last_page: number;
    per_page: number;
    total: number;
    prev_page_url: string;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string;
    links: {
            url: string;
            label: string;
            active: boolean;
    }
}

export interface PostShow {
    id: number;
    title: string;
    slug: string;
    featured: boolean;
    created_at: string;
    published_at: string;
    author: {
        id: number;
        name: string;
    }
    category: {
        id: number;
        name: string;
        slug: string;
    }
    image: {
        id: number;
        alt: string;
        url: string;
        mime: string;
        size: string;
        width: number;
        height: number;
    }
}

export type ContentBlock =
| TextContent
| ImageContent
| VideoContent
| DividerContent
// ...dodaj nove tipove po potrebi

interface BaseContent {
    type: string
    id: string // Unique ID za manipulaciju blokovima
}

export interface TextContent extends BaseContent {
    type: 'paragraph' | 'heading' | 'quote'
    text: string
    align?: 'left' | 'center' | 'right'
}

export interface ImageContent extends BaseContent {
    type: 'image'
    url: string
    alt: string
    caption?: string
    width?: number
}

export interface VideoContent extends BaseContent {
    type: 'video'
    url: string
    provider: 'youtube' | 'vimeo' | 'self-hosted'
}

export interface DividerContent extends BaseContent {
    type: 'divider'
    style: 'dashed' | 'solid'
}

interface Tag {
    id?: number
    name: string
}

export interface Categories {
    id: number,
    name: string,
    slug: string,
}
