(function($) {

  'use strict';

  $(document).ready(function() {

    'use strict';

    //initialize chosen on all the select elements
    let chosenElements = [];

    //Dashboard Menu ---------------------------------------------------------------------------------------------------
    addToChosen('op');
    addToChosen('sb');
    addToChosen('or');

    //Options Menu -----------------------------------------------------------------------------------------------------

    //Juice
    addToChosen('daextinma_remove_link_to_anchor');
    addToChosen('daextinma_remove_url_parameters');

    //Analysis
    addToChosen('daextinma-dashboard-post-types');
    addToChosen('daextinma-juice-post-types');
    addToChosen('daextinma_set_max_execution_time');
    addToChosen('daextinma_set_memory_limit');

    //Meta Boxes
    addToChosen('daextinma-interlinks-options-post-types');
    addToChosen('daextinma-interlinks-optimization-post-types');

    $(chosenElements.join(',')).chosen({
      placeholder_text_multiple: window.objectL10n.chooseAnOptionText,
    });

    function addToChosen(elementId) {

      'use strict';

      if ($('#' + elementId).length &&
          chosenElements.indexOf($('#' + elementId)) === -1) {
        chosenElements.push('#' + elementId);
      }

    }

  });

})(window.jQuery);