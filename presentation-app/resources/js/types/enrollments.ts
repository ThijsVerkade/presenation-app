import type {CourseItemProps} from "@/types/course/coures-item";

export interface EnrollmentProps {
    uuid: string;
    progress: string;
    user: UserProps;
    status: EnrollmentStatus;
    last_accessed_course_item_id: string;
    last_accessed_chapter_id: string;
    redirect_link: string;
    updated_at: Date;
    created_at: Date;
    chapter_items?: UserChapterItemProps[];
}

export interface UserProps {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    language: string;
    image_url: string | null;
    meta_data: string;
    created_at: Date;
    updated_at: Date;
}

export interface UserChapterItemProps {
    id: number;
    course_uuid: string;
    course_item: CourseItemProps;
    completed: number;
    completed_at: string;
    updated_at: Date;
    quiz_score: string;
    course_item_title: string;
    quiz_attempts: UserQuizAttemptProps[];
}

export interface UserQuizAttemptProps {
    selected_choice: string;
    correct: number;
    answered_at: string;
}

export enum EnrollmentStatus {
    COMPLETED = 'completed',
    ACTIVE = 'active',
}

export interface MetaDataProps {
    key: string;
    label: string;
    value: string|boolean|number|Date;
    type: MetaDataType;
}

export enum MetaDataType {
    STRING = 'string',
    NUMBER = 'number',
    BOOLEAN = 'boolean',
    DATE = 'date',
    LINK = 'link',
}
