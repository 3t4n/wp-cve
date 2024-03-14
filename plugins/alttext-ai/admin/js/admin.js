(function () {
  'use strict';
  const { __, sprintf } = wp.i18n;
  window.atai = window.atai || { postsPerPage: 1, lastPostId: 0, intervals: {}, redirectUrl: '' };

  function isPostDirty() {
    try {
      // Check for Gutenberg
      if (window.wp && wp.data && wp.blocks) {
        return wp.data.select('core/editor').isEditedPostDirty();
      }
    } catch (error) {
      console.error('Error checking Gutenberg post dirty status: ', error);
      return true;
    }

    // TODO: Check for Classic Editor

    return true;
  }


  function singleGenerateAJAX(attachmentId, keywords = []) {
    if (!attachmentId) {
      return Promise.reject(new Error(__('Attachment ID is missing', 'alttext-ai')));
    }

    return new Promise((resolve, reject) => {
      jQuery.ajax({
        type: 'post',
        dataType: 'json',
        data: {
          action: 'atai_single_generate',
          security: wp_atai.security_single_generate,
          attachment_id: attachmentId,
          keywords: keywords
        },
        url: wp_atai.ajax_url,
        success: function (response) {
          resolve(response);
        },
        error: function (response) {
          reject(new Error('AJAX request failed'));
        }
      });
    });
  }

  function bulkGenerateAJAX() {
    jQuery.ajax({
      type: 'post',
      dataType: 'json',
      data: {
        action: 'atai_bulk_generate',
        security: wp_atai.security_bulk_generate,
        posts_per_page: window.atai.postsPerPage,
        last_post_id: window.atai.lastPostId,
        keywords: window.atai.bulkGenerateKeywords,
        negativeKeywords: window.atai.bulkGenerateNegativeKeywords,
        mode: window.atai.bulkGenerateMode,
        onlyAttached: window.atai.bulkGenerateOnlyAttached,
        onlyNew: window.atai.bulkGenerateOnlyNew,
        batchId: window.atai.bulkGenerateBatchId,
      },
      url: wp_atai.ajax_url,
      success: function (response) {
        window.atai.progressCurrent += response.process_count;
        window.atai.progressSuccessful += response.success_count;
        window.atai.lastPostId = response.last_post_id;

        window.atai.progressBarEl.data('current', window.atai.progressCurrent);
        window.atai.progressCurrentEl.text(window.atai.progressCurrent);
        window.atai.progressSuccessfulEl.text(window.atai.progressSuccessful);

        const percentage = (window.atai.progressCurrent * 100) / window.atai.progressMax;
        window.atai.progressBarEl.css('width', percentage + '%');
        window.atai.progressPercent.text((percentage.toFixed(2)) + '%');

        if (response.recursive) {
          bulkGenerateAJAX();
        } else {
          window.atai.progressButtonCancel.hide();
          window.atai.progressBarWrapper.hide();
          window.atai.progressButtonFinished.show();
          window.atai.progressHeading.text(__('Update complete!', 'alttext-ai'));
          window.atai.redirectUrl = response?.redirect_url;
        }
      },
      error: function (response) {
        console.log(response);
        window.atai.progressButtonCancel.hide();
        window.atai.progressBarWrapper.hide();
        window.atai.progressButtonFinished.show();
        window.atai.progressHeading.text(__('The update was stopped due to a server error. Restart the update to pick up where it left off.', 'alttext-ai'));
      }
    });
  }

  function enrichPostContentAJAX(postId, overwrite = false, processExternal = false, keywords = []) {
    if (!postId) {
      return Promise.reject(new Error(__('Post ID is missing', 'alttext-ai')));
    }

    return new Promise((resolve, reject) => {
      jQuery.ajax({
        type: 'post',
        dataType: 'json',
        data: {
          action: 'atai_enrich_post_content',
          security: wp_atai.security_enrich_post_content,
          post_id: postId,
          overwrite: overwrite,
          process_external: processExternal,
          keywords: keywords
        },
        url: wp_atai.ajax_url,
        success: function (response) {
          resolve(response);
        },
        error: function (response) {
          reject(new Error(__('AJAX request failed', 'alttext-ai')));
        }
      });
    });
  }

  function extractKeywords(content) {
    return content.split(',').map(function (item) {
      return item.trim();
    }).filter(function (item) {
      return item.length > 0;
    }).slice(0, 6);
  }

  jQuery('[data-bulk-generate-start]').on('click', function () {
    const action = getQueryParam('atai_action') || 'normal';
    const batchId = getQueryParam('atai_batch_id') || 0;

    if (action === 'bulk-select-generate' && !batchId) {
      alert(__('Invalid batch ID', 'alttext-ai'));
    }

    window.atai['bulkGenerateKeywords'] = extractKeywords(jQuery('[data-bulk-generate-keywords]').val() ?? '');
    window.atai['bulkGenerateNegativeKeywords'] = extractKeywords(jQuery('[data-bulk-generate-negative-keywords]').val() ?? '');
    window.atai['progressWrapperEl'] = jQuery('[data-bulk-generate-progress-wrapper]');
    window.atai['progressHeading'] = jQuery('[data-bulk-generate-progress-heading]');
    window.atai['progressBarWrapper'] = jQuery('[data-bulk-generate-progress-bar-wrapper]');
    window.atai['progressBarEl'] = jQuery('[data-bulk-generate-progress-bar]');
    window.atai['progressPercent'] = jQuery('[data-bulk-generate-progress-percent]');
    window.atai['progressCurrentEl'] = jQuery('[data-bulk-generate-progress-current]');
    window.atai['progressCurrent'] = window.atai.progressBarEl.data('current');
    window.atai['progressSuccessfulEl'] = jQuery('[data-bulk-generate-progress-successful]');
    window.atai['progressSuccessful'] = window.atai.progressBarEl.data('successful');
    window.atai['progressMax'] = window.atai.progressBarEl.data('max');
    window.atai['progressButtonCancel'] = jQuery('[data-bulk-generate-cancel]');
    window.atai['progressButtonFinished'] = jQuery('[data-bulk-generate-finished]');

    if (action === 'bulk-select-generate') {
      window.atai['bulkGenerateMode'] = 'bulk-select';
      window.atai['bulkGenerateBatchId'] = batchId;
    } else {
      window.atai['bulkGenerateMode'] = jQuery('[data-bulk-generate-mode-all]').is(':checked') ? 'all' : 'missing';
      window.atai['bulkGenerateOnlyAttached'] = jQuery('[data-bulk-generate-only-attached]').is(':checked') ? '1' : '0';
      window.atai['bulkGenerateOnlyNew'] = jQuery('[data-bulk-generate-only-new]').is(':checked') ? '1' : '0';
    }

    jQuery('#bulk-generate-form').hide();
    window.atai.progressWrapperEl.show();

    bulkGenerateAJAX();
  });

  jQuery('[data-bulk-generate-mode-all]').on('change', function () {
    window.location.href = this.dataset.url;
  });

  jQuery('[data-bulk-generate-only-attached]').on('change', function () {
    window.location.href = this.dataset.url;
  });

  jQuery('[data-bulk-generate-only-new]').on('change', function () {
    window.location.href = this.dataset.url;
  });

  jQuery('[data-post-bulk-generate]').on('click', async function (event) {
    if (this.getAttribute('href') !== '#atai-bulk-generate') {
      return;
    }

    event.preventDefault();

    if (isPostDirty()) {
      // Ask for consent
      const consent = confirm(__('[AltText.ai] Make sure to save any changes before proceeding -- any unsaved changes will be lost. Are you sure you want to continue?', 'alttext-ai'));

      // If user doesn't consent, return
      if (!consent) {
        return;
      }
    }

    const postId = document.getElementById('post_ID')?.value;
    const buttonLabel = this.querySelector('span');
    const updateNotice = this.nextElementSibling;
    const buttonLabelText = buttonLabel.innerText;
    const overwrite = document.querySelector('[data-post-bulk-generate-overwrite]')?.checked || false;
    const processExternal = document.querySelector('[data-post-bulk-generate-process-external]')?.checked || false;
    const keywordsCheckbox = document.querySelector('[data-post-bulk-generate-keywords-checkbox]');
    const keywordsTextField = document.querySelector('[data-post-bulk-generate-keywords]');
    const keywords = keywordsCheckbox?.checked ? extractKeywords(keywordsTextField?.value) : [];

    if (!postId) {
      updateNotice.innerText = __('This is not a valid post.', 'alttext-ai');
      updateNotice.classList.add('atai-update-notice--error');
      return;
    }

    this.classList.add('disabled');
    buttonLabel.innerText = __('Processing...', 'alttext-ai');

    // Generate alt text for all images in the post
    const response = await enrichPostContentAJAX(postId, overwrite, processExternal, keywords);

    // Update notice
    if (response.success) {
      window.location.reload();
    } else {
      let errorMessage = __('Unable to generate alt text. Check error logs for details.', 'alttext-ai');

      updateNotice.innerText = errorMessage;
      updateNotice.classList.add('atai-update-notice--error');
    }

    // Reset button
    this.classList.remove('disabled');
    buttonLabel.innerText = buttonLabelText;
  });

  document.addEventListener('DOMContentLoaded', () => {
    // If not using Gutenberg, return
    if (!wp?.blocks) {
      return;
    }

    // Fetch the transient message via AJAX
    jQuery.ajax({
      url: wp_atai.ajax_url,
      type: 'GET',
      data: {
        action: 'atai_check_enrich_post_content_transient',
        security: wp_atai.security_enrich_post_content_transient,
      },
      success: function (response) {
        if (!response?.success) {
          return;
        }

        wp.data.dispatch('core/notices').createNotice(
          'success',
          response.data.message,
          { isDismissible: true }
        );
      }
    });
  });

  /**
   * Empty API key input when clicked "Clear API Key" button
   */
  jQuery('[name="handle_api_key"]').on('click', function () {
    if (this.value === 'Clear API Key') {
      jQuery('[name="atai_api_key"]').val('');
    }
  });

  jQuery('.notice--atai.is-dismissible').on('click', '.notice-dismiss', function () {
    jQuery.ajax(wp_atai.ajax_url, {
      type: 'POST',
      data: {
        action: 'atai_expire_insufficient_credits_notice',
        security: wp_atai.security_insufficient_credits_notice,
      }
    });
  });

  function getQueryParam(name) {
    name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
    let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    let paramSearch = regex.exec(window.location.search);

    return paramSearch === null ? '' : decodeURIComponent(paramSearch[1].replace(/\+/g, ' '));
  }

  function addGenerateButtonToModal(hostWrapperId, generateButtonId, attachmentId) {
    let hostWrapper = document.getElementById(hostWrapperId);

    // Remove existing button, if any
    let oldGenerateButton = document.getElementById(generateButtonId);

    if (oldGenerateButton) {
      oldGenerateButton.remove();
    }

    if (hostWrapper) {
      let generateButton = createGenerateButton(generateButtonId, attachmentId, 'modal');
      hostWrapper.appendChild(generateButton);

      return true;
    }

    return false;
  }

  function createGenerateButton(generateButtonId, attachmentId, context) {
    const generateUrl = new URL(window.location.href);
    generateUrl.searchParams.set('atai_action', 'generate');

    // Button wrapper
    const button = document.createElement('div');
    button.id = generateButtonId;

    // Clickable anchor inside the wrapper for initiating the action
    const anchor = document.createElement('a');
    anchor.id = generateButtonId + '-anchor';
    anchor.href = generateUrl;
    anchor.className = 'button-secondary button-large';

    // Create checkbox wrapper
    const keywordsCheckboxWrapper = document.createElement('div');
    keywordsCheckboxWrapper.id = generateButtonId + '-checkbox-wrapper';

    // Create checkbox
    const keywordsCheckbox = document.createElement('input');
    keywordsCheckbox.type = 'checkbox';
    keywordsCheckbox.id = generateButtonId + '-keywords-checkbox';
    keywordsCheckbox.name = 'atai-generate-button-keywords-checkbox';

    // Create label for checkbox
    const keywordsCheckboxLabel = document.createElement('label');
    keywordsCheckboxLabel.htmlFor = 'atai-generate-button-keywords-checkbox';
    keywordsCheckboxLabel.innerText = 'Add SEO keywords';

    // Create text field wrapper
    const keywordsTextFieldWrapper = document.createElement('div');
    keywordsTextFieldWrapper.id = generateButtonId + '-textfield-wrapper';
    keywordsTextFieldWrapper.style.display = 'none';

    // Create text field
    const keywordsTextField = document.createElement('input');
    keywordsTextField.type = 'text';
    keywordsTextField.id = generateButtonId + '-textfield';
    keywordsTextField.name = 'atai-generate-button-keywords';
    keywordsTextField.size = 40;

    // Append checkbox and label to its wrapper
    keywordsCheckboxWrapper.appendChild(keywordsCheckbox);
    keywordsCheckboxWrapper.appendChild(keywordsCheckboxLabel);

    // Append text field to its wrapper
    keywordsTextFieldWrapper.appendChild(keywordsTextField);

    // Event listener to show/hide text field on checkbox change
    keywordsCheckbox.addEventListener('change', function () {
      if (this.checked) {
        keywordsTextFieldWrapper.style.display = 'block';
        keywordsTextField.setSelectionRange(0, 0);
        keywordsTextField.focus();
      } else {
        keywordsTextFieldWrapper.style.display = 'none';
      }
    });

    // Check if the attachment is eligible for generation
    const isAttachmentEligible = (attachmentId) => {
      let status = 'error';

      jQuery.ajax({
        type: 'post',
        dataType: 'json',
        async: false,
        data: {
          'action': 'atai_check_image_eligibility',
          'security': wp_atai.security_check_attachment_eligibility,
          'attachment_id': attachmentId,
        },
        url: wp_atai.ajax_url,
        success: function (response) {
          status = response.status;
        }
      });

      return status;
    };

    // If attachment is not eligible, we disable the button
    if (!wp_atai.can_user_upload_files || isAttachmentEligible(attachmentId) === 'error') {
      anchor.classList.add('disabled');
      keywordsCheckbox.disabled = true;
    }

    anchor.title = __('AltText.ai: Update alt text for this single image', 'alttext-ai');
    anchor.onclick = function () {
      this.classList.add('disabled');
      let span = this.querySelector('span');

      if (span) {
        span.innerText = __('Processing...', 'alttext-ai');
      }
    };

    // Button icon
    const img = document.createElement('img');
    img.src = wp_atai.icon_button_generate;
    img.alt = __('Update Alt Text with AltText.ai', 'alttext-ai');
    anchor.appendChild(img);

    // Button label/text
    const span = document.createElement('span');
    span.innerText = __('Update Alt Text', 'alttext-ai');
    anchor.appendChild(span);

    // Append anchor to the button
    button.appendChild(anchor);

    // Append checkbox and text field wrappers to the button
    button.appendChild(keywordsCheckboxWrapper);
    button.appendChild(keywordsTextFieldWrapper);

    // Notice element below the button,
    // to display "Updated" message when action is successful
    const updateNotice = document.createElement('span');
    updateNotice.classList.add('atai-update-notice');
    button.appendChild(updateNotice);

    // Event listener to initiate generation
    anchor.addEventListener('click', async function (event) {
      event.preventDefault();

      // If API key is not set, redirect to settings page
      if (!wp_atai.has_api_key) {
        window.location.href = wp_atai.settings_page_url + '&api_key_missing=1';
      }

      const titleEl = (context == 'single') ? document.getElementById('title') : document.querySelector('[data-setting="title"] input');
      const captionEl = (context == 'single') ? document.getElementById('attachment_caption') : document.querySelector('[data-setting="caption"] textarea');
      const descriptionEl = (context == 'single') ? document.getElementById('attachment_content') : document.querySelector('[data-setting="description"] textarea');
      const altTextEl = (context == 'single') ? document.getElementById('attachment_alt') : document.querySelector('[data-setting="alt"] textarea');
      const keywords = keywordsCheckbox.checked ? extractKeywords(keywordsTextField.value) : [];

      // Hide notice
      if (updateNotice) {
        updateNotice.innerText = '';
        updateNotice.classList.remove('atai-update-notice--success', 'atai-update-notice--error');
      }

      // Generate alt text
      const response = await singleGenerateAJAX(attachmentId, keywords);

      // Update alt text in DOM
      if (response.status === 'success') {
        altTextEl.value = response.alt_text;

        if (wp_atai.should_update_title === 'yes') {
          titleEl.value = response.alt_text;

          if (context == 'single') {
            // Add class to label to hide it; initially it behaves as placeholder
            titleEl.previousElementSibling.classList.add('screen-reader-text');
          }
        }

        if (wp_atai.should_update_caption === 'yes') {
          captionEl.value = response.alt_text;
        }

        if (wp_atai.should_update_description === 'yes') {
          descriptionEl.value = response.alt_text;
        }

        updateNotice.innerText = __('Updated', 'alttext-ai');
        updateNotice.classList.add('atai-update-notice--success');

        setTimeout(() => {
          updateNotice.classList.remove('atai-update-notice--success');
        }, 3000);
      } else {
        let errorMessage = __('Unable to generate alt text. Check error logs for details.', 'alttext-ai');

        if (response?.message) {
          errorMessage = response.message;
        }

        updateNotice.innerText = errorMessage;
        updateNotice.classList.add('atai-update-notice--error');
      }

      // Reset button
      anchor.classList.remove('disabled');
      anchor.querySelector('span').innerText = __('Update Alt Text', 'alttext-ai');
    });

    return button;
  }

  /**
   * Manage Generation for Single Image
   */
  document.addEventListener('DOMContentLoaded', async () => {
    const isAttachmentPage = window.location.href.includes('post.php') && jQuery('body').hasClass('post-type-attachment');
    const isEditPost = window.location.href.includes('post-new.php') || (window.location.href.includes('post.php') && !jQuery('body').hasClass('post-type-attachment'));
    const isAttachmentModal = window.location.href.includes('upload.php');
    let attachmentId = null;
    let generateButtonId = 'atai-generate-button';
    let hostWrapperId = 'alt-text-description';

    if (isAttachmentPage) {
      attachmentId = getQueryParam('post');

      // Bail early if no post ID.
      if (!attachmentId) {
        return false;
      }

      attachmentId = parseInt(attachmentId, 10);

      // Bail early if post ID is not a number.
      if (!attachmentId) {
        return;
      }

      let hostWrapper = document.getElementById(hostWrapperId);

      if (hostWrapper) {
        let generateButton = createGenerateButton(generateButtonId, attachmentId, 'single');
        hostWrapper.appendChild(generateButton);
      }
    } else if (isAttachmentModal || isEditPost) {
      attachmentId = getQueryParam('item');

      // Listen to modal open
      jQuery(document).on('click', 'ul.attachments li.attachment', function () {
        let element = jQuery(this);

        // Bail early if no data-id attribute.
        if (!element.attr('data-id')) {
          return;
        }

        attachmentId = parseInt(element.attr('data-id'), 10);

        // Bail early if post ID is not a number.
        if (!attachmentId) {
          return;
        }

        addGenerateButtonToModal(hostWrapperId, generateButtonId, attachmentId);
      });

      // Listen to modal navigation
      document.addEventListener('click', function (event) {
        // Bail early if not clicking on the modal navigation.
        if (!event.target.matches('.media-modal .right, .media-modal .left')) {
          return;
        }

        // Get attachment ID from URL.
        const urlParams = new URLSearchParams(window.location.search);
        attachmentId = urlParams.get('item');

        // Bail early if post ID is not a number.
        if (!attachmentId) {
          return;
        }

        addGenerateButtonToModal(hostWrapperId, generateButtonId, attachmentId);
      });

      // Bail early if no post ID.
      if (!attachmentId) {
        return false;
      }

      // Check if this is a modal based on the attachment ID
      if (attachmentId) {
        // Wait until modal is in the DOM.
        let intervalCount = 0;
        window.atai.intervals['singleModal'] = setInterval(() => {
          intervalCount++;

          if (intervalCount > 20) {
            clearInterval(interval);
            return;
          }

          attachmentId = parseInt(attachmentId, 10);

          // Bail early if post ID is not a number.
          if (!attachmentId) {
            return;
          }

          let buttonAdded = addGenerateButtonToModal(hostWrapperId, generateButtonId, attachmentId);

          if (buttonAdded) {
            clearInterval(window.atai.intervals['singleModal']);
          }
        }, 500);
      }
    } else {
      return false;
    }
  });
})();
