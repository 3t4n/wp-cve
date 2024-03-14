var WPacTime = WPacTime || {

    getTime: function(time, lang, format) {
        if (format == 'chat') {
            return this.getChatTime(time, lang || 'en');
        } else if (format) {
            return this.getFormatTime(time, format, lang || 'en');
        } else {
            return this.getDefaultTime(time, lang || 'en');
        }
    },

    getChatTime: function(time, lang) {
        var now = new Date().getTime(),
            distanceMillis = now - time,
            s = distanceMillis / 1000, m = s / 60, h = m / 60, d = h / 24;

        if (h < 24) {
            return this.getFormatTime(time, 'HH:mm', lang);
        } else if (d < 365) {
            return this.getFormatTime(time, 'dd.MM HH:mm', lang);
        } else {
            return this.getFormatTime(time, 'yyyy.MM.dd HH:mm', lang);
        }
    },

    getDefaultTime: function(time, lang) {
        return this.getTimeAgo(time, lang);
    },

    getTimeAgo: function(time, lang) {
        var now = new Date().getTime(),
            distanceMillis = now - time,
            s = distanceMillis / 1000, m = s / 60, h = m / 60, d = h / 24, y = d / 365;

        lang = WPacTime.Messages[lang] ? lang : 'en';
        if (s < 45) {
            return WPacTime.Messages[lang].second;
        } else if (s < 90) {
            return WPacTime.Messages[lang].minute;
        } else if (m < 45) {
            return WPacTime.Messages[lang].minutes(m);
        } else if (m < 90) {
            return WPacTime.Messages[lang].hour;
        } else if (h < 24) {
            return WPacTime.Messages[lang].hours(h);
        } else if (h < 48) {
            return WPacTime.Messages[lang].day;
        } else if (d < 30) {
            return WPacTime.Messages[lang].days(d);
        } else if (d < 60) {
            return WPacTime.Messages[lang].month;
        } else if (d < 365) {
            return WPacTime.Messages[lang].months(d);
        } else if (y < 2) {
            return WPacTime.Messages[lang].year;
        } else {
            return WPacTime.Messages[lang].years(y);
        }
    },

    getTime12: function(time, lang) {
        var date = new Date(time);
        return ((date.getHours() % 12) ? date.getHours() % 12 : 12) + ':' + date.getMinutes() + (date.getHours() >= 12 ? ' PM' : ' AM');
    },

    getFormatTime: function(time, format, lang) {
        var date = new Date(time),
            flags = {
                SS: date.getMilliseconds(),
                ss: date.getSeconds(),
                mm: date.getMinutes(),
                HH: date.getHours(),
                hh: ((date.getHours() % 12) ? date.getHours() % 12 : 12) + (date.getHours() >= 12 ? 'PM' : 'AM'),
                dd: date.getDate(),
                MM: date.getMonth() + 1,
                yyyy: date.getFullYear(),
                yy: String(date.getFullYear()).toString().substr(2,2),
                ago: this.getTimeAgo(time, lang),
                '12': this.getTime12(time, lang)
            };

        return format.replace(/(SS|ss|mm|HH|hh|DD|dd|MM|yyyy|yy|ago|12)/g, function(i, code) {
            var val = flags[code];
            return val < 10 ? '0' + val : val;
        });
    },

    declineNum: function (n, m1, m2, m3) {
        return n + ' ' + this.declineMsg(n, m1, m2, m3);
    },

    declineMsg: function (n, m1, m2, m3, def) {
        var n10 = n % 10;
        if ((n10 == 1) && ((n == 1) || (n > 20))) {
            return m1;
        } else if ((n10 > 1) && (n10 < 5) && ((n > 20) || (n < 10))) {
            return m2;
        } else if (n) {
            return m3;
        } else {
            return def;
        }
    }
};

WPacTime.Messages = {
    ru: {
        second:  'только что',
        minute:  'минуту назад',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'минута назад', 'минуты назад', 'минут назад'); },
        hour:    'час назад',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'час назад', 'часа назад', 'часов назад'); },
        day:     'день назад',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'день назад', 'дня назад', 'дней назад'); },
        month:   'месяц назад',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'месяц назад', 'месяца назад', 'месяцев назад'); },
        year:    'год назад',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'год назад', 'года назад', 'лет назад'); }
    },
    en: {
        second:  'just now',
        minute:  '1m ago',
        minutes: function(m) { return Math.round(m) + 'm ago'; },
        hour:    '1h ago',
        hours:   function(h) { return Math.round(h) + 'h ago'; },
        day:     'a day ago',
        days:    function(d) { return Math.round(d) + ' days ago'; },
        month:   'a month ago',
        months:  function(d) { return Math.floor(d / 30) + ' months ago'; },
        year:    'a year ago',
        years:   function(y) { return Math.round(y) + ' years ago'; }
    },
    uk: {
        second:  'тільки що',
        minute:  'хвилину тому',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'хвилину тому', 'хвилини тому', 'хвилин тому'); },
        hour:    'годину тому',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'годину тому', 'години тому', 'годин тому'); },
        day:     'день тому',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'день тому', 'дні тому', 'днів тому'); },
        month:   'місяць тому',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'місяць тому', 'місяці тому', 'місяців тому'); },
        year:    'рік тому',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'рік тому', 'роки тому', 'років тому'); }
    },
    ro: {
        second:  'chiar acum',
        minute:  'în urmă minut',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'o minuta in urma', 'minute in urma', 'de minute in urma'); },
        hour:    'acum o ora',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'acum o ora', 'ore in urma', 'de ore in urma'); },
        day:     'o zi in urma',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'o zi in urma', 'zile in urma', 'de zile in urma'); },
        month:   'o luna in urma',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'o luna in urma', 'luni in urma', 'de luni in urma'); },
        year:    'un an in urma',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'un an in urma', 'ani in urma', 'de ani in urma'); }
    },
    lv: {
        second:  'Mazāk par minūti',
        minute:  'Pirms minūtes',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'pirms minūtes', 'pirms minūtēm', 'pirms minūtēm'); },
        hour:    'pirms stundas',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'pirms stundas', 'pirms stundām', 'pirms stundām'); },
        day:     'pirms dienas',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'pirms dienas', 'pirms dienām', 'pirms dienām'); },
        month:   'pirms mēneša',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'pirms mēneša', 'pirms mēnešiem', 'pirms mēnešiem'); },
        year:    'pirms gada',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'pirms gada', 'pirms gadiem', 'pirms gadiem'); }
    },
    lt: {
        second:  'ką tik',
        minute:  'prieš minutę',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'minutė prieš', 'minutės prieš', 'minučių prieš'); },
        hour:    'prieš valandą',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'valanda prieš', 'valandos prieš', 'valandų prieš'); },
        day:     'prieš dieną',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'diena prieš', 'dienos prieš', 'dienų prieš'); },
        month:   'prieš mėnesį',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'mėnesį prieš', 'mėnesiai prieš', 'mėnesių prieš'); },
        year:    'prieš metus',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'metai prieš', 'metai prieš', 'metų prieš'); }
    },
    kk: {
        second:  'бір минуттан аз уақыт бұрын',
        minute:  'бір минут бұрын',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'минут бұрын', 'минут бұрын', 'минут бұрын'); },
        hour:    'бір сағат бұрын',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'сағат бұрын', 'сағат бұрын', 'сағат бұрын'); },
        day:     'бір күн бұрын',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'күн бұрын', 'күн бұрын', 'күн бұрын'); },
        month:   'бір ай бұрын',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'ай бұрын', 'ай бұрын', 'ай бұрын'); },
        year:    'бір жыл бұрын',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'жыл бұрын', 'жыл бұрын', 'жыл бұрын'); }
    },
    ka: {
        second:  'წამის წინ',
        minute:  'წუთის წინ',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'წუთის წინ', 'წუთის წინ', 'წუთის წინ'); },
        hour:    'საათის წინ',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'საათის წინ', 'საათის წინ', 'საათის წინ'); },
        day:     'დღის წინ',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'დღის წინ', 'დღის წინ', 'დღის წინ'); },
        month:   'თვის წინ',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'თვის წინ', 'თვის წინ', 'თვის წინ'); },
        year:    'წლის წინ',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'წლის წინ', 'წლის წინ', 'წლის წინ'); }
    },
    hy: {
        second:  'մի քնի վայրկյան առաջ',
        minute:  'մեկ րոպե առաջ',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'րոպե առաջ', 'րոպե առաջ', 'րոպե առաջ'); },
        hour:    'մեկ ժամ առաջ',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'ժամ առաջ', 'ժամ առաջ', 'ժամ առաջ'); },
        day:     'մեկ օր առաջ',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'օր առաջ', 'օր առաջ', 'օր առաջ'); },
        month:   'մեկ ամիս առաջ',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'ամիս առաջ', 'ամիս առաջ', 'ամիս առաջ'); },
        year:    'մեկ տարի առաջ',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'տարի առաջ', 'տարի առաջ', 'տարի առաջ'); }
    },
    fr: {
        second:  'tout à l\'heure',
        minute:  'environ une minute',
        minutes: function(m) { return Math.round(m) + ' minutes'; },
        hour:    'environ une heure',
        hours:   function(h) { return 'environ ' + Math.round(h) + ' heures'; },
        day:     'un jour',
        days:    function(d) { return Math.round(d) + ' jours'; },
        month:   'environ un mois',
        months:  function(d) { return Math.floor(d / 30) + ' mois'; },
        year:    'environ un an',
        years:   function(y) { return Math.round(y) + ' ans'; }
    },
    es: {
        second:  'ahora',
        minute:  'hace un minuto',
        minutes: function(m) { return 'hace ' + Math.round(m) + ' minuts'; },
        hour:    'hace una hora',
        hours:   function(h) { return 'hace ' +  Math.round(h) + ' horas'; },
        day:     'hace un dia',
        days:    function(d) { return 'hace ' + Math.round(d) + ' días'; },
        month:   'hace un mes',
        months:  function(d) { return 'hace ' + Math.floor(d / 30) + ' meses'; },
        year:    'hace años',
        years:   function(y) { return 'hace ' + Math.round(y) + ' años'; }
    },
    el: {
        second:  'λιγότερο από ένα λεπτό',
        minute:  'γύρω στο ένα λεπτό',
        minutes: function(m) { return Math.round(m) + ' minutes'; },
        hour:    'γύρω στην μια ώρα',
        hours:   function(h) { return 'about ' + Math.round(h) + ' hours'; },
        day:     'μια μέρα',
        days:    function(d) { return Math.round(d) + ' days'; },
        month:   'γύρω στον ένα μήνα',
        months:  function(d) { return Math.floor(d / 30) + ' months'; },
        year:    'γύρω στον ένα χρόνο',
        years:   function(y) { return Math.round(y) + ' years'; }
    },
    de: {
        second:  'soeben',
        minute:  'vor einer Minute',
        minutes: function(m) { return 'vor '+ Math.round(m) +' Minuten'; },
        hour:    'vor einer Stunde',
        hours:   function(h) { return 'vor ' + Math.round(h) + ' Stunden'; },
        day:     'vor einem Tag',
        days:    function(d) { return 'vor ' + Math.round(d) + ' Tagen'; },
        month:   'vor einem Monat',
        months:  function(d) { return 'vor ' + Math.floor(d / 30) + ' Monaten'; },
        year:    'vor einem Jahr',
        years:   function(y) { return 'vor ' + Math.round(y) + ' Jahren'; }
    },
    be: {
        second:  'менш за хвіліну таму',
        minute:  'хвіліну таму',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'хвіліна таму', 'хвіліны таму', 'хвілін таму'); },
        hour:    'гадзіну таму',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'гадзіну таму', 'гадзіны таму', 'гадзін таму'); },
        day:     'дзень таму',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'дзень таму', 'дні таму', 'дзён таму'); },
        month:   'месяц таму',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'месяц таму', 'месяца таму', 'месяцаў таму'); },
        year:    'год таму',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'год таму', 'гады таму', 'год таму'); }
    },
    it: {
        second:  'proprio ora',
        minute:  'un minuto fa',
        minutes: function(m) { return WPacTime.declineNum(Math.round(m), 'un minuto fa', 'minuti fa', 'minuti fa'); },
        hour:    'un\'ora fa',
        hours:   function(h) { return WPacTime.declineNum(Math.round(h), 'un\'ora fa', 'ore fa', 'ore fa'); },
        day:     'un giorno fa',
        days:    function(d) { return WPacTime.declineNum(Math.round(d), 'un giorno fa', 'giorni fa', 'giorni fa'); },
        month:   'un mese fa',
        months:  function(d) { return WPacTime.declineNum(Math.floor(d / 30), 'un mese fa', 'mesi fa', 'mesi fa'); },
        year:    'un anno fa',
        years:   function(y) { return WPacTime.declineNum(Math.round(y), 'un anno fa', 'anni fa', 'anni fa'); }
    },
    tr: {
        second:  'az önce',
        minute:  'dakika önce',
        minutes: function(m) { return Math.round(m) + ' dakika önce'; },
        hour:    'saat önce',
        hours:   function(h) { return Math.round(h) + ' saat önce'; },
        day:     'gün önce',
        days:    function(d) { return Math.round(d) + ' gün önce'; },
        month:   'ay önce',
        months:  function(d) { return Math.floor(d / 30) + ' ay önce'; },
        year:    'yıl önce',
        years:   function(y) { return Math.round(y) + ' yıl önce'; }
    },
    nb: {
        second:  'nå nettopp',
        minute:  'ett minutt siden',
        minutes: function(m) { return Math.round(m) + ' minutter siden'; },
        hour:    'en time siden',
        hours:   function(h) { return Math.round(h) + ' timer siden'; },
        day:     'en dag siden',
        days:    function(d) { return Math.round(d) + ' dager siden'; },
        month:   'en måned siden',
        months:  function(d) { return Math.floor(d / 30) + ' måneder siden'; },
        year:    'ett år siden',
        years:   function(y) { return Math.round(y) + ' år siden'; }
    },
    da: {
        second:  'lige nu',
        minute:  'et minut siden',
        minutes: function(m) { return Math.round(m) + ' minutter siden'; },
        hour:    'en time siden',
        hours:   function(h) { return Math.round(h) + ' timer siden'; },
        day:     'en dag siden',
        days:    function(d) { return Math.round(d) + ' dage siden'; },
        month:   'en måned siden',
        months:  function(d) { return Math.floor(d / 30) + ' måneder siden'; },
        year:    'et år siden',
        years:   function(y) { return Math.round(y) + ' år siden'; }
    },
    nl: {
        second:  'zojuist',
        minute:  'minuten geleden',
        minutes: function(m) { return Math.round(m) + ' minuten geleden'; },
        hour:    'uur geleden',
        hours:   function(h) { return Math.round(h) + ' uur geleden'; },
        day:     '1 dag geleden',
        days:    function(d) { return Math.round(d) + ' dagen geleden'; },
        month:   'maand geleden',
        months:  function(d) { return Math.floor(d / 30) + ' maanden geleden'; },
        year:    'jaar geleden',
        years:   function(y) { return Math.round(y) + ' jaar geleden'; }
    },
    ca: {
        second:  'ara mateix',
        minute:  'fa un minut',
        minutes: function(m) { return 'fa ' + Math.round(m) + ' minuts'; },
        hour:    'fa una hora',
        hours:   function(h) { return 'fa ' +  Math.round(h) + ' hores'; },
        day:     'fa un dia',
        days:    function(d) { return 'fa ' + Math.round(d) + ' dies'; },
        month:   'fa un mes',
        months:  function(d) { return 'fa ' + Math.floor(d / 30) + ' mesos'; },
        year:    'fa un any',
        years:   function(y) { return 'fa ' + Math.round(y) + ' anys'; }
    },
    sv: {
        second:  'just nu',
        minute:  'en minut sedan',
        minutes: function(m) { return Math.round(m) + ' minuter sedan'; },
        hour:    'en timme sedan',
        hours:   function(h) { return Math.round(h) + ' timmar sedan'; },
        day:     'en dag sedan',
        days:    function(d) { return Math.round(d) + ' dagar sedan'; },
        month:   'en månad sedan',
        months:  function(d) { return Math.floor(d / 30) + ' månader sedan'; },
        year:    'ett år sedan',
        years:   function(y) { return Math.round(y) + ' år sedan'; }
    },
    pl: {
        second:  'właśnie teraz',
        minute:  'minutę temu',
        minutes: function(m) { return Math.round(m) + ' minut temu'; },
        hour:    'godzinę temu',
        hours:   function(h) { return Math.round(h) + ' godzin temu'; },
        day:     'wczoraj',
        days:    function(d) { return Math.round(d) + ' dni temu'; },
        month:   'miesiąc temu',
        months:  function(d) { return Math.floor(d / 30) + ' miesięcy temu'; },
        year:    'rok temu',
        years:   function(y) { return Math.round(y) + ' lat temu'; }
    },
    pt: {
        second:  'agora',
        minute:  '1 minuto atrás',
        minutes: function(m) { return Math.round(m) + ' minutos atrás'; },
        hour:    '1 hora atrás',
        hours:   function(h) { return Math.round(h) + ' horas atrás'; },
        day:     '1 dia atrás',
        days:    function(d) { return Math.round(d) + ' dias atrás'; },
        month:   '1 mês atrás',
        months:  function(d) { return Math.floor(d / 30) + ' meses atrás'; },
        year:    '1 ano atrás',
        years:   function(y) { return Math.round(y) + ' anos atrás'; }
    },
    hu: {
        second:  'épp az imént',
        minute:  '1 perccel ezelőtt',
        minutes: function(m) { return Math.round(m) + ' perccel ezelőtt'; },
        hour:    'órával ezelőtt',
        hours:   function(h) { return Math.round(h) + ' órával ezelőtt'; },
        day:     'nappal ezelőtt',
        days:    function(d) { return Math.round(d) + ' nappal ezelőtt'; },
        month:   'hónappal ezelőtt',
        months:  function(d) { return Math.floor(d / 30) + ' hónappal ezelőtt'; },
        year:    'évvel ezelőtt',
        years:   function(y) { return Math.round(y) + ' évvel ezelőtt'; }
    },
    fi: {
        second:  'juuri nyt',
        minute:  'minuutti sitten',
        minutes: function(m) { return Math.round(m) + ' minuuttia sitten'; },
        hour:    'tunti sitten',
        hours:   function(h) { return Math.round(h) + ' tuntia sitten'; },
        day:     'päivä sitten',
        days:    function(d) { return Math.round(d) + ' päivää sitten'; },
        month:   'kuukausi sitten',
        months:  function(d) { return Math.floor(d / 30) + ' kuukautta sitten'; },
        year:    'vuosi sitten',
        years:   function(y) { return Math.round(y) + ' vuotta sitten'; }
    },
    he: {
        second:  'הרגע',
        minute:  'לפני דקה',
        minutes: function(m) { return 'לפני ' + Math.round(m) + ' דקות'; },
        hour:    'לפני שעה',
        hours:   function(h) { return 'לפני ' + Math.round(h) + ' שעות'; },
        day:     'לפני יום',
        days:    function(d) { return 'לפני ' + Math.round(d) + ' ימים'; },
        month:   'לפני חודש',
        months:  function(d) { return Math.floor(d / 30) == 2 ? 'לפני חודשיים' : 'לפני ' + Math.floor(d / 30) + ' חודשים'; },
        year:    'לפני שנה',
        years:   function(y) { return 'לפני ' + Math.round(y) + ' שנים'; }
    },
    bg: {
        second:  'в момента',
        minute:  'преди 1 минута',
        minutes: function(m) { return 'преди ' + Math.round(m) + ' минути'; },
        hour:    'преди 1 час',
        hours:   function(h) { return 'преди ' +  Math.round(h) + ' часа'; },
        day:     'преди 1 ден',
        days:    function(d) { return 'преди ' + Math.round(d) + ' дни'; },
        month:   'преди 1 месец',
        months:  function(d) { return 'преди ' + Math.floor(d / 30) + ' месеца'; },
        year:    'преди 1 година',
        years:   function(y) { return 'преди ' + Math.round(y) + ' години'; }
    },
    sk: {
        second:  'práve teraz',
        minute:  'pred minútov',
        minutes: function(m) { return 'pred ' + Math.round(m) + ' minútami'; },
        hour:    'pred hodinou',
        hours:   function(h) { return 'pred ' +  Math.round(h) + ' hodinami'; },
        day:     'včera',
        days:    function(d) { return 'pred ' + Math.round(d) + ' dňami'; },
        month:   'pred mesiacom',
        months:  function(d) { return 'pred ' + Math.floor(d / 30) + ' mesiacmi'; },
        year:    'pred rokom',
        years:   function(y) { return 'pred ' + Math.round(y) + ' rokmi'; }
    },
    lo: {
        second:  'ວັ່ງກີ້ນີ້',
        minute:  'ໜຶ່ງນາທີກ່ອນ',
        minutes: function(m) { return Math.round(m) + ' ນາທີກ່ອນ'; },
        hour:    'ໜຶ່ງຊົ່ວໂມງກ່ອນ',
        hours:   function(h) { return Math.round(h) + ' ົ່ວໂມງກ່ອນ'; },
        day:     'ໜຶ່ງມື້ກ່ອນ',
        days:    function(d) { return Math.round(d) + ' ມື້ກ່ອນ'; },
        month:   'ໜຶ່ງເດືອນກ່ອນ',
        months:  function(d) { return Math.floor(d / 30) + ' ເດືອນກ່ອນ'; },
        year:    'ໜຶ່ງປີກ່ອນ',
        years:   function(y) { return Math.round(y) + ' ປີກ່ອນ'; }
    },
    sl: {
        second:  'pravkar',
        minute:  'pred eno minuto',
        minutes: function(m) { return 'pred ' + Math.round(m) + ' minutami'; },
        hour:    'pred eno uro',
        hours:   function(h) { return 'pred ' +  Math.round(h) + ' urami'; },
        day:     'pred enim dnem',
        days:    function(d) { return 'pred ' + Math.round(d) + ' dnevi'; },
        month:   'pred enim mesecem',
        months:  function(d) { return 'pred ' + Math.floor(d / 30) + ' meseci'; },
        year:    'pred enim letom',
        years:   function(y) { return 'pred ' + Math.round(y) + ' leti'; }
    },
    et: {
        second:  'just nüüd',
        minute:  'minut tagasi',
        minutes: function(m) { return Math.round(m) + ' minutit tagasi'; },
        hour:    'tund tagasi',
        hours:   function(h) { return Math.round(h) + ' tundi tagasi'; },
        day:     'päev tagasi',
        days:    function(d) { return Math.round(d) + ' päeva tagasi'; },
        month:   'kuu aega tagasi',
        months:  function(d) { return Math.floor(d / 30) + ' kuud tagasi'; },
        year:    'aasta tagasi',
        years:   function(y) { return Math.round(y) + ' aastat tagasi'; }
    }
};