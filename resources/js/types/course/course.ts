import type {ChapterProps} from "@interfaces/course/chapter";
import type {CourseStatus} from "@interfaces/course/course-status";
import type {MuxVideoResponse} from "@/types/input-drop-upload";
import type {UploadFileProps} from "@/types/media";

export interface CourseProps {
    uuid?: string,
    title: string,
    description: string,
    created_by?: string,
    logo: string,
    video_data?: MuxVideoResponse,
    header_image?: UploadFileProps,
    status: CourseStatus
    chapters?: ChapterProps[],
}
