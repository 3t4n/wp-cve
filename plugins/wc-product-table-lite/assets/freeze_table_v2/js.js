/*
* Why v2?
* Far more efficient version of freeze column / header feature
* using position:sticky for columns since the that the feauture 
* is now widely adopted among browsers.
*/
(function($){

  // freeze table extension for jQuery
  $.fn.freezeTable = function (options, source) { 
    // console.log(`freeze table init source: ${source}`);
    return this.each(function () {
      var $this = $(this);

      // cannot be called on clone table
      if( $this.hasClass('frzTbl-clone-table') ){
        return;
      }

      // destroy
      if( options == 'destroy' ){
        if( $this.data('freezeTable') ) {
          $this.data('freezeTable').destroy();
        }
        return true;
      }

      // create new 
      if( ! $this.data('freezeTable') ){
        $this.data('freezeTable', new $.FreezeTable(this, options));
        return true;
      }

      // cell resize
      if( options == 'resize' ){
        $this.data('freezeTable').table_resize_handler();
        return true;
      }

      // pause
      if( options == 'pause' ){
        $this.data('freezeTable').pause = true;
        return true;
      }

      // unpause
      if( options == 'unpause' ){
        $this.data('freezeTable').pause = false;
        return true;
      }

    });
    
  };
  $.FreezeTable = FreezeTable;

  // The freeze table prototype object
  function FreezeTable (table, options) {
    var $table = $(table);

    this.el = {
      $table: $table
    };

    this.ev = {
      touchstart: false
    };

    this.env = {},

    this.timeout = {},

    this.options = $.extend(true, {}, this.default_options, typeof options === 'object' ? options : {} );

    this.namespace = Math.floor((Math.random() * 100000) + 1);

    if( this.options.height && ! this.options.force_sticky_outer_heading ){
      this.options._sticky_outer_heading = false;

    }else if( this.options.force_sticky_outer_heading ){
      this.options._sticky_outer_heading = true;
    }

    $table.trigger('before_freeze_table_build', this);    
    this.build();
    $table.trigger('after_freeze_table_build', this);
  };

  // default options
  FreezeTable.prototype.default_options = {
    left: 0, // freeze left columns
    right: 0, // freeze right columns
    heading: 0, // freeze heading row
    offset: 0, // sticky headin row offset

    wrapperWidth: 0, // @TODO: maybe remove
    wrapperHeight: 0, // @TODO: maybe remove

    tableWidth: false, // precise value 1200 or +200 ie {container width + 200px}

    grab_and_scroll: 0,
    grab_and_scroll_click_selectors: false,

    captureScroll: false,
    force_sticky_outer_heading: false,
    _sticky_outer_heading: true
  };

  // // toggle based on breakpoint settings
  // FreezeTable.prototype.maybe_disable = function() {
  //   var settings = this.get_breakpoint_options();
  //       // $table = this.el.$table,
  //       // $container = $table.closest('.frzTbl').length ? $table.closest('.frzTbl') : $table.parent(),
  //       // container_width = settings.wrapperWidth ? settings.wrapperWidth : $container.width(),
  //       // table_compressed_width = $table.outerWidth(container_width).outerWidth(),
  //       // table_original_width = $table[0].style.width;

  //   // $table[0].style.width = table_original_width;

  //   $(window).off('resize.ft' + this.namespace);

  //   if(
  //     // table_compressed_width <= container_width &&
  //     ! settings.left &&
  //     ! settings.right && 
  //     ! settings.heading
  //   ){
  //     this.unwrap();

  //     // register event handler to check if FT required upon future resize
  //     $(window).on('resize.ft' + this.namespace, $.proxy(this, 'try_enable'));

  //     return true;
  //   }

  // }

  // // throttles event handler, attempts 'build' every 200 ms
  // FreezeTable.prototype.try_enable = function() {
  //   var _build = $.proxy(this, 'build');
  //   clearTimeout(this.try_enable_clear);
  //   this.try_enable_clear = setTimeout(_build, 200);

  // };

  // @TODO - remove classes
  // // check if horizontal overflow should be permitted
  // FreezeTable.prototype.get_overflow_permission = function(){
  //   var $table = this.el.$table,
  //       $table_parent = this.el.$tableInner,
  //       settings = this.get_breakpoint_options(),
  //       overflow = false;
    
  //   if( // only if fixed columns
  //     ! settings.left && 
  //     ! settings.right 
  //   ){
  //     return false;
  //   }

  //   // ... and even then only overflow if needed
  //   if( $table_parent.hasClass('frzTbl-table-wrapper__inner') ){
  //     $table_parent.addClass('frzTbl-table-wrapper__inner--overflow-check');
  //     overflow = $table.outerWidth() > $table_parent.width();
  //     $table_parent.removeClass('frzTbl-table-wrapper__inner--overflow-check');

  //   }else{
  //     overflow = $table.outerWidth() > $table_parent.width();

  //   }

  //   return overflow || settings.left || settings.right;
  // }

  FreezeTable.prototype.build = function() {
    // if( this.maybe_disable() ){
    //   return;
    // }

    // mark source table
    this.el.$table.addClass('frzTbl-table');

    // template
    // @TODO - clean this up into template function
    this.tpl = $('#freeze-table-template').html();
    this.insert_heading_in_template();
    this.toggle_shadow_columns_in_template();
    $(this.tpl).insertBefore(this.el.$table) // insert template on page
      .attr('data-freeze-table-namespace', this.namespace) // unique identifier for FT event handlers
      .find('.frzTbl-table-placeholder').replaceWith(this.el.$table); // insert the $table

    this.build_element_store();

    // init facilities
    // this.freeze_heading(); // @TODO
    this.freeze_columns();
    this.scroll_hijack(); // attach scroll and touch handlers and initial scoll position    
    // @TODO: maybe_scroll_hijack -- only activate if scroll overflow takes place
    this.grab_and_scroll();

    // attach window resize handler
    this.el.$window.on('resize.ft' + this.namespace, $.proxy(this, 'table_resize_handler', '*window resize from '+ this.namespace +'*'));

    // update env
    this.update_env({
      // window
      windowWidth: $(window).width(),
      windowHeight: $(window).height(),

      // heading row cells width
      heading_row_cells_width: this.get_heading_row_cells_width(),

      // first row cells width
      first_row_cells_width: this.get_first_row_cells_width(),      

      // breakpoint
      breakpoint: this.current_breakpoint(),      
    });

    // watch for resize
    this.attach_resize_observers();    

    // run table resize handler
    this._table_resize_handler(); // skip throttle

    this.el.$wrapper.addClass('frzTbl--init');
  };

  // syncs scroll between sticky heading, table and antiscroll 
  FreezeTable.prototype.scroll_hijack = function(){
    // wheel/scroll
    this.el.$window.on('scroll', $.proxy(this, 'page_scroll'));
    this.page_scroll({target: document});

    this.el.$wrapper.on('wheel', $.proxy(this, 'wrapper_wheel'));
    this.el.$wrapper.on('touchstart touchmove touchend', $.proxy(this, 'wrapper_touch'));
    this.el.$scrollOverlay.on('wheel scroll', $.proxy(this, 'scrollOverlay_wheel'));

    var affected = [
      this.el.$scrollOverlay,
      this.el.$tableWrapper,
      this.el.$fixedHeadingWrapper,
    ];

    $.each(affected, function(i, $elm){
      $elm[0].scrollTop = 0;
      $elm[0].scrollLeft = 0;
    })    
  }

  FreezeTable.prototype.build_element_store = function(){
    // window
    this.el.$window = $(window);

    // wrapper
    this.el.$wrapper = this.el.$table.closest('.frzTbl');

    // scroll
    this.el.$scrollOverlay = this.el.$wrapper.children('.frzTbl-scroll-overlay');
    this.el.$scrollOverlayInner = this.el.$scrollOverlay.children('.frzTbl-scroll-overlay__inner');

    // entire content
    this.el.$contentWrapper = this.el.$wrapper.children('.frzTbl-content-wrapper');

    // column shadows
    this.el.$frozenColumnsWrapper = this.el.$contentWrapper.children('.frzTbl-frozen-columns-wrapper');
    // this.el.$frozenColumnsInner = this.el.$frozenColumnsWrapper.children('.frzTbl-frozen-columns-wrapper__inner');
    // this.el.$frozenColumnsLeft = this.el.$frozenColumnsInner.children('.frzTbl-frozen-columns-wrapper__columns--left');
    this.el.$frozenColumnsLeft = this.el.$frozenColumnsWrapper.children('.frzTbl-frozen-columns-wrapper__columns--left');
    this.el.$frozenColumnsLeftSticky = this.el.$frozenColumnsLeft.children('.frzTbl-top-sticky');
    // this.el.$frozenColumnsRight = this.el.$frozenColumnsInner.children('.frzTbl-frozen-columns-wrapper__columns--right');
    this.el.$frozenColumnsRight = this.el.$frozenColumnsWrapper.children('.frzTbl-frozen-columns-wrapper__columns--right');
    this.el.$frozenColumnsRightSticky = this.el.$frozenColumnsRight.children('.frzTbl-top-sticky');

    // sticky heading
    this.el.$fixedHeadingWrapperOuter = this.el.$contentWrapper.children('.frzTbl-fixed-heading-wrapper-outer');
    this.el.$fixedHeadingWrapper = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper');
    this.el.$fixedHeadingLeftColumn = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper__columns--left');
    this.el.$fixedHeadingRightColumn = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper__columns--right');
    this.el.$fixedHeadingInner = this.el.$fixedHeadingWrapper.children('.frzTbl-fixed-heading-wrapper__inner');

    // main table
    this.el.$headingRowCells = this.el.$table.find('> thead > .wcpt-heading-row:last-child > .wcpt-heading');
    this.el.$firstRowCells = this.el.$table.find('> tbody > .wcpt-row:first-child > .wcpt-cell');
    this.el.$tableWrapper = this.el.$contentWrapper.children('.frzTbl-table-wrapper');
    this.el.$tableInner = this.el.$tableWrapper.children('.frzTbl-table-wrapper__inner');
    this.el.$tableWrapperSticky = this.el.$tableInner.children('.frzTbl-top-sticky');    
  }

  FreezeTable.prototype.grab_and_scroll = function() {
    var _ = this,
        $wrapper = _.el.$wrapper,
        $body = $('body');

    $wrapper.off('mousedown.freeze_table.grab_and_scroll');
    $body.off('mousemove.freeze_table.grab_and_scroll_' + _.namespace);
    $body.off('mousemove.freeze_table.grab_and_scroll_' + _.namespace);
    $('img, a', $wrapper).off('dragstart.freeze_table.grab_and_scroll_' + _.namespace);
    $wrapper.removeClass('frzTbl--grab-and-scroll frzTbl--grab-and-scroll--grabbing');

    if( this.get_breakpoint_options().grab_and_scroll ){
      _.grab_and_scroll__grabbed = false;

      _.grab_and_scroll__last_clientX = false;
      _.grab_and_scroll__last_pos_x = false;

      _.grab_and_scroll__last_clientY = false;
      _.grab_and_scroll__last_pos_y = false;

      _.grab_and_scroll__$el = false;

      $wrapper.addClass('frzTbl--grab-and-scroll');
  
      $wrapper.on('mousedown.freeze_table.grab_and_scroll', function(e){
        if( e.which === 3 ){ // right click
          return;
        }

        if( e.target.tagName == 'SELECT' ){
          return;
        }

        _.grab_and_scroll__grabbed = true;		

        _.grab_and_scroll__first_clientX = e.clientX; 
        _.grab_and_scroll__first_clientY = e.clientY;        

        _.grab_and_scroll__last_clientX = e.clientX; 
        _.grab_and_scroll__last_clientY = e.clientY;

        _.grab_and_scroll__$el = $(e.target).parentsUntil( $wrapper );

        _.grab_and_scroll__$el__ev_handler_attached = false;        

        $wrapper.addClass('frzTbl--grab-and-scroll--grabbing');	
        _.el.$table.trigger('freeze_table__grab_and_scroll__start');        

        $body.one('mouseup', function(){
          _.grab_and_scroll__grabbed = false;	
          $wrapper.removeClass('frzTbl--grab-and-scroll--grabbing');	
          _.el.$table.trigger('freeze_table__grab_and_scroll__stop');

          _.timeout.grab_and_scroll = setTimeout(function(){ // async else click ev will fire now
            _.grab_and_scroll__$el.off('click.freeze_table.grab_and_scroll_' + _.namespace);
          }, 1);
        })
      })
        
      $body.on('mousemove.freeze_table.grab_and_scroll_' + _.namespace, function(e){
        if( _.grab_and_scroll__grabbed ){
          var diff_x = e.clientX - _.grab_and_scroll__last_clientX;
          _.grab_and_scroll__last_pos_x += parseFloat(diff_x);
          _.horizontal_scroll( -diff_x );          
          _.grab_and_scroll__last_clientX = e.clientX;

          var diff_y = e.clientY - _.grab_and_scroll__last_clientY;
          _.grab_and_scroll__last_pos_y += parseFloat(diff_y);
          _.vertical_scroll( -diff_y );
          _.grab_and_scroll__last_clientY = e.clientY;

          if(
            (
              Math.abs( _.grab_and_scroll__last_clientX - _.grab_and_scroll__first_clientX ) > 2 ||
              Math.abs( _.grab_and_scroll__last_clientY - _.grab_and_scroll__first_clientY ) > 2
            ) &&
            ! _.grab_and_scroll__$el__ev_handler_attached 
          ){
            _.grab_and_scroll__$el.one('click.freeze_table.grab_and_scroll_' + _.namespace, function(e){
              e.preventDefault();
              e.stopPropagation();
            })
            _.grab_and_scroll__$el__ev_handler_attached = true;            
          }

          _.el.$table.trigger('freeze_table__grab_and_scroll__dragging');
        }
      })

      $('img, a', $wrapper).on('dragstart.freeze_table.grab_and_scroll_' + _.namespace, function(e){
        e.preventDefault();
      });

    }
  };

  FreezeTable.prototype.insert_heading_in_template = function() {
    if( this.get_breakpoint_options().heading ){
      var $heading = this.clone_table();
      $heading.find('tbody').remove();  
      this.tpl = this.tpl.replace(/{{heading}}/g, $heading[0].outerHTML);
    } else {
      this.tpl = this.tpl.replace(/{{heading}}/g, '');
    }
  };

  FreezeTable.prototype.toggle_shadow_columns_in_template = function() {
    ['left', 'right'].forEach((direction)=>{
      var reg = new RegExp(`{{hide-${direction}-column}}`, 'g');
      if( settings = this.get_breakpoint_options()[direction] ){
        this.tpl = this.tpl.replace( reg, '' );
      }else{
        this.tpl = this.tpl.replace( reg, 'frzTbl-frozen-columns-wrapper__columns--empty' );
      }
    });
  };

  FreezeTable.prototype.resize_freeze_heading = function() {
    var settings = this.get_breakpoint_options(),
        offset = settings.offset ? settings.offset : 0,
        $heading = this.el.$table.children('thead'),
        heading_height = $heading[0].getBoundingClientRect().height,
        gap_top = parseInt( this.el.$table.css('border-top-width') ),
        heading_border = parseInt( this.el.$table.find('> thead > tr').css('border-bottom-width') );

    if( isNaN(offset) ){
      if( typeof offset === 'string' ){ // selector
        offset = $(offset).height();

      }else if( typeof offset === 'object' ){ // jQuery object
        offset = offset.height();

      }

    }

    if( gap_top % 2 ){
      --gap_top;
    }

    gap_top *= .5;

    this.el.$fixedHeadingWrapperOuter.css({
      height: heading_height + gap_top + heading_border,
      top: parseFloat( offset ) + 'px',
    });

    if( ! settings._sticky_outer_heading ){
      this.el.$fixedHeadingWrapperOuter.hide();
    }else{
      this.el.$fixedHeadingWrapperOuter.show();
    }

    if( ! settings.heading ){
      this.el.$fixedHeadingWrapperOuter.css({
        position: 'relative',
        top: 0
      });
    }

    this.el.$wrapper.parents().each(function(){
      var $this = $(this),
          overflow = $this.css('overflow');
      if(
        overflow && 
        overflow !== 'visible' 
      ){
        $this.addClass('frzTbl-force-parent-overlow-visible');
      }
    });

  };

  FreezeTable.prototype.freeze_columns = function() {
    var settings = this.get_breakpoint_options();

    if( ! this.el.$css ){
      this.el.$css = $(`<style id="freeze-table-css-${this.namespace}"></style>`);
      $('head').append( this.el.$css );
    }

    var sticky_css = '',
        offset_css = '',
        border_fix_css = '';

    ['left', 'right'].forEach((direction)=>{
      if( ! settings[direction] ){
        return;
      }

      var i = 1,
          x = parseInt( settings[direction] ),
          column_selectors = [],
          nth_filter = direction == 'left' ? 'nth-child' : 'nth-last-child',
          cumulative_width = 0;

      while( i ) {
        // sticky
        var ft_container_selector = `[data-freeze-table-namespace="${this.namespace}"]`,
            heading_selector = `${ft_container_selector} .wcpt-cell:${nth_filter}(${i})`,
            cell_selector = `${ft_container_selector} .wcpt-heading:${nth_filter}(${i})`,
            column_selector_current = `${heading_selector}, ${cell_selector}`; 
          
        column_selectors.push (column_selector_current);

        // offset 
        // -- give this column an offset based on previous cumulative width
        if( cumulative_width ){
          offset_css += ` ${column_selector_current} { ${direction}: ${cumulative_width}px; }  `;
        }
        // -- add this column's width to the cumulative width
        var first_column_cell_selector = `${ft_container_selector} .wcpt-table > tbody > .wcpt-row:first-child > .wcpt-cell:${nth_filter}(${i})`;
        cumulative_width += $(first_column_cell_selector)[0].getBoundingClientRect().width;
        // -- border fix 
        border_fix_css += ` 
          ${heading_selector}:after, 
          ${cell_selector}:after { 
            position: absolute; 
            content: ''; 
            width: 100%; 
            height: 100%; 
            left: 0; 
            top: 0; 
            display: block; 
            border: inherit; 
            pointer-events: none;
          }
          .frzTbl--scrolled-to-${direction}-edge${heading_selector}:after, 
          .frzTbl--scrolled-to-${direction}-edge${cell_selector}:after {
            display: none;
          }
        `;

        if( i < x ){
          ++i;
        }else{
          break;
        }
      }
      column_selectors.join(', ');
      sticky_css += column_selectors.join(', ') + ` {position: sticky; ${direction}: 0; z-index: 1; background: white;} `;
    })

    this.el.$css.html(sticky_css + offset_css + border_fix_css);
  }

  FreezeTable.prototype.resize_column_shadows = function() {
    var settings = this.get_breakpoint_options(),
        $table = this.el.$table;

    if( settings.left ){
      var cumulative_width = 0,
          i = settings.left;
      while( i ){
        cumulative_width += $(`> tbody > tr > td:nth-child(${i})`, $table)[0].getBoundingClientRect().width;
        --i;
      }
      this.el.$frozenColumnsLeft.width( cumulative_width );
    }

    if( settings.right ){
      var cumulative_width = 0,
          i = settings.right;
      while( i ){
        cumulative_width += $(`> tbody > tr > td:nth-last-child(${i})`, $table)[0].getBoundingClientRect().width;
        --i;
      }
      this.el.$frozenColumnsRight.width( cumulative_width );
    }

  };    

  FreezeTable.prototype.clone_table = function() {
    var $cloneTable = this.el.$table.clone();
    
    $cloneTable
      .css({
        'width': '',
        'min-width': '',
      })
      .addClass('frzTbl-clone-table');
    $( '> tbody > tr > td, > thead > tr > th', $cloneTable ).each(function(){
      var $this = $(this);
      $this.attr({
        'data-index': $this.index(),
      });
    });

    return $cloneTable;
  };

  // @TODO - simplify now that css based sticky columns are availabe
  FreezeTable.prototype.resize_clone_cells = function() {
    var $table = this.el.$table,
        $cloneTables = this.get_clone_tables(),
        $cloneCells = $( '> tbody > tr > td, > thead > tr > th', $cloneTables ),
        dimensions = [];

    // read styles
    $cloneCells.each(function(){
      var $this = $(this),
          $row = $this.parent(),
          wrapper = $row.parent().is('thead') ? 'thead' : 'tbody',
          selector = '> '+ wrapper +' > tr:nth-child('+ ($row.index() + 1) +') > *:nth-child('+ (parseInt($this.attr('data-index')) + 1) +')',
          $original = $(selector, $table);

      dimensions.push({
        width: $original[0].getBoundingClientRect().width,
        rowOuterHeight: $original.parent()[0].getBoundingClientRect().height
      });
    });    

    // write styles
    $cloneCells.each(function(i){
      var $this = $(this);

      $this.css({
        width: dimensions[i].width,
        minWidth: dimensions[i].width,
      });

      $this.parent().outerHeight(dimensions[i].rowOuterHeight);
    });

    // frozen columns header gap
    var $heading = this.el.$table.children('thead'),
        heading_height = $heading[0].getBoundingClientRect().height,
        gap_top = parseInt( this.el.$table.css('border-top-width') );

    if( gap_top % 2 ){
      --gap_top;
    }

    gap_top *= .5;

    // $('> div > table', this.el.$frozenColumnsInner).each(function(){
    //   var $this = $(this);
    //   $this.css('margin-top', (heading_height + gap_top) + 'px');
    // })

  };

  FreezeTable.prototype.get_clone_tables = function() {
    var $table = this.el.$table,
        $cloneTables = $();
    $.each(this.el, function(name, $el){
      var $childTables = $el.children().filter(function(){ 
        return $(this).is('table') && this !== $table[0]
      });
      $cloneTables = $cloneTables.add($childTables);
    });

    return $cloneTables;
  };

  FreezeTable.prototype.page_scroll = function(e) {
    if(e.target !== document ){
      return;
    }

    if( this.el.$table[0].getBoundingClientRect().top >= 0 ){
      this.el.$wrapper.addClass('frzTbl--scrolled-to-top');
    }else{
      this.el.$wrapper.removeClass('frzTbl--scrolled-to-top');
    }
  };  

  FreezeTable.prototype.wrapper_wheel = function(e) {
    if(
      e.originalEvent.deltaY &&
      (
        ! e.originalEvent.deltaX ||
        Math.abs( e.originalEvent.deltaY / e.originalEvent.deltaX ) > .1
      ) &&
      ! this.get_breakpoint_options().wrapperHeight
    ){
      return true;
    }

    var $wrapper = this.el.$wrapper,
        scrolling = 'frzTbl--scrolling';

    $wrapper.addClass(scrolling);

    clearTimeout(this.timeout.scroll_clear);
    this.timeout.scroll_clear = setTimeout(
      function(){        
        $wrapper.removeClass(scrolling);
      }, 300
    );

    e.preventDefault();

    if( ! this.options.captureScroll || ! this.options.wrapperHeight ){
      if(
        // no scroll
        this.el.$scrollOverlay[0].scrollHeight == this.el.$scrollOverlay.height() ||
        // scroll down
        (
          e.originalEvent.deltaY > 0 &&
          this.el.$scrollOverlay[0].scrollTop + this.el.$scrollOverlay.height() == this.el.$scrollOverlayInner.height()
        ) ||
        // scroll up
        (
          e.originalEvent.deltaY < 0 &&
          ! this.el.$scrollOverlay[0].scrollTop
        )
      ){
        $('html')[0].scrollTop += e.originalEvent.deltaY;      
        $('body')[0].scrollTop += e.originalEvent.deltaY;
      }  
    }

  };

  FreezeTable.prototype.wrapper_touch = function(e) {

    if( e.type == 'touchstart' ){
      this.el.$scrollOverlay.stop(true);
    }

    if(
      e.type == 'touchmove' && 
      this.ev.prevClientX !== false
    ){

      var diffX = this.ev.prevClientX - e.originalEvent.touches[0].clientX,
          diffY = this.ev.prevClientY - e.originalEvent.touches[0].clientY;

      var e2 = {
        originalEvent: { 
          deltaX: diffX, 
          deltaY: diffY 
        }
      };

      this.scrollOverlay_wheel(e2);

      // prep animate scroll      
      if( Math.abs(diffX) > 5 ){     
        this.ev.animScroll = 20 * diffX + this.el.$scrollOverlay[0].scrollLeft;
      }else{
        this.ev.animScroll = false;
      }
    }

    if( e.type == 'touchend' ){

      if( this.ev.animScroll ){        
        this.el.$scrollOverlay.animate({scrollLeft: this.ev.animScroll}, {
          specialEasing: {
            scrollLeft : 'FreezeTable_easeOutQuad',
          }
        });
        this.ev.animScroll = false;
      }

      this.ev.prevClientX = false;
      this.ev.prevClientY = false;

    }else{
      this.ev.prevClientX = e.originalEvent.touches[0].clientX,
      this.ev.prevClientY = e.originalEvent.touches[0].clientY;

    }
  };

  FreezeTable.prototype.scrollOverlay_wheel = function(e) {
    var deltaX = e.originalEvent.deltaX || 0,
        deltaY = e.originalEvent.deltaY || 0;

    this.horizontal_scroll( deltaX );
  };

  FreezeTable.prototype.horizontal_scroll = function( deltaX, deltaY ){

    var $scrollOverlay = this.el.$scrollOverlay,

        scrollTop = this.layout_memory.scrollTop,
        scrollLeft = this.layout_memory.scrollLeft,
        scrollOverlayWidth = this.layout_memory.scrollOverlayWidth,
        scrollOverlayInnerWidth = this.layout_memory.scrollOverlayInnerWidth,

        mode = 'scroll';
        // mode = 'transform';    

    if( ! deltaX ){
      deltaX = 0;
    }

    if( ! deltaY ){
      deltaY = 0;
    }

    if( deltaX == 0 && deltaY == 0 ){ // antiscroll
      deltaX = $scrollOverlay[0].scrollLeft - this.layout_memory.scrollLeft;
    }      

    scrollTop += deltaY;
    scrollLeft += deltaX;

    // scrollLeft bounds
    if( scrollLeft < 0 ){
      scrollLeft = 0;
    }

    if( scrollLeft + scrollOverlayWidth + 1 > scrollOverlayInnerWidth ){
      scrollLeft = scrollOverlayInnerWidth - scrollOverlayWidth;
    }

    this.layout_memory.scrollTop = scrollTop;
    this.layout_memory.scrollLeft = scrollLeft;

    $scrollOverlay[0].scrollTop = scrollTop;
    $scrollOverlay[0].scrollLeft = scrollLeft;

    // scroll
    if( mode == 'scroll' ){
      // -- table
      // this.el.$tableWrapper[0].scrollTop = scrollTop;
      this.el.$tableWrapper[0].scrollLeft = scrollLeft;
      // -- fixed heading
      this.el.$fixedHeadingWrapper[0].scrollLeft = scrollLeft;
      // -- fixed columns
      // this.el.$frozenColumnsWrapper[0].scrollTop = scrollTop;
    }

    // transform
    if( mode == 'transform' ){
      // -- table
      this.el.$tableInner[0].style.transform = 'translate3d(-' + scrollLeft + 'px, 0, 0)';
      // -- fixed heading
      this.el.$fixedHeadingInner[0].style.transform = 'translate3d(-' + scrollLeft + 'px, 0, 0)';
    }

    // scrolled to edge class
    this.el.$wrapper.removeClass('frzTbl--scrolled-to-left-edge frzTbl--scrolled-to-right-edge');
    if( ! scrollLeft ){
      this.el.$wrapper.addClass('frzTbl--scrolled-to-left-edge');
    }
    if( scrollLeft + scrollOverlayWidth >= scrollOverlayInnerWidth ){
      this.el.$wrapper.addClass('frzTbl--scrolled-to-right-edge');
    }      

  }

  FreezeTable.prototype.vertical_scroll = function( deltaY ){
    $('html')[0].scrollTop += deltaY;      
    $('body')[0].scrollTop += deltaY;
  }

  FreezeTable.prototype.get_breakpoint_options = function() {
    var settings = this.get_options(),
    current_bp = this.current_breakpoint();
    
    if( current_bp ){
      var ops = $.extend(true, {}, this.default_options, settings.breakpoint[current_bp]);
      return ops;
    }

    return settings;
  };

  FreezeTable.prototype.get_options = function() {
    return $.extend(true, {}, this.options);
  };  

  // throttles event handler, attempts '_table_resize_handler' every 100 ms
  FreezeTable.prototype.table_resize_handler = function(source) {
    // console.log(source);

    var _table_resize_handler = $.proxy(this, '_table_resize_handler');
    clearTimeout(this.timeout.table_resize_handler);
    this.timeout.table_resize_handler = setTimeout(_table_resize_handler, 100);

  };

  FreezeTable.prototype.table_resize_required = function() {
    if(
      this.has_heading_width_changed() ||
      this.has_column_width_changed() ||
      this.has_table_height_changed()
    ){
      return true;

    }else{
      return false;

    }
  };  

  FreezeTable.prototype.has_heading_width_changed = function() {
    if( ! this.env.heading_row_cells_width ){
      return true;
    }

    var _ = this,
        change = false;

    this.el.$headingRowCells.each(function(i){
      if( $(this).width() !== _.env.heading_row_cells_width[i] ){
        change = true;
      }
    })

    return change;
  };  

  FreezeTable.prototype.has_column_width_changed = function() {
    if( ! this.env.first_row_cells_width ){
      return true;
    }

    var _ = this,
        change = false;

    this.el.$firstRowCells.each(function(i){
      if( $(this).width() !== _.env.first_row_cells_width[i] ){
        change = true;
      }
    })

    return change;
  };

  FreezeTable.prototype.has_table_height_changed = function() {
    return ! this.env.tableHeight || this.env.tableHeight !== this.el.$table.height();
  };      

  FreezeTable.prototype._table_resize_handler = function() {
    if( this.pause ){
      return;
    }

    if( this.crossed_breakpoint() ){
      this.reload();
      return;
    }
        
    if( ! this.table_resize_required() ){
      return;
    }

    // this.disconnect_resize_observers();

    this.manage_overflow();
    this.resize_clone_cells();
    this.resize_column_shadows();
    this.resize_freeze_heading(); // @TODO

    if( 1 > Math.abs( this.el.$wrapper.innerWidth() - this.el.$table.outerWidth() ) ){
      this.el.$wrapper.addClass('frzTbl--scrolled-to-left-edge frzTbl--scrolled-to-right-edge');
    }

    this.update_env({
      // wrapper
      wrapperWidth: this.el.$wrapper.width(),
      wrapperHeight: this.el.$wrapper.height(),

      // table
      tableWidth: this.el.$table.width(),
      tableHeight: this.el.$table.height(),
    });
  };

  FreezeTable.prototype.manage_overflow = function(){

    if( ! this.manage_overflow_required() ){
      // account for change in table height
      this.resize_wrapper_and_scroll_to_table();
      return;
    }

    this.reset_overflow();

    // record current scroll left
    // @TODO - env should record this continously
    // and this.antiscroll() should auto load scrollLeft if breakpoint didn't change
    var scrollLeft = this.el.$scrollOverlay[0].scrollLeft;

    // controlled overflow
    // - sticky columns may or may not be enabled
    // - user has entered a specific width for the table
    if( this.controlled_overflow_qualified() ){
      this.apply_controlled_overflow();

    // unrestricted overflow
    // - sticky columns are enabled
    // - user very likely wants an overflow to occur
    // - expand the table freely
    // @TODO - give  this.el.$table no width at all
    }else if( this.unrestricted_overflow_qualified() ){
      this.apply_unrestricted_overflow();

    // normal overflow
    // -- no sticky columns
    // -- any overflow is likely accidental and unwanted
    // -- do not expand the resulting overflow
    // -- let user manage overflow by modifying layout
    }else{
      this.apply_normal_overflow();

    }

    this.antiscroll();
    // return to current scroll left
    this.el.$scrollOverlay[0].scrollLeft = scrollLeft;

  };

  // @TODO - improve
  FreezeTable.prototype.manage_overflow_required = function(){
    if( 
      jQuery.isEmptyObject( this.env ) ||
      this.env.wrapperWidth !== this.el.$wrapper.width() ||
      this.env.tableWidth !== this.el.$table.width()
    ){
      return true;

    }
    return false;

  };

  FreezeTable.prototype.reset_overflow = function(){
    this.el.$wrapper.removeClass('frzTbl--controlled-overflow frzTbl--unrestricted-overflow')

    this.el.$table.css({ 
      'min-width': '' 
    });

    this.el.$scrollOverlayInner.css({
      height: '',
      width: ''        
    });

    this.el.$wrapper.css({
      height: ''
    });

    this.el.$tableInner.removeClass('frzTbl-table-wrapper__inner--deflate');
  };  

  // controlled overflow

  // -- qualify
  FreezeTable.prototype.controlled_overflow_qualified = function(){
    var user_forced_table_width = this.get_parsed_user_forced_table_width();

    if( 
      user_forced_table_width &&
      user_forced_table_width > this.el.$tableWrapper.width()
    ){
      return true;
    }

    return false;
  };

  // -- apply
  FreezeTable.prototype.apply_controlled_overflow = function(){
    this.el.$wrapper.addClass('frzTbl--controlled-overflow');

    var table_width = this.get_parsed_user_forced_table_width();

    this.el.$table.css({ 
      width: table_width 
    });

    var table_height = this.el.$table.outerHeight();

    this.resize_wrapper_and_scroll_to_table({
      width: table_width,
      height: table_height
    });
  };

  // -- get tableWidth
  FreezeTable.prototype.get_parsed_user_forced_table_width = function(){
    var breakpoint_settings = this.get_breakpoint_options(),
        user_table_width = breakpoint_settings.tableWidth ? breakpoint_settings.tableWidth : false,
        table_wrapper_width = this.el.$wrapper.width();

    if( user_table_width ){
      // clean up value
      // -- remove all spaces
      user_table_width = user_table_width.replaceAll(/\s/g, '');

      // -- remove px from end and get integer
      if( user_table_width.slice(-2) == 'px' ){
        user_table_width = user_table_width.slice(0, -2);
      }

      // add in wrapper width if '+' is placed at start of figure
      if( user_table_width.charAt(0) === '+' ){
        var _figure = user_table_width.substring(1);

        if( isNaN( _figure ) ){
          user_table_width = false;  

        }else{
          user_table_width = parseInt( user_table_width.substring(1) ) + table_wrapper_width;

        }
      }
    }

    return user_table_width;
  };  

  // unrestricted overflow

  // -- qualify
  FreezeTable.prototype.unrestricted_overflow_qualified = function(){
    var breakpoint_settings = this.get_breakpoint_options();

    return ( breakpoint_settings.left || breakpoint_settings.right ) ? true : false;
  };  

  // -- apply  
  FreezeTable.prototype.apply_unrestricted_overflow = function(){ // @TODO
    this.el.$wrapper.addClass('frzTbl--unrestricted-overflow');

    // not going to just leave the class in there. Use it to get the table width and apply it via inline style. Need to stabilize table width to avoid repeated changes to table width which would require frequent calls to table_resize_handler
    this.el.$tableInner.addClass('frzTbl-table-wrapper__inner--unrestrict-table-wrapper');

    var table_width = Math.max( this.el.$table.outerWidth(), this.el.$wrapper.innerWidth() );
    table_height = this.el.$table.outerHeight();

    if( table_width > this.el.$wrapper.width() ){
      ++table_width;
    }

    this.el.$tableInner.removeClass('frzTbl-table-wrapper__inner--unrestrict-table-wrapper');

    this.el.$table.css({ 
      'min-width': table_width 
    });

    this.resize_wrapper_and_scroll_to_table({
      width: table_width,
      height: table_height
    });

  };

  // normal overflow

  // -- apply    
  FreezeTable.prototype.apply_normal_overflow = function(){
    this.el.$wrapper.addClass('frzTbl--normal-overflow');
    this.el.$tableInner.addClass('frzTbl-table-wrapper__inner--deflate');

    var table_width = this.el.$table.outerWidth();
        table_height = this.el.$table.outerHeight();

    this.resize_wrapper_and_scroll_to_table({
      width: table_width,
      height: table_height
    });

  };

  FreezeTable.prototype.resize_wrapper_and_scroll_to_table = function( table_dimensions ){
    if( ! table_dimensions ){
      table_dimensions = {};
    }

    if( ! table_dimensions.height ){
      table_dimensions.height = this.el.$table.outerHeight();
    }

    if( ! table_dimensions.width ){
      table_dimensions.width = this.el.$table.outerWidth();
    }

    this.el.$scrollOverlayInner.css({
      height: table_dimensions.height,
      width: table_dimensions.width        
    });

    this.el.$wrapper.css({
      height: table_dimensions.height
    });

  }

  // resize observer

  // -- attach
  FreezeTable.prototype.attach_resize_observers = function(e){
    var _ = this;
    if( ! _.resize_observers ){
      _.resize_observers = {
        // table 
        table_observer: new ResizeObserver(()=>{
          _.table_resize_handler('*table resize*');
        }),
        // heading row cells
        heading_row_cells_observer: new ResizeObserver(()=>{
          _.table_resize_handler('*heading row cells resize*');
        }),
        // first row cells
        first_row_cells_observer: new ResizeObserver(()=>{
          _.table_resize_handler('*first row cells resize*');
        }),
      };  
    }

    // table 
    _.resize_observers.table_observer.observe( this.el.$table[0] );

    // heading row cells
    $('> thead > tr:last-child > th', this.el.$table).each(function(){
      _.resize_observers.heading_row_cells_observer.observe( this );
    });

    // first row cells
    $('> tbody > tr:first-child > td', this.el.$table).each(function(){
      _.resize_observers.first_row_cells_observer.observe( this );
    });
  }  

  // -- disconnect
  FreezeTable.prototype.disconnect_resize_observers = function(){
    for( var resize_observer in this.resize_observers ){
      if( Object.hasOwn(this.resize_observers, resize_observer) ){
        this.resize_observers[resize_observer].disconnect();
      }
    }
  }

  FreezeTable.prototype.antiscroll = function() {
    var $scrollOverlay = this.el.$scrollOverlay;

    // rebuild layout memory
    this.layout_memory = {
      scrollTop: $scrollOverlay[0].scrollTop = 0,
      scrollLeft: $scrollOverlay[0].scrollLeft = 0,
      scrollOverlayWidth: $scrollOverlay.width(),
      scrollOverlayInnerWidth: this.el.$scrollOverlayInner.width(),
    }

    // antiscroll
    this.el.$wrapper.antiscroll();

    // -- reposition
    this.el.$wrapper
      .children('> .frzTbl-antiscroll-control-wrap').remove().end()
      .children('.antiscroll-scrollbar-horizontal').wrap('<div class="frzTbl-antiscroll-control-wrap">');

    // -- hide / show scroll bars for corner cases
    if( this.el.$table.width() - this.el.$wrapper.width() < 5 ){
      this.el.$wrapper.addClass('frzTbl--hide-antiscroll');

    }else{
      this.el.$wrapper.removeClass('frzTbl--show-antiscroll');
      
    }
  };

  FreezeTable.prototype.crossed_breakpoint = function() {
    var crossed_breakpoint = this.current_breakpoint() !== this.env.breakpoint;
    return crossed_breakpoint;
  };

  FreezeTable.prototype.update_env = function(new_data) {
    this.env = $.extend(true, {}, this.env, new_data);
  }

  FreezeTable.prototype.get_heading_row_cells_width = function() {
    var arr = [];
    this.el.$headingRowCells.each(function(i){
      arr.push( $(this).width() );
    })
    return arr;
  }  

  FreezeTable.prototype.get_first_row_cells_width = function() {
    var arr = [];
    this.el.$firstRowCells.each(function(i){
      arr.push( $(this).width() );
    })
    return arr;
  }

  FreezeTable.prototype.current_breakpoint = function() {
    var settings = this.get_options(),
        breakpoint = false,
        windowWidth = $(window).width();

    if( ! settings.breakpoint ){
      return false;
    }

    $.each(settings.breakpoint, function(bp, bp_settings){
      var bp = parseInt( bp );
      if( bp < windowWidth ){
        return true;
      }

      if( ! breakpoint || bp < breakpoint ){
        breakpoint = bp;
      }
    });

    return breakpoint;
  };

  FreezeTable.prototype.destroy = function() {
    this.clear_handlers();
    this.clear_timeouts();
    this.disconnect_resize_observers();
    this.unwrap();
    this.el.$table.removeData('freezeTable');
  };

  FreezeTable.prototype.clear_handlers = function() {
    this.el.$window.off('resize.ft' + this.namespace);
  };

  FreezeTable.prototype.clear_timeouts = function() {
    for( var timeout in this.timeout ){
      if( Object.hasOwn(this.timeout, timeout) ){
        clearTimeout(timeout);
      }
    }
  };

  FreezeTable.prototype.unwrap = function() {
    var $table = this.el.$table,
        $wrapper = this.el.$wrapper;

    $table
      .removeClass('frzTbl-table')
      .css({
        'width': '',
        'min-width': ''
      });

    if( ! $wrapper || ! $wrapper.length ){
      return;
    }

    $table.insertBefore($wrapper);
    $wrapper.remove();
  };

  FreezeTable.prototype.reload = function(options) {
    var $table = this.el.$table,
        options = options ?? this.get_options();

    this.destroy();
    $table.freezeTable(options);
  };

  $.extend($.easing,
    {
      FreezeTable_easeOutQuad: function (x, t, b, c, d) {
        return -c *(t/=d)*(t-2) + b;
      },
    }
  );

})(jQuery);