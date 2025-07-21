export interface QuizItemProps {
    uuid: string,
    question: string,
    options: string[],
    order: number,
    correct_answer?: string
}
