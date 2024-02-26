import { format, parseISO } from 'date-fns'
import { pl } from 'date-fns/locale'

export const formatDate = (dateString) => {
    const date = parseISO(dateString)
    return format(date, 'dd, MMM, yyyy, HH:mm', { locale: pl })
}

export default { formatDate }