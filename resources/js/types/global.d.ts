interface ImportMeta {
    glob: (globPattern: string) => Promise<Record<string, () => Promise<any>>>;
}

import { AxiosStatic } from 'axios';

declare global {
    interface Window {
        axios: AxiosStatic;
        route: (path: string) => string;
        workspaceRoute: (path: string, args?: object) => string;
        workspace: {
            name: string;
            handle: string;
        };
    }

    function workspaceRoute(path: string, args?: object): string;
}
