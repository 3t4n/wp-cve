jQuery(document).ready(function($) {

  'use strict';

  /**
   * Here the wp.data API is used to detect when a post is modified and the Interlinks Optimization meta-box needs to be
   * updated.
   *
   * Note that the update of the Interlinks Optimization meta-box is performed only if:
   *
   * - The Gutenberg editor is available. (wp.blocks is checked against undefined)
   * - The Interlinks Optimization meta-box is present in the DOM (because in specific post types or when the user
   *   doesn't have the proper capability too see it it's not available)
   *
   * References:
   *
   * - https://github.com/WordPress/gutenberg/issues/4674#issuecomment-404587928
   * - https://wordpress.org/gutenberg/handbook/packages/packages-data/
   * - https://www.npmjs.com/package/@wordpress/data
   */
  if (typeof wp.blocks !== 'undefined' && //Verify if the Gutenberg editor is available
      $('#daextinma-meta-optimization').length > 0) { //Verify if the Interlinks Optimization meta-box is present in the DOM

    /**
     * Since plugins like "Classic Editor" and "Page Builder by SiteOrigin" and
     * probably others makes the object retuned by wp.data.select('core/editor')
     * empty causing JavaScript errors when methods like getCurrentPost() are
     * used, do not proceed if the object retuned by
     * wp.data.select('core/editor') is empty.
     *
     * @type {boolean}
     */
    let objectIsEmpty = true;
    let obj = wp.data.select('core/editor');
    for (let key in obj) {
      if (obj.hasOwnProperty(key))
        objectIsEmpty = false;
    }
    if (objectIsEmpty) {
      return;
    }

    let lastModified = '';

    const unsubscribe = wp.data.subscribe(function() {

      'use strict';

      const postId = wp.data.select('core/editor').getCurrentPost().id;
      let postModifiedIsChanged = false;

      if (typeof wp.data.select('core/editor').getCurrentPost().modified !==
          'undefined' &&
          wp.data.select('core/editor').getCurrentPost().modified !==
          lastModified) {
        lastModified = wp.data.select('core/editor').getCurrentPost().modified;
        postModifiedIsChanged = true;
      }

      /**
       * Update the Interlinks Optimization meta-box if:
       *
       * - The post has been saved
       * - This is not an not an autosave
       * - The "lastModified" flag used to detect if the post "modified" date has changed is set to true
       */
      if (wp.data.select('core/editor').isSavingPost() &&
          !wp.data.select('core/editor').isAutosavingPost() &&
          postModifiedIsChanged === true
      ) {
        updateInterlinksOptimizationMetaBox(postId);
      }

    });

  }

  /**
   * Updates the Interlinks Optimization meta-box content.
   *
   * @param post_id The id of the current post
   */
  function updateInterlinksOptimizationMetaBox(post_id) {

    'use strict';

    //prepare ajax request
    const data = {
      'action': 'generate_interlinks_optimization',
      'security': window.DAEXTINMA_PARAMETERS.nonce,
      'post_id': post_id,
    };

    //send ajax request
    $.post(window.DAEXTINMA_PARAMETERS.ajax_url, data, function(html_content) {

      'use strict';

      //update the content of the meta-box
      $('#daextinma-meta-optimization .inside').empty().append(html_content);

    });

  }

});