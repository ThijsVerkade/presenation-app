import { ref, computed, type Ref, type ComputedRef } from 'vue';

interface UseBEMReturn {
    blockClass: ComputedRef<string>;
    elementClass: (element: string, modifiers?: string[]) => string;
    modifierList: Ref<string[]>;
    setModifiers: (newModifiers: string[]) => void;
}

export function useBEM(blockName: string, modifiers: string[] = []): UseBEMReturn {
    const modifierList = ref(modifiers);

    const buildModifier = (className: string, modifiers: string[]) => {
        return modifiers.map(mod => `${className}--${mod}`).join(' ');
    };

    const blockClass = computed(() => {
        return `${blockName} ${buildModifier(blockName, modifierList.value)}`.trim();
    });
    const elementClass = (element: string, modifiers: string[] = []): string => {
        const elementName = `${blockName}__${element}`;
        return `${elementName} ${buildModifier(elementName, modifiers)}`.trim();
    };

    const setModifiers = (newModifiers: string[]) => {
        modifierList.value = newModifiers;
    };

    return {
        blockClass,
        elementClass,
        modifierList,
        setModifiers
    };
}
