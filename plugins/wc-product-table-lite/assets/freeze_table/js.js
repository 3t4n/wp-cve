jQuery(function($){
  
  // freeze table extension for jQuery
  $.fn.freezeTable = function (options) {
    return this.each(function () {
      
      var $this = $(this);

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

      // create
      if( ! $this.data('freezeTable') ){
        $this.data('freezeTable', new $.FreezeTable(this, options));
        return true;
      }

      // resize
      if( options == 'resize' ){
        $this.data('freezeTable').resize();
        return true;
      }

      // cell resize
      if( options == 'cell_resize' ){
        $this.data('freezeTable').cell_resize();
        return true;
      }
      
      // reload
      $this.data('freezeTable').reload(options);

    });
    
  };

  $.FreezeTable = FreezeTable;

  function FreezeTable (table, options) {
    var $table = $(table);

    this.el = {
      $table: $table
    }

    this.ev = {
      touchstart: false
    }    

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

  FreezeTable.prototype.default_options = {
    left: 0,
    right: 0,
    heading: 0,
    offset: 0,

    wrapperWidth: 0,
    wrapperHeight: 0,

    tableWidth: 0,

    grab_and_scroll: 0,
    grab_and_scroll_click_selectors: false,

    captureScroll: false,
    force_sticky_outer_heading: false,
    _sticky_outer_heading: true
  };

  // unwrap if FT not required at this window size 
  FreezeTable.prototype.maybe_disable = function() {
    var settings = this.get_breakpoint_options(),
        $table = this.el.$table,
        $container = $table.closest('.frzTbl').length ? $table.closest('.frzTbl') : $table.parent(),
        container_width = settings.wrapperWidth ? settings.wrapperWidth : $container.width(),
        table_original_width = $table[0].style.width,
        table_compressed_width = $table.outerWidth(container_width).outerWidth();

    $table[0].style.width = table_original_width;

    $(window).off('resize.ft' + this.namespace);

    if(
      ! settings.tableWidth &&
      table_compressed_width <= container_width &&
      ! settings.left &&
      ! settings.right && 
      ! settings.heading
    ){
      this.unwrap();

      // register event handler to check if FT required upon future resize
      $(window).on('resize.ft' + this.namespace, $.proxy(this, 'try_enable'));

      return true;
    }

  }

  // throttles event handler, attempts 'build' every 200 ms
  FreezeTable.prototype.try_enable = function() {
    var _build = $.proxy(this, 'build');
    clearTimeout(this.try_enable_clear);
    this.try_enable_clear = setTimeout(_build, 200);

  };

  FreezeTable.prototype.get_overflow_permission = function(){
    var $table = this.el.$table,
        $table_parent = $table.parent(),
        settings = this.get_breakpoint_options(),
        table_overflows = false;

    // no need for ov flow if columns not fixed
    if( ! settings.left && ! settings.right ){
      return false;
    }

    if( $table_parent.hasClass('frzTbl-table-wrapper__inner') ){
      $table_parent.addClass('frzTbl-table-wrapper__inner--overflow-check');
      table_overflows = $table.outerWidth() > $table_parent.width();
      $table_parent.removeClass('frzTbl-table-wrapper__inner--overflow-check');

    }else{
      table_overflows = $table.outerWidth() > $table_parent.width();

    }

    return table_overflows || settings.left || settings.right;
  }

  FreezeTable.prototype.build = function() {
    if( this.maybe_disable() ){
      return;
    }
    
    var $table = this.el.$table, 
        $temp_wrapper = $('<div class="frzTbl frzTbl--temp-wrapper">').insertBefore($table),        
        tpl_master = $('#frzTbl-tpl').html();
    
    $table.css({
      width     : '',
      minWidth  : '',
      height    : '',
    });

    $temp_wrapper[0].innerHTML = '<div>'+ $table[0].outerHTML +'</div>';

    var $temp_table = $('>div>table', $temp_wrapper),
        table_width = Math.max( $temp_table.outerWidth(), $temp_wrapper.width() ),
        table_height = $temp_table.outerHeight();

    if( table_width > $temp_wrapper.width() ){
      ++table_width;
    }

    var wrapper_width = this.options.wrapperWidth ? this.options.wrapperWidth : '',
        wrapper_height = this.options.wrapperHeight ? this.options.wrapperHeight : table_height;

    $temp_wrapper.remove();

    this.tpl = tpl_master
      .replace(/{{wrapper_height}}/g,'height:' +  wrapper_height + 'px; ')
      .replace(/{{wrapper_width}}/g, wrapper_width ? 'width:' + wrapper_width + 'px; ' : '')
      .replace(/{{table_height}}/g, 'height:' + table_height + 'px; ')
      .replace(/{{table_width}}/g, 'width:' + table_width + 'px; ');

    this.build_heading();
    this.build_left();
    this.build_right();

    $table.addClass('frzTbl-table'); 

    var $wrapper = this.el.$wrapper = $(this.tpl).insertBefore($table);
    $wrapper.find('.frzTbl-table-placeholder').replaceWith($table);

    var $window = $(window);

    // record components
    this.el.$firstCell = this.el.$table.find('.wcpt-cell:first');

    this.el.$scrollOverlay = this.el.$wrapper.children('.frzTbl-scroll-overlay');
    this.el.$scrollOverlayInner = this.el.$scrollOverlay.children('.frzTbl-scroll-overlay__inner');

    this.el.$contentWrapper = this.el.$wrapper.children('.frzTbl-content-wrapper');
    this.el.$frozenColumnsWrapper = this.el.$contentWrapper.children('.frzTbl-frozen-columns-wrapper');
    this.el.$frozenColumnsInner = this.el.$frozenColumnsWrapper.children('.frzTbl-frozen-columns-wrapper__inner');
    this.el.$frozenColumnsLeft = this.el.$frozenColumnsInner.children('.frzTbl-frozen-columns-wrapper__columns--left');
    this.el.$frozenColumnsLeftSticky = this.el.$frozenColumnsLeft.children('.frzTbl-top-sticky');
    this.el.$frozenColumnsRight = this.el.$frozenColumnsInner.children('.frzTbl-frozen-columns-wrapper__columns--right');
    this.el.$frozenColumnsRightSticky = this.el.$frozenColumnsRight.children('.frzTbl-top-sticky');

    this.el.$fixedHeadingWrapperOuter = this.el.$contentWrapper.children('.frzTbl-fixed-heading-wrapper-outer');
    this.el.$fixedHeadingWrapper = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper');
    this.el.$fixedHeadingLeftColumn = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper__columns--left');
    this.el.$fixedHeadingRightColumn = this.el.$fixedHeadingWrapperOuter.children('.frzTbl-fixed-heading-wrapper__columns--right');
    this.el.$fixedHeadingInner = this.el.$fixedHeadingWrapper.children('.frzTbl-fixed-heading-wrapper__inner');

    this.el.$tableWrapper = this.el.$contentWrapper.children('.frzTbl-table-wrapper');
    this.el.$tableInner = this.el.$tableWrapper.children('.frzTbl-table-wrapper__inner');
    this.el.$tableWrapperSticky = this.el.$tableInner.children('.frzTbl-top-sticky');

    if( this.get_overflow_permission() ){
      this.el.$tableInner.removeClass('frzTbl-table-wrapper__inner--deflate');
      this.el.$table.css( 'min-width', $temp_wrapper.innerWidth() );

      this.antiscroll();

      // wheel/scroll
      $(window).on('scroll', $.proxy(this, 'page_scroll'));
      this.page_scroll({target: document});

      this.el.$wrapper.on('wheel', $.proxy(this, 'wrapper_wheel'));
      this.el.$wrapper.on('touchstart touchmove touchend', $.proxy(this, 'wrapper_touch'));
      this.el.$scrollOverlay.on('wheel scroll', $.proxy(this, 'scrollOverlay_wheel'));

      var affected = [
        this.el.$scrollOverlay,
        this.el.$tableWrapper,
        this.el.$fixedHeadingWrapper,
        this.el.$frozenColumnsWrapper,
      ];

      $.each(affected, function(i, $elm){
        $elm[0].scrollTop = 0;
        $elm[0].scrollLeft = 0;
      })

    }else{ // overflow not permitted
      this.el.$tableInner.addClass('frzTbl-table-wrapper__inner--deflate');
      this.el.$table.css( 'min-width', '' );
      this.el.$table.css( 'width', '100%' ); // table needs to be container width at least      

    }

    if( 1 > Math.abs( this.el.$wrapper.innerWidth() - this.el.$table.outerWidth() ) ){
      this.el.$wrapper.addClass('frzTbl--scrolled-to-left-edge frzTbl--scrolled-to-right-edge');
    }    

    this.sticky_heading();
    this.resize_clone_cells();
    this.grab_and_scroll();

    // window resize handler
    $window.on('resize.ft' + this.namespace , $.proxy(this, 'resize'));
    // image load handler
    $table[0].addEventListener('load', this.load_image, true);

    this.recordEnv();
  };

  FreezeTable.prototype.load_image = function(e){
    var ft = $(this).data('freezeTable'),    
        cell_resize = $.proxy(ft, 'cell_resize');

    if( e.target.tagName === 'IMG' ){
      cell_resize(e.target);
    }
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

          setTimeout(function(){ // async else click ev will fire now
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

  FreezeTable.prototype.build_heading = function() {
    if( this.get_breakpoint_options().heading ){
      var $heading = this.clone_table();
      $heading.find('tbody').remove();  
      this.tpl = this.tpl.replace(/{{heading}}/g, $heading[0].outerHTML);
    } else {
      this.tpl = this.tpl.replace(/{{heading}}/g, '');
    }
  };

  FreezeTable.prototype.sticky_heading = function() {
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

    $('> div > table', this.el.$frozenColumnsInner).each(function(){
      var $this = $(this);
      $('> thead', $this).remove();
      $this.css('margin-top', (heading_height + gap_top) + 'px');
    })

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
        ! $this.is('html') && 
        ! $this.is('body') &&
        overflow && 
        overflow !== 'visible' 
      ){
        $this.addClass('frzTbl-force-parent-overlow-visible');
      }
    });

  };

  FreezeTable.prototype.build_left = function() {
    var settings = this.get_breakpoint_options();
    
    if( ! settings.left ){
      this.tpl = this.tpl.replace( /{{left-columns}}/g, '' );
      this.tpl = this.tpl.replace( /{{left-columns-heading}}/g, '' );
      this.tpl = this.tpl.replace( /{{hide-left-columns-heading}}/g, 'frzTbl-fixed-heading-wrapper__columns--empty' );
      this.tpl = this.tpl.replace( /{{hide-left-column}}/g, 'frzTbl-frozen-columns-wrapper__columns--empty' );
      return;
    }

    var $left = this.clone_table();

    $left.find('td, th').each(function(){
      var $this = $(this);
      if( $this.index() >= settings.left ){
        $this.remove();
      }
    })
    this.tpl = this.tpl.replace( /{{left-columns}}/g, ($left[0].outerHTML || '') );

    $left_heading = $left.clone();
    $left_heading.find('tbody').remove();
    this.tpl = this.tpl.replace( /{{left-columns-heading}}/g, ($left_heading[0].outerHTML || '') );

    this.tpl = this.tpl.replace( /{{hide-left-column}}/g, '' );

    if( $('> thead > tr.wcpt-heading-row.wcpt-hide', this.el.$table).length ){
      this.tpl = this.tpl.replace( /{{hide-top-sticky}}/g, ' frzTbl-top-sticky--empty ' );
    }else{
      this.tpl = this.tpl.replace( /{{hide-top-sticky}}/g, '' );
    }

  };

  FreezeTable.prototype.build_right = function() {
    var settings = this.get_breakpoint_options();

    if( ! settings.right ){
      this.tpl = this.tpl.replace( /{{right-columns}}/g, '' );
      this.tpl = this.tpl.replace( /{{right-columns-heading}}/g, '' );
      this.tpl = this.tpl.replace( /{{hide-right-columns-heading}}/g, 'frzTbl-fixed-heading-wrapper__columns--empty' );
      this.tpl = this.tpl.replace( /{{hide-right-column}}/g, 'frzTbl-frozen-columns-wrapper__columns--empty' );
      return;
    }

    var $right = this.clone_table();

    $right.find('td, th').each(function(){
      var $this = $(this);
      if( $this.siblings().length - $this.index() >= settings.right ){
        $this.remove();
      }
    })
    this.tpl = this.tpl.replace( /{{right-columns}}/g, ($right[0].outerHTML || '') );

    $right_heading = $right.clone();
    $right_heading.find('tbody').remove();
    this.tpl = this.tpl.replace( /{{right-columns-heading}}/g, ($right_heading[0].outerHTML || '') );

    this.tpl = this.tpl.replace( /{{hide-right-column}}/g, '' );

    if( $('> thead > tr.wcpt-heading-row.wcpt-hide', this.el.$table).length ){
      this.tpl = this.tpl.replace( /{{hide-top-sticky}}/g, ' frzTbl-top-sticky--empty ' );
    }else{
      this.tpl = this.tpl.replace( /{{hide-top-sticky}}/g, '' );
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

    $('> div > table', this.el.$frozenColumnsInner).each(function(){
      var $this = $(this);
      $this.css('margin-top', (heading_height + gap_top) + 'px');
    })

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

    var heading_rect = this.el.$fixedHeadingWrapperOuter[0].getBoundingClientRect(),
        table_rect = this.el.$table[0].getBoundingClientRect();

    if( heading_rect.top == table_rect.top ){
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
    clearTimeout(this.scroll_clear);
    this.scroll_clear = setTimeout(
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

        // mode = 'scroll';
        mode = 'transform';    

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
      // -- fixed columns
      // this.el.$frozenColumnsInner[0].style.transform = 'translate3d(0, -' + scrollTop + 'px, 0)';
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

  FreezeTable.prototype.resize = function() {
    var _resize = $.proxy(this, '_resize');
    clearTimeout(this.resize_clear);
    this.resize_clear = setTimeout(_resize, 200);
  };

  FreezeTable.prototype._resize = function() {    
    var wrapperWidth = this.el.$wrapper.width();

    if( this.env.wrapperWidth !== wrapperWidth ){
      if( this.crossed_breakpoint() ){
        this.reload(this.get_options());
        return;
      }

      this.antiscroll();
      this.recordEnv();
    }
  };

  // throttles event handler, attempts '_cell_resize' every 300 ms
  FreezeTable.prototype.cell_resize = function() {    
    var _cell_resize = $.proxy(this, '_cell_resize');
    clearTimeout(this.cell_resize_timeout);
    this.cell_resize_timeout = setTimeout(_cell_resize, 200);

  };

  FreezeTable.prototype._cell_resize = function() {
    if( ! this.el.$table.hasClass('frzTbl-table') ){
      return;        
    }

    if( typeof this.el.$scrollOverlay == 'undefined' ){
      return;
    }

    var scrollLeft = this.el.$scrollOverlay[0].scrollLeft;      

    var $table = this.el.$table;

    $table.css({
      width     : '',
      minWidth  : '',
      height    : '',
    });    

    // manage overflow
    if( this.get_overflow_permission() ){
      this.el.$tableInner.removeClass('frzTbl-table-wrapper__inner--deflate');
      this.el.$tableInner.addClass('frzTbl-table-wrapper__inner--unrestrict-table-wrapper');

      var table_width = Math.max( this.el.$table.outerWidth(), this.el.$wrapper.innerWidth() );
      table_height = this.el.$table.outerHeight();

      if( table_width > this.el.$wrapper.width() ){
        ++table_width;
      }

      this.el.$tableInner.removeClass('frzTbl-table-wrapper__inner--unrestrict-table-wrapper');      

      // table cannot be narrower than FT container
      this.el.$table.css( 'min-width', table_width );

      var $affected = [
        this.el.$scrollOverlayInner,
        // this.el.$tableInner,
        this.el.$frozenColumnsInner
      ];
  
      if( ! this.get_breakpoint_options.wrapperHeight ){
        $affected.push( this.el.$wrapper );
      }
  
      $.each($affected, function(key, $elm){
        $elm.css({
          height: table_height,
          width: table_width,
        });
      });
  
      this.antiscroll();    
      this.el.$scrollOverlay[0].scrollLeft = scrollLeft;

    }else{
      this.el.$tableInner.addClass('frzTbl-table-wrapper__inner--deflate');
      this.el.$table.css( 'min-width', '' );
      this.el.$table.css( 'width', '100%' ); // table needs to be container width at least      

    }

    this.resize_clone_cells();
    this.recordEnv();
  };

  FreezeTable.prototype.antiscroll = function() {
    var $scrollOverlay = this.el.$scrollOverlay;

    // rebuild layout memory
    this.layout_memory = {
      scrollTop: $scrollOverlay[0].scrollTop = 0,
      scrollLeft: $scrollOverlay[0].scrollLeft = 0,
      scrollOverlayWidth: $scrollOverlay.width(),
      scrollOverlayInnerWidth: this.el.$scrollOverlayInner.width(),
    }

    // reposition scrollbar
    var overflow = this.el.$table.width() - this.el.$wrapper.width();
    if( overflow > 5 ){
      this.el.$wrapper.antiscroll();
    }
    this.el.$wrapper
      .children('> .frzTbl-antiscroll-wrap').remove().end()
      .children('.antiscroll-scrollbar-horizontal').wrap('<div class="frzTbl-antiscroll-wrap">');
  };

  FreezeTable.prototype.crossed_breakpoint = function() {
    return this.current_breakpoint() !== this.env.breakpoint
  };

  FreezeTable.prototype.recordEnv = function() {
    var _ = this; 
    _.env = {
      // window
      windowWidth: $(window).width(),
      windowHeight: $(window).height(),

      // wrapper
      wrapperWidth: this.el.$wrapper.width(),
      wrapperHeight: this.el.$wrapper.height(),

      // table
      tableWidth: _.el.$table.width(),
      tableHeight: _.el.$table.height(),

      // first cell
      firstCellWidth: _.el.$firstCell.width(),
      firstCellHeight: _.el.$firstCell.height(),

      // breakpoint
      breakpoint: _.current_breakpoint(),
    }
  };

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
    })

    return breakpoint;
  };

  FreezeTable.prototype.destroy = function() {
    this.clear_handlers();
    this.unwrap();
    this.el.$table.removeData('freezeTable');
  };

  FreezeTable.prototype.clear_handlers = function() {
    $(window).off('resize.ft' + this.namespace);
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
    var $table = this.el.$table;
    this.destroy();
     
    $table.data('freezeTable', new $.FreezeTable($table[0], options));
  };

  $.extend($.easing,
    {
      FreezeTable_easeOutQuad: function (x, t, b, c, d) {
        return -c *(t/=d)*(t-2) + b;
      },
    }
  );

});