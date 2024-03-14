var __assign =
  (this && this.__assign) ||
  function () {
    __assign =
      Object.assign ||
      function (t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
          s = arguments[i];
          for (var p in s)
            if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
        }
        return t;
      };
    return __assign.apply(this, arguments);
  };
var gtgaMainJs = (function ($) {
  'use strict';
  var _this = this;
  /**
   * Binds Download Tracking
   *
   * @returns {undefined}
   */
  var VideoPercent = {
    ZERO: 0,
    TWENTYFIVE: 25,
    FIFTY: 50,
    SEVENTYFIVE: 75,
    ONEHUNDRED: 100,
  };
  function elementAddedCallback(addedNode) {
    checkVisibilityEvents();
  }
  var observer = new MutationObserver(function (mutationsList) {
    mutationsList.forEach(function (mutation) {
      if (mutation.type === 'childList') {
        Array.prototype.forEach.call(mutation.addedNodes, function (addedNode) {
          if (addedNode.nodeType === Node.ELEMENT_NODE) {
            elementAddedCallback(addedNode);
          }
        });
      }
    });
  });
  function getLinkClickParameters(event, url) {
    var tmpURL = new URL(url);
    var linkHostname = tmpURL.hostname;
    var linkEvent = {
      page_title: wpGoalTrackerGa.pageTitle,
      link_url: url,
      // page_location: window.location.href,
      outbound: isLinkExternal(url),
      link_domain: linkHostname,
      link_text: $(event.target).text(),
      link_classes: $(event.target).attr('class'),
    };
    return linkEvent;
  }
  var click_event = function (event) {
    trackCustomEvent(this, event.data.eventName, event.data.props);
    if (
      (typeof event.target.href !== 'undefined' &&
        event.target.nodeName == 'A') ||
      (typeof event.currentTarget.href !== 'undefined' &&
        event.currentTarget.nodeName == 'A')
    ) {
      if ($(event.target).parent().attr('role') !== 'tab') {
        handleLinks(this, event);
      }
    }
  }; // End of click event function
  function bindEmailLinksTracking() {
    if (wpGoalTrackerGa.trackEmailLinks === '1') {
      $('body').on('click', 'a[href^="mailto:"]', function (e) {
        e.preventDefault();
        var email = this.href.split(':').pop();
        var page = getPageName();
        var eventParameters = {
          page_title: page,
          email_address: email,
          page_location: window.location.href,
          link_text: $(e.target).text(),
          link_classes: $(e.target).attr('class'),
        };
        trackCustomEvent(this, 'email_link_click', eventParameters);
        handleLinks(this, e);
      });
    }
  }
  var isLinkExternal = function (url) {
    var query = new RegExp('//' + location.host + '($|/)');
    if (url.substring(0, 4) === 'http') {
      if (!query.test(url)) {
        return true;
      }
    }
    return false;
  };
  var link_track_external = function (event) {
    var url = getUrl(event);
    if (typeof url !== 'undefined' && url !== '') {
      if (isLinkExternal(url)) {
        link_track_all(event);
      }
    }
  };
  var link_track_external_new_tab = function (event) {
    var url = getUrl(event);
    if (isLinkExternal(url)) {
      var eventParameters = getLinkClickParameters(event, url);
      trackCustomEvent(this, 'link_click', eventParameters);
    }
  };
  var link_track_all = function (event) {
    var url = getUrl(event);
    var hash = isJustHashLink(url);
    if (
      typeof url !== 'undefined' &&
      url !== '' &&
      hash != '#' &&
      $(this).parent().attr('role') !== 'tab'
    ) {
      var eventParameters = getLinkClickParameters(event, url);
      trackCustomEvent(this, 'link_click', eventParameters);
      event.preventDefault();
      if (typeof hash !== 'undefined' && hash !== '') {
        window.location.hash = hash;
      } else {
        setTimeout(function () {
          window.location.href = url;
        }, 250);
      }
    }
  };
  var link_track_all_new_tab = function (event) {
    var url = getUrl(event);
    if (typeof url !== 'undefined' && url !== '') {
      var eventParameters = getLinkClickParameters(event, url);
      trackCustomEvent(this, 'link_click', eventParameters);
    }
  };
  var handleLinks = function (self, event) {
    event.preventDefault();
    var link = getUrl(event);
    if (link === '') return;
    var w;
    var openInNewTab = isNewTab(self);
    if (openInNewTab) {
      w = window.open('', '_blank');
    }
    var hash = isJustHashLink(link);
    if (typeof hash !== 'undefined' && hash !== '') {
      window.location.hash = hash;
    } else if (window.location.href !== link) {
      setTimeout(
        function () {
          if (openInNewTab) {
            w.location.href = link;
          } else {
            window.location.href = link;
          }
        },
        250,
        w,
      );
    }
  };
  var getUrl = function (event) {
    var url = '';
    var $target = $(event.target);
    var $link = $target.closest('a');
    if ($link.length) {
      var href = $link.attr('href');
      if (href && href !== '#') {
        url = $link.prop('href');
      }
    }
    return url;
  };
  var isJustHashLink = function (url) {
    if (url.indexOf('#') === 0) {
      return url;
    }
    var currentUrl = new URL(window.location.href);
    var targetUrl = new URL(url, currentUrl);
    if (targetUrl.origin !== currentUrl.origin) {
      return '';
    }
    if (
      targetUrl.pathname === currentUrl.pathname &&
      targetUrl.search === currentUrl.search &&
      targetUrl.hash !== ''
    ) {
      return targetUrl.hash;
    }
    return '';
  };
  var isNewTab = function (self) {
    var target = $(self).attr('target');
    if (typeof target !== 'undefined' && target.trim() === '_blank') {
      return true;
    }
    return false;
  };
  $(document).ready(function () {
    var targetNode = document.body;
    var config = { childList: true, subtree: true };
    observer.observe(targetNode, config);
    $(window).on('scroll', checkVisibilityEvents);
    // We also want to check it when
    checkVisibilityEvents();
    if (wpGoalTrackerGa.trackEmailLinks) {
      bindEmailLinksTracking();
    }
    // Bind link tracking events
    if (wpGoalTrackerGa.trackLinks.enabled) {
      if (wpGoalTrackerGa.trackLinks.type === 'all') {
        $('body').on(
          'click',
          "a:not([target~='_blank']):not(.video_popup):not(.dtq-video-popup-trigger):not(:has(.video_popup)):not(.video_popup *)",
          link_track_all,
        );
        $('body').on(
          'click',
          "a[target~='_blank']:not(.video_popup):not(.dtq-video-popup-trigger):not(:has(.video_popup)):not(.video_popup *)",
          link_track_all_new_tab,
        );
      } else if (wpGoalTrackerGa.trackLinks.type === 'external') {
        $('body').on(
          'click',
          "a:not([target~='_blank']):not(.video_popup):not(.dtq-video-popup-trigger):not(:has(.video_popup)):not(.video_popup *)",
          link_track_external,
        );
        $('body').on(
          'click',
          "a[target~='_blank']:not(.video_popup):not(.dtq-video-popup-trigger):not(:has(.video_popup)):not(.video_popup *)",
          link_track_external_new_tab,
        );
      }
    }
    wpGoalTrackerGa.click.forEach(function (el) {
      var selector = makeSelector(el);
      $('body').on('click', selector, el, click_event);
    });
  });
  function makeSelector(click_option) {
    var selector = '';
    if (click_option.selectorType === 'class') {
      selector += '.';
    } else if (click_option.selectorType === 'id') {
      selector += '#';
    }
    selector += click_option.selector;
    return selector;
  }
  function checkVisibilityEvents() {
    // TO DO this code can be simplified a lot. May be better to use
    // $('element').visibility()
    var ga_window = $(window).height();
    var ga_visibility_top = $(document).scrollTop();
    for (var i = 0; i < wpGoalTrackerGa.visibility.length; i++) {
      if (!wpGoalTrackerGa.visibility[i].sent) {
        // NB was unescapeChars( wpGoalTrackerGa.visibility[i].select)
        var $select = $(makeSelector(wpGoalTrackerGa.visibility[i]));
        wpGoalTrackerGa.visibility[i].offset = $select.offset();
        if (
          wpGoalTrackerGa.visibility[i].offset &&
          ga_visibility_top + ga_window >=
            wpGoalTrackerGa.visibility[i].offset.top + $select.height()
        ) {
          trackCustomEvent(
            $select,
            wpGoalTrackerGa.visibility[i].eventName,
            wpGoalTrackerGa.visibility[i].props,
          );
          wpGoalTrackerGa.visibility[i].sent = true;
        }
      }
    }
  } // End of bindVisibilityEvents
  var trackCustomEventBasic = function (self, name, props) {
    Object.keys(props).forEach(function (key) {
      props[key] = prepareProps(self, props[key]);
    });
    gtag('event', name, __assign({}, props));
  };
  
  function returnOriginalProp(self, prop) {
    return prop;
  }
  function getPageName() {
    if ('1' === wpGoalTrackerGa.isFrontPage) {
      return 'Home';
    } else {
      if (typeof wpGoalTrackerGa.pageTitle !== 'undefined') {
        return wpGoalTrackerGa.pageTitle;
      }
    }
    return '';
  }
  var trackCustomEvent =
    typeof trackCustomEventPro === 'function'
      ? trackCustomEventPro
      : trackCustomEventBasic;
  var prepareProps =
    typeof get_placeholder === 'function'
      ? get_placeholder
      : returnOriginalProp;
  return { isJustHashLink: isJustHashLink };
})(jQuery);

