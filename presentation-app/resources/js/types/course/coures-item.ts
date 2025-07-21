import type {CourseType} from "@interfaces/course/course-type";
import type {QuizItemProps} from "@interfaces/course/quiz-item";
import type {CourseItemStatus} from "@/types/course/course-item-status";
import type {ChapterProps} from "@/types/course/chapter";
import type { UploadFileProps } from "@/types/media";
import type { MuxVideoResponse } from "../input-drop-upload";

export interface CourseItemProps {
    uuid: string,
    title: string,
    type: CourseType,
    status: CourseItemStatus,
    order?: number,
    passing_grade?: number,
    enforce_passing_grade?: boolean,
    description?: string,
    content?: string,
    video_data?: MuxVideoResponse,
    header_image?: UploadFileProps,
    quizItems?: QuizItemProps[],
    chapter?: ChapterProps,
}
