const YITH_WCAS_Indexing = () => {

  const indexButton = document.querySelector('.ywcas-rebuild-index');
  const infoWrapper = indexButton.closest('.yith-plugin-fw__panel__option__content');
  const successMessage = document.querySelector('.yith-wcas-success-message');
  const waitingMessage = document.querySelector('.yith-wcas-waiting-message');
  let checkForUpdate = false;

  const handleRebuild = (e) => {
    e.preventDefault();
    successMessage.classList.add('hide');
    waitingMessage.classList.remove('hide');
    const indexRequest = jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: ywcas_admin_params.indexNonce,
        action: 'yith_wcas_start_index',
        form: jQuery('#plugin-fw-wc').serialize(),
      },
      type: 'POST',
      dataType: 'json',
      success: function success(response) {
        updateInfo(response);
        checkForUpdate = setInterval(checkUpdate, 2000);
      },
    });
  };

  const checkUpdate = () => {

    const indexUpdate = jQuery.ajax({
      url: ywcas_admin_params.ajaxurl,
      data: {
        security: ywcas_admin_params.indexNonce,
        action: 'yith_wcas_update_index',
      },
      type: 'POST',
      dataType: 'json',
      success: function success(response) {
        updateInfo(response);
      },
    });
    //clearInterval(myTimer);
  };

  const updateInfo = (response) => {
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

  const init = () => {
    jQuery(document).on('click', '.ywcas-rebuild-index', handleRebuild);
  };
  init();
};

YITH_WCAS_Indexing();