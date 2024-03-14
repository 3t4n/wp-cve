/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./assets/js/admin/src/indexing.js ***!
  \*****************************************/
var YITH_WCAS_Indexing = function YITH_WCAS_Indexing() {
  var indexButton = document.querySelector('.ywcas-rebuild-index');
  var infoWrapper = indexButton.closest('.yith-plugin-fw__panel__option__content');
  var successMessage = document.querySelector('.yith-wcas-success-message');
  var waitingMessage = document.querySelector('.yith-wcas-waiting-message');
  var checkForUpdate = false;
  var handleRebuild = function handleRebuild(e) {
    e.preventDefault();
    successMessage.classList.add('hide');
    waitingMessage.classList.remove('hide');
    var indexRequest = jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: ywcas_admin_params.indexNonce,
        action: 'yith_wcas_start_index',
        form: jQuery('#plugin-fw-wc').serialize()
      },
      type: 'POST',
      dataType: 'json',
      success: function success(response) {
        updateInfo(response);
        checkForUpdate = setInterval(checkUpdate, 2000);
      }
    });
  };
  var checkUpdate = function checkUpdate() {
    var indexUpdate = jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: ywcas_admin_params.indexNonce,
        action: 'yith_wcas_update_index'
      },
      type: 'POST',
      dataType: 'json',
      success: function success(response) {
        updateInfo(response);
      }
    });
    //clearInterval(myTimer);
  };
  var updateInfo = function updateInfo(response) {
    if (response.data.content) {
      infoWrapper.innerHTML = response.data.content;
      document.querySelector('.yith-wcas-waiting-message').classList.remove('hide');
      percentage = document.querySelector('#process_percentage').value;
      if (parseInt(percentage) === 100) {
        clearInterval(checkForUpdate);
        document.querySelector('.yith-wcas-success-message').classList.remove('hide');
        document.querySelector('.yith-wcas-waiting-message').classList.add('hide');
      }
    }
  };
  var init = function init() {
    jQuery(document).on('click', '.ywcas-rebuild-index', handleRebuild);
  };
  init();
};
YITH_WCAS_Indexing();
var __webpack_export_target__ = window;
for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ })()
;
//# sourceMappingURL=indexing.js.map