var wcsca = {
  init : function() {
    this.openIcon = '<i class="'+wcscaOpenIcon+'"></i>';
    this.closedIcon = '<i class="'+wcscaClosedIcon+'"></i>';
    this.cacheDom();
    this.initElements();
    jQuery(window).click(this.deligate);  
  },

  cacheDom : function() {
    this.$main   = jQuery('.widget_product_categories .product-categories');
    this.$topLvl = this.$main.children();
    this.$parent = this.$main.find('.cat-parent');
    this.$subCat = this.$main.find('.children');
  },

  appendIcon : function($el) {
    $el.find('a').first().after('<div class="wcsca-icon">'+this.closedIcon+'</div>');
    
  },

  initElements : function() {
    this.$subCat.hide();
    this.$topLvl.addClass('wcsca-top-lvl');
    this.$parent.each(function(){
      var $el = jQuery(this);
      wcsca.appendIcon($el);
      if ($el.is('.current-cat, .current-cat-parent')) {
        var $subCat = $el.children('.children');
        $subCat.parent().children('.wcsca-icon').html(wcsca.openIcon);
        $subCat.show().addClass('wcsca-expand');
      }
    });
  },

  deligate : function(e) {
    var $clicked = jQuery(e.target);
    var $parent  = ($clicked.parent().hasClass('wcsca-icon')) ? $clicked.parent().parent() : $clicked;
    var $subCat  = $parent.children('.children');
    if ($subCat.hasClass('wcsca-expand')) {
      wcsca.collapseEl($subCat, $parent);
    }
    else if ($parent.hasClass('cat-parent')) {
      wcsca.collapseAll($parent);
      wcsca.expandEl($subCat, $parent);
    }
  },

  expandEl : function($subCat, $parent) {
    $parent.children('.wcsca-icon').html(this.openIcon);
    $subCat.slideDown().addClass('wcsca-expand');;
  },

  collapseEl : function($subCat, $parent) {
    $parent.children('.wcsca-icon').html(this.closedIcon);
    $subCat.slideUp().removeClass('wcsca-expand');
  },

  collapseAll : function($parent) {
    if (!$parent.hasClass('wcsca-top-lvl')) { return false; }
    this.$subCat.slideUp().removeClass('wcsca-expand');
    this.$parent.children('.wcsca-icon').html(this.closedIcon);
  }
}

jQuery(window).on('load', function(){
  if (jQuery('.widget_product_categories .product-categories').length) {
    wcsca.init();
  }
})


