import type {CourseItemProps} from "@interfaces/course/coures-item";

export interface ChapterProps {
    uuid: string,
    title: string,
    description: string,
    order: number,
    courseItems: CourseItemProps[],
}
