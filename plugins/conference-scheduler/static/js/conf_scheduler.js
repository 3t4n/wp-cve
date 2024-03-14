/*
Conference Scheduler
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Setup master JS var
var conf_scheduler = conf_scheduler || {};
conf_scheduler.search = '';
conf_scheduler.myPicks = [];
conf_scheduler.hooks = wp.hooks.createHooks();

jQuery(document).ready(function($){

  conf_scheduler.formatFilterOptions = function ( state ) {
    if (!state.id) {
      return state.text;
    }
    var tagIcon = '<i class="fas fa-search"></i>';
    if (state.id.substring(0,8) == 'keyword_') tagIcon = '<i class="fas fa-tag"></i>';
    if (state.id.substring(0,6) == 'theme_') tagIcon = '<i class="fas fa-folder-open"></i>';

    var $state = $(' <span>'+tagIcon+' <span></span></span>');

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $state.find("span").text(state.text);

    return $state;
  };
  conf_scheduler.dayTabs = $('.conf_scheduler.day_tabs').length;
  var s2Options = {
    placeholder: conf_scheduler_ldata.i18n.search,
    allowClear: true,
    templateResult: conf_scheduler.hooks.applyFilters('conf_scheduler_filter_options_formatter', conf_scheduler.formatFilterOptions),
    templateSelection: conf_scheduler.hooks.applyFilters('conf_scheduler_filter_options_formatter', conf_scheduler.formatFilterOptions),
    tags: true,
    tokenSeparators: [','],
    createTag: function (params) {
      var term = $.trim(params.term);

      if (term === '') {
        return null;
      }

      return {
        id: term,
        text: term,
        isSearch: true // add additional parameters
      }
    }
  };
  $('.conf_scheduler .multi_filter').select2(s2Options);

  $('.conf_block').attr('data-order','5');

  if (!conf_scheduler_ldata.disableMyPicks) {
    // load saved picks
    if (Cookies.get('conf_scheduler_picks')) conf_scheduler.myPicks = JSON.parse(Cookies.get('conf_scheduler_picks'));
    if (conf_scheduler.myPicks.length > 0 ) {
      conf_scheduler.myPicks.forEach(function(workshopID) {
        $('.workshop[data-workshopID='+workshopID+']').addClass('picked').closest('.conf_block').attr('data-order', '1');
      });
    }
  }

  conf_scheduler.layouts = [];
  $('.conf_scheduler').each(function(i) {
    var $instance = $(this).data('instance',i);
    conf_scheduler.layouts.push(
      $instance.find('.workshops').each(function() {
        var _sizer = $(this).find('.conf_block');
        $(this).isotope({
          itemSelector: '.conf_block',
          percentPosition: true,
          masonry: { columnWidth: _sizer[0] },
          getSortData: { order: '[data-order]' },
        });
      })
    );
  });

  if (conf_scheduler.myPicks.length > 0 )
    $.each(conf_scheduler.layouts, function(){
      this.isotope({ sortBy: 'order'});
    });


  // Filter function
  conf_scheduler.filterFunc = function(iIndex) {
    var multiFilters = conf_scheduler.multiFilterVal;
    var searches = multiFilters.filter(opt => opt.isSearch);
    var filters = multiFilters.filter(opt => !opt.isSearch);

    var $workshop = $(this);
    var grouped = false;
    if ($workshop.hasClass('workshop_group')) {
      grouped = true;
      $workshop = $workshop.find('.workshop');
    }

    var found = false;
    $workshop.each(function() {
      var $this = $(this);
      inFilters = filters.every(function(v) {
        return this.hasClass(v.id)
      }, $this);

      if(!inFilters && !grouped) return false; // break early if it doesn't mach filters

      if (searches.length) {
        conf_scheduler.inSearch = 0;
        $.each(searches, function(searchIndex,term) {
          var searchTerm = term.text.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ''); // normalize to deal with unicode characters (accents, etc), regex to remove the accents
          // console.log(searchTerm + ' - ' + searchTerm.length + ' vs. ' + term.text.toLowerCase() + ' - ' + term.text.toLowerCase().length);
          $.each(conf_scheduler_ldata.searchSelectors, function(selectorIndex,selector) {
            var haystack = $this.find(selector).text().toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, '');
            // console.log(searchTerm + ' - ' + searchTerm.length + ' vs. ' + haystack + ' - ' + haystack.length);
            conf_scheduler.inSearch += (haystack.indexOf(searchTerm) != -1);
          });
        });
      } else { conf_scheduler.inSearch = 1; }

      if( !conf_scheduler.inSearch && !grouped) return false; // break early if it doesn't mach search
      inPluggable = conf_scheduler.hooks.applyFilters( 'conf_scheduler_schedule_filters', true, $this );

      found = found || (inFilters && (conf_scheduler.inSearch > 0) && inPluggable);
      // console.log('run filterfuc - ID:'+$this.data('workshopid')+' '+inFilters+' '+conf_scheduler.inSearch+' '+inPluggable);
    });
    return found;
  };

  // Live search function
  conf_scheduler.liveSearcher = function() {
    if (conf_scheduler.searchTimeout) clearTimeout(conf_scheduler.searchTimeout);
    conf_scheduler.searchTimeout = setTimeout(function(){
      if ( !conf_scheduler.dayTabs ) $('.conference_day').addClass('open');
      $('.session').addClass('open');
      $('.my_picks').removeClass('show');
      $('.conf_scheduler').removeClass('picks');
      if ($('.workshop_search').val().toLowerCase() != '') {
        $('.conf_scheduler').addClass('searching');
      } else {
        $('.conf_scheduler').removeClass('searching');
      }
      conf_scheduler.layouts.isotope({ filter: conf_scheduler.filterFunc})
    }, 500);
  };

  // Trigger events
  $('.multi_filter').on('change', function(e){
    //console.log('multifilter fired change.select2');
    var $instance = $(this).parents('.conf_scheduler');
    var iIndex = $instance.data('instance');
    $instance.removeClass('picks').find('.my_picks').removeClass('show');
    if ( !conf_scheduler.dayTabs ) $instance.find('.conference_day').addClass('open');
    $instance.find('.session').addClass('open');
    conf_scheduler.multiFilterVal = $('.multi_filter', $instance).select2('data');
    //var t0 = performance.now();
    conf_scheduler.layouts[iIndex].isotope({ filter: conf_scheduler.filterFunc });
    //var t1 = performance.now();
    //console.log("Call to filter took " + (t1 - t0) + " milliseconds.");
  });
  $('.workshop_search').on('keyup', conf_scheduler.liveSearcher);


  conf_scheduler.filterByTag = function($tag, tagType) {
    var $instance = $tag.parents('.conf_scheduler');
    var slug = $tag.attr('class').split(/\s+/)[1];
    var selection = $('.multi_filter', $instance).val();
    selection.push(tagType+'_'+slug);
    $('.multi_filter', $instance).val(selection).trigger('change');
  }
  $('.conf_scheduler').on('click', '.workshop .keyword', function(e) {
    conf_scheduler.filterByTag($(this), 'keyword');
  });
  $('.conf_scheduler').on('click', '.workshop .theme', function(e) {
    conf_scheduler.filterByTag($(this), 'theme');
  });

  // Expand workshop details
  $('.conf_scheduler')
  .on('click', '.workshop', function(){
    $(this).find('.details').toggle();
    $(this).toggleClass("open");
    $(this).parents('.workshops').isotope('layout');
  })
  .on('click', '.workshop a, .workshop .favorite, .workshop button, .workshop .themes, .workshop .keywords', function(e) { e.stopPropagation(); });


  // Fold days  - delegated to parent elem becuase elems might be initially hidden
  $('body').on('click','.conference_day > h3', function(){
    $(this).parent().toggleClass('open');
  });

  // Fold sessions - delegated to parent elem becuase elems are initially hidden
  $('body').on('click', '.session > h3', function(){
    $(this).parent().toggleClass('open');
    $(this).siblings('.workshops').isotope('layout');
  });

  // show/hide all workshops
  $('.toggle_all').on('click', function(){
    var $instance = $(this).parents('.conf_scheduler');
    var iIndex = $instance.data('instance');
    $instance.find('.multi_filter').val('').trigger('change.select2');
    $instance.removeClass('picks').removeClass('show_available');
    if ($instance.find('.session.open').length > 0) {
      conf_scheduler.layouts[iIndex].isotope({ filter: '*'});
      if ( !conf_scheduler.dayTabs ) $instance.find('.conference_day').removeClass('open');
      $instance.find('.session').removeClass('open');
    } else {
      if ( !conf_scheduler.dayTabs ) $instance.find('.conference_day').addClass('open');
      $instance.find('.session').addClass('open');
      conf_scheduler.layouts[iIndex].isotope({ filter: '*'});
    }

  });

  if (!conf_scheduler_ldata.disableMyPicks ) {
    // Show only My Picks
    $('button.my_picks').on('click', function(){
      var $instance = $(this).parents('.conf_scheduler');
      var iIndex = $instance.data('instance');
      $instance.toggleClass('picks');
      if( $instance.hasClass('picks') ) {
        // $instance.find('.multi_filter').val('').trigger('change.select2'); // reset keyword filter - trigger change to update select2
        if ( !conf_scheduler.dayTabs ) $instance.find('.conference_day').addClass('open');
        $instance.find('.session').addClass('open');
      }
      conf_scheduler.multiFilterVal = $('.multi_filter', $instance).select2('data');
      conf_scheduler.layouts[iIndex].isotope({ filter: conf_scheduler.filterFunc });
    });

    conf_scheduler.filterPicks = function ( found, $workshop) {
      // only check if button checked
      if ( !$workshop.parents('.conf_scheduler').hasClass('picks') )
        return found;
      return found && $workshop.hasClass('picked');
    }
    conf_scheduler.hooks.addFilter('conf_scheduler_schedule_filters', 'conf-scheduler', conf_scheduler.filterPicks);

    // Pick a workshop
    $('.conf_scheduler').on('click', '.workshop .favorite', function(e){
      e.stopPropagation();
      var $workshop = $(this).parents('.workshop');
      conf_scheduler.pickWorkshop($workshop, 'toggle');
    });
  }

  conf_scheduler.pickWorkshop = function ($workshop, setPickStatus = 'toggle') {
    var workshopID = parseInt($workshop.attr('data-workshopid'));
    var hadPicked = conf_scheduler.myPicks.includes(workshopID);
    if(setPickStatus == 'toggle')
      setPickStatus = !hadPicked;

    if( hadPicked != setPickStatus ) {
      // pick status changed
      if (setPickStatus) {
        conf_scheduler.myPicks.push(workshopID);
      } else {
        conf_scheduler.myPicks = conf_scheduler.myPicks.filter(function(item) {
            return item !== workshopID;
        });
      }

      Cookies.set('conf_scheduler_picks', conf_scheduler.myPicks, { expires: 365 }); // store the cookie for 1 year

      $('.conf_scheduler .workshop[data-workshopid="'+workshopID+'"]').each(function(){
        var $workshop = $(this);
        // promote this block and re-sort
        var $block = $(this).closest('.conf_block');
        if(setPickStatus) {
          $workshop.addClass('picked');
        } else {
          $workshop.removeClass('picked');
        }
        if ( $block.hasClass('picked') || $block.find('.picked').length ) {
          $block.attr('data-order','1');
        } else { $block.attr('data-order','5'); }

        $(this).closest('.workshops').isotope('updateSortData').isotope({ sortBy: 'order'});
      });
    }
  }

  // Day tabs JS
  if( $('.conf_scheduler.day_tabs').length ) {
    $('.conf_scheduler').on('click', 'ul.day_tabs li', function(){
      var $instance = $(this).parents('.conf_scheduler');
      var day = $(this).data('day');
      $instance.find('ul.day_tabs li').removeClass('open');
      $(this).addClass('open');
      $instance.find('.conference_day').removeClass('open');
      $instance.find('.conference_day.day_'+day).addClass('open').find('.workshops').isotope('layout');
    });
  }

  conf_scheduler.hooks.doAction( 'conf_scheduler_js_loaded' );
});
