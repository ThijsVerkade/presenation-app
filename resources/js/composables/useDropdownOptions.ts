import { ref } from 'vue';
import { i18n } from '../i18n';
import type { Workspace } from '@interfaces/workspace';

const { t } = i18n.global;

export function useDropdownOptions(workspaceData = [] as Workspace[]) {
    const dropdownOptions = ref([]);

    const updateOptions = () => {
        dropdownOptions.value = [
            { type: 'header', label: t('sidebar.dropdown.options') },
            {
                type: 'link',
                label: t('sidebar.dropdown.workspace-settings'),
                link: '/settings',
                icon: 'fa-sharp fa-light fa-gear'
            },
            { type: 'link', label: t('sidebar.dropdown.app-settings'), link: '/app_settings', icon: 'fa-light fa-grid-2' },
            { type: 'link', label: t('sidebar.dropdown.discover'), link: '/discover', icon: 'fa-sharp fa-light fa-store' },
            { type: 'divider' },
            {
                type: 'dropdown',
                label: t('sidebar.dropdown.switch-workspace'),
                link: '/switch_workspace',
                icon: 'fa-regular fa-shuffle',
                submenu: [
                    { type: 'header', label: t('sidebar.dropdown.submenu.options') },
                    ...workspaceData.map(workspace => ({
                        type: 'link',
                        label: workspace.name,
                        link: '/acme',
                        image: workspace.image,
                        imageType: 'square'
                    })),
                    {
                        type: 'link',
                        label: t('sidebar.dropdown.submenu.workspace.create'),
                        link: '/create',
                        icon: 'fa-regular fa-plus'
                    },
                    { type: 'divider' },
                    {
                        type: 'link',
                        label: t('sidebar.dropdown.submenu.personal'),
                        link: '/personal',
                        icon: 'fa-regular fa-user'
                    }
                ]
            },
            { type: 'divider' },
            {
                type: 'link',
                label: t('sidebar.dropdown.account'),
                subLabel: 'davmnatsakan@gmail.com',
                link: '/my account',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRptlR2UudU1OftdjDm2gptQkECb2qniVSxHu98zuHbZKX6-KFDPOipuV_2v87NOxJoR88&usqp=CAU'
            },
            {
                type: 'link',
                label: t('sidebar.dropdown.log-out'),
                link: '/logout',
                icon: 'fa-light fa-right-from-bracket',
                iconColor: '#DF3030'
            }
        ] as any;
    };

    updateOptions();

    return {
        dropdownOptions
    };
}
