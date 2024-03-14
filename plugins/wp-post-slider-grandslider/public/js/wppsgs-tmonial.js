(function ($) {
    'use strict';

    var cards = $("#card-slider .slider-item").toArray();
    var cardSlide = $(".slider-wrap").data('slide');
    var cardSpeed = $(".slider-wrap").data('speed');

    startAnim(cards);
    function startAnim(array) {
        if (array.length >= 2) {
            if ('two' === cardSlide) {
                TweenMax.fromTo(
                    array[0],
                    0.5,
                    { x: 0, y: 0, opacity: 0.75 },
                    {
                        x: 0,
                        y: -120,
                        opacity: 0,
                        zIndex: 0,
                        delay: 0.03,
                        ease: Cubic.easeInOut,
                        onComplete: sortArray(array)
                    }
                );
                TweenMax.fromTo(
                    array[1],
                    0.5,
                    { x: 140, y: 125, opacity: 1, zIndex: 1 },
                    {
                        x: 0,
                        y: 0,
                        opacity: 0.75,
                        zIndex: 0,
                        boxShadow: "rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;",
                        ease: Cubic.easeInOut
                    }
                );
                TweenMax.to(array[2], 0.5, {
                    bezier: [
                        { x: 0, y: 250 },
                        { x: 65, y: 200 },
                        { x: 140, y: 125 }
                    ],
                    boxShadow: "rgba(0, 0, 0, 0.24) 0px 3px 8px;",
                    zIndex: 1,
                    opacity: 1,
                    ease: Cubic.easeInOut
                });
            } else {
                TweenMax.fromTo(
                    array[0],
                    0.5,
                    { x: 0, y: 0, opacity: 0.75 },
                    {
                        x: 0,
                        y: -120,
                        opacity: 0,
                        zIndex: 0,
                        delay: 0.03,
                        ease: Cubic.easeInOut,
                        onComplete: sortArray(array)
                    }
                );

                TweenMax.fromTo(
                    array[1],
                    0.5,
                    { x: 79, y: 125, opacity: 1, zIndex: 1 },
                    {
                        x: 0,
                        y: 0,
                        opacity: 0.75,
                        zIndex: 0,
                        boxShadow: "-5px 8px 8px 0 rgba(82,89,129,0.05)",
                        ease: Cubic.easeInOut
                    }
                );

                TweenMax.to(array[2], 0.5, {
                    bezier: [
                        { x: 0, y: 250 },
                        { x: 65, y: 200 },
                        { x: 150, y: 125 }
                    ],
                    boxShadow: "-5px 8px 8px 0 rgba(82,89,129,0.05)",
                    zIndex: 1,
                    opacity: 1,
                    ease: Cubic.easeInOut
                });

                TweenMax.fromTo(
                    array[3],
                    0.5,
                    { x: 0, y: 400, opacity: 0, zIndex: 0 },
                    { x: 0, y: 250, opacity: 0.75, zIndex: 0, ease: Cubic.easeInOut }
                );
            }
        } else {
            $("#card-slider").append(
                "Sorry, carousel should contain more than 2 slides"
            );
        }
    }
    function sortArray(array) {
        clearTimeout(delay);
        var delay = setTimeout(function () {
            var firstElem = array.shift();
            array.push(firstElem);
            return startAnim(array);
        }, cardSpeed);
    }

})(jQuery);