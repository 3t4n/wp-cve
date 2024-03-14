export const toTime = (timestamp: number | null): string | undefined => {
    if (!timestamp) return
    const date = new Date(timestamp * 1000)
    const formatter = new Intl.DateTimeFormat('en', { month: 'short' })
    const day = date.getDate()
    const month = formatter.format(date)
    const year = date.getFullYear()

    return `${month} ${day}, ${year}`
}

const handleZero = (num: number) => {
    if (num < 10) {
        return `0${num}`
    }
    return num
}

export const toDuration = (value: string): string => {
    if (!value) {
        return ''
    }
    const sec_num: number = parseInt(value, 10)
    const hours = Math.floor(sec_num / 3600)
    const minutes = Math.floor((sec_num - hours * 3600) / 60)
    const seconds = sec_num - hours * 3600 - minutes * 60

    return `${handleZero(hours)}:${handleZero(minutes)}:${handleZero(seconds)}`
}
