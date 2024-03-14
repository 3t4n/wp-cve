/**
 * This file is used to generate the cookie notice and handle the feedback form.
 */
window.daexthefuCookieNotice = (function(utility, $) {

  'use strict';

  //This object is used to save all the settings -----------------------------------------------------------------------
  let settings = {};

  //This object is used to save all the variable states of the cookie notice ---------------------------------------------
  let states = {
    value: null,
  };

  /**
   * Bind the event listeners.
   */
  function bindEventListeners() {

    'use strict';

    //Handles the clicks on the positive and negative feedback buttons
    $(document).on('click', '.daexthefu-yes, .daexthefu-no', function() {

      'use strict';

      //Set the new value of the state
      states.value = parseInt($(this).attr('data-value'), 10);

      //Update the buttons
      updateButtonsSelectedClasses();

      //Update the textarea label
      if (states.value === 1) {
        $('#daexthefu-comment-label').
            text(window.DAEXTHEFU_PHPDATA.textareaLabelPositiveFeedback);
      } else {
        $('#daexthefu-comment-label').
            text(window.DAEXTHEFU_PHPDATA.textareaLabelNegativeFeedback);
      }

      const comment = $('.daexthefu-comment');
      switch (window.DAEXTHEFU_PHPDATA.commentForm) {

          //Always
        case 0:

          //show comments area
          comment.fadeIn(200);

          break;

          //After Positive Feedback
        case 1:

          if (states.value === 1) {

            //show comments area
            comment.fadeIn(200);

          } else {

            //Save the feedback of the user with an AJAX request.
            save_feedback();

          }

          break;

          //After Negative Feedback
        case 2:

          if (states.value === 0) {

            //show comments area
            comment.fadeIn(200);

          } else {

            //Save the feedback of the user with an AJAX request.
            save_feedback();

          }

          break;

          //Never
        case 3:

          //Save the feedback of the user with an AJAX request.
          save_feedback();

          break;

      }

    });

    //Handle the clicks on the comment submit button
    $(document).on('click', '.daexthefu-comment-submit', function() {

      'use strict';

      //Save the feedback of the user with an AJAX request.
      save_feedback();

    });

    //Handle the clicks on the comment cancel button
    $(document).on('click', '.daexthefu-comment-cancel', function() {

      'use strict';

      //hide the comments area
      $('.daexthefu-comment').fadeOut(200);

      //reset the state used to store the selected value
      states.value = null;

      //update the classes of the icons
      updateButtonsSelectedClasses();

    });

    //Implement the character counter
    update_character_counter();
    $('#daexthefu-comment-textarea').on('input', function() {
      update_character_counter();
    });

  }

  /**
   * Update the character counter based on the character counted on the
   * textarea.
   */
  function update_character_counter() {

    'use strict';

    const charCount = $('#daexthefu-comment-textarea').val().length;
    $('#daexthefu-comment-character-counter-number').
        html(charCount + '/' + window.DAEXTHEFU_PHPDATA.textareaCharacters);

  }

  /**
   * Save the feedback of the user with an AJAX request.
   */
  function save_feedback() {

    'use strict';

    //Get the feedback data from the DOM
    const postId = parseInt($('#daexthefu-container').attr('data-post-id'), 10);
    const comment = $('#daexthefu-comment-textarea').val();

    //Prepare the ajax request data
    let data = {
      'action': 'daexthefu_save_feedback',
      'security': window.DAEXTHEFU_PHPDATA.nonce,
      'value': states.value,
      'post_id': postId,
      'comment': comment,
    };

    //Send ajax request
    $.post(window.DAEXTHEFU_PHPDATA.ajaxUrl, data, function() {

      'use strict';

      //Hide the feedback section from the form
      $('.daexthefu-feedback').hide();

      //Hide the feedback comment area from the form
      $('.daexthefu-comment').hide();

      //Show a successful submission message
      $('.daexthefu-successful-submission-text').show();

      //Save the cookie used to store for which posts the user has already submitted a feedback
      saveCookie(postId);

    });

  }

  /**
   * Save in a cookie named "daexthefu-data" the ID of the post for which has
   * been already submitted a feedback. Note that this cookie includes a
   * serialized array of post IDs.
   *
   * @param postId
   */
  function saveCookie(postId) {

    'use strict';

    /**
     * If the cookie doesn't exist create it as a serialized array of post IDs.
     * If the cookie exists add the current post ID to the serialized array of
     * post IDs.
     */
    if (utility.getCookie('daexthefu-data') === false) {

      //Create the new cookie

      //Save the new submission status in the serialized "daexthefu-data" cookie
      let dataCookie = [];
      dataCookie.push(postId);

      //Convert the array with the data to JSON and save these data in the 'daexthefu-data' cookie.
      dataCookie = JSON.stringify(dataCookie);

      //Set the cookie
      if (parseInt(window.DAEXTHEFU_PHPDATA.uniqueSubmission, 10) === 1 ||
          parseInt(window.DAEXTHEFU_PHPDATA.uniqueSubmission, 10) === 3) {
        utility.setCookie('daexthefu-data', dataCookie,
            window.DAEXTHEFU_PHPDATA.cookieExpiration);
      }

    } else {

      //Update the existing cookie

      //Get the current data as an array
      const currentCookieValue = utility.getCookie('daexthefu-data');
      let decodedCookieValue = JSON.parse(currentCookieValue);

      //Add the current post ID to the array
      decodedCookieValue.push(postId);

      //Serialize the array
      const completeCookie = JSON.stringify(decodedCookieValue);

      //Set the cookie
      if (parseInt(window.DAEXTHEFU_PHPDATA.uniqueSubmission, 10) === 1 ||
          parseInt(window.DAEXTHEFU_PHPDATA.uniqueSubmission, 10) === 3) {
        utility.setCookie('daexthefu-data', completeCookie,
            window.DAEXTHEFU_PHPDATA.cookieExpiration);
      }

    }

  }

  /**
   * Update classes used to identify which is the positive and negative button
   * based on the status of states.value
   *
   * The plugin uses these classed to apply custom colors to the positive and
   * negative feedback buttons.
   */
  function updateButtonsSelectedClasses() {

    'use strict';

    if (states.value === null) {

      //No button is selected
      $('.daexthefu-yes').removeClass('daexthefu-yes-selected');
      $('.daexthefu-no').removeClass('daexthefu-no-selected');

    } else if (parseInt(states.value, 10) === 1) {

      //The positive feedback button is selected
      $('.daexthefu-yes').addClass('daexthefu-yes-selected');
      $('.daexthefu-no').removeClass('daexthefu-no-selected');

    } else {

      //The negative feedback button is selected
      $('.daexthefu-yes').removeClass('daexthefu-yes-selected');
      $('.daexthefu-no').addClass('daexthefu-no-selected');

    }

  }

  /**
   * Add the cookie notice to the DOM and add the event listeners.
   */
  function bootstrap() {

    'use strict';

    //Bind the event listeners
    $(document).ready(function() {

      if($('#daexthefu-container').length){
        bindEventListeners();
      }

    });

  }

  //Return an object exposed to the public -----------------------------------------------------------------------------
  return {

    initialize: function(configuration) {

      'use strict';

      //Merge the custom configuration provided by the user with the default configuration
      settings = configuration;

      //Start the process
      bootstrap();

    },

  };

}(daexthefuUtility, jQuery));

//Init
window.daexthefuCookieNotice.initialize({});