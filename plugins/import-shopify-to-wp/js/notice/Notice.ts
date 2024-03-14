class Notice {
    private notice: HTMLElement | undefined;

    constructor(notice: HTMLElement | null) {
        if (!notice) {
            return;
        }

        this.notice = notice;

        this.events();
    }

    events() {
        this.notice?.addEventListener('click', (event) => {
            const target = event.target as HTMLElement;

            if (target && target.classList.contains('notice-dismiss')) {
                Notice.hide();
            }
        });
    }

    public static setCookie(name: string, value: string, expirationDays = 365) {
        const d = new Date();
        d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    public static hide() {
        Notice.setCookie('s2wp_notice_dismissed', 'yes');
    }
}

export {
    Notice
};
