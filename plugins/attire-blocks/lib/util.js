import slugify from 'slugify';
import striptags from 'striptags';
import * as backgrounds from "../blocks/09-icon/backgrounds";

const {__} = wp.i18n;

const {
    getBlocks,
} = wp.data.select('core/block-editor');

class Util {
    constructor() {

    }

    static hexToRgba(hex, alpha) {
        if (!hex || hex === 'transparent') return 'transparent';
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        const rgba = result ? {
            r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16), a: alpha / 100
        } : null;
        return "rgba( " + rgba.r + ", " + rgba.g + ", " + rgba.b + ", " + rgba.a + ")";
    }

    static defaultColor() {

        return [{color: '#ffffff', name: 'white'}, {color: '#000000', name: 'black'}, {
            color: '#3373dc',
            name: 'royal blue'
        }, {color: '#209cef', name: 'sky blue'}, {color: '#2BAD59', name: 'green'}, {
            color: '#ff3860',
            name: 'pink'
        }, {color: '#7941b6', name: 'purple'}, {color: '#F7812B', name: 'orange'}, {
            color: 'transparent',
            name: 'Transparent'
        },];
    }

    static titleCase(s) {
        return s.replace(/^_*(.)|_+(.)/g, (s, c, d) => c ? c.toUpperCase() : ' ' + d.toUpperCase())
    }

    static guidGenerator() {
        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1) + (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    }

    static getBgOverlay(attributes, prefix = '') {
        if (!attributes[prefix + 'ColorLeft'] || !attributes[prefix + 'ColorRight']) {
            return 'none';
        }
        const rgba1 = Util.hexToRgba(attributes[prefix + 'ColorLeft'], attributes[prefix + 'Alpha']);
        const rgba2 = Util.hexToRgba(attributes[prefix + 'ColorRight'], attributes[prefix + 'Alpha']);
        const cs1 = attributes[prefix + 'CS1'] + "%";
        const cs2 = attributes[prefix + 'CS2'] + "%";
        return "linear-gradient( " + attributes[prefix + 'GradAngle'] + "deg, " + rgba1 + " " + cs1 + ", " + rgba2 + " " + cs2 + ")";
    }

    static getSpacingStyles(props, prefix = '') {
        const {attributes} = props;
        let style = {};
        style ['padding'] = `${attributes[prefix + 'Padding'][0]}px ${attributes[prefix + 'Padding'][1]}px ${attributes[prefix + 'Padding'][2]}px ${attributes[prefix + 'Padding'][3]}px`;
        style ['margin'] = `${attributes[prefix + 'Margin'][0]}px ${attributes[prefix + 'Margin'][1]}px ${attributes[prefix + 'Margin'][2]}px ${attributes[prefix + 'Margin'][3]}px`;
        return style;
    }

    static getSpacingV2Styles(attributes, prefix = '') {
        let style = {};
        let padding = prefix + 'Padding';
        let margin = prefix + 'Margin';
        style ['padding'] = `${this.getValueByIndex(attributes, padding, 0)} ${this.getValueByIndex(attributes, padding, 1)} ${this.getValueByIndex(attributes, padding, 2)} ${this.getValueByIndex(attributes, padding, 3)}`;
        style ['margin'] = `${this.getValueByIndex(attributes, margin, 0)} ${this.getValueByIndex(attributes, margin, 1)} ${this.getValueByIndex(attributes, margin, 2)} ${this.getValueByIndex(attributes, margin, 3)}`;
        return style;
    }

    static getSpacingV2StylesFlipped(attributes, prefix = '') {
        let style = {};
        let padding = prefix + 'Padding';
        let margin = prefix + 'Margin';
        style ['padding'] = `${this.getValueByIndex(attributes, padding, 3)} ${this.getValueByIndex(attributes, padding, 2)} ${this.getValueByIndex(attributes, padding, 1)} ${this.getValueByIndex(attributes, padding, 0)}`;
        style ['margin'] = `${this.getValueByIndex(attributes, margin, 3)} ${this.getValueByIndex(attributes, margin, 2)} ${this.getValueByIndex(attributes, margin, 1)} ${this.getValueByIndex(attributes, margin, 0)}`;
        return style;
    }

    static getValueByIndex(attributes, propName, index) {
        if (attributes[propName + 'Unit'] && (attributes[propName + 'Unit'][index] === 'auto')) {
            return 'auto';
        }
        if (!attributes[propName][index] || !attributes[propName + 'Unit']) return '0px';
        return `${attributes[propName][index]}${attributes[propName + 'Unit'][index]}`;
    }

    static getBorderStylesInline(attributes) {
        let style = {};
        style ['borderColor'] = `${(attributes.BorderColor || 'grey')}`;
        style ['borderRadius'] = `${(attributes.BorderRadius || 0)}px`;
        style ['borderStyle'] = `${(attributes.BorderStyle || 'solid')}`;
        style ['borderWidth'] = `${(attributes.BorderWidth || 0)}px`;
        return style;
    }

    /**
     * Get all block IDs.
     *
     * @param {Array} excludeId exclude block client id.
     * @param {Array} blocks blocks list to check.
     *
     * @return {Array} block anchors and slugs array.
     */
    static getAllSlugs(excludeId, blocks = 'none') {
        let slugs = [];

        if ('none' === blocks) {
            blocks = getBlocks();
        }

        blocks.forEach((block) => {
            if (block.clientId !== excludeId && block.attributes) {
                if (block.attributes.anchor) {
                    slugs.push(block.attributes.anchor);
                }
                if ('attire-blocks/tab' === block.name && block.attributes.slug) {
                    slugs.push(block.attributes.slug);
                }
            }

            if (block.innerBlocks && block.innerBlocks.length) {
                slugs = [...slugs, ...this.getAllSlugs(excludeId, block.innerBlocks),];
            }
        });

        return slugs;
    }

    /**
     * Check if slug is unique.
     *
     * @param {String} slug new slug.
     * @param {Array} slugs slugs list to check.
     *
     * @return {Boolean} is unique.
     */
    static isUniqueSlug(slug, slugs) {
        let isUnique = true;

        slugs.forEach((thisSlug) => {
            if (thisSlug === slug) {
                isUnique = false;
            }
        });

        return isUnique;
    }

    /**
     * Get slug from title.
     *
     * @param {String} title title string.
     *
     * @return {String} slug.
     */
    static getSlug(title) {
        return slugify(striptags(title), {
            replacement: '-', remove: /[*_+~()'"!?/\-—–−:@^|&#.,;%<>{}]/g, lower: true,
        });
    }

    /**
     * Get unique slug from title.
     *
     * @param {String} title title string.
     * @param {String} excludeBlockId exclude block id to not check.
     *
     * @return {String} slug.
     */
    static getUniqueSlug(title, excludeBlockId) {
        let newSlug = '';
        let i = 0;
        const allSlugs = this.getAllSlugs(excludeBlockId);

        while (!newSlug || !this.isUniqueSlug(newSlug, allSlugs)) {
            if (newSlug) {
                i += 1;
            }
            newSlug = `${this.getSlug(title)}${i ? `-${i}` : ''}`;
        }
        return newSlug;
    }

    static serialize(obj) {
        let str = [];
        for (let p in obj) if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
        return str.join("&");
    }

    static getValuesFromMultiSelectObject(array) {
        let data = [];
        array.forEach(item => {
            data.push(item.value);
        });
        return data;
    }

    static getRandomIcons(arr, n = 100) {
        let result = new Array(n), len = arr.length, taken = new Array(len);
        if (n > len) throw new RangeError("getRandom: more elements taken than available");
        while (n--) {
            let x = Math.floor(Math.random() * len);
            result[n] = arr[x in taken ? taken[x] : x];
            taken[x] = --len in taken ? taken[len] : len;
        }
        return result;
    }

    //Used in Icons and Social Share block
    static generateIconCss(attributes, id, class_name = 'atbs-icon') {
        let style = `
       .${class_name}-${id} span{
           background-color:${(attributes.backgroundColor || 'transparent')}!important;
           border:${attributes.BorderWidth}px ${attributes.BorderStyle} ${(attributes.BorderColor || 'transparent')};
           border-radius:${attributes.BorderRadius}px!important;
           padding:${attributes.Padding[0]}px ${attributes.Padding[1]}px ${attributes.Padding[2]}px ${attributes.Padding[3]}px !important;
           margin:${attributes.Margin[0]}px ${attributes.Margin[1]}px ${attributes.Margin[2]}px ${attributes.Margin[3]}px !important;}
       .${class_name}-${id} i{color:${(attributes.iconColor || '#000')}!important;
           font-size:${attributes.iconSize} !important;}`;

        if (attributes.iconHoverColor) {
            style += `.${class_name}-${id} span:hover i{color:${attributes.iconHoverColor}!important;}`;
        }
        if (attributes.backgroundImage) {
            style += `.${class_name}-${id} span{
            mask-repeat: no-repeat; 
            -webkit-mask-repeat: no-repeat;
            mask-size: contain; 
            -webkit-mask-size: contain; display: inline-block !important; 
            mask-position: center;
            -webkit-mask-position: center;
            -webkit-mask-image: url(${backgrounds[attributes.backgroundImage]});
            mask-image: url(${backgrounds[attributes.backgroundImage]});}`;
        }
        return style;
    }

    static textAlignOptions() {
        return [{
            label: __('Left', 'attire-blocks'), value: 'left',
        }, {
            label: __('Center', 'attire-blocks'), value: 'center',
        }, {
            label: __('Right', 'attire-blocks'), value: 'right',
        }];
    }


    static socialShareLinks() {
        return {
            blogger: {
                link: "https://www.blogger.com/blog_this.pyra?t&amp;u=", label: 'Blogger', icon: 'fab fa-blogger'
            },
            buffer: {link: 'https://buffer.com/add?url=', label: 'Buffer', icon: 'fab fa-buffer'},
            digg: {link: "http://digg.com/submit?url=", label: 'Digg', icon: 'fab fa-digg'},
            email: {link: "mailto:?body=", label: 'Email', icon: 'fas fa-envelope'},
            facebook: {link: "https://www.facebook.com/sharer.php?u=", label: 'Facebook', icon: 'fab fa-facebook'},
            google: {
                link: "https://plus.google.com/share?url=", label: 'Google Currents', icon: 'fab fa-google-plus-g'
            },
            linkedin: {link: "https://www.linkedin.com/shareArticle?url=", label: 'LinkedIn', icon: 'fab fa-linkedin'},
            odnoklassniki: {
                link: 'https://connect.ok.ru/offer?url=',
                label: 'Odnoklassniki (Одноклассники)',
                icon: 'fab fa-odnoklassniki'
            },
            pinterest: {
                link: "https://pinterest.com/pin/create/link/?url=", label: 'Pinterest', icon: 'fab fa-pinterest'
            },
            pocket: {link: 'https://getpocket.com/edit?url=', label: 'Pocket', icon: 'fab fa-get-pocket'},
            reddit: {link: "https://reddit.com/submit?url=", label: 'Reddit', icon: 'fab fa-reddit'},
            skype: {link: 'https://web.skype.com/share?url=', label: 'Skype', icon: 'fab fa-skype'},
            stumbleupon: {
                link: "https://www.stumbleupon.com/submit?url=", label: 'StumbleUpon', icon: 'fab fa-stumbleupon'
            },
            telegram: {link: 'https://telegram.me/share/url?url=', label: 'Telegram', icon: 'fab fa-telegram'},
            tumblr: {
                link: "https://www.tumblr.com/widgets/share/tool?canonicalUrl=", label: 'Tumblr', icon: 'fab fa-tumblr'
            },
            twitter: {link: "https://twitter.com/share?url=", label: 'Twitter', icon: 'fab fa-twitter'},
            vk: {link: 'https://vkontakte.ru/share.php?url=', label: 'VK', icon: 'fab fa-vk'},
            whatsapp: {link: 'https://api.whatsapp.com/send?text=', label: 'WhatsApp', icon: 'fab fa-whatsapp'},
            xing: {link: 'https://www.xing.com/app/user?op=share&url=', label: 'XING', icon: 'fab fa-xing'},
        }
    }

    static spacing_css(attributes, prefix = '') {
        let css = {}
        css['padding'] = attributes[prefix + 'Padding'][0] + 'px ' + attributes[prefix + 'Padding'][1] + 'px ' + attributes[prefix + 'Padding'][2] + 'px ' + attributes[prefix + 'Padding'][3] + 'px';
        css['margin'] = attributes[prefix + 'Margin'][0] + 'px ' + attributes[prefix + 'Margin'][1] + 'px ' + attributes[prefix + 'Margin'][2] + 'px ' + attributes[prefix + 'Margin'][3] + 'px';
        return css;
    }

    static position_css(attributes, prefix = '') {
        return {
            top: this.getValueByIndex(attributes, prefix + 'Position', 0),
            right: this.getValueByIndex(attributes, prefix + 'Position', 1),
            bottom: this.getValueByIndex(attributes, prefix + 'Position', 2),
            left: this.getValueByIndex(attributes, prefix + 'Position', 3),
        }
    }

    static spacing_css_flipped(attributes, prefix = '') {
        let css = {}
        css['padding'] = attributes[prefix + 'Padding'][0] + 'px ' + attributes[prefix + 'Padding'][3] + 'px ' + attributes[prefix + 'Padding'][2] + 'px ' + attributes[prefix + 'Padding'][1] + 'px';
        css['margin'] = attributes[prefix + 'Margin'][0] + 'px ' + attributes[prefix + 'Margin'][3] + 'px ' + attributes[prefix + 'Margin'][2] + 'px ' + attributes[prefix + 'Margin'][1] + 'px';
        return css;
    }

    static borderCss(attributes, prefix = '') {
        return {
            'border': attributes[prefix + 'BorderWidth'] + 'px ' + attributes[prefix + 'BorderStyle'] + ' ' + attributes[prefix + 'BorderColor'],
            'borderRadius': attributes[prefix + 'BorderRadius'] + 'px'
        }
    }

    static unSlash(site) {
        return site.replace(/\/$/, "");
    }

    static typographyCss(attributes, prefix = '', ignore = []) {
        let css = {};

        if (ignore.indexOf('FontSize') === -1) {
            css['fontSize'] = attributes[prefix + 'FontSize'] + attributes[prefix + 'FontSizeUnit'];
        }
        if (ignore.indexOf('FontWeight') === -1) {
            css['fontWeight'] = attributes[prefix + 'FontWeight'];
        }
        if (ignore.indexOf('LineHeight') === -1) {
            css['lineHeight'] = attributes[prefix + 'LineHeight'] + attributes[prefix + 'LineHeightUnit'];
        }
        if (ignore.indexOf('LetterSpacing') === -1) {
            css['letterSpacing'] = attributes[prefix + 'LetterSpacing'] + attributes[prefix + 'LetterSpacingUnit'];
        }
        if (ignore.indexOf('TextAlign') === -1) {
            css['textAlign'] = attributes[prefix + 'TextAlign'];
        }
        if (ignore.indexOf('FontStyle') === -1) {
            css['fontStyle'] = attributes[prefix + 'FontStyle'];
        }
        if (ignore.indexOf('TextColor') === -1) {
            css['color'] = attributes[prefix + 'TextColor'];
        }
        if (ignore.indexOf('TextTransform') === -1) {
            css['textTransform'] = attributes[prefix + 'TextTransform'];
        }

        return css;
    }

    static JSToCSS(JS) {
        let cssString = "";
        for (let objectKey in JS) {
            cssString += objectKey.replace(/([A-Z])/g, (g) => `-${g[0].toLowerCase()}`) + ": " + JS[objectKey] + ";\n";
        }

        return cssString;
    };

    static isValidHttpUrl(string) {
        let url;

        try {
            url = new URL(string);
        } catch (_) {
            return false;
        }

        return url.protocol === "http:" || url.protocol === "https:";
    }

}

export default Util;