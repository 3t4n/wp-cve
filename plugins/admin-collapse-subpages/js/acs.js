jQuery(document).ready(function($) {

    var sep = " | ";
    var expandAllLink   = '<a class="acs-expand-all" href="javascript:void(0);">' + acs_l10n_vars.lexpandall + '</a>';
    var collapseAllLink = '<a class="acs-collapse-all" href="javascript:void(0);">' + acs_l10n_vars.lcollapseall + '</a>';
    var links = '<li class="expand_all_link">'+sep+expandAllLink+'</li><li class="collapse_all_link">'+sep+collapseAllLink+'</li>';
    var catLinks = expandAllLink+sep+collapseAllLink;

    /*
     * Add Expand/Collapse ALL Links to DOM (has to be first for listeners)
     */
    jQuery('.acs-hier .subsubsub').append(links);
    jQuery('.acs-hier.taxonomy-category .actions').append(catLinks);
    /*
     * Initial loading
     */
     var acs;
     acs_get();
     initial_collapse_work();
     reset_listeners();

     function acs_get() {
       acs = JSON.parse(localStorage.getItem('acs'));
       if(acs) {
         acs = JSON.parse(localStorage.getItem('acs'));
         acs.pid = acs.pid || [];
         acs.cid = acs.cid || [];
       } else {
         acs = {};
         acs.pid = [];
         acs.cid = [];
       }
     }
    /*
     * Does all initial stuff (adding plus/minus buttons, adding top links, perform initial collapse)
     */
    function initial_collapse_work() {

        /*
         * Loop through to add parent and post-id data
         */

        jQuery('.pages #the-list tr').each(function() {

            var parent = jQuery(this).find('.post_parent').html();
            var id = jQuery(this).find('[name="post[]"]').attr('value');

            jQuery(this).attr('data-parent', parent);
            jQuery(this).attr('data-acs-id', id);
            jQuery(this).attr('data-collapsed', 0);

        });

        jQuery('.acs-hier .tags #the-list tr').each(function() {

            var parent = jQuery(this).find('.parent').html();
            var id = jQuery(this).find('.check-column input').attr('value');

            jQuery(this).attr('data-parent', parent);
            jQuery(this).attr('data-acs-id', id);
            jQuery(this).attr('data-collapsed', 0);

        });

        /*
         * Loop through again to add +/- as needed
         */
        jQuery('.pages #the-list tr').each(function() {

            var id = jQuery(this).find('[name="post[]"]').attr('value');

            if (jQuery('#the-list').find('[data-parent="' + id + '"]').length > 0)
                jQuery(this).find('.page-title strong').append('<span class="expand_link"><a href="javascript:void(0);" class="minus"></a></span>');
        });

        jQuery('.acs-hier .tags #the-list tr').each(function() {

            var id = jQuery(this).find('.check-column input').attr('value');
            if (jQuery('#the-list').find('[data-parent="' + id + '"]').length > 0) {
              jQuery(this).find('.name strong').append('<span class="expand_link"><a href="javascript:void(0);" class="minus"></a></span>');
            }

        });

        /*
         * Collapse from cookie to start with
         */
        collapse_from_cookie(jQuery('.acs-hier .tags').length);


    }

    function reset_listeners() {

        /*
         * Called on click, expands and contracts pages by calling functions below
         */
        jQuery('.expand_link').click(function() {
            acs_get();
            var row = jQuery(this).closest('tr');
            var acs_id = row.attr('data-acs-id');
            jQuery(this).children('a').toggleClass('minus');

            if (row.attr('data-collapsed') == 0) {
                if(jQuery('.acs-hier .tags').length) {
                  if(acs.cid) {
                    acs.cid.push(acs_id);
                  }
                } else{
                  if(acs.pid) {
                    acs.pid.push(acs_id);
                  }
                }
                localStorage.setItem("acs",JSON.stringify(acs));
                collapse_subpages(acs_id);
                row.attr('data-collapsed', 1);
            } else {
                if(jQuery('.acs-hier .tags').length) {
                  acs.cid = acs.cid.filter(function(e,i){
                    return e != acs_id;
                  });
                } else {
                  acs.pid = acs.pid.filter(function(e,i){
                    return e != acs_id;
                  });
                }
                localStorage.setItem("acs",JSON.stringify(acs));
                expand_subpages(acs_id);
                row.attr('data-collapsed', 0);
            }
        });

        /*
         * Called on click when "Quick Update" is used
         */
        jQuery('.inline-edit-save .save').click(function() {

            /*
             * delay before reset, allows WordPress to finish reseting rows
             * (not ideal, but the "Quick Edit" is a little wonky to begin with)
             */
            setTimeout(function() {

                jQuery('#the-list tr').show();
                jQuery('.expand_link').remove();

                //redo collapses
                initial_collapse_work();
                reset_listeners();

            }, 1000);

        });

        /*
         * Expand and collapse all links
         */
        jQuery('.expand_all_link a').click(function() {
            expand_all();
        });
        jQuery('.acs-expand-all').click(function() {
            expand_all();
        });
        jQuery('.collapse_all_link a').click(function() {
            collapse_all();
        });
        jQuery('.acs-collapse-all').click(function() {
            collapse_all();
        });
    }

    function collapse_all() {
        var ids = [];
        acs_get();
        jQuery('.pages #the-list tr').each(function() {
            var acs_id = jQuery(this).attr('data-acs-id');
            if (jQuery(this).attr('data-collapsed') == 0) {
                ids.push(acs_id);
                acs.pid.push(acs_id);
                collapse_subpages(acs_id);
                jQuery(this).attr('data-collapsed', 1).find('.expand_link a').toggleClass('minus');
            }
        });
        jQuery('.acs-hier .tags #the-list tr').each(function() {
            var acs_id = jQuery(this).attr('data-acs-id');
            if (jQuery(this).attr('data-collapsed') == 0) {
                ids.push(acs_id);
                acs.cid.push(acs_id);
                collapse_subpages(acs_id);
                jQuery(this).attr('data-collapsed', 1).find('.expand_link a').toggleClass('minus');
            }
        });

        localStorage.setItem('acs',JSON.stringify(acs));
    }

    function expand_all() {
        var ids = [];
        acs_get();
        jQuery('.pages #the-list tr').each(function() {
            var acs_id = jQuery(this).attr('data-acs-id');
            if (jQuery(this).attr('data-collapsed') == 1) {
                ids.push(acs_id);
                expand_subpages(acs_id);
                jQuery(this).attr('data-collapsed', 0).find('.expand_link a').toggleClass('minus');
            }
        });

        jQuery('.acs-hier .tags #the-list tr').each(function() {
            var acs_id = jQuery(this).attr('data-acs-id');
            if (jQuery(this).attr('data-collapsed') == 1) {
                ids.push(acs_id);
                expand_subpages(acs_id);
                jQuery(this).attr('data-collapsed', 0).find('.expand_link a').toggleClass('minus');
            }
        });
        ids.forEach(function(val){
          acs.cid = acs.cid.filter(function(e,i){
            return e != val;
          })
        });
        ids.forEach(function(val){
          acs.pid = acs.pid.filter(function(e,i){
            return e != val;
          })
        });
        localStorage.setItem('acs',JSON.stringify(acs));
    }

    /*
     * Two recursive functions that show/hide the table rows
     */
    function collapse_subpages(parent_id) {
        jQuery('#the-list').find('[data-parent="' + parent_id + '"]').each(function() {

            jQuery(this).hide();

            collapse_subpages(jQuery(this).attr('data-acs-id'));
        });
    }

    function expand_subpages(parent_id) {
        jQuery('#the-list').find('[data-parent="' + parent_id + '"]').each(function() {

            jQuery(this).show();

            //does not unhide rows if group was previously hidden
            if (jQuery(this).attr('data-collapsed') == 0)
                expand_subpages(jQuery(this).attr('data-acs-id'));
        });
    }

    function updateStorage(acs){

    }
    /*
     * Read localStorage and expand pages as needed
     */
    function collapse_from_cookie(cat) {
        var acsLocal = JSON.parse(localStorage.getItem("acs"));
        if(acsLocal) {
          var ids;
          if(cat){
            ids = acsLocal.cid;
          } else {
            ids = acsLocal.pid;
          }
          jQuery.each(ids, function(i,v){
            jQuery('#the-list').find('[data-acs-id="' + v + '"]').attr('data-collapsed', 1).find('.expand_link a').toggleClass('minus');
          });
          jQuery.each(ids, function(index, value) {
              collapse_subpages(value);
          });
        }
    }

});
