<script lang="ts" setup>
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline'

import {
    AlignCenter,
    AlignJustify,
    AlignLeft,
    AlignRight,
    Bold,
    Underline as UnderlineIcon,
    CornerDownLeft,
    Heading1,
    Heading2,
    Heading3,
    Heading4,
    Italic,
    List,
    ListOrdered,
    Minus,
    Pilcrow,
    Quote,
    Strikethrough,
} from 'lucide-vue-next';

import { ArrowUturnLeftIcon, ArrowUturnRightIcon } from '@heroicons/vue/24/outline';
import TipTapToolbarButton from './TipTapToolbarButton.vue';

const content = defineModel<string | undefined>('content');


const editor = useEditor({
    content: content.value,
    editorProps: {
        attributes: {
            class: 'w-full prose focus:outline-none px-4 py-2 min-h-[300px]',
        },
    },
    extensions: [
        StarterKit,
        Underline,
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
    ],
    onUpdate: ({editor}) => {
        content.value = editor.getHTML();
    }
});
</script>

<template>
    <div v-if="editor" class="sticky min-w-full top-0 z-10 mb-4 rounded-md bg-white/90 p-3 shadow backdrop-blur-md prose">
        <div class="flex flex-wrap items-center gap-2">
            <!-- Text styles -->
            <div class="flex gap-1">
                <TipTapToolbarButton
                    :active="editor.isActive('bold')"
                    :disabled="!editor.can().chain().focus().toggleBold().run()"
                    :onClick="() => editor?.chain().focus().toggleBold().run()"
                >
                    <Bold class="h-4 w-4" />
                </TipTapToolbarButton>

                <TipTapToolbarButton
                    :active="editor.isActive('italic')"
                    :disabled="!editor.can().chain().focus().toggleItalic().run()"
                    :onClick="() => editor?.chain().focus().toggleItalic().run()"
                >
                    <Italic class="h-4 w-4" />
                </TipTapToolbarButton>

                <TipTapToolbarButton
                    :active="editor.isActive('underline')"
                    :disabled="!editor.can().chain().focus().toggleUnderline().run()"
                    :onClick="() => editor?.chain().focus().toggleUnderline().run()"
                >
                    <UnderlineIcon class="h-4 w-4" />
                </TipTapToolbarButton>

                <TipTapToolbarButton
                    :active="editor.isActive('strike')"
                    :disabled="!editor.can().chain().focus().toggleStrike().run()"
                    :onClick="() => editor?.chain().focus().toggleStrike().run()"
                >
                    <Strikethrough class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>

            <!-- Headings -->
            <div class="flex gap-1">
                <TipTapToolbarButton :active="editor.isActive('paragraph')" :onClick="() => editor?.chain().focus().setParagraph().run()">
                    <Pilcrow class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive('heading', { level: 1 })"
                    :onClick="() => editor?.chain().focus().toggleHeading({ level: 1 }).run()"
                >
                    <Heading1 class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive('heading', { level: 2 })"
                    :onClick="() => editor?.chain().focus().toggleHeading({ level: 2 }).run()"
                >
                    <Heading2 class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive('heading', { level: 3 })"
                    :onClick="() => editor?.chain().focus().toggleHeading({ level: 3 }).run()"
                >
                    <Heading3 class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive('heading', { level: 4 })"
                    :onClick="() => editor?.chain().focus().toggleHeading({ level: 4 }).run()"
                >
                    <Heading4 class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>

            <!-- Lists & block -->
            <div class="flex gap-1">
                <TipTapToolbarButton :active="editor.isActive('bulletList')" :onClick="() => editor?.chain().focus().toggleBulletList().run()">
                    <List class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton :active="editor.isActive('orderedList')" :onClick="() => editor?.chain().focus().toggleOrderedList().run()">
                    <ListOrdered class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton :active="editor.isActive('blockquote')" :onClick="() => editor?.chain().focus().toggleBlockquote().run()">
                    <Quote class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>

            <!-- Align -->
            <div class="flex gap-1">
                <TipTapToolbarButton
                    :active="editor.isActive({ textAlign: 'left' })"
                    :onClick="() => editor?.chain().focus().setTextAlign('left').run()"
                >
                    <AlignLeft class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive({ textAlign: 'center' })"
                    :onClick="() => editor?.chain().focus().setTextAlign('center').run()"
                >
                    <AlignCenter class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive({ textAlign: 'right' })"
                    :onClick="() => editor?.chain().focus().setTextAlign('right').run()"
                >
                    <AlignRight class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton
                    :active="editor.isActive({ textAlign: 'justify' })"
                    :onClick="() => editor?.chain().focus().setTextAlign('justify').run()"
                >
                    <AlignJustify class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>

            <!-- Insert -->
            <div class="flex gap-1">
                <TipTapToolbarButton :onClick="() => editor?.chain().focus().setHorizontalRule().run()">
                    <Minus class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton :onClick="() => editor?.chain().focus().setHardBreak().run()">
                    <CornerDownLeft class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>

            <!-- Undo / Redo -->
            <div class="flex gap-1">
                <TipTapToolbarButton :disabled="!editor.can().chain().focus().undo().run()" :onClick="() => editor?.chain().focus().undo().run()">
                    <ArrowUturnLeftIcon class="h-4 w-4" />
                </TipTapToolbarButton>
                <TipTapToolbarButton :disabled="!editor.can().chain().focus().redo().run()" :onClick="() => editor?.chain().focus().redo().run()">
                    <ArrowUturnRightIcon class="h-4 w-4" />
                </TipTapToolbarButton>
            </div>
        </div>
    </div>

    <EditorContent :editor="editor" class="rounded-lg border border-gray-300 bg-white shadow-sm" />
</template>
