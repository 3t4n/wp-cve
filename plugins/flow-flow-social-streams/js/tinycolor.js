// TinyColor v0.9.16
// https://github.com/bgrins/TinyColor
// 2013-08-10, Brian Grinstead, MIT License

(function() {

var trimLeft = /^[\s,#]+/,
    trimRight = /\s+$/,
    tinyCounter = 0,
    math = Math,
    mathRound = math.round,
    mathMin = math.min,
    mathMax = math.max,
    mathRandom = math.random;

function tinycolor (color, opts) {

    color = (color) ? color : '';
    opts = opts || { };

    // If input is already a tinycolor, return itself
    if (typeof color == "object" && color.hasOwnProperty("_tc_id")) {
       return color;
    }

    var rgb = inputToRGB(color);
    var r = rgb.r,
        g = rgb.g,
        b = rgb.b,
        a = rgb.a,
        roundA = mathRound(100*a) / 100,
        format = opts.format || rgb.format;

    // Don't let the range of [0,255] come back in [0,1].
    // Potentially lose a little bit of precision here, but will fix issues where
    // .5 gets interpreted as half of the total, instead of half of 1
    // If it was supposed to be 128, this was already taken care of by `inputToRgb`
    if (r < 1) { r = mathRound(r); }
    if (g < 1) { g = mathRound(g); }
    if (b < 1) { b = mathRound(b); }

    return {
        ok: rgb.ok,
        format: format,
        _tc_id: tinyCounter++,
        alpha: a,
        getAlpha: function() {
            return a;
        },
        setAlpha: function(value) {
            a = boundAlpha(value);
            roundA = mathRound(100*a) / 100;
        },
        toHsv: function() {
            var hsv = rgbToHsv(r, g, b);
            return { h: hsv.h * 360, s: hsv.s, v: hsv.v, a: a };
        },
        toHsvString: function() {
            var hsv = rgbToHsv(r, g, b);
            var h = mathRound(hsv.h * 360), s = mathRound(hsv.s * 100), v = mathRound(hsv.v * 100);
            return (a == 1) ?
              "hsv("  + h + ", " + s + "%, " + v + "%)" :
              "hsva(" + h + ", " + s + "%, " + v + "%, "+ roundA + ")";
        },
        toHsl: function() {
            var hsl = rgbToHsl(r, g, b);
            return { h: hsl.h * 360, s: hsl.s, l: hsl.l, a: a };
        },
        toHslString: function() {
            var hsl = rgbToHsl(r, g, b);
            var h = mathRound(hsl.h * 360), s = mathRound(hsl.s * 100), l = mathRound(hsl.l * 100);
            return (a == 1) ?
              "hsl("  + h + ", " + s + "%, " + l + "%)" :
              "hsla(" + h + ", " + s + "%, " + l + "%, "+ roundA + ")";
        },
        toHex: function(allow3Char) {
            return rgbToHex(r, g, b, allow3Char);
        },
        toHexString: function(allow3Char) {
            return '#' + rgbToHex(r, g, b, allow3Char);
        },
        toRgb: function() {
            return { r: mathRound(r), g: mathRound(g), b: mathRound(b), a: a };
        },
        toRgbString: function() {
            return (a == 1) ?
              "rgb("  + mathRound(r) + ", " + mathRound(g) + ", " + mathRound(b) + ")" :
              "rgba(" + mathRound(r) + ", " + mathRound(g) + ", " + mathRound(b) + ", " + roundA + ")";
        },
        toPercentageRgb: function() {
            return { r: mathRound(bound01(r, 255) * 100) + "%", g: mathRound(bound01(g, 255) * 100) + "%", b: mathRound(bound01(b, 255) * 100) + "%", a: a };
        },
        toPercentageRgbString: function() {
            return (a == 1) ?
              "rgb("  + mathRound(bound01(r, 255) * 100) + "%, " + mathRound(bound01(g, 255) * 100) + "%, " + mathRound(bound01(b, 255) * 100) + "%)" :
              "rgba(" + mathRound(bound01(r, 255) * 100) + "%, " + mathRound(bound01(g, 255) * 100) + "%, " + mathRound(bound01(b, 255) * 100) + "%, " + roundA + ")";
        },
        toName: function() {
            if (a === 0) {
                return "transparent";
            }

            return hexNames[rgbToHex(r, g, b, true)] || false;
        },
        toFilter: function(secondColor) {
            var hex = rgbToHex(r, g, b);
            var secondHex = hex;
            var alphaHex = Math.round(parseFloat(a) * 255).toString(16);
            var secondAlphaHex = alphaHex;
            var gradientType = opts && opts.gradientType ? "GradientType = 1, " : "";

            if (secondColor) {
                var s = tinycolor(secondColor);
                secondHex = s.toHex();
                secondAlphaHex = Math.round(parseFloat(s.alpha) * 255).toString(16);
            }

            return "progid:DXImageTransform.Microsoft.gradient("+gradientType+"startColorstr=#" + pad2(alphaHex) + hex + ",endColorstr=#" + pad2(secondAlphaHex) + secondHex + ")";
        },
        toString: function(format) {
            var formatSet = !!format;
            format = format || this.format;

            var formattedString = false;
            var hasAlphaAndFormatNotSet = !formatSet && a < 1 && a > 0;
            var formatWithAlpha = hasAlphaAndFormatNotSet && (format === "hex" || format === "hex6" || format === "hex3" || format === "name");

            if (format === "rgb") {
                formattedString = this.toRgbString();
            }
            if (format === "prgb") {
                formattedString = this.toPercentageRgbString();
            }
            if (format === "hex" || format === "hex6") {
                formattedString = this.toHexString();
            }
            if (format === "hex3") {
                formattedString = this.toHexString(true);
            }
            if (format === "name") {
                formattedString = this.toName();
            }
            if (format === "hsl") {
                formattedString = this.toHslString();
            }
            if (format === "hsv") {
                formattedString = this.toHsvString();
            }

            if (formatWithAlpha) {
                return this.toRgbString();
            }

            return formattedString || this.toHexString();
        }
    };
}

// If input is an object, force 1 into "1.0" to handle ratios properly
// String input requires "1.0" as input, so 1 will be treated as 1
tinycolor.fromRatio = function(color, opts) {
    if (typeof color == "object") {
        var newColor = {};
        for (var i in color) {
            if (color.hasOwnProperty(i)) {
                if (i === "a") {
                    newColor[i] = color[i];
                }
                else {
                    newColor[i] = convertToPercentage(color[i]);
                }
            }
        }
        color = newColor;
    }

    return tinycolor(color, opts);
};

// Given a string or object, convert that input to RGB
// Possible string inputs:
//
//     "red"
//     "#f00" or "f00"
//     "#ff0000" or "ff0000"
//     "rgb 255 0 0" or "rgb (255, 0, 0)"
//     "rgb 1.0 0 0" or "rgb (1, 0, 0)"
//     "rgba (255, 0, 0, 1)" or "rgba 255, 0, 0, 1"
//     "rgba (1.0, 0, 0, 1)" or "rgba 1.0, 0, 0, 1"
//     "hsl(0, 100%, 50%)" or "hsl 0 100% 50%"
//     "hsla(0, 100%, 50%, 1)" or "hsla 0 100% 50%, 1"
//     "hsv(0, 100%, 100%)" or "hsv 0 100% 100%"
//
function inputToRGB(color) {

    var rgb = { r: 0, g: 0, b: 0 };
    var a = 1;
    var ok = false;
    var format = false;

    if (typeof color == "string") {
        color = stringInputToObject(color);
    }

    if (typeof color == "object") {
        if (color.hasOwnProperty("r") && color.hasOwnProperty("g") && color.hasOwnProperty("b")) {
            rgb = rgbToRgb(color.r, color.g, color.b);
            ok = true;
            format = String(color.r).substr(-1) === "%" ? "prgb" : "rgb";
        }
        else if (color.hasOwnProperty("h") && color.hasOwnProperty("s") && color.hasOwnProperty("v")) {
            color.s = convertToPercentage(color.s);
            color.v = convertToPercentage(color.v);
            rgb = hsvToRgb(color.h, color.s, color.v);
            ok = true;
            format = "hsv";
        }
        else if (color.hasOwnProperty("h") && color.hasOwnProperty("s") && color.hasOwnProperty("l")) {
            color.s = convertToPercentage(color.s);
            color.l = convertToPercentage(color.l);
            rgb = hslToRgb(color.h, color.s, color.l);
            ok = true;
            format = "hsl";
        }

        if (color.hasOwnProperty("a")) {
            a = color.a;
        }
    }

    a = boundAlpha(a);

    return {
        ok: ok,
        format: color.format || format,
        r: mathMin(255, mathMax(rgb.r, 0)),
        g: mathMin(255, mathMax(rgb.g, 0)),
        b: mathMin(255, mathMax(rgb.b, 0)),
        a: a
    };
}


// Conversion Functions
// --------------------

// `rgbToHsl`, `rgbToHsv`, `hslToRgb`, `hsvToRgb` modified from:
// <http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript>

// `rgbToRgb`
// Handle bounds / percentage checking to conform to CSS color spec
// <http://www.w3.org/TR/css3-color/>
// *Assumes:* r, g, b in [0, 255] or [0, 1]
// *Returns:* { r, g, b } in [0, 255]
function rgbToRgb(r, g, b){
    return {
        r: bound01(r, 255) * 255,
        g: bound01(g, 255) * 255,
        b: bound01(b, 255) * 255
    };
}

// `rgbToHsl`
// Converts an RGB color value to HSL.
// *Assumes:* r, g, and b are contained in [0, 255] or [0, 1]
// *Returns:* { h, s, l } in [0,1]
function rgbToHsl(r, g, b) {

    r = bound01(r, 255);
    g = bound01(g, 255);
    b = bound01(b, 255);

    var max = mathMax(r, g, b), min = mathMin(r, g, b);
    var h, s, l = (max + min) / 2;

    if(max == min) {
        h = s = 0; // achromatic
    }
    else {
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }

        h /= 6;
    }

    return { h: h, s: s, l: l };
}

// `hslToRgb`
// Converts an HSL color value to RGB.
// *Assumes:* h is contained in [0, 1] or [0, 360] and s and l are contained [0, 1] or [0, 100]
// *Returns:* { r, g, b } in the set [0, 255]
function hslToRgb(h, s, l) {
    var r, g, b;

    h = bound01(h, 360);
    s = bound01(s, 100);
    l = bound01(l, 100);

    function hue2rgb(p, q, t) {
        if(t < 0) t += 1;
        if(t > 1) t -= 1;
        if(t < 1/6) return p + (q - p) * 6 * t;
        if(t < 1/2) return q;
        if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
        return p;
    }

    if(s === 0) {
        r = g = b = l; // achromatic
    }
    else {
        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }

    return { r: r * 255, g: g * 255, b: b * 255 };
}

// `rgbToHsv`
// Converts an RGB color value to HSV
// *Assumes:* r, g, and b are contained in the set [0, 255] or [0, 1]
// *Returns:* { h, s, v } in [0,1]
function rgbToHsv(r, g, b) {

    r = bound01(r, 255);
    g = bound01(g, 255);
    b = bound01(b, 255);

    var max = mathMax(r, g, b), min = mathMin(r, g, b);
    var h, s, v = max;

    var d = max - min;
    s = max === 0 ? 0 : d / max;

    if(max == min) {
        h = 0; // achromatic
    }
    else {
        switch(max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }
    return { h: h, s: s, v: v };
}

// `hsvToRgb`
// Converts an HSV color value to RGB.
// *Assumes:* h is contained in [0, 1] or [0, 360] and s and v are contained in [0, 1] or [0, 100]
// *Returns:* { r, g, b } in the set [0, 255]
 function hsvToRgb(h, s, v) {

    h = bound01(h, 360) * 6;
    s = bound01(s, 100);
    v = bound01(v, 100);

    var i = math.floor(h),
        f = h - i,
        p = v * (1 - s),
        q = v * (1 - f * s),
        t = v * (1 - (1 - f) * s),
        mod = i % 6,
        r = [v, q, p, p, t, v][mod],
        g = [t, v, v, q, p, p][mod],
        b = [p, p, t, v, v, q][mod];

    return { r: r * 255, g: g * 255, b: b * 255 };
}

// `rgbToHex`
// Converts an RGB color to hex
// Assumes r, g, and b are contained in the set [0, 255]
// Returns a 3 or 6 character hex
function rgbToHex(r, g, b, allow3Char) {

    var hex = [
        pad2(mathRound(r).toString(16)),
        pad2(mathRound(g).toString(16)),
        pad2(mathRound(b).toString(16))
    ];

    // Return a 3 character hex if possible
    if (allow3Char && hex[0].charAt(0) == hex[0].charAt(1) && hex[1].charAt(0) == hex[1].charAt(1) && hex[2].charAt(0) == hex[2].charAt(1)) {
        return hex[0].charAt(0) + hex[1].charAt(0) + hex[2].charAt(0);
    }

    return hex.join("");
}

// `equals`
// Can be called with any tinycolor input
tinycolor.equals = function (color1, color2) {
    if (!color1 || !color2) { return false; }
    return tinycolor(color1).toRgbString() == tinycolor(color2).toRgbString();
};
tinycolor.random = function() {
    return tinycolor.fromRatio({
        r: mathRandom(),
        g: mathRandom(),
        b: mathRandom()
    });
};


// Modification Functions
// ----------------------
// Thanks to less.js for some of the basics here
// <https://github.com/cloudhead/less.js/blob/master/lib/less/functions.js>

tinycolor.desaturate = function (color, amount) {
    amount = (amount === 0) ? 0 : (amount || 10);
    var hsl = tinycolor(color).toHsl();
    hsl.s -= amount / 100;
    hsl.s = clamp01(hsl.s);
    return tinycolor(hsl);
};
tinycolor.saturate = function (color, amount) {
    amount = (amount === 0) ? 0 : (amount || 10);
    var hsl = tinycolor(color).toHsl();
    hsl.s += amount / 100;
    hsl.s = clamp01(hsl.s);
    return tinycolor(hsl);
};
tinycolor.greyscale = function(color) {
    return tinycolor.desaturate(color, 100);
};
tinycolor.lighten = function(color, amount) {
    amount = (amount === 0) ? 0 : (amount || 10);
    var hsl = tinycolor(color).toHsl();
    hsl.l += amount / 100;
    hsl.l = clamp01(hsl.l);
    return tinycolor(hsl);
};
tinycolor.darken = function (color, amount) {
    amount = (amount === 0) ? 0 : (amount || 10);
    var hsl = tinycolor(color).toHsl();
    hsl.l -= amount / 100;
    hsl.l = clamp01(hsl.l);
    return tinycolor(hsl);
};
tinycolor.complement = function(color) {
    var hsl = tinycolor(color).toHsl();
    hsl.h = (hsl.h + 180) % 360;
    return tinycolor(hsl);
};


// Combination Functions
// ---------------------
// Thanks to jQuery xColor for some of the ideas behind these
// <https://github.com/infusion/jQuery-xcolor/blob/master/jquery.xcolor.js>

tinycolor.triad = function(color) {
    var hsl = tinycolor(color).toHsl();
    var h = hsl.h;
    return [
        tinycolor(color),
        tinycolor({ h: (h + 120) % 360, s: hsl.s, l: hsl.l }),
        tinycolor({ h: (h + 240) % 360, s: hsl.s, l: hsl.l })
    ];
};
tinycolor.tetrad = function(color) {
    var hsl = tinycolor(color).toHsl();
    var h = hsl.h;
    return [
        tinycolor(color),
        tinycolor({ h: (h + 90) % 360, s: hsl.s, l: hsl.l }),
        tinycolor({ h: (h + 180) % 360, s: hsl.s, l: hsl.l }),
        tinycolor({ h: (h + 270) % 360, s: hsl.s, l: hsl.l })
    ];
};
tinycolor.splitcomplement = function(color) {
    var hsl = tinycolor(color).toHsl();
    var h = hsl.h;
    return [
        tinycolor(color),
        tinycolor({ h: (h + 72) % 360, s: hsl.s, l: hsl.l}),
        tinycolor({ h: (h + 216) % 360, s: hsl.s, l: hsl.l})
    ];
};
tinycolor.analogous = function(color, results, slices) {
    results = results || 6;
    slices = slices || 30;

    var hsl = tinycolor(color).toHsl();
    var part = 360 / slices;
    var ret = [tinycolor(color)];

    for (hsl.h = ((hsl.h - (part * results >> 1)) + 720) % 360; --results; ) {
        hsl.h = (hsl.h + part) % 360;
        ret.push(tinycolor(hsl));
    }
    return ret;
};
tinycolor.monochromatic = function(color, results) {
    results = results || 6;
    var hsv = tinycolor(color).toHsv();
    var h = hsv.h, s = hsv.s, v = hsv.v;
    var ret = [];
    var modification = 1 / results;

    while (results--) {
        ret.push(tinycolor({ h: h, s: s, v: v}));
        v = (v + modification) % 1;
    }

    return ret;
};


// Readability Functions
// ---------------------
// <http://www.w3.org/TR/AERT#color-contrast>

// `readability`
// Analyze the 2 colors and returns an object with the following properties:
//    `brightness`: difference in brightness between the two colors
//    `color`: difference in color/hue between the two colors
tinycolor.readability = function(color1, color2) {
    var a = tinycolor(color1).toRgb();
    var b = tinycolor(color2).toRgb();
    var brightnessA = (a.r * 299 + a.g * 587 + a.b * 114) / 1000;
    var brightnessB = (b.r * 299 + b.g * 587 + b.b * 114) / 1000;
    var colorDiff = (
        Math.max(a.r, b.r) - Math.min(a.r, b.r) +
        Math.max(a.g, b.g) - Math.min(a.g, b.g) +
        Math.max(a.b, b.b) - Math.min(a.b, b.b)
    );

    return {
        brightness: Math.abs(brightnessA - brightnessB),
        color: colorDiff
    };
};

// `readable`
// http://www.w3.org/TR/AERT#color-contrast
// Ensure that foreground and background color combinations provide sufficient contrast.
// *Example*
//    tinycolor.readable("#000", "#111") => false
tinycolor.readable = function(color1, color2) {
    var readability = tinycolor.readability(color1, color2);
    return readability.brightness > 125 && readability.color > 500;
};

// `mostReadable`
// Given a base color and a list of possible foreground or background
// colors for that base, returns the most readable color.
// *Example*
//    tinycolor.mostReadable("#123", ["#fff", "#000"]) => "#000"
tinycolor.mostReadable = function(baseColor, colorList) {
    var bestColor = null;
    var bestScore = 0;
    var bestIsReadable = false;
    for (var i=0; i < colorList.length; i++) {

        // We normalize both around the "acceptable" breaking point,
        // but rank brightness constrast higher than hue.

        var readability = tinycolor.readability(baseColor, colorList[i]);
        var readable = readability.brightness > 125 && readability.color > 500;
        var score = 3 * (readability.brightness / 125) + (readability.color / 500);

        if ((readable && ! bestIsReadable) ||
            (readable && bestIsReadable && score > bestScore) ||
            ((! readable) && (! bestIsReadable) && score > bestScore)) {
            bestIsReadable = readable;
            bestScore = score;
            bestColor = tinycolor(colorList[i]);
        }
    }
    return bestColor;
};


// Big List of Colors
// ------------------
// <http://www.w3.org/TR/css3-color/#svg-color>
var names = tinycolor.names = {
    aliceblue: "f0f8ff",
    antiquewhite: "faebd7",
    aqua: "0ff",
    aquamarine: "7fffd4",
    azure: "f0ffff",
    beige: "f5f5dc",
    bisque: "ffe4c4",
    black: "000",
    blanchedalmond: "ffebcd",
    blue: "00f",
    blueviolet: "8a2be2",
    brown: "a52a2a",
    burlywood: "deb887",
    burntsienna: "ea7e5d",
    cadetblue: "5f9ea0",
    chartreuse: "7fff00",
    chocolate: "d2691e",
    coral: "ff7f50",
    cornflowerblue: "6495ed",
    cornsilk: "fff8dc",
    crimson: "dc143c",
    cyan: "0ff",
    darkblue: "00008b",
    darkcyan: "008b8b",
    darkgoldenrod: "b8860b",
    darkgray: "a9a9a9",
    darkgreen: "006400",
    darkgrey: "a9a9a9",
    darkkhaki: "bdb76b",
    darkmagenta: "8b008b",
    darkolivegreen: "556b2f",
    darkorange: "ff8c00",
    darkorchid: "9932cc",
    darkred: "8b0000",
    darksalmon: "e9967a",
    darkseagreen: "8fbc8f",
    darkslateblue: "483d8b",
    darkslategray: "2f4f4f",
    darkslategrey: "2f4f4f",
    darkturquoise: "00ced1",
    darkviolet: "9400d3",
    deeppink: "ff1493",
    deepskyblue: "00bfff",
    dimgray: "696969",
    dimgrey: "696969",
    dodgerblue: "1e90ff",
    firebrick: "b22222",
    floralwhite: "fffaf0",
    forestgreen: "228b22",
    fuchsia: "f0f",
    gainsboro: "dcdcdc",
    ghostwhite: "f8f8ff",
    gold: "ffd700",
    goldenrod: "daa520",
    gray: "808080",
    green: "008000",
    greenyellow: "adff2f",
    grey: "808080",
    honeydew: "f0fff0",
    hotpink: "ff69b4",
    indianred: "cd5c5c",
    indigo: "4b0082",
    ivory: "fffff0",
    khaki: "f0e68c",
    lavender: "e6e6fa",
    lavenderblush: "fff0f5",
    lawngreen: "7cfc00",
    lemonchiffon: "fffacd",
    lightblue: "add8e6",
    lightcoral: "f08080",
    lightcyan: "e0ffff",
    lightgoldenrodyellow: "fafad2",
    lightgray: "d3d3d3",
    lightgreen: "90ee90",
    lightgrey: "d3d3d3",
    lightpink: "ffb6c1",
    lightsalmon: "ffa07a",
    lightseagreen: "20b2aa",
    lightskyblue: "87cefa",
    lightslategray: "789",
    lightslategrey: "789",
    lightsteelblue: "b0c4de",
    lightyellow: "ffffe0",
    lime: "0f0",
    limegreen: "32cd32",
    linen: "faf0e6",
    magenta: "f0f",
    maroon: "800000",
    mediumaquamarine: "66cdaa",
    mediumblue: "0000cd",
    mediumorchid: "ba55d3",
    mediumpurple: "9370db",
    mediumseagreen: "3cb371",
    mediumslateblue: "7b68ee",
    mediumspringgreen: "00fa9a",
    mediumturquoise: "48d1cc",
    mediumvioletred: "c71585",
    midnightblue: "191970",
    mintcream: "f5fffa",
    mistyrose: "ffe4e1",
    moccasin: "ffe4b5",
    navajowhite: "ffdead",
    navy: "000080",
    oldlace: "fdf5e6",
    olive: "808000",
    olivedrab: "6b8e23",
    orange: "ffa500",
    orangered: "ff4500",
    orchid: "da70d6",
    palegoldenrod: "eee8aa",
    palegreen: "98fb98",
    paleturquoise: "afeeee",
    palevioletred: "db7093",
    papayawhip: "ffefd5",
    peachpuff: "ffdab9",
    peru: "cd853f",
    pink: "ffc0cb",
    plum: "dda0dd",
    powderblue: "b0e0e6",
    purple: "800080",
    red: "f00",
    rosybrown: "bc8f8f",
    royalblue: "4169e1",
    saddlebrown: "8b4513",
    salmon: "fa8072",
    sandybrown: "f4a460",
    seagreen: "2e8b57",
    seashell: "fff5ee",
    sienna: "a0522d",
    silver: "c0c0c0",
    skyblue: "87ceeb",
    slateblue: "6a5acd",
    slategray: "708090",
    slategrey: "708090",
    snow: "fffafa",
    springgreen: "00ff7f",
    steelblue: "4682b4",
    tan: "d2b48c",
    teal: "008080",
    thistle: "d8bfd8",
    tomato: "ff6347",
    turquoise: "40e0d0",
    violet: "ee82ee",
    wheat: "f5deb3",
    white: "fff",
    whitesmoke: "f5f5f5",
    yellow: "ff0",
    yellowgreen: "9acd32"
};

// Make it easy to access colors via `hexNames[hex]`
var hexNames = tinycolor.hexNames = flip(names);


// Utilities
// ---------

// `{ 'name1': 'val1' }` becomes `{ 'val1': 'name1' }`
function flip(o) {
    var flipped = { };
    for (var i in o) {
        if (o.hasOwnProperty(i)) {
            flipped[o[i]] = i;
        }
    }
    return flipped;
}

// Return a valid alpha value [0,1] with all invalid values being set to 1
function boundAlpha(a) {
    a = parseFloat(a);

    if (isNaN(a) || a < 0 || a > 1) {
        a = 1;
    }

    return a;
}

// Take input from [0, n] and return it as [0, 1]
function bound01(n, max) {
    if (isOnePointZero(n)) { n = "100%"; }

    var processPercent = isPercentage(n);
    n = mathMin(max, mathMax(0, parseFloat(n)));

    // Automatically convert percentage into number
    if (processPercent) {
        n = parseInt(n * max, 10) / 100;
    }

    // Handle floating point rounding errors
    if ((math.abs(n - max) < 0.000001)) {
        return 1;
    }

    // Convert into [0, 1] range if it isn't already
    return (n % max) / parseFloat(max);
}

// Force a number between 0 and 1
function clamp01(val) {
    return mathMin(1, mathMax(0, val));
}

// Parse an integer into hex
function parseHex(val) {
    return parseInt(val, 16);
}

// Need to handle 1.0 as 100%, since once it is a number, there is no difference between it and 1
// <http://stackoverflow.com/questions/7422072/javascript-how-to-detect-number-as-a-decimal-including-1-0>
function isOnePointZero(n) {
    return typeof n == "string" && n.indexOf('.') != -1 && parseFloat(n) === 1;
}

// Check to see if string passed in is a percentage
function isPercentage(n) {
    return typeof n === "string" && n.indexOf('%') != -1;
}

// Force a hex value to have 2 characters
function pad2(c) {
    return c.length == 1 ? '0' + c : '' + c;
}

// Replace a decimal with it's percentage value
function convertToPercentage(n) {
    if (n <= 1) {
        n = (n * 100) + "%";
    }

    return n;
}

var matchers = (function() {

    // <http://www.w3.org/TR/css3-values/#integers>
    var CSS_INTEGER = "[-\\+]?\\d+%?";

    // <http://www.w3.org/TR/css3-values/#number-value>
    var CSS_NUMBER = "[-\\+]?\\d*\\.\\d+%?";

    // Allow positive/negative integer/number.  Don't capture the either/or, just the entire outcome.
    var CSS_UNIT = "(?:" + CSS_NUMBER + ")|(?:" + CSS_INTEGER + ")";

    // Actual matching.
    // Parentheses and commas are optional, but not required.
    // Whitespace can take the place of commas or opening paren
    var PERMISSIVE_MATCH3 = "[\\s|\\(]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")\\s*\\)?";
    var PERMISSIVE_MATCH4 = "[\\s|\\(]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")[,|\\s]+(" + CSS_UNIT + ")\\s*\\)?";

    return {
        rgb: new RegExp("rgb" + PERMISSIVE_MATCH3),
        rgba: new RegExp("rgba" + PERMISSIVE_MATCH4),
        hsl: new RegExp("hsl" + PERMISSIVE_MATCH3),
        hsla: new RegExp("hsla" + PERMISSIVE_MATCH4),
        hsv: new RegExp("hsv" + PERMISSIVE_MATCH3),
        hex3: /^([0-9a-fA-F]{1})([0-9a-fA-F]{1})([0-9a-fA-F]{1})$/,
        hex6: /^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/
    };
})();

// `stringInputToObject`
// Permissive string parsing.  Take in a number of formats, and output an object
// based on detected format.  Returns `{ r, g, b }` or `{ h, s, l }` or `{ h, s, v}`
function stringInputToObject(color) {

    color = color.replace(trimLeft,'').replace(trimRight, '').toLowerCase();
    var named = false;
    if (names[color]) {
        color = names[color];
        named = true;
    }
    else if (color == 'transparent') {
        return { r: 0, g: 0, b: 0, a: 0, format: "name" };
    }

    // Try to match string input using regular expressions.
    // Keep most of the number bounding out of this function - don't worry about [0,1] or [0,100] or [0,360]
    // Just return an object and let the conversion functions handle that.
    // This way the result will be the same whether the tinycolor is initialized with string or object.
    var match;
    if ((match = matchers.rgb.exec(color))) {
        return { r: match[1], g: match[2], b: match[3] };
    }
    if ((match = matchers.rgba.exec(color))) {
        return { r: match[1], g: match[2], b: match[3], a: match[4] };
    }
    if ((match = matchers.hsl.exec(color))) {
        return { h: match[1], s: match[2], l: match[3] };
    }
    if ((match = matchers.hsla.exec(color))) {
        return { h: match[1], s: match[2], l: match[3], a: match[4] };
    }
    if ((match = matchers.hsv.exec(color))) {
        return { h: match[1], s: match[2], v: match[3] };
    }
    if ((match = matchers.hex6.exec(color))) {
        return {
            r: parseHex(match[1]),
            g: parseHex(match[2]),
            b: parseHex(match[3]),
            format: named ? "name" : "hex"
        };
    }
    if ((match = matchers.hex3.exec(color))) {
        return {
            r: parseHex(match[1] + '' + match[1]),
            g: parseHex(match[2] + '' + match[2]),
            b: parseHex(match[3] + '' + match[3]),
            format: named ? "name" : "hex"
        };
    }

    return false;
}

// Node: Export function
if (typeof module !== "undefined" && module.exports) {
    module.exports = tinycolor;
}
// AMD/requirejs: Define the module
else if (typeof define !== "undefined") {
    define(function () {return tinycolor;});
}
// Browser: Expose to window
else {
    window.tinycolor = tinycolor;
}

})();

/**
 * jQuery jPages v0.7
 * Client side pagination with jQuery
 * http://luis-almeida.github.com/jPages
 *
 * Licensed under the MIT license.
 * Copyright 2012 Luís Almeida
 * https://github.com/luis-almeida
 */

;(function($, window, document, undefined) {

    var name = "jPages",
        instance = null,
        defaults = {
            containerID: "",
            first: false,
            previous: "← previous",
            next: "next →",
            last: false,
            links: "numeric", // blank || title
            startPage: 1,
            perPage: 10,
            midRange: 5,
            startRange: 1,
            endRange: 1,
            keyBrowse: false,
            scrollBrowse: false,
            pause: 0,
            clickStop: false,
            delay: 50,
            direction: "forward", // backwards || auto || random ||
            animation: "", // http://daneden.me/animate/ - any entrance animations
            fallback: 400,
            minHeight: true,
            callback: undefined // function( pages, items ) { }
        };


    function Plugin(element, options) {
        this.options = $.extend({}, defaults, options);

        this._container = this.options.containerEl ? $( this.options.containerEl ) : $( "#" + this.options.containerID );
        if (!this._container.length) return;

        this.jQwindow = $(window);
        this.jQdocument = $(document);

        this._holder = $(element);
        this._nav = {};

        this._first = $(this.options.first);
        this._previous = $(this.options.previous);
        this._next = $(this.options.next);
        this._last = $(this.options.last);

        /* only visible items! */
        // this._items = this._container.children(":visible");
        this._items = this._container.children();
        this._itemsShowing = $([]);
        this._itemsHiding = $([]);

        // this._numPages = Math.ceil(this._items.length / this.options.perPage);
        this._numPages = Math.ceil( this.options.items.length / this.options.perPage);
        this._currentPageNum = this.options.startPage;

        this._clicked = false;
        this._cssAnimSupport = this.getCSSAnimationSupport();

        this.init();
    }

    Plugin.prototype = {

        constructor : Plugin,

        getCSSAnimationSupport : function() {
            var animation = false,
                animationstring = 'animation',
                keyframeprefix = '',
                domPrefixes = 'Webkit Moz O ms Khtml'.split(' '),
                pfx = '',
                elm = this._container.get(0);

            if (elm.style.animationName) animation = true;

            if (animation === false) {
                for (var i = 0; i < domPrefixes.length; i++) {
                    if (elm.style[domPrefixes[i] + 'AnimationName'] !== undefined) {
                        pfx = domPrefixes[i];
                        animationstring = pfx + 'Animation';
                        keyframeprefix = '-' + pfx.toLowerCase() + '-';
                        animation = true;
                        break;
                    }
                }
            }

            return animation;
        },

        init : function() {
            this.setStyles();
            this.setNav();
            this.paginate(this._currentPageNum);
            //this.setMinHeight();
        },

        setStyles : function() {
            var requiredStyles = "<style>" +
                ".jp-invisible { visibility: hidden !important; } " +
                ".jp-hidden { display: none !important; }" +
                "</style>";

            $(requiredStyles).appendTo("head");

            
            return;
            
            if (this._cssAnimSupport && this.options.animation.length)
                this._items.addClass("animated jp-hidden");
            else this._items.hide();

        },

        setNav : function() {
            var navhtml = this.writeNav();

            this._holder.each(this.bind(function(index, element) {
                var holder = $(element);
                holder.html(navhtml);
                this.cacheNavElements(holder, index);
                this.bindNavHandlers(index);
                this.disableNavSelection(element);
            }, this));

            if (this.options.keyBrowse) this.bindNavKeyBrowse();
            if (this.options.scrollBrowse) this.bindNavScrollBrowse();
        },

        writeNav : function() {
            var i = 1, navhtml;
            navhtml = this.writeBtn("first") + this.writeBtn("previous");

            for (; i <= this._numPages; i++) {
                if (i === 1 && this.options.startRange === 0) navhtml += "<span>...</span>";
                if (i > this.options.startRange && i <= this._numPages - this.options.endRange)
                    navhtml += "<a href='#' class='jp-hidden'>";
                else
                    navhtml += "<a>";

                switch (this.options.links) {
                    case "numeric":
                        navhtml += i;
                        break;
                    case "blank":
                        break;
                    case "title":
                        var title = this._items.eq(i - 1).attr("data-title");
                        navhtml += title !== undefined ? title : "";
                        break;
                }

                navhtml += "</a>";
                if (i === this.options.startRange || i === this._numPages - this.options.endRange)
                    navhtml += "<span>...</span>";
            }
            navhtml += this.writeBtn("next") + this.writeBtn("last") + "</div>";
            return navhtml;
        },

        writeBtn : function(which) {

            return this.options[which] !== false && !$(this["_" + which]).length ?
            "<a class='jp-" + which + "'>" + this.options[which] + "</a>" : "";

        },

        cacheNavElements : function(holder, index) {
            this._nav[index] = {};
            this._nav[index].holder = holder;
            this._nav[index].first = this._first.length ? this._first : this._nav[index].holder.find("a.jp-first");
            this._nav[index].previous = this._previous.length ? this._previous : this._nav[index].holder.find("a.jp-previous");
            this._nav[index].next = this._next.length ? this._next : this._nav[index].holder.find("a.jp-next");
            this._nav[index].last = this._last.length ? this._last : this._nav[index].holder.find("a.jp-last");
            this._nav[index].fstBreak = this._nav[index].holder.find("span:first");
            this._nav[index].lstBreak = this._nav[index].holder.find("span:last");
            this._nav[index].pages = this._nav[index].holder.find("a").not(".jp-first, .jp-previous, .jp-next, .jp-last");
            this._nav[index].permPages =
                this._nav[index].pages.slice(0, this.options.startRange)
                    .add(this._nav[index].pages.slice(this._numPages - this.options.endRange, this._numPages));
            this._nav[index].pagesShowing = $([]);
            this._nav[index].currentPage = $([]);
        },

        bindNavHandlers : function(index) {
            var nav = this._nav[index];

            // default nav
            nav.holder.bind("click.jPages", this.bind(function(evt) {
                var newPage = this.getNewPage(nav, $(evt.target));
                if (this.validNewPage(newPage)) {
                    this._clicked = true;
                    this.paginate(newPage);
                }
                evt.preventDefault();
            }, this));

            // custom first
            if (this._first.length) {
                this._first.bind("click.jPages", this.bind(function() {
                    if (this.validNewPage(1)) {
                        this._clicked = true;
                        this.paginate(1);
                    }
                }, this));
            }

            // custom previous
            if (this._previous.length) {
                this._previous.bind("click.jPages", this.bind(function() {
                    var newPage = this._currentPageNum - 1;
                    if (this.validNewPage(newPage)) {
                        this._clicked = true;
                        this.paginate(newPage);
                    }
                }, this));
            }

            // custom next
            if (this._next.length) {
                this._next.bind("click.jPages", this.bind(function() {
                    var newPage = this._currentPageNum + 1;
                    if (this.validNewPage(newPage)) {
                        this._clicked = true;
                        this.paginate(newPage);
                    }
                }, this));
            }

            // custom last
            if (this._last.length) {
                this._last.bind("click.jPages", this.bind(function() {
                    if (this.validNewPage(this._numPages)) {
                        this._clicked = true;
                        this.paginate(this._numPages);
                    }
                }, this));
            }

        },

        disableNavSelection : function(element) {
            if (typeof element.onselectstart != "undefined")
                element.onselectstart = function() {
                    return false;
                };
            else if (typeof element.style.MozUserSelect != "undefined")
                element.style.MozUserSelect = "none";
            else
                element.onmousedown = function() {
                    return false;
                };
        },

        bindNavKeyBrowse : function() {
            this.jQdocument.bind("keydown.jPages", this.bind(function(evt) {
                var target = evt.target.nodeName.toLowerCase();
                if (this.elemScrolledIntoView() && target !== "input" && target != "textarea") {
                    var newPage = this._currentPageNum;

                    if (evt.which == 37) newPage = this._currentPageNum - 1;
                    if (evt.which == 39) newPage = this._currentPageNum + 1;

                    if (this.validNewPage(newPage)) {
                        this._clicked = true;
                        this.paginate(newPage);
                    }
                }
            }, this));
        },

        elemScrolledIntoView : function() {
            var docViewTop, docViewBottom, elemTop, elemBottom;
            docViewTop = this.jQwindow.scrollTop();
            docViewBottom = docViewTop + this.jQwindow.height();
            elemTop = this._container.offset().top;
            elemBottom = elemTop + this._container.height();
            return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));

            // comment above and uncomment below if you want keyBrowse to happen
            // only when container is completely visible in the page
            /*return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom) &&
             (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );*/
        },

        bindNavScrollBrowse : function() {
            this._container.bind("mousewheel.jPages DOMMouseScroll.jPages", this.bind(function(evt) {
                var newPage = (evt.originalEvent.wheelDelta || -evt.originalEvent.detail) > 0 ?
                    (this._currentPageNum - 1) : (this._currentPageNum + 1);
                if (this.validNewPage(newPage)) {
                    this._clicked = true;
                    this.paginate(newPage);
                }
                evt.preventDefault();
                return false;
            }, this));
        },

        getNewPage : function(nav, target) {
            if (target.is(nav.currentPage)) return this._currentPageNum;
            if (target.is(nav.pages)) return nav.pages.index(target) + 1;
            if (target.is(nav.first)) return 1;
            if (target.is(nav.last)) return this._numPages;
            if (target.is(nav.previous)) return nav.pages.index(nav.currentPage);
            if (target.is(nav.next)) return nav.pages.index(nav.currentPage) + 2;
        },

        validNewPage : function(newPage) {
            return newPage !== this._currentPageNum && newPage > 0 && newPage <= this._numPages;
        },

        paginate : function(page) {
            var itemRange, pageInterval;
            itemRange = this.updateItems(page);
            pageInterval = this.updatePages(page);
            this._currentPageNum = page;
            if ($.isFunction(this.options.callback)) {
                this.callback(page, itemRange, pageInterval);
            }

            this.updatePause();
        },

        updateItems : function(page) {
            var range = this.getItemRange(page);
            this._itemsHiding = this._itemsShowing;
            this._itemsShowing = this._items.slice(range.start, range.end);
            if (this._cssAnimSupport && this.options.animation.length) {
                this.cssAnimations(page);
            } else {
                this.jQAnimations(page);
            }
            return range;
        },

        getItemRange : function(page) {
            var range = {};
            range.start = (page - 1) * this.options.perPage;
            range.end = range.start + this.options.perPage;
            if (range.end > this._items.length) range.end = this._items.length;
            return range;
        },

        cssAnimations : function(page) {
            clearInterval(this._delay);

            this._itemsHiding
                .removeClass(this.options.animation + " jp-invisible")
                .addClass("jp-hidden");

            this._itemsShowing
                .removeClass("jp-hidden")
                .addClass("jp-invisible");

            this._itemsOriented = this.getDirectedItems(page);
            this._index = 0;

            this._itemsOriented
                .removeClass("jp-invisible")
                .addClass(this.options.animation);

            // this._delay = setInterval(this.bind(function() {
            //     console.log('one ny one')
            //     if (this._index === this._itemsOriented.length) clearInterval(this._delay);
            //     else {
            //         this._itemsOriented
            //             .eq(this._index)
            //             .removeClass("jp-invisible")
            //             .addClass(this.options.animation);
            //     }
            //     this._index = this._index + 1;
            // }, this), 0);
        },

        jQAnimations : function(page) {
            clearInterval(this._delay);
            this._itemsHiding.addClass("jp-hidden");
            this._itemsShowing.fadeTo(0, 0).removeClass("jp-hidden");
            this._itemsOriented = this.getDirectedItems(page);
            this._index = 0;
            this._delay = setInterval(this.bind(function() {
                if (this._index === this._itemsOriented.length) clearInterval(this._delay);
                else {
                    this._itemsOriented
                        .eq(this._index)
                        .fadeTo(this.options.fallback, 1);
                }
                this._index = this._index + 1;
            }, this), this.options.delay);
        },

        getDirectedItems : function(page) {
            var itemsToShow;

            switch (this.options.direction) {
                case "backwards":
                    itemsToShow = $(this._itemsShowing.get().reverse());
                    break;
                case "random":
                    itemsToShow = $(this._itemsShowing.get().sort(function() {
                        return (Math.round(Math.random()) - 0.5);
                    }));
                    break;
                case "auto":
                    itemsToShow = page >= this._currentPageNum ?
                        this._itemsShowing : $(this._itemsShowing.get().reverse());
                    break;
                default:
                    itemsToShow = this._itemsShowing;
            }

            return itemsToShow;
        },

        updatePages : function(page) {
            var interval, index, nav;
            interval = this.getInterval(page);
            for (index in this._nav) {
                if (this._nav.hasOwnProperty(index)) {
                    nav = this._nav[index];
                    this.updateBtns(nav, page);
                    this.updateCurrentPage(nav, page);
                    this.updatePagesShowing(nav, interval);
                    this.updateBreaks(nav, interval);
                }
            }
            return interval;
        },

        getInterval : function(page) {
            var neHalf, upperLimit, start, end;
            neHalf = Math.ceil(this.options.midRange / 2);
            upperLimit = this._numPages - this.options.midRange;
            start = page > neHalf ? Math.max(Math.min(page - neHalf, upperLimit), 0) : 0;
            end = page > neHalf ?
                Math.min(page + neHalf - (this.options.midRange % 2 > 0 ? 1 : 0), this._numPages) :
                Math.min(this.options.midRange, this._numPages);
            return {start: start,end: end};
        },

        updateBtns : function(nav, page) {
            if (page === 1) {
                nav.first.addClass("jp-disabled");
                nav.previous.addClass("jp-disabled");
            }
            if (page === this._numPages) {
                nav.next.addClass("jp-disabled");
                nav.last.addClass("jp-disabled");
            }
            if (this._currentPageNum === 1 && page > 1) {
                nav.first.removeClass("jp-disabled");
                nav.previous.removeClass("jp-disabled");
            }
            if (this._currentPageNum === this._numPages && page < this._numPages) {
                nav.next.removeClass("jp-disabled");
                nav.last.removeClass("jp-disabled");
            }
        },

        updateCurrentPage : function(nav, page) {
            nav.currentPage.removeClass("jp-current");
            nav.currentPage = nav.pages.eq(page - 1).addClass("jp-current");
	        this.jQdocument.trigger('list-nav', {page: page, nav: nav})
        },

        updatePagesShowing : function(nav, interval) {
            var newRange = nav.pages.slice(interval.start, interval.end).not(nav.permPages);
            nav.pagesShowing.not(newRange).addClass("jp-hidden");
            newRange.not(nav.pagesShowing).removeClass("jp-hidden");
            nav.pagesShowing = newRange;
        },

        updateBreaks : function(nav, interval) {
            if (
                interval.start > this.options.startRange ||
                (this.options.startRange === 0 && interval.start > 0)
            ) nav.fstBreak.removeClass("jp-hidden");
            else nav.fstBreak.addClass("jp-hidden");

            if (interval.end < this._numPages - this.options.endRange) nav.lstBreak.removeClass("jp-hidden");
            else nav.lstBreak.addClass("jp-hidden");
        },

        callback : function(page, itemRange, pageInterval) {
            var pages = {
                    current: page,
                    interval: pageInterval,
                    count: this._numPages
                },
                items = {
                    showing: this._itemsShowing,
                    oncoming: this._items.slice(itemRange.start + this.options.perPage, itemRange.end + this.options.perPage),
                    range: itemRange,
                    count: this._items.length
                };

            pages.interval.start = pages.interval.start + 1;
            items.range.start = items.range.start + 1;
            this.options.callback(pages, items);
        },

        updatePause : function() {
            if (this.options.pause && this._numPages > 1) {
                clearTimeout(this._pause);
                if (this.options.clickStop && this._clicked) return;
                else {
                    this._pause = setTimeout(this.bind(function() {
                        this.paginate(this._currentPageNum !== this._numPages ? this._currentPageNum + 1 : 1);
                    }, this), this.options.pause);
                }
            }
        },

        setMinHeight : function() {
            if (this.options.minHeight && !this._container.is("table, tbody")) {
                setTimeout(this.bind(function() {
                    this._container.css({ "min-height": this._container.css("height") });
                }, this), 1000);
            }
        },

        bind : function(fn, me) {
            return function() {
                return fn.apply(me, arguments);
            };
        },

        destroy : function() {
            this.jQdocument.unbind("keydown.jPages");
            this._container.unbind("mousewheel.jPages DOMMouseScroll.jPages");

            if (this.options.minHeight) this._container.css("min-height", "");
            if (this._cssAnimSupport && this.options.animation.length)
                this._items.removeClass("animated jp-hidden jp-invisible " + this.options.animation);
            else this._items.removeClass("jp-hidden").fadeTo(0, 1);
            this._holder.unbind("click.jPages").empty();
        }

    };

    $.fn[name] = function(arg) {
        var type = $.type(arg);

        if (type === "object") {
            if (this.length && !$.data(this, name)) {
                instance = new Plugin(this, arg);
                this.each(function() {
                    $.data(this, name, instance);
                });
            }
            return this;
        }

        if (type === "string" && arg === "destroy" && instance) {
            instance.destroy();
            this.each(function() {
                $.removeData(this, name);
            });
            return this;
        }

        if (type === 'number' && arg % 1 === 0) {
            if (instance.validNewPage(arg)) instance.paginate(arg);
            return this;
        }

        return this;
    };

})(jQuery, window, document);

/* Chosen v1.6.2 | (c) 2011-2016 by Harvest | MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md */
(function(){var a,AbstractChosen,Chosen,SelectParser,b,c={}.hasOwnProperty,d=function(a,b){function d(){this.constructor=a}for(var e in b)c.call(b,e)&&(a[e]=b[e]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};SelectParser=function(){function SelectParser(){this.options_index=0,this.parsed=[]}return SelectParser.prototype.add_node=function(a){return"OPTGROUP"===a.nodeName.toUpperCase()?this.add_group(a):this.add_option(a)},SelectParser.prototype.add_group=function(a){var b,c,d,e,f,g;for(b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:this.escapeExpression(a.label),title:a.title?a.title:void 0,children:0,disabled:a.disabled,classes:a.className}),f=a.childNodes,g=[],d=0,e=f.length;e>d;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},SelectParser.prototype.add_option=function(a,b,c){return"OPTION"===a.nodeName.toUpperCase()?(""!==a.text?(null!=b&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,title:a.title?a.title:void 0,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,group_label:null!=b?this.parsed[b].label:null,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1):void 0},SelectParser.prototype.escapeExpression=function(a){var b,c;return null==a||a===!1?"":/[\&\<\>\"\'\`]/.test(a)?(b={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},c=/&(?!\w+;)|[\<\>\"\'\`]/g,a.replace(c,function(a){return b[a]||"&amp;"})):a},SelectParser}(),SelectParser.select_to_array=function(a){var b,c,d,e,f;for(c=new SelectParser,f=a.childNodes,d=0,e=f.length;e>d;d++)b=f[d],c.add_node(b);return c.parsed},AbstractChosen=function(){function AbstractChosen(a,b){this.form_field=a,this.options=null!=b?b:{},AbstractChosen.browser_is_supported()&&(this.is_multiple=this.form_field.multiple,this.set_default_text(),this.set_default_values(),this.setup(),this.set_up_html(),this.register_observers(),this.on_ready())}return AbstractChosen.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.allow_single_deselect=null!=this.options.allow_single_deselect&&null!=this.form_field.options[0]&&""===this.form_field.options[0].text?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.disable_search=this.options.disable_search||!1,this.enable_split_word_search=null!=this.options.enable_split_word_search?this.options.enable_split_word_search:!0,this.group_search=null!=this.options.group_search?this.options.group_search:!0,this.search_contains=this.options.search_contains||!1,this.single_backstroke_delete=null!=this.options.single_backstroke_delete?this.options.single_backstroke_delete:!0,this.max_selected_options=this.options.max_selected_options||1/0,this.inherit_select_classes=this.options.inherit_select_classes||!1,this.display_selected_options=null!=this.options.display_selected_options?this.options.display_selected_options:!0,this.display_disabled_options=null!=this.options.display_disabled_options?this.options.display_disabled_options:!0,this.include_group_label_in_selected=this.options.include_group_label_in_selected||!1,this.max_shown_results=this.options.max_shown_results||Number.POSITIVE_INFINITY,this.case_sensitive_search=this.options.case_sensitive_search||!1},AbstractChosen.prototype.set_default_text=function(){return this.form_field.getAttribute("data-placeholder")?this.default_text=this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.default_text=this.options.placeholder_text_multiple||this.options.placeholder_text||AbstractChosen.default_multiple_text:this.default_text=this.options.placeholder_text_single||this.options.placeholder_text||AbstractChosen.default_single_text,this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||AbstractChosen.default_no_result_text},AbstractChosen.prototype.choice_label=function(a){return this.include_group_label_in_selected&&null!=a.group_label?"<b class='group-name'>"+a.group_label+"</b>"+a.html:a.html},AbstractChosen.prototype.mouse_enter=function(){return this.mouse_on_container=!0},AbstractChosen.prototype.mouse_leave=function(){return this.mouse_on_container=!1},AbstractChosen.prototype.input_focus=function(a){var b=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return b.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()},AbstractChosen.prototype.input_blur=function(a){var b=this;return this.mouse_on_container?void 0:(this.active_field=!1,setTimeout(function(){return b.blur_test()},100))},AbstractChosen.prototype.results_option_build=function(a){var b,c,d,e,f,g,h;for(b="",e=0,h=this.results_data,f=0,g=h.length;g>f&&(c=h[f],d="",d=c.group?this.result_add_group(c):this.result_add_option(c),""!==d&&(e++,b+=d),(null!=a?a.first:void 0)&&(c.selected&&this.is_multiple?this.choice_build(c):c.selected&&!this.is_multiple&&this.single_set_selected_text(this.choice_label(c))),!(e>=this.max_shown_results));f++);return b},AbstractChosen.prototype.result_add_option=function(a){var b,c;return a.search_match&&this.include_option_in_results(a)?(b=[],a.disabled||a.selected&&this.is_multiple||b.push("active-result"),!a.disabled||a.selected&&this.is_multiple||b.push("disabled-result"),a.selected&&b.push("result-selected"),null!=a.group_array_index&&b.push("group-option"),""!==a.classes&&b.push(a.classes),c=document.createElement("li"),c.className=b.join(" "),c.style.cssText=a.style,c.setAttribute("data-option-array-index",a.array_index),c.innerHTML=a.search_text,a.title&&(c.title=a.title),this.outerHTML(c)):""},AbstractChosen.prototype.result_add_group=function(a){var b,c;return(a.search_match||a.group_match)&&a.active_options>0?(b=[],b.push("group-result"),a.classes&&b.push(a.classes),c=document.createElement("li"),c.className=b.join(" "),c.innerHTML=a.search_text,a.title&&(c.title=a.title),this.outerHTML(c)):""},AbstractChosen.prototype.results_update_field=function(){return this.set_default_text(),this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.results_build(),this.results_showing?this.winnow_results():void 0},AbstractChosen.prototype.reset_single_select_options=function(){var a,b,c,d,e;for(d=this.results_data,e=[],b=0,c=d.length;c>b;b++)a=d[b],a.selected?e.push(a.selected=!1):e.push(void 0);return e},AbstractChosen.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},AbstractChosen.prototype.results_search=function(a){return this.results_showing?this.winnow_results():this.results_show()},AbstractChosen.prototype.winnow_results=function(){var a,b,c,d,e,f,g,h,i,j,k,l;for(this.no_results_clear(),d=0,f=this.get_search_text(),a=f.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),i=new RegExp(a,"i"),c=this.get_search_regex(a),l=this.results_data,j=0,k=l.length;k>j;j++)b=l[j],b.search_match=!1,e=null,this.include_option_in_results(b)&&(b.group&&(b.group_match=!1,b.active_options=0),null!=b.group_array_index&&this.results_data[b.group_array_index]&&(e=this.results_data[b.group_array_index],0===e.active_options&&e.search_match&&(d+=1),e.active_options+=1),b.search_text=b.group?b.label:b.html,(!b.group||this.group_search)&&(b.search_match=this.search_string_match(b.search_text,c),b.search_match&&!b.group&&(d+=1),b.search_match?(f.length&&(g=b.search_text.search(i),h=b.search_text.substr(0,g+f.length)+"</em>"+b.search_text.substr(g+f.length),b.search_text=h.substr(0,g)+"<em>"+h.substr(g)),null!=e&&(e.group_match=!0)):null!=b.group_array_index&&this.results_data[b.group_array_index].search_match&&(b.search_match=!0)));return this.result_clear_highlight(),1>d&&f.length?(this.update_results_content(""),this.no_results(f)):(this.update_results_content(this.results_option_build()),this.winnow_results_set_highlight())},AbstractChosen.prototype.get_search_regex=function(a){var b,c;return b=this.search_contains?"":"^",c=this.case_sensitive_search?"":"i",new RegExp(b+a,c)},AbstractChosen.prototype.search_string_match=function(a,b){var c,d,e,f;if(b.test(a))return!0;if(this.enable_split_word_search&&(a.indexOf(" ")>=0||0===a.indexOf("["))&&(d=a.replace(/\[|\]/g,"").split(" "),d.length))for(e=0,f=d.length;f>e;e++)if(c=d[e],b.test(c))return!0},AbstractChosen.prototype.choices_count=function(){var a,b,c,d;if(null!=this.selected_option_count)return this.selected_option_count;for(this.selected_option_count=0,d=this.form_field.options,b=0,c=d.length;c>b;b++)a=d[b],a.selected&&(this.selected_option_count+=1);return this.selected_option_count},AbstractChosen.prototype.choices_click=function(a){return a.preventDefault(),this.results_showing||this.is_disabled?void 0:this.results_show()},AbstractChosen.prototype.keyup_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:if(a.preventDefault(),this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:case 18:break;default:return this.results_search()}},AbstractChosen.prototype.clipboard_event_checker=function(a){var b=this;return setTimeout(function(){return b.results_search()},50)},AbstractChosen.prototype.container_width=function(){return null!=this.options.width?this.options.width:""+this.form_field.offsetWidth+"px"},AbstractChosen.prototype.include_option_in_results=function(a){return this.is_multiple&&!this.display_selected_options&&a.selected?!1:!this.display_disabled_options&&a.disabled?!1:a.empty?!1:!0},AbstractChosen.prototype.search_results_touchstart=function(a){return this.touch_started=!0,this.search_results_mouseover(a)},AbstractChosen.prototype.search_results_touchmove=function(a){return this.touch_started=!1,this.search_results_mouseout(a)},AbstractChosen.prototype.search_results_touchend=function(a){return this.touch_started?this.search_results_mouseup(a):void 0},AbstractChosen.prototype.outerHTML=function(a){var b;return a.outerHTML?a.outerHTML:(b=document.createElement("div"),b.appendChild(a),b.innerHTML)},AbstractChosen.browser_is_supported=function(){return"Microsoft Internet Explorer"===window.navigator.appName?document.documentMode>=8:/iP(od|hone)/i.test(window.navigator.userAgent)||/IEMobile/i.test(window.navigator.userAgent)||/Windows Phone/i.test(window.navigator.userAgent)||/BlackBerry/i.test(window.navigator.userAgent)||/BB10/i.test(window.navigator.userAgent)||/Android.*Mobile/i.test(window.navigator.userAgent)?!1:!0},AbstractChosen.default_multiple_text="Select Some Options",AbstractChosen.default_single_text="Select an Option",AbstractChosen.default_no_result_text="No results match",AbstractChosen}(),a=jQuery,a.fn.extend({chosen:function(b){return AbstractChosen.browser_is_supported()?this.each(function(c){var d,e;return d=a(this),e=d.data("chosen"),"destroy"===b?void(e instanceof Chosen&&e.destroy()):void(e instanceof Chosen||d.data("chosen",new Chosen(this,b)))}):this}}),Chosen=function(c){function Chosen(){return b=Chosen.__super__.constructor.apply(this,arguments)}return d(Chosen,c),Chosen.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.current_selectedIndex=this.form_field.selectedIndex,this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")},Chosen.prototype.set_up_html=function(){var b,c;return b=["chosen-container"],b.push("chosen-container-"+(this.is_multiple?"multi":"single")),this.inherit_select_classes&&this.form_field.className&&b.push(this.form_field.className),this.is_rtl&&b.push("chosen-rtl"),c={"class":b.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title},this.form_field.id.length&&(c.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen"),this.container=a("<div />",c),this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),this.form_field_jq.hide().after(this.container),this.dropdown=this.container.find("div.chosen-drop").first(),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chosen-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chosen-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chosen-search").first(),this.selected_item=this.container.find(".chosen-single").first()),this.results_build(),this.set_tab_index(),this.set_label_behavior()},Chosen.prototype.on_ready=function(){return this.form_field_jq.trigger("chosen:ready",{chosen:this})},Chosen.prototype.register_observers=function(){var a=this;return this.container.bind("touchstart.chosen",function(b){return a.container_mousedown(b),b.preventDefault()}),this.container.bind("touchend.chosen",function(b){return a.container_mouseup(b),b.preventDefault()}),this.container.bind("mousedown.chosen",function(b){a.container_mousedown(b)}),this.container.bind("mouseup.chosen",function(b){a.container_mouseup(b)}),this.container.bind("mouseenter.chosen",function(b){a.mouse_enter(b)}),this.container.bind("mouseleave.chosen",function(b){a.mouse_leave(b)}),this.search_results.bind("mouseup.chosen",function(b){a.search_results_mouseup(b)}),this.search_results.bind("mouseover.chosen",function(b){a.search_results_mouseover(b)}),this.search_results.bind("mouseout.chosen",function(b){a.search_results_mouseout(b)}),this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(b){a.search_results_mousewheel(b)}),this.search_results.bind("touchstart.chosen",function(b){a.search_results_touchstart(b)}),this.search_results.bind("touchmove.chosen",function(b){a.search_results_touchmove(b)}),this.search_results.bind("touchend.chosen",function(b){a.search_results_touchend(b)}),this.form_field_jq.bind("chosen:updated.chosen",function(b){a.results_update_field(b)}),this.form_field_jq.bind("chosen:activate.chosen",function(b){a.activate_field(b)}),this.form_field_jq.bind("chosen:open.chosen",function(b){a.container_mousedown(b)}),this.form_field_jq.bind("chosen:close.chosen",function(b){a.input_blur(b)}),this.search_field.bind("blur.chosen",function(b){a.input_blur(b)}),this.search_field.bind("keyup.chosen",function(b){a.keyup_checker(b)}),this.search_field.bind("keydown.chosen",function(b){a.keydown_checker(b)}),this.search_field.bind("focus.chosen",function(b){a.input_focus(b)}),this.search_field.bind("cut.chosen",function(b){a.clipboard_event_checker(b)}),this.search_field.bind("paste.chosen",function(b){a.clipboard_event_checker(b)}),this.is_multiple?this.search_choices.bind("click.chosen",function(b){a.choices_click(b)}):this.container.bind("click.chosen",function(a){a.preventDefault()})},Chosen.prototype.destroy=function(){return a(this.container[0].ownerDocument).unbind("click.chosen",this.click_test_action),this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex),this.container.remove(),this.form_field_jq.removeData("chosen"),this.form_field_jq.show()},Chosen.prototype.search_field_disabled=function(){return this.is_disabled=this.form_field_jq[0].disabled,this.is_disabled?(this.container.addClass("chosen-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action),this.close_field()):(this.container.removeClass("chosen-disabled"),this.search_field[0].disabled=!1,this.is_multiple?void 0:this.selected_item.bind("focus.chosen",this.activate_action))},Chosen.prototype.container_mousedown=function(b){return this.is_disabled||(b&&"mousedown"===b.type&&!this.results_showing&&b.preventDefault(),null!=b&&a(b.target).hasClass("search-choice-close"))?void 0:(this.active_field?this.is_multiple||!b||a(b.target)[0]!==this.selected_item[0]&&!a(b.target).parents("a.chosen-single").length||(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(this.container[0].ownerDocument).bind("click.chosen",this.click_test_action),this.results_show()),this.activate_field())},Chosen.prototype.container_mouseup=function(a){return"ABBR"!==a.target.nodeName||this.is_disabled?void 0:this.results_reset(a)},Chosen.prototype.search_results_mousewheel=function(a){var b;return a.originalEvent&&(b=a.originalEvent.deltaY||-a.originalEvent.wheelDelta||a.originalEvent.detail),null!=b?(a.preventDefault(),"DOMMouseScroll"===a.type&&(b=40*b),this.search_results.scrollTop(b+this.search_results.scrollTop())):void 0},Chosen.prototype.blur_test=function(a){return!this.active_field&&this.container.hasClass("chosen-container-active")?this.close_field():void 0},Chosen.prototype.close_field=function(){return a(this.container[0].ownerDocument).unbind("click.chosen",this.click_test_action),this.active_field=!1,this.results_hide(),this.container.removeClass("chosen-container-active"),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},Chosen.prototype.activate_field=function(){return this.container.addClass("chosen-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},Chosen.prototype.test_active_click=function(b){var c;return c=a(b.target).closest(".chosen-container"),c.length&&this.container[0]===c[0]?this.active_field=!0:this.close_field()},Chosen.prototype.results_build=function(){return this.parsing=!0,this.selected_option_count=null,this.results_data=SelectParser.select_to_array(this.form_field),this.is_multiple?this.search_choices.find("li.search-choice").remove():this.is_multiple||(this.single_set_selected_text(),this.disable_search||this.form_field.options.length<=this.disable_search_threshold?(this.search_field[0].readOnly=!0,this.container.addClass("chosen-container-single-nosearch")):(this.search_field[0].readOnly=!1,this.container.removeClass("chosen-container-single-nosearch"))),this.update_results_content(this.results_option_build({first:!0})),this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.parsing=!1},Chosen.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){if(this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight(),b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(f>c)return this.search_results.scrollTop(c)}},Chosen.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},Chosen.prototype.results_show=function(){return this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.container.addClass("chosen-with-drop"),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results(),this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this}))},Chosen.prototype.update_results_content=function(a){return this.search_results.html(a)},Chosen.prototype.results_hide=function(){return this.results_showing&&(this.result_clear_highlight(),this.container.removeClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})),this.results_showing=!1},Chosen.prototype.set_tab_index=function(a){var b;return this.form_field.tabIndex?(b=this.form_field.tabIndex,this.form_field.tabIndex=-1,this.search_field[0].tabIndex=b):void 0},Chosen.prototype.set_label_behavior=function(){var b=this;return this.form_field_label=this.form_field_jq.parents("label"),!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=a("label[for='"+this.form_field.id+"']")),this.form_field_label.length>0?this.form_field_label.bind("click.chosen",function(a){return b.is_multiple?b.container_mousedown(a):b.activate_field()}):void 0},Chosen.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices_count()<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},Chosen.prototype.search_results_mouseup=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c.length?(this.result_highlight=c,this.result_select(b),this.search_field.focus()):void 0},Chosen.prototype.search_results_mouseover=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c?this.result_do_highlight(c):void 0},Chosen.prototype.search_results_mouseout=function(b){return a(b.target).hasClass("active-result")?this.result_clear_highlight():void 0},Chosen.prototype.choice_build=function(b){var c,d,e=this;return c=a("<li />",{"class":"search-choice"}).html("<span>"+this.choice_label(b)+"</span>"),b.disabled?c.addClass("search-choice-disabled"):(d=a("<a />",{"class":"search-choice-close","data-option-array-index":b.array_index}),d.bind("click.chosen",function(a){return e.choice_destroy_link_click(a)}),c.append(d)),this.search_container.before(c)},Chosen.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),b.stopPropagation(),this.is_disabled?void 0:this.choice_destroy(a(b.target))},Chosen.prototype.choice_destroy=function(a){return this.result_deselect(a[0].getAttribute("data-option-array-index"))?(this.show_search_field_default(),this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide(),a.parents("li").first().remove(),this.search_field_scale()):void 0},Chosen.prototype.results_reset=function(){return this.reset_single_select_options(),this.form_field.options[0].selected=!0,this.single_set_selected_text(),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change"),this.active_field?this.results_hide():void 0},Chosen.prototype.results_reset_cleanup=function(){return this.current_selectedIndex=this.form_field.selectedIndex,this.selected_item.find("abbr").remove()},Chosen.prototype.result_select=function(a){var b,c;return this.result_highlight?(b=this.result_highlight,this.result_clear_highlight(),this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.is_multiple?b.removeClass("active-result"):this.reset_single_select_options(),b.addClass("result-selected"),c=this.results_data[b[0].getAttribute("data-option-array-index")],c.selected=!0,this.form_field.options[c.options_index].selected=!0,this.selected_option_count=null,this.is_multiple?this.choice_build(c):this.single_set_selected_text(this.choice_label(c)),(a.metaKey||a.ctrlKey)&&this.is_multiple||this.results_hide(),this.show_search_field_default(),(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[c.options_index].value}),this.current_selectedIndex=this.form_field.selectedIndex,a.preventDefault(),this.search_field_scale())):void 0},Chosen.prototype.single_set_selected_text=function(a){return null==a&&(a=this.default_text),a===this.default_text?this.selected_item.addClass("chosen-default"):(this.single_deselect_control_build(),this.selected_item.removeClass("chosen-default")),this.selected_item.find("span").html(a)},Chosen.prototype.result_deselect=function(a){var b;return b=this.results_data[a],this.form_field.options[b.options_index].disabled?!1:(b.selected=!1,this.form_field.options[b.options_index].selected=!1,this.selected_option_count=null,this.result_clear_highlight(),this.results_showing&&this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[b.options_index].value}),this.search_field_scale(),!0)},Chosen.prototype.single_deselect_control_build=function(){return this.allow_single_deselect?(this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'),this.selected_item.addClass("chosen-single-with-deselect")):void 0},Chosen.prototype.get_search_text=function(){return a("<div/>").text(a.trim(this.search_field.val())).html()},Chosen.prototype.winnow_results_set_highlight=function(){var a,b;return b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first(),null!=a?this.result_do_highlight(a):void 0},Chosen.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c),this.form_field_jq.trigger("chosen:no_results",{chosen:this})},Chosen.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},Chosen.prototype.keydown_arrow=function(){var a;return this.results_showing&&this.result_highlight?(a=this.result_highlight.nextAll("li.active-result").first())?this.result_do_highlight(a):void 0:this.results_show()},Chosen.prototype.keyup_arrow=function(){var a;return this.results_showing||this.is_multiple?this.result_highlight?(a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices_count()>0&&this.results_hide(),this.result_clear_highlight())):void 0:this.results_show()},Chosen.prototype.keydown_backstroke=function(){var a;return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(a=this.search_container.siblings("li.search-choice").last(),a.length&&!a.hasClass("search-choice-disabled")?(this.pending_backstroke=a,this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")):void 0)},Chosen.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},Chosen.prototype.keydown_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),8!==b&&this.pending_backstroke&&this.clear_backstroke(),b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:this.results_showing&&a.preventDefault();break;case 32:this.disable_search&&a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:a.preventDefault(),this.keydown_arrow()}},Chosen.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){for(d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"],i=0,j=g.length;j>i;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return b=a("<div />",{style:f}),b.text(this.search_field.val()),a("body").append(b),h=b.width()+25,b.remove(),c=this.container.outerWidth(),h>c-10&&(h=c-10),this.search_field.css({width:h+"px"})}},Chosen}(AbstractChosen)}).call(this);

/*! rangeslider.js - v2.2.1 | (c) 2016 @andreruffert | MIT license | https://github.com/andreruffert/rangeslider.js */
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){"use strict";function b(){var a=document.createElement("input");return a.setAttribute("type","range"),"text"!==a.type}function c(a,b){var c=Array.prototype.slice.call(arguments,2);return setTimeout(function(){return a.apply(null,c)},b)}function d(a,b){return b=b||100,function(){if(!a.debouncing){var c=Array.prototype.slice.apply(arguments);a.lastReturnVal=a.apply(window,c),a.debouncing=!0}return clearTimeout(a.debounceTimeout),a.debounceTimeout=setTimeout(function(){a.debouncing=!1},b),a.lastReturnVal}}function e(a){return a&&(0===a.offsetWidth||0===a.offsetHeight||a.open===!1)}function f(a){for(var b=[],c=a.parentNode;e(c);)b.push(c),c=c.parentNode;return b}function g(a,b){function c(a){"undefined"!=typeof a.open&&(a.open=!a.open)}var d=f(a),e=d.length,g=[],h=a[b];if(e){for(var i=0;e>i;i++)g[i]=d[i].style.cssText,d[i].style.setProperty?d[i].style.setProperty("display","block","important"):d[i].style.cssText+=";display: block !important",d[i].style.height="0",d[i].style.overflow="hidden",d[i].style.visibility="hidden",c(d[i]);h=a[b];for(var j=0;e>j;j++)d[j].style.cssText=g[j],c(d[j])}return h}function h(a,b){var c=parseFloat(a);return Number.isNaN(c)?b:c}function i(a){return a.charAt(0).toUpperCase()+a.substr(1)}function j(b,e){if(this.$window=a(window),this.$document=a(document),this.$element=a(b),this.options=a.extend({},n,e),this.polyfill=this.options.polyfill,this.orientation=this.$element[0].getAttribute("data-orientation")||this.options.orientation,this.onInit=this.options.onInit,this.onSlide=this.options.onSlide,this.onSlideEnd=this.options.onSlideEnd,this.DIMENSION=o.orientation[this.orientation].dimension,this.DIRECTION=o.orientation[this.orientation].direction,this.DIRECTION_STYLE=o.orientation[this.orientation].directionStyle,this.COORDINATE=o.orientation[this.orientation].coordinate,this.polyfill&&m)return!1;this.identifier="js-"+k+"-"+l++,this.startEvent=this.options.startEvent.join("."+this.identifier+" ")+"."+this.identifier,this.moveEvent=this.options.moveEvent.join("."+this.identifier+" ")+"."+this.identifier,this.endEvent=this.options.endEvent.join("."+this.identifier+" ")+"."+this.identifier,this.toFixed=(this.step+"").replace(".","").length-1,this.$fill=a('<div class="'+this.options.fillClass+'" />'),this.$handle=a('<div class="'+this.options.handleClass+'" />'),this.$range=a('<div class="'+this.options.rangeClass+" "+this.options[this.orientation+"Class"]+'" id="'+this.identifier+'" />').insertAfter(this.$element).prepend(this.$fill,this.$handle),this.$element.css({position:"absolute",width:"1px",height:"1px",overflow:"hidden",opacity:"0"}),this.handleDown=a.proxy(this.handleDown,this),this.handleMove=a.proxy(this.handleMove,this),this.handleEnd=a.proxy(this.handleEnd,this),this.init();var f=this;this.$window.on("resize."+this.identifier,d(function(){c(function(){f.update(!1,!1)},300)},20)),this.$document.on(this.startEvent,"#"+this.identifier+":not(."+this.options.disabledClass+")",this.handleDown),this.$element.on("change."+this.identifier,function(a,b){if(!b||b.origin!==f.identifier){var c=a.target.value,d=f.getPositionFromValue(c);f.setPosition(d)}})}Number.isNaN=Number.isNaN||function(a){return"number"==typeof a&&a!==a};var k="rangeslider",l=0,m=b(),n={polyfill:!0,orientation:"horizontal",rangeClass:"rangeslider",disabledClass:"rangeslider--disabled",horizontalClass:"rangeslider--horizontal",verticalClass:"rangeslider--vertical",fillClass:"rangeslider__fill",handleClass:"rangeslider__handle",startEvent:["mousedown","touchstart","pointerdown"],moveEvent:["mousemove","touchmove","pointermove"],endEvent:["mouseup","touchend","pointerup"]},o={orientation:{horizontal:{dimension:"width",direction:"left",directionStyle:"left",coordinate:"x"},vertical:{dimension:"height",direction:"top",directionStyle:"bottom",coordinate:"y"}}};return j.prototype.init=function(){this.update(!0,!1),this.onInit&&"function"==typeof this.onInit&&this.onInit()},j.prototype.update=function(a,b){a=a||!1,a&&(this.min=h(this.$element[0].getAttribute("min"),0),this.max=h(this.$element[0].getAttribute("max"),100),this.value=h(this.$element[0].value,Math.round(this.min+(this.max-this.min)/2)),this.step=h(this.$element[0].getAttribute("step"),1)),this.handleDimension=g(this.$handle[0],"offset"+i(this.DIMENSION)),this.rangeDimension=g(this.$range[0],"offset"+i(this.DIMENSION)),this.maxHandlePos=this.rangeDimension-this.handleDimension,this.grabPos=this.handleDimension/2,this.position=this.getPositionFromValue(this.value),this.$element[0].disabled?this.$range.addClass(this.options.disabledClass):this.$range.removeClass(this.options.disabledClass),this.setPosition(this.position,b)},j.prototype.handleDown=function(a){if(this.$document.on(this.moveEvent,this.handleMove),this.$document.on(this.endEvent,this.handleEnd),!((" "+a.target.className+" ").replace(/[\n\t]/g," ").indexOf(this.options.handleClass)>-1)){var b=this.getRelativePosition(a),c=this.$range[0].getBoundingClientRect()[this.DIRECTION],d=this.getPositionFromNode(this.$handle[0])-c,e="vertical"===this.orientation?this.maxHandlePos-(b-this.grabPos):b-this.grabPos;this.setPosition(e),b>=d&&b<d+this.handleDimension&&(this.grabPos=b-d)}},j.prototype.handleMove=function(a){a.preventDefault();var b=this.getRelativePosition(a),c="vertical"===this.orientation?this.maxHandlePos-(b-this.grabPos):b-this.grabPos;this.setPosition(c)},j.prototype.handleEnd=function(a){a.preventDefault(),this.$document.off(this.moveEvent,this.handleMove),this.$document.off(this.endEvent,this.handleEnd),this.$element.trigger("change",{origin:this.identifier}),this.onSlideEnd&&"function"==typeof this.onSlideEnd&&this.onSlideEnd(this.position,this.value)},j.prototype.cap=function(a,b,c){return b>a?b:a>c?c:a},j.prototype.setPosition=function(a,b){var c,d;void 0===b&&(b=!0),c=this.getValueFromPosition(this.cap(a,0,this.maxHandlePos)),d=this.getPositionFromValue(c),this.$fill[0].style[this.DIMENSION]=d+this.grabPos+"px",this.$handle[0].style[this.DIRECTION_STYLE]=d+"px",this.setValue(c),this.position=d,this.value=c,b&&this.onSlide&&"function"==typeof this.onSlide&&this.onSlide(d,c)},j.prototype.getPositionFromNode=function(a){for(var b=0;null!==a;)b+=a.offsetLeft,a=a.offsetParent;return b},j.prototype.getRelativePosition=function(a){var b=i(this.COORDINATE),c=this.$range[0].getBoundingClientRect()[this.DIRECTION],d=0;return"undefined"!=typeof a["page"+b]?d=a["page"+b]:"undefined"!=typeof a.originalEvent["client"+b]?d=a.originalEvent["client"+b]:a.originalEvent.touches&&a.originalEvent.touches[0]&&"undefined"!=typeof a.originalEvent.touches[0]["page"+b]?d=a.originalEvent.touches[0]["page"+b]:a.currentPoint&&"undefined"!=typeof a.currentPoint[this.COORDINATE]&&(d=a.currentPoint[this.COORDINATE]),d-c},j.prototype.getPositionFromValue=function(a){var b,c;return b=(a-this.min)/(this.max-this.min),c=Number.isNaN(b)?0:b*this.maxHandlePos},j.prototype.getValueFromPosition=function(a){var b,c;return b=a/(this.maxHandlePos||1),c=this.step*Math.round(b*(this.max-this.min)/this.step)+this.min,Number(c.toFixed(this.toFixed))},j.prototype.setValue=function(a){a===this.value&&""!==this.$element[0].value||this.$element.val(a).trigger("input",{origin:this.identifier})},j.prototype.destroy=function(){this.$document.off("."+this.identifier),this.$window.off("."+this.identifier),this.$element.off("."+this.identifier).removeAttr("style").removeData("plugin_"+k),this.$range&&this.$range.length&&this.$range[0].parentNode.removeChild(this.$range[0])},a.fn[k]=function(b){var c=Array.prototype.slice.call(arguments,1);return this.each(function(){var d=a(this),e=d.data("plugin_"+k);e||d.data("plugin_"+k,e=new j(this,b)),"string"==typeof b&&e[b].apply(e,c)})},"rangeslider.js is available in jQuery context e.g $(selector).rangeslider(options);"});
/**!
 * Sortable
 * @author	RubaXa   <trash@rubaxa.org>
 * @license MIT
 */

(function sortableModule(factory) {
    "use strict";

    if (typeof define === "function" && define.amd) {
        define(factory);
    }
    else if (typeof module != "undefined" && typeof module.exports != "undefined") {
        module.exports = factory();
    }
    else {
        /* jshint sub:true */
        window["Sortable"] = factory();
    }
})(function sortableFactory() {
    "use strict";

    if (typeof window === "undefined" || !window.document) {
        return function sortableError() {
            throw new Error("Sortable.js requires a window with a document");
        };
    }

    var dragEl,
        parentEl,
        ghostEl,
        cloneEl,
        rootEl,
        nextEl,
        lastDownEl,

        scrollEl,
        scrollParentEl,
        scrollCustomFn,

        lastEl,
        lastCSS,
        lastParentCSS,

        oldIndex,
        newIndex,

        activeGroup,
        putSortable,

        autoScroll = {},

        tapEvt,
        touchEvt,

        moved,

        forRepaintDummy,

        /** @const */
        R_SPACE = /\s+/g,
        R_FLOAT = /left|right|inline/,

        expando = 'Sortable' + (new Date).getTime(),

        win = window,
        document = win.document,
        parseInt = win.parseInt,
        setTimeout = win.setTimeout,

        $ = win.jQuery || win.Zepto,
        Polymer = win.Polymer,

        captureMode = false,
        passiveMode = false,

        supportDraggable = ('draggable' in document.createElement('div')),
        supportCssPointerEvents = (function (el) {
            // false when IE11
            if (!!navigator.userAgent.match(/(?:Trident.*rv[ :]?11\.|msie)/i)) {
                return false;
            }
            el = document.createElement('x');
            el.style.cssText = 'pointer-events:auto';
            return el.style.pointerEvents === 'auto';
        })(),

        _silent = false,

        abs = Math.abs,
        min = Math.min,

        savedInputChecked = [],
        touchDragOverListeners = [],

        alwaysFalse = function () { return false; },

        _autoScroll = _throttle(function (/**Event*/evt, /**Object*/options, /**HTMLElement*/rootEl) {
            // Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=505521
            if (rootEl && options.scroll) {
                var _this = rootEl[expando],
                    el,
                    rect,
                    sens = options.scrollSensitivity,
                    speed = options.scrollSpeed,

                    x = evt.clientX,
                    y = evt.clientY,

                    winWidth = window.innerWidth,
                    winHeight = window.innerHeight,

                    vx,
                    vy,

                    scrollOffsetX,
                    scrollOffsetY
                ;

                // Delect scrollEl
                if (scrollParentEl !== rootEl) {
                    scrollEl = options.scroll;
                    scrollParentEl = rootEl;
                    scrollCustomFn = options.scrollFn;

                    if (scrollEl === true) {
                        scrollEl = rootEl;

                        do {
                            if ((scrollEl.offsetWidth < scrollEl.scrollWidth) ||
                                (scrollEl.offsetHeight < scrollEl.scrollHeight)
                            ) {
                                break;
                            }
                            /* jshint boss:true */
                        } while (scrollEl = scrollEl.parentNode);
                    }
                }

                if (scrollEl) {
                    el = scrollEl;
                    rect = scrollEl.getBoundingClientRect();
                    vx = (abs(rect.right - x) <= sens) - (abs(rect.left - x) <= sens);
                    vy = (abs(rect.bottom - y) <= sens) - (abs(rect.top - y) <= sens);
                }


                if (!(vx || vy)) {
                    vx = (winWidth - x <= sens) - (x <= sens);
                    vy = (winHeight - y <= sens) - (y <= sens);

                    /* jshint expr:true */
                    (vx || vy) && (el = win);
                }


                if (autoScroll.vx !== vx || autoScroll.vy !== vy || autoScroll.el !== el) {
                    autoScroll.el = el;
                    autoScroll.vx = vx;
                    autoScroll.vy = vy;

                    clearInterval(autoScroll.pid);

                    if (el) {
                        autoScroll.pid = setInterval(function () {
                            scrollOffsetY = vy ? vy * speed : 0;
                            scrollOffsetX = vx ? vx * speed : 0;

                            if ('function' === typeof(scrollCustomFn)) {
                                if (scrollCustomFn.call(_this, scrollOffsetX, scrollOffsetY, evt, touchEvt, el) !== 'continue') {
                                    return;
                                }
                            }

                            if (el === win) {
                                win.scrollTo(win.pageXOffset + scrollOffsetX, win.pageYOffset + scrollOffsetY);
                            } else {
                                el.scrollTop += scrollOffsetY;
                                el.scrollLeft += scrollOffsetX;
                            }
                        }, 24);
                    }
                }
            }
        }, 30),

        _prepareGroup = function (options) {
            function toFn(value, pull) {
                if (value == null || value === true) {
                    value = group.name;
                    if (value == null) {
                        return alwaysFalse;
                    }
                }

                if (typeof value === 'function') {
                    return value;
                } else {
                    return function (to, from) {
                        var fromGroup = from.options.group.name;

                        return pull
                            ? value
                            : value && (value.join
                                ? value.indexOf(fromGroup) > -1
                                : (fromGroup == value)
                        );
                    };
                }
            }

            var group = {};
            var originalGroup = options.group;

            if (!originalGroup || typeof originalGroup != 'object') {
                originalGroup = {name: originalGroup};
            }

            group.name = originalGroup.name;
            group.checkPull = toFn(originalGroup.pull, true);
            group.checkPut = toFn(originalGroup.put);
            group.revertClone = originalGroup.revertClone;

            options.group = group;
        }
    ;

    // Detect support a passive mode
    try {
        window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
            get: function () {
                // `false`, because everything starts to work incorrectly and instead of d'n'd,
                // begins the page has scrolled.
                passiveMode = false;
                captureMode = {
                    capture: false,
                    passive: passiveMode
                };
            }
        }));
    } catch (err) {}

    /**
     * @class  Sortable
     * @param  {HTMLElement}  el
     * @param  {Object}       [options]
     */
    function Sortable(el, options) {
        if (!(el && el.nodeType && el.nodeType === 1)) {
            throw 'Sortable: `el` must be HTMLElement, and not ' + {}.toString.call(el);
        }

        this.el = el; // root element
        this.options = options = _extend({}, options);


        // Export instance
        el[expando] = this;

        // Default options
        var defaults = {
            group: null,
            sort: true,
            disabled: false,
            store: null,
            handle: null,
            scroll: true,
            scrollSensitivity: 30,
            scrollSpeed: 10,
            draggable: /[uo]l/i.test(el.nodeName) ? 'li' : '>*',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            ignore: 'a, img',
            filter: null,
            preventOnFilter: true,
            animation: 0,
            setData: function (dataTransfer, dragEl) {
                dataTransfer.setData('Text', dragEl.textContent);
            },
            dropBubble: false,
            dragoverBubble: false,
            dataIdAttr: 'data-id',
            delay: 0,
            touchStartThreshold: parseInt(window.devicePixelRatio, 10) || 1,
            forceFallback: false,
            fallbackClass: 'sortable-fallback',
            fallbackOnBody: false,
            fallbackTolerance: 0,
            fallbackOffset: {x: 0, y: 0},
            supportPointer: Sortable.supportPointer !== false
        };


        // Set default options
        for (var name in defaults) {
            !(name in options) && (options[name] = defaults[name]);
        }

        _prepareGroup(options);

        // Bind all private methods
        for (var fn in this) {
            if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
                this[fn] = this[fn].bind(this);
            }
        }

        // Setup drag mode
        this.nativeDraggable = options.forceFallback ? false : supportDraggable;

        // Bind events
        _on(el, 'mousedown', this._onTapStart);
        _on(el, 'touchstart', this._onTapStart);
        options.supportPointer && _on(el, 'pointerdown', this._onTapStart);

        if (this.nativeDraggable) {
            _on(el, 'dragover', this);
            _on(el, 'dragenter', this);
        }

        touchDragOverListeners.push(this._onDragOver);

        // Restore sorting
        options.store && this.sort(options.store.get(this));
    }


    Sortable.prototype = /** @lends Sortable.prototype */ {
        constructor: Sortable,

        _onTapStart: function (/** Event|TouchEvent */evt) {
            var _this = this,
                el = this.el,
                options = this.options,
                preventOnFilter = options.preventOnFilter,
                type = evt.type,
                touch = evt.touches && evt.touches[0],
                target = (touch || evt).target,
                originalTarget = evt.target.shadowRoot && (evt.path && evt.path[0]) || target,
                filter = options.filter,
                startIndex;

            _saveInputCheckedState(el);


            // Don't trigger start event when an element is been dragged, otherwise the evt.oldindex always wrong when set option.group.
            if (dragEl) {
                return;
            }

            if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
                return; // only left button or enabled
            }

            // cancel dnd if original target is content editable
            if (originalTarget.isContentEditable) {
                return;
            }

            target = _closest(target, options.draggable, el);

            if (!target) {
                return;
            }

            if (lastDownEl === target) {
                // Ignoring duplicate `down`
                return;
            }

            // Get the index of the dragged element within its parent
            startIndex = _index(target, options.draggable);

            // Check filter
            if (typeof filter === 'function') {
                if (filter.call(this, evt, target, this)) {
                    _dispatchEvent(_this, originalTarget, 'filter', target, el, el, startIndex);
                    preventOnFilter && evt.preventDefault();
                    return; // cancel dnd
                }
            }
            else if (filter) {
                filter = filter.split(',').some(function (criteria) {
                    criteria = _closest(originalTarget, criteria.trim(), el);

                    if (criteria) {
                        _dispatchEvent(_this, criteria, 'filter', target, el, el, startIndex);
                        return true;
                    }
                });

                if (filter) {
                    preventOnFilter && evt.preventDefault();
                    return; // cancel dnd
                }
            }

            if (options.handle && !_closest(originalTarget, options.handle, el)) {
                return;
            }

            // Prepare `dragstart`
            this._prepareDragStart(evt, touch, target, startIndex);
        },

        _prepareDragStart: function (/** Event */evt, /** Touch */touch, /** HTMLElement */target, /** Number */startIndex) {
            var _this = this,
                el = _this.el,
                options = _this.options,
                ownerDocument = el.ownerDocument,
                dragStartFn;

            if (target && !dragEl && (target.parentNode === el)) {
                tapEvt = evt;

                rootEl = el;
                dragEl = target;
                parentEl = dragEl.parentNode;
                nextEl = dragEl.nextSibling;
                lastDownEl = target;
                activeGroup = options.group;
                oldIndex = startIndex;

                this._lastX = (touch || evt).clientX;
                this._lastY = (touch || evt).clientY;

                dragEl.style['will-change'] = 'all';

                dragStartFn = function () {
                    // Delayed drag has been triggered
                    // we can re-enable the events: touchmove/mousemove
                    _this._disableDelayedDrag();

                    // Make the element draggable
                    dragEl.draggable = _this.nativeDraggable;

                    // Chosen item
                    _toggleClass(dragEl, options.chosenClass, true);

                    // Bind the events: dragstart/dragend
                    _this._triggerDragStart(evt, touch);

                    // Drag start event
                    _dispatchEvent(_this, rootEl, 'choose', dragEl, rootEl, rootEl, oldIndex);
                };

                // Disable "draggable"
                options.ignore.split(',').forEach(function (criteria) {
                    _find(dragEl, criteria.trim(), _disableDraggable);
                });

                _on(ownerDocument, 'mouseup', _this._onDrop);
                _on(ownerDocument, 'touchend', _this._onDrop);
                _on(ownerDocument, 'touchcancel', _this._onDrop);
                _on(ownerDocument, 'selectstart', _this);
                options.supportPointer && _on(ownerDocument, 'pointercancel', _this._onDrop);

                if (options.delay) {
                    // If the user moves the pointer or let go the click or touch
                    // before the delay has been reached:
                    // disable the delayed drag
                    _on(ownerDocument, 'mouseup', _this._disableDelayedDrag);
                    _on(ownerDocument, 'touchend', _this._disableDelayedDrag);
                    _on(ownerDocument, 'touchcancel', _this._disableDelayedDrag);
                    _on(ownerDocument, 'mousemove', _this._disableDelayedDrag);
                    _on(ownerDocument, 'touchmove', _this._delayedDragTouchMoveHandler);
                    options.supportPointer && _on(ownerDocument, 'pointermove', _this._delayedDragTouchMoveHandler);

                    _this._dragStartTimer = setTimeout(dragStartFn, options.delay);
                } else {
                    dragStartFn();
                }


            }
        },

        _delayedDragTouchMoveHandler: function (/** TouchEvent|PointerEvent **/e) {
            if (min(abs(e.clientX - this._lastX), abs(e.clientY - this._lastY)) >= this.options.touchStartThreshold) {
                this._disableDelayedDrag();
            }
        },

        _disableDelayedDrag: function () {
            var ownerDocument = this.el.ownerDocument;

            clearTimeout(this._dragStartTimer);
            _off(ownerDocument, 'mouseup', this._disableDelayedDrag);
            _off(ownerDocument, 'touchend', this._disableDelayedDrag);
            _off(ownerDocument, 'touchcancel', this._disableDelayedDrag);
            _off(ownerDocument, 'mousemove', this._disableDelayedDrag);
            _off(ownerDocument, 'touchmove', this._disableDelayedDrag);
            _off(ownerDocument, 'pointermove', this._disableDelayedDrag);
        },

        _triggerDragStart: function (/** Event */evt, /** Touch */touch) {
            touch = touch || (evt.pointerType == 'touch' ? evt : null);

            if (touch) {
                // Touch device support
                tapEvt = {
                    target: dragEl,
                    clientX: touch.clientX,
                    clientY: touch.clientY
                };

                this._onDragStart(tapEvt, 'touch');
            }
            else if (!this.nativeDraggable) {
                this._onDragStart(tapEvt, true);
            }
            else {
                _on(dragEl, 'dragend', this);
                _on(rootEl, 'dragstart', this._onDragStart);
            }

            try {
                if (document.selection) {
                    // Timeout neccessary for IE9
                    _nextTick(function () {
                        document.selection.empty();
                    });
                } else {
                    window.getSelection().removeAllRanges();
                }
            } catch (err) {
            }
        },

        _dragStarted: function () {
            if (rootEl && dragEl) {
                var options = this.options;

                // Apply effect
                _toggleClass(dragEl, options.ghostClass, true);
                _toggleClass(dragEl, options.dragClass, false);

                Sortable.active = this;

                // Drag start event
                _dispatchEvent(this, rootEl, 'start', dragEl, rootEl, rootEl, oldIndex);
            } else {
                this._nulling();
            }
        },

        _emulateDragOver: function () {
            if (touchEvt) {
                if (this._lastX === touchEvt.clientX && this._lastY === touchEvt.clientY) {
                    return;
                }

                this._lastX = touchEvt.clientX;
                this._lastY = touchEvt.clientY;

                if (!supportCssPointerEvents) {
                    _css(ghostEl, 'display', 'none');
                }

                var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
                var parent = target;
                var i = touchDragOverListeners.length;

                while (target && target.shadowRoot) {
                    target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
                    parent = target;
                }

                if (parent) {
                    do {
                        if (parent[expando]) {
                            while (i--) {
                                touchDragOverListeners[i]({
                                    clientX: touchEvt.clientX,
                                    clientY: touchEvt.clientY,
                                    target: target,
                                    rootEl: parent
                                });
                            }

                            break;
                        }

                        target = parent; // store last element
                    }
                        /* jshint boss:true */
                    while (parent = parent.parentNode);
                }

                if (!supportCssPointerEvents) {
                    _css(ghostEl, 'display', '');
                }
            }
        },


        _onTouchMove: function (/**TouchEvent*/evt) {
            if (tapEvt) {
                var	options = this.options,
                    fallbackTolerance = options.fallbackTolerance,
                    fallbackOffset = options.fallbackOffset,
                    touch = evt.touches ? evt.touches[0] : evt,
                    dx = (touch.clientX - tapEvt.clientX) + fallbackOffset.x,
                    dy = (touch.clientY - tapEvt.clientY) + fallbackOffset.y,
                    translate3d = evt.touches ? 'translate3d(' + dx + 'px,' + dy + 'px,0)' : 'translate(' + dx + 'px,' + dy + 'px)';

                // only set the status to dragging, when we are actually dragging
                if (!Sortable.active) {
                    if (fallbackTolerance &&
                        min(abs(touch.clientX - this._lastX), abs(touch.clientY - this._lastY)) < fallbackTolerance
                    ) {
                        return;
                    }

                    this._dragStarted();
                }

                // as well as creating the ghost element on the document body
                this._appendGhost();

                moved = true;
                touchEvt = touch;

                _css(ghostEl, 'webkitTransform', translate3d);
                _css(ghostEl, 'mozTransform', translate3d);
                _css(ghostEl, 'msTransform', translate3d);
                _css(ghostEl, 'transform', translate3d);

                evt.preventDefault();
            }
        },

        _appendGhost: function () {
            if (!ghostEl) {
                var rect = dragEl.getBoundingClientRect(),
                    css = _css(dragEl),
                    options = this.options,
                    ghostRect;

                ghostEl = dragEl.cloneNode(true);

                _toggleClass(ghostEl, options.ghostClass, false);
                _toggleClass(ghostEl, options.fallbackClass, true);
                _toggleClass(ghostEl, options.dragClass, true);

                _css(ghostEl, 'top', rect.top - parseInt(css.marginTop, 10));
                _css(ghostEl, 'left', rect.left - parseInt(css.marginLeft, 10));
                _css(ghostEl, 'width', rect.width);
                _css(ghostEl, 'height', rect.height);
                _css(ghostEl, 'opacity', '0.8');
                _css(ghostEl, 'position', 'fixed');
                _css(ghostEl, 'zIndex', '100000');
                _css(ghostEl, 'pointerEvents', 'none');

                options.fallbackOnBody && document.body.appendChild(ghostEl) || rootEl.appendChild(ghostEl);

                // Fixing dimensions.
                ghostRect = ghostEl.getBoundingClientRect();
                _css(ghostEl, 'width', rect.width * 2 - ghostRect.width);
                _css(ghostEl, 'height', rect.height * 2 - ghostRect.height);
            }
        },

        _onDragStart: function (/**Event*/evt, /**boolean*/useFallback) {
            var _this = this;
            var dataTransfer = evt.dataTransfer;
            var options = _this.options;

            _this._offUpEvents();

            if (activeGroup.checkPull(_this, _this, dragEl, evt)) {
                cloneEl = _clone(dragEl);

                cloneEl.draggable = false;
                cloneEl.style['will-change'] = '';

                _css(cloneEl, 'display', 'none');
                _toggleClass(cloneEl, _this.options.chosenClass, false);

                // #1143: IFrame support workaround
                _this._cloneId = _nextTick(function () {
                    rootEl.insertBefore(cloneEl, dragEl);
                    _dispatchEvent(_this, rootEl, 'clone', dragEl);
                });
            }

            _toggleClass(dragEl, options.dragClass, true);

            if (useFallback) {
                if (useFallback === 'touch') {
                    // Bind touch events
                    _on(document, 'touchmove', _this._onTouchMove);
                    _on(document, 'touchend', _this._onDrop);
                    _on(document, 'touchcancel', _this._onDrop);

                    if (options.supportPointer) {
                        _on(document, 'pointermove', _this._onTouchMove);
                        _on(document, 'pointerup', _this._onDrop);
                    }
                } else {
                    // Old brwoser
                    _on(document, 'mousemove', _this._onTouchMove);
                    _on(document, 'mouseup', _this._onDrop);
                }

                _this._loopId = setInterval(_this._emulateDragOver, 50);
            }
            else {
                if (dataTransfer) {
                    dataTransfer.effectAllowed = 'move';
                    options.setData && options.setData.call(_this, dataTransfer, dragEl);
                }

                _on(document, 'drop', _this);

                // #1143: Бывает элемент с IFrame внутри блокирует `drop`,
                // поэтому если вызвался `mouseover`, значит надо отменять весь d'n'd.
                // Breaking Chrome 62+
                // _on(document, 'mouseover', _this);

                _this._dragStartId = _nextTick(_this._dragStarted);
            }
        },

        _onDragOver: function (/**Event*/evt) {
            var el = this.el,
                target,
                dragRect,
                targetRect,
                revert,
                options = this.options,
                group = options.group,
                activeSortable = Sortable.active,
                isOwner = (activeGroup === group),
                isMovingBetweenSortable = false,
                canSort = options.sort;

            if (evt.preventDefault !== void 0) {
                evt.preventDefault();
                !options.dragoverBubble && evt.stopPropagation();
            }

            if (dragEl.animated) {
                return;
            }

            moved = true;

            if (activeSortable && !options.disabled &&
                (isOwner
                        ? canSort || (revert = !rootEl.contains(dragEl)) // Reverting item into the original list
                        : (
                            putSortable === this ||
                            (
                                (activeSortable.lastPullMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) &&
                                group.checkPut(this, activeSortable, dragEl, evt)
                            )
                        )
                ) &&
                (evt.rootEl === void 0 || evt.rootEl === this.el) // touch fallback
            ) {
                // Smart auto-scrolling
                _autoScroll(evt, options, this.el);

                if (_silent) {
                    return;
                }

                target = _closest(evt.target, options.draggable, el);
                dragRect = dragEl.getBoundingClientRect();

                if (putSortable !== this) {
                    putSortable = this;
                    isMovingBetweenSortable = true;
                }

                if (revert) {
                    _cloneHide(activeSortable, true);
                    parentEl = rootEl; // actualization

                    if (cloneEl || nextEl) {
                        rootEl.insertBefore(dragEl, cloneEl || nextEl);
                    }
                    else if (!canSort) {
                        rootEl.appendChild(dragEl);
                    }

                    return;
                }


                if ((el.children.length === 0) || (el.children[0] === ghostEl) ||
                    (el === evt.target) && (_ghostIsLast(el, evt))
                ) {
                    //assign target only if condition is true
                    if (el.children.length !== 0 && el.children[0] !== ghostEl && el === evt.target) {
                        target = el.lastElementChild;
                    }

                    if (target) {
                        if (target.animated) {
                            return;
                        }

                        targetRect = target.getBoundingClientRect();
                    }

                    _cloneHide(activeSortable, isOwner);

                    if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt) !== false) {
                        if (!dragEl.contains(el)) {
                            el.appendChild(dragEl);
                            parentEl = el; // actualization
                        }

                        this._animate(dragRect, dragEl);
                        target && this._animate(targetRect, target);
                    }
                }
                else if (target && !target.animated && target !== dragEl && (target.parentNode[expando] !== void 0)) {
                    if (lastEl !== target) {
                        lastEl = target;
                        lastCSS = _css(target);
                        lastParentCSS = _css(target.parentNode);
                    }

                    targetRect = target.getBoundingClientRect();

                    var width = targetRect.right - targetRect.left,
                        height = targetRect.bottom - targetRect.top,
                        floating = R_FLOAT.test(lastCSS.cssFloat + lastCSS.display)
                            || (lastParentCSS.display == 'flex' && lastParentCSS['flex-direction'].indexOf('row') === 0),
                        isWide = (target.offsetWidth > dragEl.offsetWidth),
                        isLong = (target.offsetHeight > dragEl.offsetHeight),
                        halfway = (floating ? (evt.clientX - targetRect.left) / width : (evt.clientY - targetRect.top) / height) > 0.5,
                        nextSibling = target.nextElementSibling,
                        after = false
                    ;

                    if (floating) {
                        var elTop = dragEl.offsetTop,
                            tgTop = target.offsetTop;

                        if (elTop === tgTop) {
                            after = (target.previousElementSibling === dragEl) && !isWide || halfway && isWide;
                        }
                        else if (target.previousElementSibling === dragEl || dragEl.previousElementSibling === target) {
                            after = (evt.clientY - targetRect.top) / height > 0.5;
                        } else {
                            after = tgTop > elTop;
                        }
                    } else if (!isMovingBetweenSortable) {
                        after = (nextSibling !== dragEl) && !isLong || halfway && isLong;
                    }

                    var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);

                    if (moveVector !== false) {
                        if (moveVector === 1 || moveVector === -1) {
                            after = (moveVector === 1);
                        }

                        _silent = true;
                        setTimeout(_unsilent, 30);

                        _cloneHide(activeSortable, isOwner);

                        if (!dragEl.contains(el)) {
                            if (after && !nextSibling) {
                                el.appendChild(dragEl);
                            } else {
                                target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
                            }
                        }

                        parentEl = dragEl.parentNode; // actualization

                        this._animate(dragRect, dragEl);
                        this._animate(targetRect, target);
                    }
                }
            }
        },

        _animate: function (prevRect, target) {
            var ms = this.options.animation;

            if (ms) {
                var currentRect = target.getBoundingClientRect();

                if (prevRect.nodeType === 1) {
                    prevRect = prevRect.getBoundingClientRect();
                }

                _css(target, 'transition', 'none');
                _css(target, 'transform', 'translate3d('
                    + (prevRect.left - currentRect.left) + 'px,'
                    + (prevRect.top - currentRect.top) + 'px,0)'
                );

                forRepaintDummy = target.offsetWidth; // repaint

                _css(target, 'transition', 'all ' + ms + 'ms');
                _css(target, 'transform', 'translate3d(0,0,0)');

                clearTimeout(target.animated);
                target.animated = setTimeout(function () {
                    _css(target, 'transition', '');
                    _css(target, 'transform', '');
                    target.animated = false;
                }, ms);
            }
        },

        _offUpEvents: function () {
            var ownerDocument = this.el.ownerDocument;

            _off(document, 'touchmove', this._onTouchMove);
            _off(document, 'pointermove', this._onTouchMove);
            _off(ownerDocument, 'mouseup', this._onDrop);
            _off(ownerDocument, 'touchend', this._onDrop);
            _off(ownerDocument, 'pointerup', this._onDrop);
            _off(ownerDocument, 'touchcancel', this._onDrop);
            _off(ownerDocument, 'pointercancel', this._onDrop);
            _off(ownerDocument, 'selectstart', this);
        },

        _onDrop: function (/**Event*/evt) {
            var el = this.el,
                options = this.options;

            clearInterval(this._loopId);
            clearInterval(autoScroll.pid);
            clearTimeout(this._dragStartTimer);

            _cancelNextTick(this._cloneId);
            _cancelNextTick(this._dragStartId);

            // Unbind events
            _off(document, 'mouseover', this);
            _off(document, 'mousemove', this._onTouchMove);

            if (this.nativeDraggable) {
                _off(document, 'drop', this);
                _off(el, 'dragstart', this._onDragStart);
            }

            this._offUpEvents();

            if (evt) {
                if (moved) {
                    evt.preventDefault();
                    !options.dropBubble && evt.stopPropagation();
                }

                ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);

                if (rootEl === parentEl || Sortable.active.lastPullMode !== 'clone') {
                    // Remove clone
                    cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
                }

                if (dragEl) {
                    if (this.nativeDraggable) {
                        _off(dragEl, 'dragend', this);
                    }

                    _disableDraggable(dragEl);
                    dragEl.style['will-change'] = '';

                    // Remove class's
                    _toggleClass(dragEl, this.options.ghostClass, false);
                    _toggleClass(dragEl, this.options.chosenClass, false);

                    // Drag stop event
                    _dispatchEvent(this, rootEl, 'unchoose', dragEl, parentEl, rootEl, oldIndex, null, evt);

                    if (rootEl !== parentEl) {
                        newIndex = _index(dragEl, options.draggable);

                        if (newIndex >= 0) {
                            // Add event
                            _dispatchEvent(null, parentEl, 'add', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

                            // Remove event
                            _dispatchEvent(this, rootEl, 'remove', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

                            // drag from one list and drop into another
                            _dispatchEvent(null, parentEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
                            _dispatchEvent(this, rootEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
                        }
                    }
                    else {
                        if (dragEl.nextSibling !== nextEl) {
                            // Get the index of the dragged element within its parent
                            newIndex = _index(dragEl, options.draggable);

                            if (newIndex >= 0) {
                                // drag & drop within the same list
                                _dispatchEvent(this, rootEl, 'update', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
                                _dispatchEvent(this, rootEl, 'sort', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);
                            }
                        }
                    }

                    if (Sortable.active) {
                        /* jshint eqnull:true */
                        if (newIndex == null || newIndex === -1) {
                            newIndex = oldIndex;
                        }

                        _dispatchEvent(this, rootEl, 'end', dragEl, parentEl, rootEl, oldIndex, newIndex, evt);

                        // Save sorting
                        this.save();
                    }
                }

            }

            this._nulling();
        },

        _nulling: function() {
            rootEl =
                dragEl =
                    parentEl =
                        ghostEl =
                            nextEl =
                                cloneEl =
                                    lastDownEl =

                                        scrollEl =
                                            scrollParentEl =

                                                tapEvt =
                                                    touchEvt =

                                                        moved =
                                                            newIndex =

                                                                lastEl =
                                                                    lastCSS =

                                                                        putSortable =
                                                                            activeGroup =
                                                                                Sortable.active = null;

            savedInputChecked.forEach(function (el) {
                el.checked = true;
            });
            savedInputChecked.length = 0;
        },

        handleEvent: function (/**Event*/evt) {
            switch (evt.type) {
                case 'drop':
                case 'dragend':
                    this._onDrop(evt);
                    break;

                case 'dragover':
                case 'dragenter':
                    if (dragEl) {
                        this._onDragOver(evt);
                        _globalDragOver(evt);
                    }
                    break;

                case 'mouseover':
                    this._onDrop(evt);
                    break;

                case 'selectstart':
                    evt.preventDefault();
                    break;
            }
        },


        /**
         * Serializes the item into an array of string.
         * @returns {String[]}
         */
        toArray: function () {
            var order = [],
                el,
                children = this.el.children,
                i = 0,
                n = children.length,
                options = this.options;

            for (; i < n; i++) {
                el = children[i];
                if (_closest(el, options.draggable, this.el)) {
                    order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
                }
            }

            return order;
        },


        /**
         * Sorts the elements according to the array.
         * @param  {String[]}  order  order of the items
         */
        sort: function (order) {
            var items = {}, rootEl = this.el;

            this.toArray().forEach(function (id, i) {
                var el = rootEl.children[i];

                if (_closest(el, this.options.draggable, rootEl)) {
                    items[id] = el;
                }
            }, this);

            order.forEach(function (id) {
                if (items[id]) {
                    rootEl.removeChild(items[id]);
                    rootEl.appendChild(items[id]);
                }
            });
        },


        /**
         * Save the current sorting
         */
        save: function () {
            var store = this.options.store;
            store && store.set(this);
        },


        /**
         * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
         * @param   {HTMLElement}  el
         * @param   {String}       [selector]  default: `options.draggable`
         * @returns {HTMLElement|null}
         */
        closest: function (el, selector) {
            return _closest(el, selector || this.options.draggable, this.el);
        },


        /**
         * Set/get option
         * @param   {string} name
         * @param   {*}      [value]
         * @returns {*}
         */
        option: function (name, value) {
            var options = this.options;

            if (value === void 0) {
                return options[name];
            } else {
                options[name] = value;

                if (name === 'group') {
                    _prepareGroup(options);
                }
            }
        },


        /**
         * Destroy
         */
        destroy: function () {
            var el = this.el;

            el[expando] = null;

            _off(el, 'mousedown', this._onTapStart);
            _off(el, 'touchstart', this._onTapStart);
            _off(el, 'pointerdown', this._onTapStart);

            if (this.nativeDraggable) {
                _off(el, 'dragover', this);
                _off(el, 'dragenter', this);
            }

            // Remove draggable attributes
            Array.prototype.forEach.call(el.querySelectorAll('[draggable]'), function (el) {
                el.removeAttribute('draggable');
            });

            touchDragOverListeners.splice(touchDragOverListeners.indexOf(this._onDragOver), 1);

            this._onDrop();

            this.el = el = null;
        }
    };


    function _cloneHide(sortable, state) {
        if (sortable.lastPullMode !== 'clone') {
            state = true;
        }

        if (cloneEl && (cloneEl.state !== state)) {
            _css(cloneEl, 'display', state ? 'none' : '');

            if (!state) {
                if (cloneEl.state) {
                    if (sortable.options.group.revertClone) {
                        rootEl.insertBefore(cloneEl, nextEl);
                        sortable._animate(dragEl, cloneEl);
                    } else {
                        rootEl.insertBefore(cloneEl, dragEl);
                    }
                }
            }

            cloneEl.state = state;
        }
    }


    function _closest(/**HTMLElement*/el, /**String*/selector, /**HTMLElement*/ctx) {
        if (el) {
            ctx = ctx || document;

            do {
                if ((selector === '>*' && el.parentNode === ctx) || _matches(el, selector)) {
                    return el;
                }
                /* jshint boss:true */
            } while (el = _getParentOrHost(el));
        }

        return null;
    }


    function _getParentOrHost(el) {
        var parent = el.host;

        return (parent && parent.nodeType) ? parent : el.parentNode;
    }


    function _globalDragOver(/**Event*/evt) {
        if (evt.dataTransfer) {
            evt.dataTransfer.dropEffect = 'move';
        }
        evt.preventDefault();
    }


    function _on(el, event, fn) {
        el.addEventListener(event, fn, captureMode);
    }


    function _off(el, event, fn) {
        el.removeEventListener(event, fn, captureMode);
    }


    function _toggleClass(el, name, state) {
        if (el) {
            if (el.classList) {
                el.classList[state ? 'add' : 'remove'](name);
            }
            else {
                var className = (' ' + el.className + ' ').replace(R_SPACE, ' ').replace(' ' + name + ' ', ' ');
                el.className = (className + (state ? ' ' + name : '')).replace(R_SPACE, ' ');
            }
        }
    }


    function _css(el, prop, val) {
        var style = el && el.style;

        if (style) {
            if (val === void 0) {
                if (document.defaultView && document.defaultView.getComputedStyle) {
                    val = document.defaultView.getComputedStyle(el, '');
                }
                else if (el.currentStyle) {
                    val = el.currentStyle;
                }

                return prop === void 0 ? val : val[prop];
            }
            else {
                if (!(prop in style)) {
                    prop = '-webkit-' + prop;
                }

                style[prop] = val + (typeof val === 'string' ? '' : 'px');
            }
        }
    }


    function _find(ctx, tagName, iterator) {
        if (ctx) {
            var list = ctx.getElementsByTagName(tagName), i = 0, n = list.length;

            if (iterator) {
                for (; i < n; i++) {
                    iterator(list[i], i);
                }
            }

            return list;
        }

        return [];
    }



    function _dispatchEvent(sortable, rootEl, name, targetEl, toEl, fromEl, startIndex, newIndex, originalEvt) {
        sortable = (sortable || rootEl[expando]);

        var evt = document.createEvent('Event'),
            options = sortable.options,
            onName = 'on' + name.charAt(0).toUpperCase() + name.substr(1);

        evt.initEvent(name, true, true);

        evt.to = toEl || rootEl;
        evt.from = fromEl || rootEl;
        evt.item = targetEl || rootEl;
        evt.clone = cloneEl;

        evt.oldIndex = startIndex;
        evt.newIndex = newIndex;

        evt.originalEvent = originalEvt;

        rootEl.dispatchEvent(evt);

        if (options[onName]) {
            options[onName].call(sortable, evt);
        }
    }


    function _onMove(fromEl, toEl, dragEl, dragRect, targetEl, targetRect, originalEvt, willInsertAfter) {
        var evt,
            sortable = fromEl[expando],
            onMoveFn = sortable.options.onMove,
            retVal;

        evt = document.createEvent('Event');
        evt.initEvent('move', true, true);

        evt.to = toEl;
        evt.from = fromEl;
        evt.dragged = dragEl;
        evt.draggedRect = dragRect;
        evt.related = targetEl || toEl;
        evt.relatedRect = targetRect || toEl.getBoundingClientRect();
        evt.willInsertAfter = willInsertAfter;

        evt.originalEvent = originalEvt;

        fromEl.dispatchEvent(evt);

        if (onMoveFn) {
            retVal = onMoveFn.call(sortable, evt, originalEvt);
        }

        return retVal;
    }


    function _disableDraggable(el) {
        el.draggable = false;
    }


    function _unsilent() {
        _silent = false;
    }


    /** @returns {HTMLElement|false} */
    function _ghostIsLast(el, evt) {
        var lastEl = el.lastElementChild,
            rect = lastEl.getBoundingClientRect();

        // 5 — min delta
        // abs — нельзя добавлять, а то глюки при наведении сверху
        return (evt.clientY - (rect.top + rect.height) > 5) ||
            (evt.clientX - (rect.left + rect.width) > 5);
    }


    /**
     * Generate id
     * @param   {HTMLElement} el
     * @returns {String}
     * @private
     */
    function _generateId(el) {
        var str = el.tagName + el.className + el.src + el.href + el.textContent,
            i = str.length,
            sum = 0;

        while (i--) {
            sum += str.charCodeAt(i);
        }

        return sum.toString(36);
    }

    /**
     * Returns the index of an element within its parent for a selected set of
     * elements
     * @param  {HTMLElement} el
     * @param  {selector} selector
     * @return {number}
     */
    function _index(el, selector) {
        var index = 0;

        if (!el || !el.parentNode) {
            return -1;
        }

        while (el && (el = el.previousElementSibling)) {
            if ((el.nodeName.toUpperCase() !== 'TEMPLATE') && (selector === '>*' || _matches(el, selector))) {
                index++;
            }
        }

        return index;
    }

    function _matches(/**HTMLElement*/el, /**String*/selector) {
        if (el) {
            try {
                if (el.matches) {
                    return el.matches(selector);
                } else if (el.msMatchesSelector) {
                    return el.msMatchesSelector(selector);
                }
            } catch(_) {
                return false;
            }
        }

        return false;
    }

    function _throttle(callback, ms) {
        var args, _this;

        return function () {
            if (args === void 0) {
                args = arguments;
                _this = this;

                setTimeout(function () {
                    if (args.length === 1) {
                        callback.call(_this, args[0]);
                    } else {
                        callback.apply(_this, args);
                    }

                    args = void 0;
                }, ms);
            }
        };
    }

    function _extend(dst, src) {
        if (dst && src) {
            for (var key in src) {
                if (src.hasOwnProperty(key)) {
                    dst[key] = src[key];
                }
            }
        }

        return dst;
    }

    function _clone(el) {
        if (Polymer && Polymer.dom) {
            return Polymer.dom(el).cloneNode(true);
        }
        else if ($) {
            return $(el).clone(true)[0];
        }
        else {
            return el.cloneNode(true);
        }
    }

    function _saveInputCheckedState(root) {
        savedInputChecked.length = 0;

        var inputs = root.getElementsByTagName('input');
        var idx = inputs.length;

        while (idx--) {
            var el = inputs[idx];
            el.checked && savedInputChecked.push(el);
        }
    }

    function _nextTick(fn) {
        return setTimeout(fn, 0);
    }

    function _cancelNextTick(id) {
        return clearTimeout(id);
    }

    // Fixed #973:
    _on(document, 'touchmove', function (evt) {
        if (Sortable.active) {
            evt.preventDefault();
        }
    });

    // Export utils
    Sortable.utils = {
        on: _on,
        off: _off,
        css: _css,
        find: _find,
        is: function (el, selector) {
            return !!_closest(el, selector, el);
        },
        extend: _extend,
        throttle: _throttle,
        closest: _closest,
        toggleClass: _toggleClass,
        clone: _clone,
        index: _index,
        nextTick: _nextTick,
        cancelNextTick: _cancelNextTick
    };


    /**
     * Create sortable instance
     * @param {HTMLElement}  el
     * @param {Object}      [options]
     */
    Sortable.create = function (el, options) {
        return new Sortable(el, options);
    };


    // Export
    Sortable.version = '1.7.0';
    return Sortable;
});

/*!

 /**
 * jQuery plugin for Sortable
 * @author	RubaXa   <trash@rubaxa.org>
 * @license MIT
 */
(function (factory) {
    "use strict";

    if (typeof define === "function" && define.amd) {
        define(["jquery"], factory);
    }
    else {
        /* jshint sub:true */
        factory(jQuery);
    }
})(function ($) {
    "use strict";


    /* CODE */


    /**
     * jQuery plugin for Sortable
     * @param   {Object|String} options
     * @param   {..*}           [args]
     * @returns {jQuery|*}
     */
    $.fn.sortableCustom = function (options) {
        var retVal,
            args = arguments;

        this.each(function () {
            var $el = $(this),
                sortable = $el.data('sortable');

            if (!sortable && (options instanceof Object || !options)) {
                sortable = new Sortable(this, options);
                $el.data('sortable', sortable);
            }

            if (sortable) {
                if (options === 'widget') {
                    return sortable;
                }
                else if (options === 'destroy') {
                    sortable.destroy();
                    $el.removeData('sortable');
                }
                else if (typeof sortable[options] === 'function') {
                    retVal = sortable[options].apply(sortable, [].slice.call(args, 1));
                }
                else if (options in sortable.options) {
                    retVal = sortable.option.apply(sortable, args);
                }
            }
        });

        return (retVal === void 0) ? this : retVal;
    };
});


(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.dragula = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
        'use strict';

        var cache = {};
        var start = '(?:^|\\s)';
        var end = '(?:\\s|$)';

        function lookupClass (className) {
            var cached = cache[className];
            if (cached) {
                cached.lastIndex = 0;
            } else {
                cache[className] = cached = new RegExp(start + className + end, 'g');
            }
            return cached;
        }

        function addClass (el, className) {
            var current = el.className;
            if (!current.length) {
                el.className = className;
            } else if (!lookupClass(className).test(current)) {
                el.className += ' ' + className;
            }
        }

        function rmClass (el, className) {
            el.className = el.className.replace(lookupClass(className), ' ').trim();
        }

        module.exports = {
            add: addClass,
            rm: rmClass
        };

    },{}],2:[function(require,module,exports){
        (function (global){
            'use strict';

            var emitter = require('contra/emitter');
            var crossvent = require('crossvent');
            var classes = require('./classes');
            var doc = document;
            var documentElement = doc.documentElement;

            function dragula (initialContainers, options) {
                var len = arguments.length;
                if (len === 1 && Array.isArray(initialContainers) === false) {
                    options = initialContainers;
                    initialContainers = [];
                }
                var _mirror; // mirror image
                var _source; // source container
                var _item; // item being dragged
                var _offsetX; // reference x
                var _offsetY; // reference y
                var _moveX; // reference move x
                var _moveY; // reference move y
                var _initialSibling; // reference sibling when grabbed
                var _currentSibling; // reference sibling now
                var _copy; // item used for copying
                var _renderTimer; // timer for setTimeout renderMirrorImage
                var _lastDropTarget = null; // last container item was over
                var _grabbed; // holds mousedown context until first mousemove

                var _throttledDrag;

                var o = options || {};
                if (o.moves === void 0) { o.moves = always; }
                if (o.accepts === void 0) { o.accepts = always; }
                if (o.invalid === void 0) { o.invalid = invalidTarget; }
                if (o.containers === void 0) { o.containers = initialContainers || []; }
                if (o.isContainer === void 0) { o.isContainer = never; }
                if (o.copy === void 0) { o.copy = false; }
                if (o.copySortSource === void 0) { o.copySortSource = false; }
                if (o.revertOnSpill === void 0) { o.revertOnSpill = false; }
                if (o.removeOnSpill === void 0) { o.removeOnSpill = false; }
                if (o.direction === void 0) { o.direction = 'vertical'; }
                if (o.ignoreInputTextSelection === void 0) { o.ignoreInputTextSelection = true; }
                if (o.mirrorContainer === void 0) { o.mirrorContainer = doc.body; }

                var drake = emitter({
                    containers: o.containers,
                    start: manualStart,
                    end: end,
                    cancel: cancel,
                    remove: remove,
                    destroy: destroy,
                    canMove: canMove,
                    dragging: false
                });

                if (o.removeOnSpill === true) {
                    drake.on('over', spillOver).on('out', spillOut);
                }

                events();

                return drake;

                function isContainer (el) {
                    return drake.containers.indexOf(el) !== -1 || o.isContainer(el);
                }

                function events (remove) {
                    var op = remove ? 'remove' : 'add';
                    touchy(documentElement, op, 'mousedown', grab);
                    touchy(documentElement, op, 'mouseup', release);
                }

                function eventualMovements (remove) {
                    var op = remove ? 'remove' : 'add';
                    touchy(documentElement, op, 'mousemove', startBecauseMouseMoved);
                }

                function movements (remove) {
                    var op = remove ? 'remove' : 'add';
                    crossvent[op](documentElement, 'selectstart', preventGrabbed); // IE8
                    crossvent[op](documentElement, 'click', preventGrabbed);
                }

                function destroy () {
                    events(true);
                    release({});
                }

                function preventGrabbed (e) {
                    if (_grabbed) {
                        e.preventDefault();
                    }
                }

                function grab (e) {
                    _moveX = e.clientX;
                    _moveY = e.clientY;

                    var ignore = whichMouseButton(e) !== 1 || e.metaKey || e.ctrlKey;
                    if (ignore) {
                        return; // we only care about honest-to-god left clicks and touch events
                    }
                    var item = e.target;
                    var context = canStart(item);
                    if (!context) {
                        return;
                    }
                    _grabbed = context;
                    eventualMovements();
                    if (e.type === 'mousedown') {
                        if (isInput(item)) { // see also: https://github.com/bevacqua/dragula/issues/208
                            item.focus(); // fixes https://github.com/bevacqua/dragula/issues/176
                        } else {
                            e.preventDefault(); // fixes https://github.com/bevacqua/dragula/issues/155
                        }
                    }
                }

                function startBecauseMouseMoved (e) {
                    if (!_grabbed) {
                        return;
                    }
                    if (whichMouseButton(e) === 0) {
                        release({});
                        return; // when text is selected on an input and then dragged, mouseup doesn't fire. this is our only hope
                    }
                    // truthy check fixes #239, equality fixes #207
                    if (e.clientX !== void 0 && e.clientX === _moveX && e.clientY !== void 0 && e.clientY === _moveY) {
                        return;
                    }
                    if (o.ignoreInputTextSelection) {
                        var clientX = getCoord('clientX', e);
                        var clientY = getCoord('clientY', e);
                        var elementBehindCursor = doc.elementFromPoint(clientX, clientY);
                        if (isInput(elementBehindCursor)) {
                            return;
                        }
                    }

                    var grabbed = _grabbed; // call to end() unsets _grabbed
                    eventualMovements(true);
                    movements();
                    end();
                    start(grabbed);

                    if ( ! _item ) return;
                    var offset = getOffset(_item);
                    _offsetX = getCoord('pageX', e) - offset.left;
                    _offsetY = getCoord('pageY', e) - offset.top;

                    classes.add(_copy || _item, 'gu-transit');
                    renderMirrorImage();
                    drag(e);
                }

                function canStart (item) {
                    if (drake.dragging && _mirror) {
                        return;
                    }
                    if (isContainer(item)) {
                        return; // don't drag container itself
                    }
                    var handle = item;
                    while (getParent(item) && isContainer(getParent(item)) === false) {
                        if (o.invalid(item, handle)) {
                            return;
                        }
                        item = getParent(item); // drag target should be a top element
                        if (!item) {
                            return;
                        }
                    }
                    var source = getParent(item);
                    if (!source) {
                        return;
                    }
                    if (o.invalid(item, handle)) {
                        return;
                    }

                    var movable = o.moves(item, source, handle, nextEl(item));
                    if (!movable) {
                        return;
                    }

                    return {
                        item: item,
                        source: source
                    };
                }

                function canMove (item) {
                    return !!canStart(item);
                }

                function manualStart (item) {
                    var context = canStart(item);
                    if (context) {
                        start(context);
                    }
                }

                function start (context) {
                    if (isCopy(context.item, context.source)) {
                        _copy = context.item.cloneNode(true);
                        drake.emit('cloned', _copy, context.item, 'copy');
                    }

                    _source = context.source;
                    _item = context.item;
                    _initialSibling = _currentSibling = nextEl(context.item);

                    drake.dragging = true;
                    drake.emit('drag', _item, _source);
                }

                function invalidTarget () {
                    return false;
                }

                function end () {
                    if (!drake.dragging) {
                        return;
                    }
                    var item = _copy || _item;
                    drop(item, getParent(item));
                }

                function ungrab () {
                    _grabbed = false;
                    eventualMovements(true);
                    movements(true);
                }

                function release (e) {
                    ungrab();

                    if (!drake.dragging) {
                        return;
                    }
                    var item = _copy || _item;
                    var clientX = getCoord('clientX', e);
                    var clientY = getCoord('clientY', e);
                    var elementBehindCursor = getElementBehindPoint(_mirror, clientX, clientY);
                    var dropTarget = findDropTarget(elementBehindCursor, clientX, clientY);
                    if (dropTarget && ((_copy && o.copySortSource) || (!_copy || dropTarget !== _source))) {
                        drop(item, dropTarget);
                    } else if (o.removeOnSpill) {
                        remove();
                    } else {
                        cancel();
                    }
                }

                function drop (item, target) {
                    var parent = getParent(item);
                    if (_copy && o.copySortSource && target === _source) {
                        parent.removeChild(_item);
                    }
                    if (isInitialPlacement(target)) {
                        drake.emit('cancel', item, _source, _source);
                    } else {
                        drake.emit('drop', item, target, _source, _currentSibling);
                    }
                    cleanup();
                }

                function remove () {
                    if (!drake.dragging) {
                        return;
                    }
                    var item = _copy || _item;
                    var parent = getParent(item);
                    if (parent) {
                        parent.removeChild(item);
                    }
                    drake.emit(_copy ? 'cancel' : 'remove', item, parent, _source);
                    cleanup();
                }

                function cancel (revert) {
                    if (!drake.dragging) {
                        return;
                    }
                    var reverts = arguments.length > 0 ? revert : o.revertOnSpill;
                    var item = _copy || _item;
                    var parent = getParent(item);
                    var initial = isInitialPlacement(parent);
                    if (initial === false && reverts) {
                        if (_copy) {
                            if (parent) {
                                parent.removeChild(_copy);
                            }
                        } else {
                            _source.insertBefore(item, _initialSibling);
                        }
                    }
                    if (initial || reverts) {
                        drake.emit('cancel', item, _source, _source);
                    } else {
                        drake.emit('drop', item, parent, _source, _currentSibling);
                    }
                    cleanup();
                }

                function cleanup () {
                    var item = _copy || _item;
                    ungrab();
                    removeMirrorImage();
                    if (item) {
                        classes.rm(item, 'gu-transit');
                    }
                    if (_renderTimer) {
                        clearTimeout(_renderTimer);
                    }
                    drake.dragging = false;
                    if (_lastDropTarget) {
                        drake.emit('out', item, _lastDropTarget, _source);
                    }
                    drake.emit('dragend', item);
                    _source = _item = _copy = _initialSibling = _currentSibling = _renderTimer = _lastDropTarget = null;
                }

                function isInitialPlacement (target, s) {
                    var sibling;
                    if (s !== void 0) {
                        sibling = s;
                    } else if (_mirror) {
                        sibling = _currentSibling;
                    } else {
                        sibling = nextEl(_copy || _item);
                    }
                    return target === _source && sibling === _initialSibling;
                }

                function findDropTarget (elementBehindCursor, clientX, clientY) {
                    var target = elementBehindCursor;
                    while (target && !accepted()) {
                        target = getParent(target);
                    }
                    return target;

                    function accepted () {
                        var droppable = isContainer(target);
                        if (droppable === false) {
                            return false;
                        }

                        var immediate = getImmediateChild(target, elementBehindCursor);
                        var reference = getReference(target, immediate, clientX, clientY);
                        var initial = isInitialPlacement(target, reference);
                        if (initial) {
                            return true; // should always be able to drop it right back where it was
                        }
                        return o.accepts(_item, target, _source, reference);
                    }
                }

                function drag (e) {
                    if (!_mirror) {
                        return;
                    }
                    e.preventDefault();

                    var clientX = getCoord('clientX', e);
                    var clientY = getCoord('clientY', e);
                    var x = clientX - _offsetX;
                    var y = clientY - _offsetY;

                    _mirror.style.left = x + 'px';
                    _mirror.style.top = y + 'px';

                    var item = _copy || _item;

                    var elementBehindCursor = getElementBehindPoint(_mirror, clientX, clientY);
                    var dropTarget = findDropTarget(elementBehindCursor, clientX, clientY);
                    var changed = dropTarget !== null && dropTarget !== _lastDropTarget;
                    if (changed || dropTarget === null) {
                        out();
                        _lastDropTarget = dropTarget;
                        over();
                    }
                    var parent = getParent(item);
                    if (dropTarget === _source && _copy && !o.copySortSource) {
                        if (parent) {
                            parent.removeChild(item);
                        }
                        return;
                    }
                    var reference;
                    var immediate = getImmediateChild(dropTarget, elementBehindCursor);
                    if (immediate !== null) {
                        reference = getReference(dropTarget, immediate, clientX, clientY);
                    } else if (o.revertOnSpill === true && !_copy) {
                        reference = _initialSibling;
                        dropTarget = _source;
                    } else {
                        if (_copy && parent) {
                            parent.removeChild(item);
                        }
                        return;
                    }
                    if (
                        (reference === null && changed) ||
                        reference !== item &&
                        reference !== nextEl(item)
                    ) {
                        _currentSibling = reference;
                        // disable dropping and preview
                        //dropTarget.insertBefore(item, reference);
                        drake.emit('shadow', item, dropTarget, _source);
                    }
                    function moved (type) { drake.emit(type, item, _lastDropTarget, _source); }
                    function over () { if (changed) { moved('over'); } }
                    function out () { if (_lastDropTarget) { moved('out'); } }
                }

                function spillOver (el) {
                    classes.rm(el, 'gu-hide');
                }

                function spillOut (el) {
                    if (drake.dragging) { classes.add(el, 'gu-hide'); }
                }


                function renderMirrorImage () {
                    if (_mirror) {
                        return;
                    }
                    var rect = _item.getBoundingClientRect();
                    _mirror = _item.cloneNode(true);
                    _mirror.style.width = getRectWidth(rect) + 'px';
                    _mirror.style.height = getRectHeight(rect) + 'px';
                    classes.rm(_mirror, 'gu-transit');
                    classes.add(_mirror, 'gu-mirror');
                    o.mirrorContainer.appendChild(_mirror);
                    _throttledDrag = _.throttle(drag, 14);
                    touchy(documentElement, 'add', 'mousemove', _throttledDrag);
                    classes.add(o.mirrorContainer, 'gu-unselectable');
                    drake.emit('cloned', _mirror, _item, 'mirror');
                }

                function removeMirrorImage () {
                    if (_mirror) {
                        classes.rm(o.mirrorContainer, 'gu-unselectable');
                        touchy(documentElement, 'remove', 'mousemove', _throttledDrag);
                        getParent(_mirror).removeChild(_mirror);
                        _mirror = null;
                    }
                }

                function getImmediateChild (dropTarget, target) {
                    var immediate = target;
                    while (immediate !== dropTarget && getParent(immediate) !== dropTarget) {
                        immediate = getParent(immediate);
                    }
                    if (immediate === documentElement) {
                        return null;
                    }
                    return immediate;
                }

                function getReference (dropTarget, target, x, y) {
                    var horizontal = o.direction === 'horizontal';
                    var reference = target !== dropTarget ? inside() : outside();
                    return reference;

                    function outside () { // slower, but able to figure out any position
                        var len = dropTarget.children.length;
                        var i;
                        var el;
                        var rect;
                        for (i = 0; i < len; i++) {
                            el = dropTarget.children[i];
                            rect = el.getBoundingClientRect();
                            if (horizontal && (rect.left + rect.width / 2) > x) { return el; }
                            if (!horizontal && (rect.top + rect.height / 2) > y) { return el; }
                        }
                        return null;
                    }

                    function inside () { // faster, but only available if dropped inside a child element
                        var rect = target.getBoundingClientRect();
                        if (horizontal) {
                            return resolve(x > rect.left + getRectWidth(rect) / 2);
                        }
                        return resolve(y > rect.top + getRectHeight(rect) / 2);
                    }

                    function resolve (after) {
                        return after ? nextEl(target) : target;
                    }
                }

                function isCopy (item, container) {
                    return typeof o.copy === 'boolean' ? o.copy : o.copy(item, container);
                }
            }

            function touchy (el, op, type, fn) {
                var touch = {
                    mouseup: 'touchend',
                    mousedown: 'touchstart',
                    mousemove: 'touchmove'
                };
                var pointers = {
                    mouseup: 'pointerup',
                    mousedown: 'pointerdown',
                    mousemove: 'pointermove'
                };
                var microsoft = {
                    mouseup: 'MSPointerUp',
                    mousedown: 'MSPointerDown',
                    mousemove: 'MSPointerMove'
                };
                if (global.navigator.pointerEnabled) {
                    crossvent[op](el, pointers[type], fn);
                } else if (global.navigator.msPointerEnabled) {
                    crossvent[op](el, microsoft[type], fn);
                } else {
                    crossvent[op](el, touch[type], fn);
                    crossvent[op](el, type, fn);
                }
            }

            function whichMouseButton (e) {
                if (e.touches !== void 0) { return e.touches.length; }
                if (e.which !== void 0 && e.which !== 0) { return e.which; } // see https://github.com/bevacqua/dragula/issues/261
                if (e.buttons !== void 0) { return e.buttons; }
                var button = e.button;
                if (button !== void 0) { // see https://github.com/jquery/jquery/blob/99e8ff1baa7ae341e94bb89c3e84570c7c3ad9ea/src/event.js#L573-L575
                    return button & 1 ? 1 : button & 2 ? 3 : (button & 4 ? 2 : 0);
                }
            }

            function getOffset (el) {
                var rect = el.getBoundingClientRect();
                return {
                    left: rect.left + getScroll('scrollLeft', 'pageXOffset'),
                    top: rect.top + getScroll('scrollTop', 'pageYOffset')
                };
            }

            function getScroll (scrollProp, offsetProp) {
                if (typeof global[offsetProp] !== 'undefined') {
                    return global[offsetProp];
                }
                if (documentElement.clientHeight) {
                    return documentElement[scrollProp];
                }
                return doc.body[scrollProp];
            }

            function getElementBehindPoint (point, x, y) {
                var p = point || {};
                var state = p.className;
                var el;
                p.className += ' gu-hide';
                el = doc.elementFromPoint(x, y);
                p.className = state;
                return el;
            }

            function never () { return false; }
            function always () { return true; }
            function getRectWidth (rect) { return rect.width || (rect.right - rect.left); }
            function getRectHeight (rect) { return rect.height || (rect.bottom - rect.top); }
            function getParent (el) { return el.parentNode === doc ? null : el.parentNode; }
            function isInput (el) { return el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT' || isEditable(el); }
            function isEditable (el) {
                if (!el) { return false; } // no parents were editable
                if (el.contentEditable === 'false') { return false; } // stop the lookup
                if (el.contentEditable === 'true') { return true; } // found a contentEditable element in the chain
                return isEditable(getParent(el)); // contentEditable is set to 'inherit'
            }

            function nextEl (el) {
                return el.nextElementSibling || manually();
                function manually () {
                    var sibling = el;
                    do {
                        sibling = sibling.nextSibling;
                    } while (sibling && sibling.nodeType !== 1);
                    return sibling;
                }
            }

            function getEventHost (e) {
                // on touchend event, we have to use `e.changedTouches`
                // see http://stackoverflow.com/questions/7192563/touchend-event-properties
                // see https://github.com/bevacqua/dragula/issues/34
                if (e.targetTouches && e.targetTouches.length) {
                    return e.targetTouches[0];
                }
                if (e.changedTouches && e.changedTouches.length) {
                    return e.changedTouches[0];
                }
                return e;
            }

            function getCoord (coord, e) {
                var host = getEventHost(e);
                var missMap = {
                    pageX: 'clientX', // IE8
                    pageY: 'clientY' // IE8
                };
                if (coord in missMap && !(coord in host) && missMap[coord] in host) {
                    coord = missMap[coord];
                }
                return host[coord];
            }

            module.exports = dragula;

        }).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

    },{"./classes":1,"contra/emitter":5,"crossvent":6}],3:[function(require,module,exports){
        module.exports = function atoa (a, n) { return Array.prototype.slice.call(a, n); }

    },{}],4:[function(require,module,exports){
        'use strict';

        var ticky = require('ticky');

        module.exports = function debounce (fn, args, ctx) {
            if (!fn) { return; }
            ticky(function run () {
                fn.apply(ctx || null, args || []);
            });
        };

    },{"ticky":9}],5:[function(require,module,exports){
        'use strict';

        var atoa = require('atoa');
        var debounce = require('./debounce');

        module.exports = function emitter (thing, options) {
            var opts = options || {};
            var evt = {};
            if (thing === undefined) { thing = {}; }
            thing.on = function (type, fn) {
                if (!evt[type]) {
                    evt[type] = [fn];
                } else {
                    evt[type].push(fn);
                }
                return thing;
            };
            thing.once = function (type, fn) {
                fn._once = true; // thing.off(fn) still works!
                thing.on(type, fn);
                return thing;
            };
            thing.off = function (type, fn) {
                var c = arguments.length;
                if (c === 1) {
                    delete evt[type];
                } else if (c === 0) {
                    evt = {};
                } else {
                    var et = evt[type];
                    if (!et) { return thing; }
                    et.splice(et.indexOf(fn), 1);
                }
                return thing;
            };
            thing.emit = function () {
                var args = atoa(arguments);
                return thing.emitterSnapshot(args.shift()).apply(this, args);
            };
            thing.emitterSnapshot = function (type) {
                var et = (evt[type] || []).slice(0);
                return function () {
                    var args = atoa(arguments);
                    var ctx = this || thing;
                    if (type === 'error' && opts.throws !== false && !et.length) { throw args.length === 1 ? args[0] : args; }
                    et.forEach(function emitter (listen) {
                        if (opts.async) { debounce(listen, args, ctx); } else { listen.apply(ctx, args); }
                        if (listen._once) { thing.off(type, listen); }
                    });
                    return thing;
                };
            };
            return thing;
        };

    },{"./debounce":4,"atoa":3}],6:[function(require,module,exports){
        (function (global){
            'use strict';

            var customEvent = require('custom-event');
            var eventmap = require('./eventmap');
            var doc = global.document;
            var addEvent = addEventEasy;
            var removeEvent = removeEventEasy;
            var hardCache = [];

            if (!global.addEventListener) {
                addEvent = addEventHard;
                removeEvent = removeEventHard;
            }

            module.exports = {
                add: addEvent,
                remove: removeEvent,
                fabricate: fabricateEvent
            };

            function addEventEasy (el, type, fn, capturing) {
                return el.addEventListener(type, fn, capturing);
            }

            function addEventHard (el, type, fn) {
                return el.attachEvent('on' + type, wrap(el, type, fn));
            }

            function removeEventEasy (el, type, fn, capturing) {
                return el.removeEventListener(type, fn, capturing);
            }

            function removeEventHard (el, type, fn) {
                var listener = unwrap(el, type, fn);
                if (listener) {
                    return el.detachEvent('on' + type, listener);
                }
            }

            function fabricateEvent (el, type, model) {
                var e = eventmap.indexOf(type) === -1 ? makeCustomEvent() : makeClassicEvent();
                if (el.dispatchEvent) {
                    el.dispatchEvent(e);
                } else {
                    el.fireEvent('on' + type, e);
                }
                function makeClassicEvent () {
                    var e;
                    if (doc.createEvent) {
                        e = doc.createEvent('Event');
                        e.initEvent(type, true, true);
                    } else if (doc.createEventObject) {
                        e = doc.createEventObject();
                    }
                    return e;
                }
                function makeCustomEvent () {
                    return new customEvent(type, { detail: model });
                }
            }

            function wrapperFactory (el, type, fn) {
                return function wrapper (originalEvent) {
                    var e = originalEvent || global.event;
                    e.target = e.target || e.srcElement;
                    e.preventDefault = e.preventDefault || function preventDefault () { e.returnValue = false; };
                    e.stopPropagation = e.stopPropagation || function stopPropagation () { e.cancelBubble = true; };
                    e.which = e.which || e.keyCode;
                    fn.call(el, e);
                };
            }

            function wrap (el, type, fn) {
                var wrapper = unwrap(el, type, fn) || wrapperFactory(el, type, fn);
                hardCache.push({
                    wrapper: wrapper,
                    element: el,
                    type: type,
                    fn: fn
                });
                return wrapper;
            }

            function unwrap (el, type, fn) {
                var i = find(el, type, fn);
                if (i) {
                    var wrapper = hardCache[i].wrapper;
                    hardCache.splice(i, 1); // free up a tad of memory
                    return wrapper;
                }
            }

            function find (el, type, fn) {
                var i, item;
                for (i = 0; i < hardCache.length; i++) {
                    item = hardCache[i];
                    if (item.element === el && item.type === type && item.fn === fn) {
                        return i;
                    }
                }
            }

        }).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

    },{"./eventmap":7,"custom-event":8}],7:[function(require,module,exports){
        (function (global){
            'use strict';

            var eventmap = [];
            var eventname = '';
            var ron = /^on/;

            for (eventname in global) {
                if (ron.test(eventname)) {
                    eventmap.push(eventname.slice(2));
                }
            }

            module.exports = eventmap;

        }).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

    },{}],8:[function(require,module,exports){
        (function (global){

            var NativeCustomEvent = global.CustomEvent;

            function useNative () {
                try {
                    var p = new NativeCustomEvent('cat', { detail: { foo: 'bar' } });
                    return  'cat' === p.type && 'bar' === p.detail.foo;
                } catch (e) {
                }
                return false;
            }

            /**
             * Cross-browser `CustomEvent` constructor.
             *
             * https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent.CustomEvent
             *
             * @public
             */

            module.exports = useNative() ? NativeCustomEvent :

                // IE >= 9
                'function' === typeof document.createEvent ? function CustomEvent (type, params) {
                        var e = document.createEvent('CustomEvent');
                        if (params) {
                            e.initCustomEvent(type, params.bubbles, params.cancelable, params.detail);
                        } else {
                            e.initCustomEvent(type, false, false, void 0);
                        }
                        return e;
                    } :

                    // IE <= 8
                    function CustomEvent (type, params) {
                        var e = document.createEventObject();
                        e.type = type;
                        if (params) {
                            e.bubbles = Boolean(params.bubbles);
                            e.cancelable = Boolean(params.cancelable);
                            e.detail = params.detail;
                        } else {
                            e.bubbles = false;
                            e.cancelable = false;
                            e.detail = void 0;
                        }
                        return e;
                    }

        }).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})

    },{}],9:[function(require,module,exports){
        var si = typeof setImmediate === 'function', tick;
        if (si) {
            tick = function (fn) { setImmediate(fn); };
        } else {
            tick = function (fn) { setTimeout(fn, 0); };
        }

        module.exports = tick;
    },{}]},{},[2])(2)
});
