jQuery(document).ready(() => {
  const sendReviews = () => {
    return new Promise((resolve, reject) => {
      const data = jQuery('#review-form').serialize();

      jQuery.ajax({
        type: 'POST',
        url: ___recapture.ajax,
        data: data,
        success: function () {
          resolve();
        },
        error: function (req, err) {
          reject(err);
        }
      });
    });
  };

  const validateReviewForm = function () {
    let valid = true;

    const reviews = [];

    jQuery('#review-form .validation-failed').removeClass('validation-failed');
    jQuery('#review-form fieldset').each(function () {
      const el = jQuery(this);
      const skip = el.find('.skip').first();

      if (skip.val() === '1') {
        return;
      }

      const summary = el.find('.summary-field');
      const content = el.find('.review-field');
      const productId = el.find('.product_id').val();

      if (summary.val() === '') {
        valid = false;
        summary.addClass('validation-failed');
      }

      if (content.val() === '') {
        valid = false;
        content.addClass('validation-failed');
      }

      const rating = el.find('.rating_value');
      if (rating.val() === '' || rating.val() === 0) {
        valid = false;
        rating.parents('.rating').addClass('validation-failed');
      }

      reviews.push({
        productId,
        rating: rating.val(),
        title: summary.val(),
        body: content.val(),
      });
    });

    if (!valid) {
      jQuery('html, body').animate({ scrollTop: jQuery('.validation-failed')
        .first()
        .offset().top - 30 }, 100);

      return false;
    }

    jQuery('button').attr('disabled', 'disabled');

    sendReviews()
      .then(() => {
        jQuery('.success-inner').removeClass('recapture-hide');
        jQuery('.review-inner').addClass('recapture-hide');
        jQuery('button').removeAttr('disabled');
      })
      .catch(() => {
        jQuery('html, body').animate({ scrollTop: jQuery('#error-message')
          .first()
          .offset().top - 30 }, 100);

        jQuery('#error-message').removeClass('recapture-hide');
        jQuery('#error-message').text(
          'There was an error saving your review(s), please try again.'
        );
        jQuery('button').removeAttr('disabled');
      });

    return false;
  };

  jQuery('#review-form fieldset a.opt-out').click(function () {
    const el = jQuery(this);
    const fieldset = el.parents('fieldset.review-item');
    const reviewForm = fieldset.find('.product-review-form');
    const indicator = fieldset.find('input.skip[type="hidden"]');

    if (!reviewForm.hasClass('invis')) {
      indicator.attr('value', 1);
      el.html(el.data('show'));
      reviewForm
        .hide()
        .addClass('invis')
        .find('input')
        .attr('disabled', true);
    } else {
      indicator.attr('value', 0);
      el.html(el.data('hide'));
      reviewForm
        .show()
        .removeClass('invis')
        .find('input')
        .attr('disabled', false);
    }
  });

  jQuery('.product-review .ratings .stars')
    .on('mouseover', '.star', function () {
      const el = jQuery(this);
      el.siblings().removeClass('hover');
      el.prevAll().addClass('hover');
      el.nextAll().each(function (e) {
        const subEl = jQuery(e);
        if (subEl.hasClass('active')) subEl.addClass('active-hold');
      });

      el.addClass('hover').removeClass('active-hold');
    })
    .on('mouseout', '.star', function () {
      const el = jQuery(this);
      el
        .siblings()
        .removeClass('hover')
        .removeClass('active-hold');

      el.removeClass('hover');
    })
    .on('click', '.star', function () {
      const el = jQuery(this);
      el
        .siblings()
        .removeClass('hover')
        .removeClass('active')
        .removeClass('active-hold');
      el
        .removeClass('hover')
        .removeClass('active-hold')
        .addClass('active');
      el.prevAll().addClass('active');

      const ratingHook = el.attr('data-id');
      jQuery(`#${ratingHook}`).attr('value', el.attr('data-val'));
    });

  jQuery('#review-form').submit(validateReviewForm);

  // Create our styles, we can't rely on .hide because some themes don't support it by default
  const styles = document.createElement('style');
  styles.type = 'text/css';
  styles.innerHTML = `
    .recapture-hide { display: none }
  `;
  document.getElementsByTagName('head')[0].appendChild(styles);
});
