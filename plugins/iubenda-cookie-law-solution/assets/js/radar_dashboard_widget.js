function circularBar(el) {
    el.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg"\n' +
        '     viewBox="0 0 32 32">\n' +
        '    <circle class="circle1"\n' +
        '            cy="16"\n' +
        '            cx="16"\n' +
        '            r="13"/>\n' +
        '    <circle class="circle2"\n' +
        '            cy="16"\n' +
        '            cx="16"\n' +
        '            r="13"/>\n' +
        '</svg>\n' +
        '<span> </span>';

    var perc   = parseInt( el.dataset.perc );
    var circle = el.querySelector( '.circle2' );
    var color  = '#CF7463';

    el.querySelector( 'span' ).innerHTML = perc + '<b>%</b>';

    if (perc >= 50) {
        color = '#F5B350';
    }
    if (perc > 80) {
        color = '#1CC691';
    }

    var strokeDashArray  = parseInt( getComputedStyle( circle, null ).getPropertyValue( "stroke-dasharray" ) );
    var strokeDashOffset = strokeDashArray - ((strokeDashArray * perc) / 100);

    circle.style.strokeDashoffset = strokeDashOffset;
    el.style.color                = color;

}

document.addEventListener(
    "DOMContentLoaded",
    function () {
        // search all .circularBar and initialize them.
        document.querySelectorAll( "#iubenda-compliance-status .circularBar" ).forEach(
            function (el) {
                circularBar( el );
            }
        );
    }
);