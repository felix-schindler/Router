export function isMobile(): boolean {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function leadingZero(n: number|string) {
    if (n <= 9)
        return "0" + n;
    return n;
}

export function getDateStr(date: Date) {
    return date.getFullYear() + "-" + leadingZero(date.getMonth() + 1) + "-" + leadingZero(date.getDate()) + "T" + leadingZero(date.getHours()) + ":" + leadingZero(date.getMinutes()) + ":" + leadingZero(date.getSeconds());
}

export function isUUID(uuid: string) {
    return /^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i.test(uuid);
}
