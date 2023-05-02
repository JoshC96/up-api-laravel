import { format, formatDuration, intervalToDuration } from 'date-fns'

export function humanDate(date_string, date_format = 'hh:mmaa E d MMM Y ') {
    return format(new Date(date_string), date_format)
}

export function formatCurrency(numberAsCents) {
    return new Intl.NumberFormat('en-AU', { style: 'currency', currency: 'AUD', maximumFractionDigits : 2 }).format(numberAsCents / 100);
}

