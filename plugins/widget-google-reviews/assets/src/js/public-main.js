function rplg_badge_init(el, name, root_class) {
    var btn = el.querySelector('.wp-' + name + '-badge'),
        form = el.querySelector('.wp-' + name + '-form');

    if (!btn || !form) return;

    var wpac = document.createElement('div');
    wpac.className = root_class + ' wpac';

    if (btn.className.indexOf('-fixed') > -1) {
        wpac.appendChild(btn);
    }
    wpac.appendChild(form);
    document.body.appendChild(wpac);

    btn.onclick = function() {
        rplg_load_imgs(wpac);
        form.style.display='block';
    };
}

function rplg_load_imgs(el) {
    var imgs = el.querySelectorAll('img.rplg-blazy[data-src]');
    for (var i = 0; i < imgs.length; i++) {
        imgs[i].setAttribute('src', imgs[i].getAttribute('data-src'));
        imgs[i].removeAttribute('data-src');
    }
}

function rplg_next_reviews(name, pagin) {
    var parent = this.parentNode,
        selector = '.' + name + '-review.' + name + '-hide';
        reviews = parent.querySelectorAll(selector);
    for (var i = 0; i < pagin && i < reviews.length; i++) {
        if (reviews[i]) {
            reviews[i].className = reviews[i].className.replace(name + '-hide', ' ');
            rplg_load_imgs(reviews[i]);
        }
    }
    reviews = parent.querySelectorAll(selector);
    if (reviews.length < 1) {
        parent.removeChild(this);
    }
    window.rplg_blazy && window.rplg_blazy.revalidate();
    return false;
}

function rplg_leave_review_window() {
    _rplg_popup(this.getAttribute('href'), 620, 500);
    return false;
}

function _rplg_lang() {
    var n = navigator;
    return (n.language || n.systemLanguage || n.userLanguage ||  'en').substr(0, 2).toLowerCase();
}

function _rplg_popup(url, width, height, prms, top, left) {
    top = top || (screen.height/2)-(height/2);
    left = left || (screen.width/2)-(width/2);
    return window.open(url, '', 'location=1,status=1,resizable=yes,width='+width+',height='+height+',top='+top+',left='+left);
}

function _rplg_timeago(els) {
    for (var i = 0; i < els.length; i++) {
        var clss = els[i].className, time;
        if (clss.indexOf('google') > -1) {
            time = parseInt(els[i].getAttribute('data-time'));
            time *= 1000;
        } else if (clss.indexOf('facebook') > -1) {
            time = new Date(els[i].getAttribute('data-time').replace(/\+\d+$/, '')).getTime();
        } else {
            time = new Date(els[i].getAttribute('data-time').replace(/ /, 'T')).getTime();
        }
        els[i].innerHTML = WPacTime.getTime(time, _rplg_lang(), 'ago');
    }
}

function _rplg_init_blazy(attempts) {
    if (!window.Blazy) {
        if (attempts > 0) {
            setTimeout(function() { _rplg_init_blazy(attempts - 1); }, 200);
        }
        return;
    }
    window.rplg_blazy = new Blazy({selector: 'img.rplg-blazy'});
}

function _rplg_read_more() {
    var read_more = document.querySelectorAll('.wp-more-toggle');
    for (var i = 0; i < read_more.length; i++) {
        (function(rm) {
        rm.onclick = function() {
            rm.parentNode.removeChild(rm.previousSibling.previousSibling);
            rm.previousSibling.className = '';
            rm.textContent = '';
        };
        })(read_more[i]);
    }
}

function _rplg_get_parent(el, cl) {
    cl = cl || 'rplg';
    if (el.className.split(' ').indexOf(cl) < 0) {
        // the last semicolon (;) without braces ({}) in empty loop makes error in WP Faster Cache
        //while ((el = el.parentElement) && el.className.split(' ').indexOf(cl) < 0);
        while ((el = el.parentElement) && el.className.split(' ').indexOf(cl) < 0){}
    }
    return el;
}

function _grw_init_slider(el) {
    const SLIDER_ELEM  = el.querySelector('.grw-row'),
          SLIDER_OPTS  = JSON.parse(SLIDER_ELEM.getAttribute('data-options')),
          SLIDER_SPEED = SLIDER_OPTS.speed * 1000,
          REVIEWS_ELEM = el.querySelector('.grw-reviews'),
          REVIEW_ELEMS = el.querySelectorAll('.grw-review');

    var rootElSize   = '',
        resizeTimout = null,
        swipeTimout  = null;

    var init = function() {
        if (isVisible(SLIDER_ELEM)) {
            setTimeout(resize, 1);
            _rplg_init_blazy(10);
            if (REVIEW_ELEMS.length && SLIDER_OPTS.autoplay) {
                setTimeout(swipe, SLIDER_SPEED);
            }
        } else {
            setTimeout(init, 300);
        }
    }
    init();

    window.addEventListener('resize', function() {
        var vv = reviewsBack();
        clearTimeout(resizeTimout);
        resizeTimout = setTimeout(resize, 150, vv);
    });

    if (REVIEWS_ELEM) {
        REVIEWS_ELEM.addEventListener('scroll', function() {
            setTimeout(dotsinit, 200);
            if (window.rplg_blazy) {
                window.rplg_blazy.revalidate();
            }
        });
    }

    function resize(vv) {
        var size,
            row_elems = el.querySelectorAll('.grw-row');

        for (let i = 0; i < row_elems.length; i++) {
            let row_elem = row_elems[i],
                offsetWidth = row_elem.offsetWidth;
            if (offsetWidth < 510) {
                size = 'xs';
            } else if (offsetWidth < 750) {
                size = 'x';
            } else if (offsetWidth < 1100) {
                size = 's';
            } else if (offsetWidth < 1450) {
                size = 'm';
            } else if (offsetWidth < 1800) {
                size = 'l';
            } else {
                size = 'xl';
            }
            row_elem.className = 'grw-row grw-row-' + size;
        }

        if (REVIEW_ELEMS.length && rootElSize != size) {
            setTimeout(function() {
                if (REVIEWS_ELEM.scrollLeft != vv * REVIEW_ELEMS[0].offsetWidth) {
                    REVIEWS_ELEM.scrollLeft = vv * REVIEW_ELEMS[0].offsetWidth;
                }
                dotsinit();
                rootElSize = size;
            }, 200);
        }
    }

    function dotsinit() {
        var reviews_elem = el.querySelector('.grw-reviews'),
            review_elems = el.querySelectorAll('.grw-review'),
            t = review_elems.length,
            v = Math.round(reviews_elem.offsetWidth / review_elems[0].offsetWidth);

        var dots = Math.ceil(t/v),
            dotscnt = el.querySelector('.grw-dots');

        if (!dotscnt) return;

        dotscnt.innerHTML = '';
        for (var i = 0; i < dots; i++) {
            var dot = document.createElement('div');
            dot.className = 'grw-dot';

            var revWidth = review_elems[0].offsetWidth;
            var center = (reviews_elem.scrollLeft + (reviews_elem.scrollLeft + revWidth * v)) / 2;

            x = Math.ceil((center * dots) / reviews_elem.scrollWidth);
            if (x == i + 1) dot.className = 'grw-dot active';

            dot.setAttribute('data-index', i + 1);
            dot.setAttribute('data-visible', v);
            dotscnt.appendChild(dot);

            dot.onclick = function() {
                var curdot = el.querySelector('.grw-dot.active'),
                    ii = parseInt(curdot.getAttribute('data-index')),
                    i = parseInt(this.getAttribute('data-index')),
                    v = parseInt(this.getAttribute('data-visible'));

                if (ii < i) {
                    scrollNext(v * Math.abs(i - ii));
                } else {
                    scrollPrev(v * Math.abs(i - ii));
                }

                el.querySelector('.grw-dot.active').className = 'grw-dot';
                this.className = 'grw-dot active';

                if (swipeTimout) {
                    clearInterval(swipeTimout);
                }
            };
        }
    }

    function isVisible(elem) {
        return !!(elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length) && window.getComputedStyle(elem).visibility !== 'hidden';
    }

    function isVisibleInParent(elem) {
        var elemRect = elem.getBoundingClientRect(),
            parentRect = elem.parentNode.getBoundingClientRect();

        return (Math.abs(parentRect.left - elemRect.left) < 2 || parentRect.left <= elemRect.left) && elemRect.left < parentRect.right &&
               (Math.abs(parentRect.right - elemRect.right) < 2 || parentRect.right >= elemRect.right) && elemRect.right > parentRect.left;
    }

    function scrollPrev(offset) {
        REVIEWS_ELEM.scrollBy(
            -REVIEW_ELEMS[0].offsetWidth * offset, 0
        );
    }

    function scrollNext(offset) {
        REVIEWS_ELEM.scrollBy(
            REVIEW_ELEMS[0].offsetWidth * offset, 0
        );
    }

    var prev = el.querySelector('.grw-prev');
    if (prev) {
        prev.onclick = function() {
            scrollPrev(1);
            if (swipeTimout) {
                clearInterval(swipeTimout);
            }
        };
    }

    var next = el.querySelector('.grw-next');
    if (next) {
        next.onclick = function() {
            scrollNext(1);
            if (swipeTimout) {
                clearInterval(swipeTimout);
            }
        };
    }

    function swipe() {
        if (isVisibleInParent(el.querySelector('.grw-review:last-child'))) {
            scrollPrev(REVIEW_ELEMS.length - reviewsPerView());
        } else {
            scrollNext(1);
        }
        swipeTimout = setTimeout(swipe, SLIDER_SPEED);
    }

    function reviewsBack() {
        return Math.round(REVIEWS_ELEM.scrollLeft / REVIEW_ELEMS[0].offsetWidth);
    }

    function reviewsPerView() {
        return Math.round(REVIEWS_ELEM.offsetWidth / REVIEW_ELEMS[0].offsetWidth);
    }
}

function grw_init(el, layout) {
    _rplg_timeago(document.querySelectorAll('.wpac [data-time]'));
    _rplg_read_more();
    _rplg_init_blazy(10);

    el = _rplg_get_parent(el, 'wp-gr');
    if (el && el.getAttribute('data-exec') != 'true' && (layout == 'slider' || layout == 'grid')) {
        el.setAttribute('data-exec', 'true');
        _grw_init_slider(el);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.wp-gr[data-exec="false"]');
    for (var i = 0; i < elems.length; i++) {
        (function(elem) {
            grw_init(elem, elem.getAttribute('data-layout'));
        })(elems[i]);
    }
});