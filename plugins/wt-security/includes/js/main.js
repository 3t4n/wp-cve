
jQuery(document).ready(function ($) {

  $('#wtotem-dos').on('change', function () {
    let checked = $(this).prop('checked');
    toggleLimits($('#wtotem-dos-limit'), $('#wtotem-dos-limit input'), checked);
  });

  $('#wtotem-login-attempts').on('change', function () {
    let checked = $(this).prop('checked');
    toggleLimits($('#wtotem-login-attempts-limit'), $('#wtotem-login-attempts-limit input'), checked);
  });

  function toggleLimits(limitWrap, limitInput, checked) {
    (checked) ? limitWrap.removeClass('visually-hidden') : limitWrap.addClass('visually-hidden');
    (checked) ? limitInput.removeAttr('disabled') : limitInput.attr('disabled', 'disabled');
  }

  $('body').on('click', 'ul.wtotem-tabs__caption li:not(.active)', function () {
    $(this)
      .addClass('active').siblings().removeClass('active')
      .closest('div.wtotem-tabs').find('div.wtotem-tabs__content').removeClass('active').eq($(this).index()).addClass('active');
  });

  $('body').on('click', '#wtotem_ps_settings, .port-scanner-ports .port__result-list-item', function () {
    $('.popup-overlay').removeClass('d-none');
    $('body').addClass('lock');
  }).on('click', '.port-scanner-list__header--close, .antivirus-log__close', function (e) {
    $('.popup-overlay').addClass('d-none');
    $('body').removeClass('lock');
  }).on('click', '.popup-overlay', function (e) {
    if (e.target.className.includes('popup-overlay')) {
      $('.popup-overlay').addClass('d-none');
      $('body').removeClass('lock');
    }
  }).on('click', '.firewall-configuration__multi-adding', function () {
    let list = $(this).attr('data-list');
    $('#wtotem-ip-list-type').val(list);
    $('.firewall-multi-adding__close').attr('data-list', list);
    if(list === 'white'){
      AmplitudeAnalytics.addWhiteIpList();
    } else {
      AmplitudeAnalytics.addBlackIpList();
    }
  }).on('click', '.firewall-multi-adding__close', function () {
    if($(this).attr('data-list') === "white"){
      AmplitudeAnalytics.closeWhiteIp();
    } else{
      AmplitudeAnalytics.closeBlackIp();
    }
  }).on('click', '.wtotem_reports-accordion__title', function () {
    $(this).next('div').toggleClass('visually-hidden')
  }).on('click', '.wtotem_alert__close', function () {
    $(this).parent('.wtotem_alert').remove();
  }).on('hover', '.wtotem_title-info__info', function () {
    let service = $(this).attr('data-service');
    if(service) {
      AmplitudeAnalytics.showTooltip(service);
    }
  }).on('click', '.wtotem_calendar_from', function () {
    AmplitudeAnalytics.selectGraphStartDay($(this).attr('data-service'));
  }).on('click', '.wtotem_calendar_to', function () {
    AmplitudeAnalytics.selectGraphEndDay($(this).attr('data-service'));
  }).on('click', '.open_support_dialog', function () {
    AmplitudeAnalytics.openSupportDialog($(this).attr('data-service'));
  });


  // Pop-up notification options.
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "5000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

});

function treatAsUTC(date) {
  var result = new Date(date);
  result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
  return result;
}

function daysBetween(startDate, endDate) {
  var millisecondsPerDay = 24 * 60 * 60 * 1000;
  return (treatAsUTC(endDate) - treatAsUTC(startDate)) / millisecondsPerDay;
}

// Hamburger menu.
const burgerEl = document.querySelector(".wtotem_burger");
const menuEl = document.querySelector(".wtotem_nav");
const bodyEl = document.getElementsByTagName("body")[0];

if (burgerEl) {
  burgerEl.addEventListener("click", () => {
    burgerEl.classList.toggle("active");
    menuEl.classList.toggle("active");
    // Adds body overflow hidden when the menu is open.
    bodyEl.classList.toggle("lock");
  });
}

// Report generate Modal.
const reportsBtn = document.querySelectorAll(".add_report");
const reportsModal = document.querySelector(".wtotem_reports-modal");
const reportsModalClose = document.querySelector(".wtotem_reports-modal__close");
const reportsModalMessage = document.querySelector("#wtotem_reports_form-messages");

if (reportsBtn) {
  for (let elem of reportsBtn) {
    elem.addEventListener("click", () => {
      reportsModal.classList.add("wtotem_reports-modal--active");
      bodyEl.classList.add("lock");
    });
  }
}

if (reportsModalClose) {
  reportsModalClose.addEventListener("click", () => {
    reportsModal.classList.remove("wtotem_reports-modal--active");
    bodyEl.classList.remove("lock");
    reportsModalMessage.innerHTML = '';
  });
}


const addSideModal = (modal, openButton, closeButton, form) => {

  const overlay = document.querySelector(".side-modal__overlay");
  const message = document.querySelector(".wtotem_input__messages");
  const bodyEl = document.getElementsByTagName("body")[0];
  const toggleClassName = "side-modal--opened";
  const toggleBody = "lock";

  const showModal = () => {
    modal.classList.add(toggleClassName);
    bodyEl.classList.add(toggleBody);
  };

  const closeModal = () => {
    modal.classList.remove(toggleClassName);
    bodyEl.classList.remove(toggleBody);
    if (form) {
      form.reset();
      message.innerHTML = '';
    }
  };

  openButton.addEventListener("click", showModal);
  closeButton.addEventListener("click", closeModal);
  overlay.addEventListener("click", closeModal);
};

(function () {
  const modal = document.querySelector(".firewall-multi-adding");
  const openButton = document.querySelector(".firewall-configuration__multi-adding");
  const openButton2 = document.querySelector(".multi-adding-deny_list");
  const closeButton = document.querySelector(".firewall-multi-adding__close");
  const form = document.querySelector("#wtotem-allow-deny-multi-add-form");

  if (modal) {
    addSideModal(modal, openButton, closeButton, form);
    addSideModal(modal, openButton2, closeButton, form);
  }
})();

(function () {
  const modal = document.querySelector(".country-blocking-modal");
  const openButton = document.querySelector("#block_countries_btn");
  const closeButton = document.querySelector(".country-blocking-modal__closeBtn");

  if (modal) {
    addSideModal(modal, openButton, closeButton, false);
  }
})();

window.addEventListener('DOMContentLoaded', function () {

  function tlite(t) {
    document.addEventListener("mouseover", function (e) {
      var i = e.target, n = t(i);
      n || (n = (i = i.parentElement) && t(i)), n && tlite.show(i, n, !0)
    })
  }

  tlite.show = function (t, e, i) {
    var n = "data-tlite";
    e = e || {}, (t.tooltip || function (t, e) {
      function o() {
        tlite.hide(t, !0)
      }

      function l() {
        r || (r = function (t, e, i) {
          function n() {
            o.className = "tlite tlite-" + r + s;
            var e = t.offsetTop, i = t.offsetLeft;
            o.offsetParent === t && (e = i = 0);
            var n = t.offsetWidth, l = t.offsetHeight, d = o.offsetHeight, f = o.offsetWidth, a = i + n / 2;
            o.style.top = ("s" === r ? e - d - 10 : "n" === r ? e + l + 10 : e + l / 2 - d / 2) + "px", o.style.left = ("w" === s ? i : "e" === s ? i + n - f : "w" === r ? i + n + 10 : "e" === r ? i - f - 10 : a - f / 2) + "px"
          }

          var o = document.createElement("span"), l = i.grav || t.getAttribute("data-tlite") || "n";
          o.innerHTML = e, t.appendChild(o);
          var r = l[0] || "", s = l[1] || "";
          n();
          var d = o.getBoundingClientRect();
          return "s" === r && d.top < 0 ? (r = "n", n()) : "n" === r && d.bottom > window.innerHeight ? (r = "s", n()) : "e" === r && d.left < 0 ? (r = "w", n()) : "w" === r && d.right > window.innerWidth && (r = "e", n()), o.className += " tlite-visible", o
        }(t, d, e))
      }

      var r, s, d;
      return t.addEventListener("mousedown", o), t.addEventListener("mouseleave", o), t.tooltip = {
        show: function () {
          d = t.title || t.getAttribute(n) || d, t.title = "", t.setAttribute(n, ""), d && !s && (s = setTimeout(l, i ? 150 : 1))
        }, hide: function (t) {
          if (i === t) {
            s = clearTimeout(s);
            var e = r && r.parentNode;
            e && e.removeChild(r), r = void 0
          }
        }
      }
    }(t, e)).show()
  }, tlite.hide = function (t, e) {
    t.tooltip && t.tooltip.hide(e)
  }, "undefined" != typeof module && module.exports && (module.exports = tlite);

  // init tooltip with class js--tooltip
  tlite(el => {
    return {
      el: el.classList.contains('js--tooltip'), grav: 'sw',
    }
  });

});

/**
 * Calendar init
 *
 * @param element
 *   Input selector element with the start date of the period.
 * @param dateFromSelector
 *   Input selector with the start date of the period.
 * @param dateToSelector
 *   Input selector with the end date of the period.
 * @param toggler
 *    Selector element of the wrapper of the elements that open the calendar when clicked.
 * @param period
 * @returns {*}
 * @private
 */
const setFlatpickr_ = (element, dateFromSelector, dateToSelector, toggler, period = null) => {

    const calendar = flatpickr(element,
        {
            mode: "range",
            dateFormat: "j M, Y",
            onChange: function (selectedDates) {
                // debugger;
                const dates = selectedDates.map(date => this.formatDate(date, "j M, Y"));
                document.querySelector(dateFromSelector).value = dates[0];
                if (dates[1]) {
                    document.querySelector(dateToSelector).value = dates[1];
                }
            }
        });

    toggler.addEventListener("click", calendar.open);

    if(period){
      calendar.setDate(period, true);
    }

    return calendar;
};

/*! @source http://purl.eligrey.com/github/FileSaver.js/blob/master/FileSaver.js */
var saveAs=saveAs||function(e){"use strict";if(typeof e==="undefined"||typeof navigator!=="undefined"&&/MSIE [1-9]\./.test(navigator.userAgent)){return}var t=e.document,n=function(){return e.URL||e.webkitURL||e},r=t.createElementNS("http://www.w3.org/1999/xhtml","a"),o="download"in r,i=function(e){var t=new MouseEvent("click");e.dispatchEvent(t)},a=/constructor/i.test(e.HTMLElement),f=/CriOS\/[\d]+/.test(navigator.userAgent),u=function(t){(e.setImmediate||e.setTimeout)(function(){throw t},0)},d="application/octet-stream",s=1e3*40,c=function(e){var t=function(){if(typeof e==="string"){n().revokeObjectURL(e)}else{e.remove()}};setTimeout(t,s)},l=function(e,t,n){t=[].concat(t);var r=t.length;while(r--){var o=e["on"+t[r]];if(typeof o==="function"){try{o.call(e,n||e)}catch(i){u(i)}}}},p=function(e){if(/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(e.type)){return new Blob([String.fromCharCode(65279),e],{type:e.type})}return e},v=function(t,u,s){if(!s){t=p(t)}var v=this,w=t.type,m=w===d,y,h=function(){l(v,"writestart progress write writeend".split(" "))},S=function(){if((f||m&&a)&&e.FileReader){var r=new FileReader;r.onloadend=function(){var t=f?r.result:r.result.replace(/^data:[^;]*;/,"data:attachment/file;");var n=e.open(t,"_blank");if(!n)e.location.href=t;t=undefined;v.readyState=v.DONE;h()};r.readAsDataURL(t);v.readyState=v.INIT;return}if(!y){y=n().createObjectURL(t)}if(m){e.location.href=y}else{var o=e.open(y,"_blank");if(!o){e.location.href=y}}v.readyState=v.DONE;h();c(y)};v.readyState=v.INIT;if(o){y=n().createObjectURL(t);setTimeout(function(){r.href=y;r.download=u;i(r);h();c(y);v.readyState=v.DONE});return}S()},w=v.prototype,m=function(e,t,n){return new v(e,t||e.name||"download",n)};if(typeof navigator!=="undefined"&&navigator.msSaveOrOpenBlob){return function(e,t,n){t=t||e.name||"download";if(!n){e=p(e)}return navigator.msSaveOrOpenBlob(e,t)}}w.abort=function(){};w.readyState=w.INIT=0;w.WRITING=1;w.DONE=2;w.error=w.onwritestart=w.onprogress=w.onwrite=w.onabort=w.onerror=w.onwriteend=null;return m}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content);if(typeof module!=="undefined"&&module.exports){module.exports.saveAs=saveAs}else if(typeof define!=="undefined"&&define!==null&&define.amd!==null){define([],function(){return saveAs})}

!function(t){"use strict";if(t.URL=t.URL||t.webkitURL,t.Blob&&t.URL)try{return void new Blob}catch(e){}var n=t.BlobBuilder||t.WebKitBlobBuilder||t.MozBlobBuilder||function(t){var e=function(t){return Object.prototype.toString.call(t).match(/^\[object\s(.*)\]$/)[1]},n=function(){this.data=[]},o=function(t,e,n){this.data=t,this.size=t.length,this.type=e,this.encoding=n},i=n.prototype,a=o.prototype,r=t.FileReaderSync,c=function(t){this.code=this[this.name=t]},l="NOT_FOUND_ERR SECURITY_ERR ABORT_ERR NOT_READABLE_ERR ENCODING_ERR NO_MODIFICATION_ALLOWED_ERR INVALID_STATE_ERR SYNTAX_ERR".split(" "),s=l.length,u=t.URL||t.webkitURL||t,d=u.createObjectURL,f=u.revokeObjectURL,R=u,p=t.btoa,h=t.atob,b=t.ArrayBuffer,g=t.Uint8Array,w=/^[\w-]+:\/*\[?[\w\.:-]+\]?(?::[0-9]+)?/;for(o.fake=a.fake=!0;s--;)c.prototype[l[s]]=s+1;return u.createObjectURL||(R=t.URL=function(t){var e,n=document.createElementNS("http://www.w3.org/1999/xhtml","a");return n.href=t,"origin"in n||("data:"===n.protocol.toLowerCase()?n.origin=null:(e=t.match(w),n.origin=e&&e[1])),n}),R.createObjectURL=function(t){var e,n=t.type;return null===n&&(n="application/octet-stream"),t instanceof o?(e="data:"+n,"base64"===t.encoding?e+";base64,"+t.data:"URI"===t.encoding?e+","+decodeURIComponent(t.data):p?e+";base64,"+p(t.data):e+","+encodeURIComponent(t.data)):d?d.call(u,t):void 0},R.revokeObjectURL=function(t){"data:"!==t.substring(0,5)&&f&&f.call(u,t)},i.append=function(t){var n=this.data;if(g&&(t instanceof b||t instanceof g)){for(var i="",a=new g(t),l=0,s=a.length;s>l;l++)i+=String.fromCharCode(a[l]);n.push(i)}else if("Blob"===e(t)||"File"===e(t)){if(!r)throw new c("NOT_READABLE_ERR");var u=new r;n.push(u.readAsBinaryString(t))}else t instanceof o?"base64"===t.encoding&&h?n.push(h(t.data)):"URI"===t.encoding?n.push(decodeURIComponent(t.data)):"raw"===t.encoding&&n.push(t.data):("string"!=typeof t&&(t+=""),n.push(unescape(encodeURIComponent(t))))},i.getBlob=function(t){return arguments.length||(t=null),new o(this.data.join(""),t,"raw")},i.toString=function(){return"[object BlobBuilder]"},a.slice=function(t,e,n){var i=arguments.length;return 3>i&&(n=null),new o(this.data.slice(t,i>1?e:this.data.length),n,this.encoding)},a.toString=function(){return"[object Blob]"},a.close=function(){this.size=0,delete this.data},n}(t);t.Blob=function(t,e){var o=e?e.type||"":"",i=new n;if(t)for(var a=0,r=t.length;r>a;a++)Uint8Array&&t[a]instanceof Uint8Array?i.append(t[a].buffer):i.append(t[a]);var c=i.getBlob(o);return!c.slice&&c.webkitSlice&&(c.slice=c.webkitSlice),c};var o=Object.getPrototypeOf||function(t){return t.__proto__};t.Blob.prototype=o(new t.Blob)}("undefined"!=typeof self&&self||"undefined"!=typeof window&&window||this.content||this);

