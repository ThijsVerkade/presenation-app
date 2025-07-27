import axios from 'axios';
import { useToast } from '@composables/useToast';

const { toastSuccess, toastError } = useToast();

export function createApiCall() {
    return async function apiCall<T>(
        method: 'get' | 'post' | 'patch' | 'delete' | 'put',
        url: string,
        data?: any,
        successMessage?: string,
        errorMessage?: string,
        headers?: Record<string, string>
    ): Promise<T> {
        try {

            const requestHeaders = {
                ...headers,
            };

            let response;
            switch (method) {
                case 'get':
                    response = await axios.get(url, { headers: requestHeaders });
                    break;
                case 'post':
                    response = await axios.post(url, data, { headers: requestHeaders });
                    break;
                case 'patch':
                    response = await axios.patch(url, data, { headers: requestHeaders });
                    break;
                case 'put':
                    response = await axios.put(url, data, { headers: requestHeaders });
                    break;
                case 'delete':
                    response = await axios.delete(url, { headers: requestHeaders });
                    break;
            }
            if (successMessage && method !== 'get') {
                toastSuccess(successMessage);
            }

            return response.data.data;
        } catch (error) {
            toastError(error.response.data.message || errorMessage || 'An error occurred');
            throw error;
        }
    };
}
