export const getMimeTypeIcon = (mimeType: string): string => {
    const iconMap: Record<string, string> = {
        image: 'fa-file-image',
        'image/jpeg': 'fa-file-image',
        'image/png': 'fa-file-image',
        audio: 'fa-file-audio',
        video: 'fa-file-video',
        'audio/mpeg': 'fa-file-audio',
        // Documents
        'application/pdf': 'fa-file-pdf',
        'application/msword': 'fa-file-word',
        'application/vnd.ms-word': 'fa-file-word',
        'application/vnd.oasis.opendocument.text': 'fa-file-word',
        'application/vnd.openxmlformatsfficedocument.wordprocessingml': 'fa-file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word',
        'application/vnd.ms-excel': 'fa-file-excel',
        'application/vnd.openxmlformatsfficedocument.spreadsheetml': 'fa-file-excel',
        'application/vnd.oasis.opendocument.spreadsheet': 'fa-file-excel',
        'application/vnd.ms-powerpoint': 'fa-file-powerpoint',
        'application/vnd.openxmlformatsfficedocument.presentationml': 'fa-file-powerpoint',
        'application/vnd.oasis.opendocument.presentation': 'fa-file-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'fa-file-powerpoint',
        'text/plain': 'fa-file-text',
        'text/html': 'fa-file-code',
        'application/json': 'fa-file-code',
        // Archives
        'application/gzip': 'fa-file-archive',
        'application/zip': 'fa-file-archive'
    };

    return iconMap[mimeType || ''] || 'fa-file';
};
