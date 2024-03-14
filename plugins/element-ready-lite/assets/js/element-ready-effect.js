;(function ($) {

"use strict";

var Element_Ready_Buttons_Script = function ( $scope, $ ){ 

    /*------------------------------
        BUTTON RIPPLE EFFECT
    -------------------------------*/
    var rippleButton = $('.ripple__btn');
    rippleButton.append('<span class="ripples"></span>').html();
    rippleButton.on('mouseenter', function (e) {
        var parentOffset = $(this).offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $(this).find('span').css({
            top: relY,
            left: relX
        })
    })
    rippleButton.on('mouseout', function (e) {
        var parentOffset = $(this).offset(),
            relX = e.pageX - parentOffset.left,
            relY = e.pageY - parentOffset.top;
        $(this).find('span').css({
            top: relY,
            left: relX
        })
    });
    
/*-------------------------------
    BUTTON TEXT EFFECT 1
---------------------------------*/
 $('.btn__texteffect__1 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__1');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateX: [0, -30],opacity: [1, 0],easing: 'easeInExpo',duration: 500,delay: function (el, index) {return index * 30;},}).add({targets: letter,translateX: [40, 0],opacity: [0, 1],easing: 'easeOutExpo',duration: 1100,delay: function (el, index) {return index * 30;},});});});

/*-------------------------------
    BUTTON TEXT EFFECT 2
---------------------------------*/
 $('.btn__texteffect__2 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__2');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateX: [0, 40],opacity: [1, 0],easing: 'easeInExpo',duration: 500,delay: function (el, index) {return index * 30;},}).add({targets: letter,translateX: [-30, 0],opacity: [0, 1],easing: 'easeOutExpo',duration: 1100,delay: function (el, index) {return index * 30;},});});});

/*-------------------------------
    BUTTON TEXT EFFECT 3
---------------------------------*/
 $('.btn__texteffect__3 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__3');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateY: [0, -30],opacity: [1, 0],easing: 'easeInExpo',duration: 500,delay: function (el, index) {return index * 30;},}).add({targets: letter,translateY: [40, 0],opacity: [0, 1],easing: 'easeOutExpo',duration: 1100,delay: function (el, index) {return index * 30;},});});});

/*-------------------------------
    BUTTON TEXT EFFECT 4
---------------------------------*/
 $('.btn__texteffect__4 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__4');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateY: [0, 30],opacity: [1, 0],easing: 'easeInExpo',duration: 500,delay: function (el, index) {return index * 30;},}).add({targets: letter,translateY: [-40, 0],opacity: [0, 1],easing: 'easeOutExpo',duration: 1100,delay: function (el, index) {return index * 30;},});});});

/*-------------------------------
    BUTTON TEXT EFFECT 5
---------------------------------*/
 $('.btn__texteffect__5 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__5');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,scale: [0.3, 1],opacity: [0, 1],translateZ: 0,easing: "easeOutExpo",duration: 600,delay: (el, i) => 70 * (i + 1)});});});

/*-------------------------------
    BUTTON TEXT EFFECT 6
---------------------------------*/
 $('.btn__texteffect__6 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__6');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,scale: [4, 1],opacity: [0, 1],translateZ: 0,easing: "easeOutExpo",duration: 950,delay: (el, i) => 70 * i});});});

/*-------------------------------
    BUTTON TEXT EFFECT 7
---------------------------------*/
 $('.btn__texteffect__7 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__7');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,opacity: [0, 1],easing: "easeInOutQuad",duration: 1000,delay: (el, i) => 150 * (i + 1)});});});

/*-------------------------------
    BUTTON TEXT EFFECT 8
---------------------------------*/
 $('.btn__texteffect__8 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__8');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateY: ["1.1em", 0],translateZ: 0,duration: 750,delay: (el, i) => 50 * i});});});

/*-------------------------------
    BUTTON TEXT EFFECT 9
---------------------------------*/
 $('.btn__texteffect__9 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__9');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,translateY: ["1.1em", 0],translateX: ["0.55em", 0],translateZ: 0,rotateZ: [180, 0],duration: 750,easing: "easeOutExpo",delay: (el, i) => 50 * i});});});

/*-------------------------------
    BUTTON TEXT EFFECT 10
---------------------------------*/
 $('.btn__texteffect__10 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__10');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,scale: [0, 1],duration: 1500,elasticity: 600,delay: (el, i) => 45 * (i + 1)});});});

/*-------------------------------
    BUTTON TEXT EFFECT 11
---------------------------------*/
 $('.btn__texteffect__11 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__11');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,rotateY: [-90, 0],duration: 1300,delay: (el, i) => 45 * i});});});

/*-------------------------------
    BUTTON TEXT EFFECT 12
---------------------------------*/
 $('.btn__texteffect__12 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__12');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,scaleY: [0, 1],opacity: [0.5, 1],easing: "easeOutExpo",duration: 1000});});});
    
/*-------------------------------
    BUTTON TEXT EFFECT 13
---------------------------------*/
 $('.btn__texteffect__13 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__13');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,opacity: [0, 1],translateX: [40, 0],translateZ: 0,scaleX: [0.3, 1],easing: "easeOutExpo",duration: 1800,offset: '-=600',delay: (el, i) => 150 + 25 * i});});});

/*-------------------------------
    BUTTON TEXT EFFECT 14
---------------------------------*/
 $('.btn__texteffect__14 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__14');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 1000,delay: function (el, index) {return 75 + index * 40;},easing: 'easeOutElastic',elasticity: 650,opacity: {value: 1,easing: 'easeOutExpo',},translateY: ['50%', '0%']});});});
    
/*-------------------------------
    BUTTON TEXT EFFECT 15
---------------------------------*/
 $('.btn__texteffect__15 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__15');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 700,delay: function (el, index) {return index * 50;},easing: 'easeOutCirc',opacity: 1,translateX: function (el, index) {return [(50 + index * 10), 0]}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 16
---------------------------------*/
 $('.btn__texteffect__16 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__16');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 800,delay: function (el, index) {return index * 50;},easing: 'easeOutElastic',opacity: 1,translateY: function (el, index) {return index % 2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];}});});});
    
/*-------------------------------
    BUTTON TEXT EFFECT 17
---------------------------------*/
 $('.btn__texteffect__17 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__17');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 700,delay: function (el, index) {return (el.parentNode.children.length - index - 1) * 80;},easing: 'easeOutElastic',opacity: 1,translateY: function (el, index) {return index % 2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];},rotateZ: [90, 0]});});});

/*-------------------------------
    BUTTON TEXT EFFECT 18
---------------------------------*/
 $('.btn__texteffect__18 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__18');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 700,delay: function (el, index) {return 550 + index * 50;},easing: 'easeOutQuint',opacity: {value: 1,easing: 'linear',},translateY: ['-150%', '0%'],rotateY: [180, 0]});});});

/*-------------------------------
    BUTTON TEXT EFFECT 19
---------------------------------*/
 $('.btn__texteffect__19 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__19');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 600,easing: 'easeOutQuart',opacity: 1,translateY: function (el, index) {return index % 2 === 0 ? ['-40%', '0%'] : ['40%', '0%'];},rotateZ: [10, 0]});});});
    
/*-------------------------------
    BUTTON TEXT EFFECT 20
---------------------------------*/
 $('.btn__texteffect__20 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__20');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 250,delay: function (el, index) {return 200 + index * 25;},easing: 'easeOutCubic',opacity: 1,translateY: ['-50%', '0%']});});});

/*-------------------------------
    BUTTON TEXT EFFECT 21
---------------------------------*/
 $('.btn__texteffect__21 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__21');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return 150 + (el.parentNode.children.length - index - 1) * 20;},easing: 'easeOutQuad',opacity: 1,translateY: ['100%', '0%']});});});

/*-------------------------------
    BUTTON TEXT EFFECT 22
---------------------------------*/
 $('.btn__texteffect__22 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__22');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return index * 50;},easing: 'easeOutSine',opacity: 1,rotateY: [-90, 0]});});});

/*-------------------------------
    BUTTON TEXT EFFECT 23
---------------------------------*/
 $('.btn__texteffect__23 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__23');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 1000,delay: function (el, index) {return 100 + index * 30;},easing: 'easeOutElastic',elasticity: anime.random(400, 700),opacity: 1,rotateZ: function (el, index) {return [anime.random(20, 40), 0];}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 24
---------------------------------*/
 $('.btn__texteffect__24 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__24');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return 200 + index * 20;},easing: 'easeOutExpo',opacity: 1,rotateY: [-90, 0]});});});
/*-------------------------------
    BUTTON TEXT EFFECT 25
---------------------------------*/
 $('.btn__texteffect__25 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__25');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return 200 + index * 30;},easing: 'easeOutExpo',opacity: 1,rotateX: [90, 0]});});});

/*-------------------------------
    BUTTON TEXT EFFECT 26
---------------------------------*/
function lineEq(y2, y1, x2, x1, currentVal) {var m = (y2 - y1) / (x2 - x1),b = y1 - m * x1;return m * currentVal + b;}function TextFx(el) {this.el = el;this._init();}
 $('.btn__texteffect__26 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__26');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 800,easing: 'easeOutExpo',opacity: 1,translateY: function (el, index) {var p = el.parentNode,lastElOffW = p.lastElementChild.offsetWidth,firstElOffL = p.firstElementChild.offsetLeft,w = p.offsetWidth - lastElOffW - firstElOffL - (p.offsetWidth - lastElOffW - p.lastElementChild.offsetLeft),tyVal = lineEq(0, 200, firstElOffL + w / 2, firstElOffL, el.offsetLeft);return [Math.abs(tyVal) + 50 + '%', '0%'];},rotateZ: function (el, index) {var p = el.parentNode,lastElOffW = p.lastElementChild.offsetWidth,firstElOffL = p.firstElementChild.offsetLeft,w = p.offsetWidth - lastElOffW - p.firstElementChild.offsetLeft - (p.offsetWidth - lastElOffW - p.lastElementChild.offsetLeft),rz = lineEq(90, -90, firstElOffL + w, firstElOffL, el.offsetLeft);return [rz, 0];}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 27
---------------------------------*/
 $('.btn__texteffect__27 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__27');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 500,easing: 'easeOutExpo',delay: function (el, index) {return 200 + index * 30;},opacity: 1,rotateZ: [20, 0],translateY: function (el, index) {var p = el.parentNode,lastElOffW = p.lastElementChild.offsetWidth,firstElOffL = p.firstElementChild.offsetLeft,w = p.offsetWidth - lastElOffW - firstElOffL - (p.offsetWidth - lastElOffW - p.lastElementChild.offsetLeft),tyVal = lineEq(-130, -60, firstElOffL + w, firstElOffL, el.offsetLeft);return [Math.abs(tyVal) + '%', '0%'];}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 28
---------------------------------*/
 $('.btn__texteffect__28 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__28');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return 100 + index * 50;},easing: 'easeOutExpo',opacity: 1,rotateX: [110, 0]});});});

/*-------------------------------
    BUTTON TEXT EFFECT 29
---------------------------------*/
 $('.btn__texteffect__29 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__29');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: function (el, index) {return anime.random(800, 1000)},delay: function (el, index) {return anime.random(0, 75)},easing: 'easeInOutExpo',opacity: 1,translateY: ['-300%', '0%'],rotateZ: function (el, index) {return [anime.random(-50, 50), 0];}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 30
---------------------------------*/
 $('.btn__texteffect__30 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__30');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 650,easing: 'easeOutQuint',delay: function (el, index) {return 450 + (el.parentNode.children.length - index - 1) * 30;},opacity: 1,translateX: function (el, index) {return [-1 * el.offsetLeft, 0];}});});});

/*-------------------------------
    BUTTON TEXT EFFECT 31
---------------------------------*/
 $('.btn__texteffect__31 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__31');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 800,delay: function (el, index) {return 300 + index * 75;},easing: 'easeInOutQuint',opacity: 1,scaleY: [8, 1],scaleX: [0.5, 1],translateY: ['-100%', '0%']});});});

/*-------------------------------
    BUTTON TEXT EFFECT 32
---------------------------------*/
 $('.btn__texteffect__32 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__32');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,duration: 400,delay: function (el, index) {return 50 + index * 20;},easing: 'easeOutExpo',opacity: 1,translateY: ['50%', '0%']});});});

/*-------------------------------
    BUTTON TEXT EFFECT 33
---------------------------------*/
 $('.btn__texteffect__33 .button__title').each(function () {$(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='btn__letters'>$&</span>"));});var btnHover = document.querySelectorAll('.btn__texteffect__33');btnHover.forEach(function (btnHover) {btnHover.addEventListener('mouseenter', function () {var letter = btnHover.querySelectorAll('.btn__letters');anime.timeline({}).add({targets: letter,scale: [0.3, 1],opacity: [0, 1],translateZ: 0,easing: "easeOutExpo",duration: 600,delay: (el, i) => 70 * (i + 1)});});});

}


 $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Dual_Button.default', Element_Ready_Buttons_Script);
});

})(jQuery);