import type { User } from './user';
import type { DocumentData } from '@interfaces/document';

export interface chatProps {
    author: User;
    users: User[];
    readCursor: number;
    appAttachmentOptions?: ChatAppAttachment[];
    channel: string;
    api: {
        sendMessage: Function;
        updateMessage: Function;
        deleteMessage: Function;
        uploadImage: Function;
        uploadFile: Function;
        react: Function;
        loadAttachment: Function;
        loadMessages: Function;
        setReadCursor: Function;
        getMessage: Function;
    };
}

export interface ChatNotification {
    actor: User;
    action: string;
    target: string | User | null;
    timestamp: number;
}

export type ChatItem = ChatMessage | ChatNotificationMessage;

export interface ChatMessage {
    id: string;
    text: string;
    user: User;
    timestamp: number;
    isMe?: boolean;
    showAvatar?: boolean;
    showName?: boolean;
    type: 'message';
    isLoading?: boolean;
    isDeleted?: boolean;
    error?: boolean;
    audio?: Blob | null;
    images?: UploadChatImage[];
    documents?: UploadChatFile[];
    reactions: ChatReaction[];
    attachments?: ChatAttachment[];
}

export interface ChatAppAttachment {
    label: string;
    icon: string;
    namespace: string;
}

export interface ChatAttachment {
    type: string;
    data?: any;
    urn?: string;
}

export interface ChatReaction {
    messageId: string;
    emoji: Object;
    label: string;
    users: User[];
}

export interface ChatNotificationMessage {
    id: string;
    type: 'system-message';
    data: ChatNotification;
    createdAt: number;
}

export type UploadChatImage = {
    type: 'image';
    data: {
        id: string;
        image?: File;
        url: {
            small: string | undefined;
            medium: string | undefined;
        };
        progress?: number;
        ready?: boolean;
        title?: string;
        disk?: string | undefined;
        path?: string | undefined;
        error?: string | undefined;
    };
};

export type UploadChatFile = {
    type: 'document';
    data: {
        id: string;
        file: File;
        progress: number;
        ready: boolean;
        title: string;
        size: number;
        mime_type: string;
        url?: string;
        disk?: string | undefined;
        path?: string | undefined;
        error?: string | undefined;
    };
};
