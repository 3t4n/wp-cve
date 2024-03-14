(function ($) {
    'use strict';

    function WPGAutoScrollPlugin(options){
    	this.options = options | {};
        this.wN2scRl;
        this.setTimeout;
        this.timer;
        this.timerX = 145;
        this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
        this.addScrollHeight = Math.floor( window.innerHeight * 0.7, 1 );
        this.userHasScrolled = false;
        this.speed = 1;
        this.dbOptions = undefined;

        this.timerSVG = '<svg class="wpg-progress-circle" width="50px" height="50px" xmlns="https://www.w3.org/2000/svg"><circle class="wpg-progress-circle-back" cx="25" cy="25" r="23"></circle><circle class="wpg-progress-circle-prog" cx="25" cy="25" r="23"></circle></svg>';

        this.init();

        return this;
    }

    WPGAutoScrollPlugin.prototype.init = function() {
        var _this = this;

      	_this.createButtons();

        window.onmousewheel = function (e) {
            _this.userHasScrolled = true;
            _this.resetScroll();
            _this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
        }
        window.ontouchmove = function (e) {
            _this.userHasScrolled = true;
            _this.resetScroll();
            _this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
        }
        var clickedOnScrollbar = function(mouseX){
            if( $(window).width() <= mouseX ){
                return true;
            }else{
                return false;
            }
        }

        document.addEventListener('mousedown', function(e) {
            if( clickedOnScrollbar(e.clientX) ){
                _this.userHasScrolled = true;
                _this.resetScroll();
            }
        }, false);
        
        // document.addEventListener('scroll', function(e) {
        //     // console.log("BLOJ");
        //     var button = document.querySelector("#wpg-autoscroll-play-button");
        //     if( ( window.innerHeight + window.scrollY ) >= document.body.offsetHeight ){
        //         var z =  setTimeout(function(){
        //                 console.log("asdasd");
        //             if(button != null){
        //                 button.classList.add('wpg-autoscroll-button-to-top');
        //                 button.innerHTML = WPGAutoscrollObj.toTopIcon;
        //                 clearTimeout(z);
        //             }
        //             }, 500)
        //         }
        //         else{
        //             var z =  setTimeout(function(){
        //                 _this.resetScroll();
        //                     clearTimeout(z);
                        
        //             }, 500)
        //         }
            
        // }, false);

        document.addEventListener('mouseup', function(e) {
            if( clickedOnScrollbar(e.clientX) ){
                _this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
            }
        }, false);

        $(document).on('keydown', function(e) {
            if(e.which == 80 && !( e.which == 19 )) {

                let buttonWrap = $("#wpg-autoscroll-buttons-wrap");
                let playButton = $("#wpg-autoscroll-play-button");

                if( playButton[0] ) {
                    var pauseButton = document.createElement('div');
                    pauseButton.classList.add('wpg-autoscroll-button');
                    pauseButton.classList.add('wpg_animate__zoomIn');
                    pauseButton.setAttribute('id', 'wpg-autoscroll-stop-button');

                    pauseButton.innerHTML = WPGAutoscrollObj.stopIcon;

                    buttonWrap.append(pauseButton);

                    var speedButton = document.createElement('div');
                    speedButton.classList.add('wpg-autoscroll-button');
                    speedButton.classList.add('wpg_animate__zoomIn');
                    speedButton.setAttribute('id', 'wpg-autoscroll-speed-button');
                    speedButton.setAttribute('data-speed', _this.speed);
                    var speedText = 'x1';
                    if( _this.speed == 1 ){
                        speedText = 'x1';
                    }else if( _this.speed == 2 ){
                        speedText = 'x2';
                    }else if ( _this.speed == 4 ) {
                        speedText = 'x4';
                    }
                    speedButton.innerHTML = WPGAutoscrollObj.fastForwardIcon + '<span>' + speedText + '</span>';

                    buttonWrap.append(speedButton);
                    buttonWrap.attr('data-active', 'true');
                    _this.scrolling( _this.speed );
                    $("#wpg-autoscroll-play-button").remove();

                    return;
                } else {
                    _this.resetScroll();
                }
            }
        })
    };

    WPGAutoScrollPlugin.prototype.createButtons = function() {
    	var _this = this;
        document.addEventListener('DOMContentLoaded', function(){
            var body = document.body,
                html = document.documentElement;

            var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );

            _this.prevScrollHeight = html.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
            if( typeof window.wpgAutoScrollOptions != 'undefined' ){
                _this.dbOptions = JSON.parse( atob( window.wpgAutoScrollOptions['wpg_auto_scroll_options'] ) );
                _this.speed = Number(_this.dbOptions.wpg_auto_scroll_default_speed);
            }
        	var buttonWrap = document.createElement('div');
            buttonWrap.setAttribute('data-active', 'false');
        	buttonWrap.classList.add('wpg-autoscroll-buttons-wrap');
            buttonWrap.setAttribute('id', 'wpg-autoscroll-buttons-wrap');

            var hoverTitle = document.createElement('div');
            hoverTitle.classList.add('wpg-hover-title');
            var bottomTriangle = document.createElement('div');
            bottomTriangle.classList.add('wpg-bottom-triangle')
            var titleText = document.createTextNode(WPGAutoscrollObj.buttonHoverTitle);
            hoverTitle.append(titleText);

            var button = document.createElement('div');
            button.classList.add('wpg-autoscroll-button');
            button.classList.add('wpg_animate__zoomIn');
            button.setAttribute('id', 'wpg-autoscroll-play-button');
            if( ( window.innerHeight + window.scrollY ) >= document.body.offsetHeight ){
                button.innerHTML = WPGAutoscrollObj.toTopIcon;
                button.classList.add('wpg-autoscroll-button-to-top');
            }
            else{
                button.innerHTML = WPGAutoscrollObj.playIcon + _this.timerSVG;
            }

            /*
             * Show scroll with progress bar inth bottom
             */
            // var scrollWidth = document.createElement('div');
            // scrollWidth.classList.add('wpg-autoscroll-scrollwidth-wrap');
            // scrollWidth.setAttribute('id', 'wpg-autoscroll-scrollwidth-wrap');

            // var scrollWidthContent = document.createElement('div');
            // scrollWidthContent.classList.add('wpg-autoscroll-scrollwidth-content');
            // scrollWidthContent.setAttribute('id', 'wpg-autoscroll-scrollwidth-content');

            // scrollWidth.appendChild(scrollWidthContent);

            /*
             * Show scroll with progress bar inth bottom
             */

            buttonWrap.appendChild(button);
            hoverTitle.appendChild(bottomTriangle);
            buttonWrap.appendChild(hoverTitle);

            if(document.body != null){
                /*
                 * Show scroll with progress bar inth bottom
                 */
               // document.body.appendChild(scrollWidth);
                /*
                 * Show scroll with progress bar inth bottom
                 */
               document.body.appendChild(buttonWrap);
            }


            /*
             * Show scroll with progress bar inth bottom
             */
            // window.addEventListener("scroll", function(e) {
            //     var triangle = document.getElementById('wpg-autoscroll-scrollwidth-content');
            //     var scrollpercent = (document.body.scrollTop + document.documentElement.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight);
            //     var length = window.innerWidth;
            //     var draw = length * scrollpercent;
              
            //     // Reverse the drawing (when scrolling upwards)
            //     triangle.style.width = ( scrollpercent * 100 ) + '%';
            // }, false);

            /*
             * Show scroll with progress bar inth bottom end
             */

            document.addEventListener('click', function(e){
                if(e.target && e.target.id == 'wpg-autoscroll-play-button'){
                    var pauseButton = document.createElement('div');
                    pauseButton.classList.add('wpg-autoscroll-button');
                    pauseButton.classList.add('wpg_animate__zoomIn');
                    pauseButton.setAttribute('id', 'wpg-autoscroll-stop-button');

                    pauseButton.innerHTML = WPGAutoscrollObj.stopIcon;

                    buttonWrap.appendChild(pauseButton);

                    var speedButton = document.createElement('div');
                    speedButton.classList.add('wpg-autoscroll-button');
                    speedButton.classList.add('wpg_animate__zoomIn');
                    speedButton.setAttribute('id', 'wpg-autoscroll-speed-button');
                    speedButton.setAttribute('data-speed', _this.speed);
                    var speedText = 'x1';
                    if( _this.speed == 1 ){
                        speedText = 'x1';
                    }else if( _this.speed == 2 ){
                        speedText = 'x2';
                    }else if ( _this.speed == 4 ) {
                        speedText = 'x4';
                    }
                    speedButton.innerHTML = WPGAutoscrollObj.fastForwardIcon + '<span>' + speedText + '</span>';

                    buttonWrap.appendChild(speedButton);
                    buttonWrap.setAttribute('data-active', 'true');

                    _this.scrolling( _this.speed );
                    e.target.remove();
                }else if(e.target && e.target.id == 'wpg-autoscroll-stop-button'){
                    _this.resetScroll();
                }else if(e.target && e.target.id == 'wpg-autoscroll-speed-button'){
                    var speed = e.target.getAttribute('data-speed');
                    if( speed == 1 ){
                        _this.scrolling( 2 );
                        _this.speed = 2;
                        e.target.setAttribute('data-speed', '2');
                        // e.target.innerHTML = WPGAutoscrollObj.boltIcon;
                        e.target.innerHTML = WPGAutoscrollObj.fastForwardIcon + '<span>x2</span>';
                    }else if( speed == 2 ){
                        _this.scrolling( 4 );
                        _this.speed = 4;
                        e.target.setAttribute('data-speed', '4');
                        // e.target.innerHTML = WPGAutoscrollObj.flashOnIcon;
                        e.target.innerHTML = WPGAutoscrollObj.fastForwardIcon + '<span>x4</span>';
                    }else if( speed == 4 ){
                        _this.scrolling( 1 );
                        _this.speed = 1;
                        e.target.setAttribute('data-speed', '1');
                        e.target.innerHTML = WPGAutoscrollObj.fastForwardIcon + '<span>x1</span>';
                    }
                }else{
                    _this.resetScroll();
                }

            }, false);
 
            if(_this.dbOptions.wpg_auto_scroll_hover_title) {
                document.addEventListener('mouseover', function(e){                   
                    if(e.target && e.target.id == 'wpg-autoscroll-play-button') {
                        $(document).find('.wpg-hover-title').css("display", "block");
                    }
                    else if(e.target && e.target.id !== 'wpg-autoscroll-play-button') {
                        $(document).find('.wpg-hover-title').css("display", "none");
                    }
                })             
            }

            if(_this.dbOptions.wpg_auto_scroll_button_position == 'left') {
                $(document).find('.wpg-hover-title').css("left", -3 + 'px');
                $(document).find('.wpg-bottom-triangle').css("left", 32 + 'px');
            }

            if( _this.dbOptions.wpg_auto_scroll_autoplay ){
                var autoPlayDelay = _this.dbOptions.wpg_auto_scroll_autoplay_delay > 0 ? _this.dbOptions.wpg_auto_scroll_autoplay_delay * 1000 : 100;
                
                setTimeout( function(){
                    $(document).find("#wpg-autoscroll-play-button").trigger("click");
                },autoPlayDelay);
            }

            document.addEventListener('click',function(e){
                if(e.target.classList.value.indexOf("wpg-autoscroll-button-to-top") !== -1 ){
                      var button = e.target;
                      _this.toTopButton(button);
                 }
            }, false);

        }, false);
    }

    WPGAutoScrollPlugin.prototype.scrolling = function(speed) {
        var _this = this;
        _this.doScroll( speed );
        if( _this.userHasScrolled ){
            _this.userHasScrolled = false;
        }
    };

    WPGAutoScrollPlugin.prototype.resetScroll = function() {
        var wN2scRl;
        var _this = this;
        var wN2scRl;

        var Sa5gNA9k = function(){
            clearTimeout( _this.wN2scRl );
            clearTimeout( _this.setTimeout );
            clearTimeout( _this.timer );
            var buttonWrap = document.getElementById('wpg-autoscroll-buttons-wrap');
            var active = buttonWrap ? buttonWrap.getAttribute('data-active') : 'false';

            if(active == 'true'){
                var button = document.createElement('div');
                button.classList.add('wpg-autoscroll-button');
                button.classList.add('wpg_animate__zoomIn');
                button.setAttribute('id', 'wpg-autoscroll-play-button');

                button.innerHTML = WPGAutoscrollObj.playIcon + _this.timerSVG;


                buttonWrap.appendChild(button);
                document.getElementById('wpg-autoscroll-stop-button').remove();
                document.getElementById('wpg-autoscroll-speed-button').remove();
                buttonWrap.setAttribute('data-active', 'false');
            }

            var y = document.querySelector('.wpg-progress-circle');
            if( y ){
                y.style.display = 'none';
            }
        }

        $(document).on("keydown", function(e){
            if(!e.which == 80) {
                document.onkeydown = Sa5gNA9k;
            }
        })

        if( pageYOffset < document.height - innerHeight ){
            window.scrollBy(0,0);
        }else{
            Sa5gNA9k();
        }
    }

    WPGAutoScrollPlugin.prototype.doScroll = function(speed) {
        var _this = this;
        clearTimeout( _this.wN2scRl );
        clearTimeout( _this.setTimeout );
        clearTimeout( _this.timer );

        // speed = 1;
        _this.wN2scRl = setInterval(function () {
            document.documentElement.scrollTop += speed;
            if( window.scrollY + 10 >= _this.prevScrollHeight ){
                _this.prevScrollHeight += _this.addScrollHeight;

                if (_this.dbOptions.wpg_auto_scroll_rescroll_delay != 0) {
                    _this.resetScroll();
                }

                var time = parseInt(_this.dbOptions.wpg_auto_scroll_rescroll_delay);
                time = parseFloat(time);

                _this.timerX = 145;
                if( _this.dbOptions.wpg_auto_scroll_rescroll_delay != 0 ){
                    var y = document.querySelector('.wpg-progress-circle');
                    var x = document.querySelector('.wpg-progress-circle-prog');
                                y.style.display = 'none';
                }

                var timeN = (time * 1000)/29;
                if (timeN !== 0) {
                    _this.setTimeout = setTimeout(function(){
                        document.getElementById('wpg-autoscroll-play-button').click();
                    }, time * 1000 + timeN + 1000 );
                } else {
                    if (document.getElementById('wpg-autoscroll-play-button')) {
                        document.getElementById('wpg-autoscroll-play-button').click();
                    };
                }

                if (_this.dbOptions.wpg_auto_scroll_rescroll_delay != 0) {
                    y.style.display = 'block';
                }

                _this.timer = setInterval(function(){
                    if( _this.dbOptions.wpg_auto_scroll_rescroll_delay != 0 ){
                        x.style.strokeDasharray = _this.timerX + ' 999';
                    }
                    if( _this.timerX <= 0 ){
                        setTimeout(function(){
                            if( _this.dbOptions.wpg_auto_scroll_rescroll_delay != 0 ){
                                y.style.display = 'none';
                            }
                        }, 1000);
                        clearInterval( _this.timer );
                    }
                    _this.timerX -= 5;
                }, timeN );

            }

            if ( ( window.innerHeight + window.scrollY ) >= document.body.offsetHeight ) {
                _this.resetScroll();
                _this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
                var button = document.querySelector("#wpg-autoscroll-play-button");                
                button.classList.add('wpg-autoscroll-button-to-top');
                button.innerHTML = WPGAutoscrollObj.toTopIcon;
                if(_this.dbOptions.wpg_auto_scroll_go_to_top_automatically){
                    var goToTopAutomaticallyDelay = _this.dbOptions.wpg_auto_scroll_go_to_top_automatically_delay > 0 ? _this.dbOptions.wpg_auto_scroll_go_to_top_automatically_delay * 1000 : 0;

                    if ( goToTopAutomaticallyDelay) {
                        setTimeout( function(){
                            $(document).find(".wpg-autoscroll-button-to-top").trigger("click");
                        }, goToTopAutomaticallyDelay)                        
                    } else {
                        $(document).find(".wpg-autoscroll-button-to-top").trigger("click");
                    };
                }
            }

        }, 1 );
    }

    WPGAutoScrollPlugin.prototype.toTopButton = function(button) {
        var _this = this;
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
        _this.prevScrollHeight = document.documentElement.scrollTop + Math.floor( window.innerHeight * 0.7, 1 );
        _this.resetScroll();
    }

    new WPGAutoScrollPlugin();

})(jQuery);