<template>
    <div :class="blockClass">
        <editor-content :editor="editor" :class="elementClass('editor')" />

        <div v-if="editor">
            <bubble-menu :class="elementClass('menu')" :tippy-options="{ duration: 100 }" :editor="editor">
                <button type="button" @click="toggleHeading(1)" :class="getButtonClass('heading', 1)">
                    <i class="fa fa-heading"></i>
                </button>
                <button type="button" @click="toggleBold" :class="getButtonClass('bold')">
                    <i class="fa fa-bold"></i>
                </button>
                <button type="button" @click="toggleItalic" :class="getButtonClass('italic')">
                    <i class="fa fa-italic"></i>
                </button>
                <div :class="elementClass('divider')"></div>
                <Multiselect
                    v-model="selectedListType"
                    :class="[
                        { 'is-active': editor.isActive('bulletList') || editor.isActive('orderedList') },
                        wysiwygButtonClass
                    ]"
                    @change="changeListType"
                    :options="listOptions"
                    :searchable="false"
                    placeholder="select"
                >
                    <template #singlelabel="{ value }">
                        <div class="multiselect-single-label">
                            <div class="u-text-faded u-text-xs u-w-8 u-flex">
                                <i :class="`fas ${value.label || 'fa fa-list'}`" />
                            </div>
                        </div>
                    </template>

                    <template #placeholder>
                        <div class="u-text-faded u-text-xs">
                            <i class="fa fa-list"></i>
                        </div>
                    </template>

                    <template #option="{ option }">
                        <div class="u-text-faded u-text-xs u-w-8 u-flex">
                            <i :class="option.label" />
                        </div>
                    </template>
                    <template #caret>
                        <span class="u-text-faded u-text-xs u-flex u-align-middle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                    </template>
                </Multiselect>
                <div :class="elementClass('divider')"></div>
                <button type="button" @click="setLink" :class="getButtonClass('link')">
                    <i class="fa fa-link"></i>
                </button>
                <div :class="elementClass('divider')"></div>
                <button type="button" :class="wysiwygButtonClass">
                    <i class="fa-regular fa-wand-magic-sparkles"></i>
                </button>
            </bubble-menu>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Editor, EditorContent, type EditorEvents, BubbleMenu } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Heading from '@tiptap/extension-heading';
import OrderedList from '@tiptap/extension-ordered-list';
import BulletList from '@tiptap/extension-bullet-list';
import ListItem from '@tiptap/extension-list-item';
import Link from '@tiptap/extension-link';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import { useBEM } from '@helpers/bem';
import type { ChainedCommands } from '@tiptap/core';
import Placeholder from '@tiptap/extension-placeholder';
import { useI18n } from 'vue-i18n';

export interface TextareaProps {
    modelValue: string;
    placeholder?: string;
}

type Level = 1 | 2 | 3 | 4 | 5 | 6;

const props = defineProps<TextareaProps>();
const { t } = useI18n();

const blockName = 'c-textarea-wysiwyg';
const { blockClass, elementClass } = useBEM(blockName, []);
const wysiwygButtonClass = elementClass('button');
const emit = defineEmits<{
    (event: 'update:modelValue', value: string): void;
}>();

const editor = ref<Editor>();
const selectedListType = ref<ListType | null>(null);

const listOptions = [
    { value: 'bullet', label: 'fa fa-list' },
    { value: 'ordered', label: 'fa-solid fa-list-ol' }
];

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [
            Placeholder.configure({
                placeholder: props.placeholder ?? t('components.textarea.placeholder')
            }),
            StarterKit.configure({
                heading: false,
                bulletList: false,
                orderedList: false,
                listItem: false
            }),
            Heading,
            Link.configure({
                autolink: false,
                openOnClick: false,
                linkOnPaste: false
            }),
            BulletList,
            OrderedList,
            ListItem
        ],
        editorProps: {
            attributes: { class: 'u-prose dark:u-prose-invert focus:u-outline-none m-0' }
        },
        onUpdate: (props: EditorEvents['update']) => {
            emit('update:modelValue', props.editor.getHTML());
        }
    });
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const toggleHeading = (level: Level) => {
    editor.value?.chain().focus().toggleHeading({ level }).run();
};

const toggleBold = () => {
    editor.value?.chain().focus().toggleBold().run();
};

const toggleItalic = () => {
    editor.value?.chain().focus().toggleItalic().run();
};

type ListType = 'bullet' | 'ordered';

const mappedMethods: { [key in ListType]: string } = {
    bullet: 'toggleBulletList',
    ordered: 'toggleOrderedList'
};

const changeListType = (value: ListType | null) => {
    let action = mappedMethods[value || (selectedListType.value as ListType)];

    if (action) {
        (editor.value?.chain().focus()[action as keyof ChainedCommands] as Function)().run();
    }
};

const setLink = () => {
    const url = prompt('Enter a URL') || '';
    editor.value?.chain().focus().setLink({ href: url }).run();
};

const getButtonClass = (type: string, level?: number) => {
    return [{ 'is-active': editor.value?.isActive(type, { level }) }, wysiwygButtonClass];
};
</script>
