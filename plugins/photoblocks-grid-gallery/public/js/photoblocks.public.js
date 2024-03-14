/*global jQuery*/
var PhotoBlocks = {};

//credits James Padolsey http://james.padolsey.com/
var qualifyURL = function (url) {
  var img = document.createElement("img");
  img.src = url; // set string url
  url = img.src; // get qualified url
  img.src = null; // no server request
  return url;
};

(function($) {
  $.fn.visible = function (partial) {

      if (!$(this).offset())
          return true;

      var $t = $(this),
          $w = $(window),
          viewTop = $w.scrollTop(),
          viewBottom = viewTop + $w.height(),
          _top = $t.offset().top,
          _bottom = _top + $t.height(),
          compareTop = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;

      return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

  };

  PhotoBlocks = function(conf) {
    this.$grid = null;
    this.$blocks = [];
    this.geometry = {
      width: 0,
      squareSize: 0,
      on: {
        before: null,
        after: null,
        refresh: null
      },
      debug: false
    };
    this.loaded_images = 0;
    this.err_images = 0;
    this.images_count = 0;
  
    this.mode = "grid";
    this.packery_init = false;

    this.settings = {
      selector: null,
      columns: 4,
      padding: 10,
      disable_below: 320,
      image_quality: 80,
      imageExtraWidth: 0,
      imageFactor: 1.5,
      lazy: false,
      mobile_layout: []
    };
    for (var a in conf) {
      this.settings[a] = conf[a];
    }

    this.effects = {
      fade: {
        animeOpts: {
          targets : ".pb-block",
          duration: function(t,i) {
            return 600 + i*75;
          },
          easing: 'easeOutExpo',
          delay: function(t,i) {
            return i*50;
          },
          opacity: {
            value: [0,1],
            easing: 'linear'
          },
        }	
      }
    };
    

    if (this.init()) {
      this.load_images(this.$blocks);
      this.setup_social();

      
    }
  };

  PhotoBlocks.prototype.setup_social = function () {
    var self = this;

    function block_text($block) {
      var text = {
        title: "",
        description: ""
      };
      
      if($block.find(".pb-title").length)
        text.title = $block.find(".pb-title").text();

        if($block.find(".pb-description").length)
        text.description = $block.find(".pb-description").text();

      return text;
    }

    self.social_actions = {
      facebook: function ($block) {        
        var text = block_text($block);
        var url = "https://www.facebook.com/dialog/feed?app_id=1447224948871585&"+
                            "link="+encodeURIComponent(location.href)+"&" +
                            "display=popup&"+
                            "name="+encodeURIComponent(text.title)+"&"+
                            "caption=&"+
                            "description="+encodeURIComponent(text.description)+"&"+
                            "ref=share&"+
                            "actions={%22name%22:%22View%20the%20gallery%22,%20%22link%22:%22"+encodeURIComponent(location.href)+"%22}&"+
                            "redirect_uri=http://www.final-tiles-gallery.com/facebook_redirect.html";

                var w = window.open(url, "photoblocks-share-facebook", "location=1,status=1,scrollbars=1,width=600,height=400");
                w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      },
      twitter: function ($block) {
        var text = block_text($block);
        var w = window.open("https://twitter.com/intent/tweet?url=" + encodeURI(location.href.split('#')[0]) + "&text=" + encodeURI(text.title + " " + text.description), "photoblocks-share-twitter", "location=1,status=1,scrollbars=1,width=600,height=400");
        w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      },
      houzz: function ($block) {
        var image = $block.find(".pb-image").attr("src");
        var text = block_text($block);
        var w = window.open("http://www.houzz.com/imageClipperUpload?imageUrl="+encodeURIComponent(qualifyURL(image))+"&title="+ text.title + " " + text.description +"&link=" + encodeURI(location.href), "photoblocks-share-houzz", "location=1,status=1,scrollbars=1,width=800,height=500");
        w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      },
      pinterest: function ($block) {
        var image = $block.find(".pb-image").attr("src");
        var text = block_text($block);
        var url = "http://pinterest.com/pin/create/button/?url=" + encodeURIComponent(location.href) + "&description=" + encodeURI(text.title + " " + text.description);

        url += ("&media=" + encodeURIComponent(qualifyURL(image)));

        var w = window.open(url, "photoblocks-share-pinterest", "location=1,status=1,scrollbars=1,width=600,height=400");
        w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      },
      google: function ($block) {
        var url = "https://plus.google.com/share?url=" + encodeURI(location.href);

        var w = window.open(url, "photoblocks-share-google", "location=1,status=1,scrollbars=1,width=600,height=400");
        w.moveTo((screen.width / 2) - (300), (screen.height / 2) - (200));
      }
    };

    this.$blocks.each(function (i, block) {
      var $block = $(block);

      $block.find(".pb-social button").each(function (i, button) {
        var $button = $(button);
        var social = $button.data("social");
        $button.click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          self.social_actions[social]($block);
        });
      });
    });    
  };

  PhotoBlocks.prototype.apply_effects = function ($block) {
    var anim = this.$grid.data("anim");

    var effect = this.effects[anim];
    effect.animeOpts.targets = $block.get(0);

    $block.css({
      transition: "none"
    });
    anime(effect.animeOpts);
    setTimeout(function () {
      $block.css({
        transition: "left .25s, top .45s, transform .25s"
      });
    }, 1000);    
  };

  PhotoBlocks.prototype.print_i = function(data) {
    if (this.settings.debug) console.info("PB > ", data);
  };

  PhotoBlocks.prototype.print_e = function(data) {
    if (this.settings.debug) console.error("PB > ", data);
  };

  PhotoBlocks.prototype.print_w = function(data) {
    if (this.settings.debug) console.warn("PB > ", data);
  };

  

  PhotoBlocks.prototype.cellSize = function() {    
    var self = this;
    var w = self.$grid.width();    
    if(w < self.settings.disable_below) {
      self.$grid.addClass("pb-disabled");
      this.mode.type = "stack";
    } else {
      self.$grid.removeClass("pb-disabled");
      this.mode.type = "grid";
    }

    if(this.mode == "stack")
      return this.geometry.width;

    var w =
      this.geometry.width -
      this.settings.padding * (this.settings.columns - 1);
    var size = (w / this.settings.columns);
    return size;
  };

  PhotoBlocks.prototype.init_packery = function() {
    var self = this;

    self.build();
    if(self.$grid.find(".pb-blocks").data('packery'))
      self.$grid.find(".pb-blocks").packery('destroy');

    self.$grid.find(".pb-blocks").css({
      width: "calc(100% + " + self.settings.padding + "px)"
    });

    var settings = {
      itemSelector: '.pb-block',
      gutter: self.settings.padding,      
      resize: false
    };

    if(this.mode.type != "stack") {
      settings.columnWidth = self.geometry.squareSize;
      settings.rowHeight = self.geometry.squareSize;
      self.$grid.find(".pb-blocks").packery(settings);
    } 

    this.packery_init = true;
  }

  PhotoBlocks.prototype.loaded_image = function () {
    var self = this;
    if(self.err_images == self.images_count) {
      self.$grid.append("<strong>Cannot load all images, check cache settings</strong>");
    }
  };

  PhotoBlocks.prototype.apply_alignments = function($image, img) {
    var $block = $image.parent();

    var bw = $block.width();
    var bh = $block.height();
    var iw = $image.width();
    var ih = $image.height();

    if(ih > bh) {
      switch($block.data("valign")) {
        case "top": 
          $image.css({
            top: 0,
            bottom: "auto"
          });
          break;
        case "bottom": 
          $image.css({
            top: "auto",
            bottom: 0
          });
          break;
        case "center": 
          $image.css({
            top: - ((ih - bh) / 2),
            bottom: "auto"
          });   
          break;
      }
    }
    if(iw > bw) {
      switch($block.data("halign")) {
        case "left": 
          $image.css({
            left: 0,
            right: "auto"
          });
          break;
        case "right": 
          $image.css({
            left: "auto",
            right: 0
          });
          break;
        case "center": 
          $image.css({
            left: - ((iw - bw) / 2),
            right: "auto"
          });   
          break;
      }
    }
  }

  PhotoBlocks.prototype.load_images = function($blocks) {  
    var self = this;
    $blocks.not(".pb-ready").each(function() {
      var $block = $(this);

      if(self.settings.lazy && ! $block.visible(true))
        return false;

      var type = $(this).data("type");

      if(type == "empty" || type == "text") {
        $block.addClass("pb-ready");
        self.apply_effects($block);
      }
      if(type == "image" || type == "post") {
        self.setImageSrc($block)
        var $image = $(this).find(".pb-image");
        var src = $image.attr("src");        

        var i = new Image();
        i.onload = function () {
          self.loaded_images++;
          self.loaded_image();
          $block.addClass("pb-ready");
          $block.data("image-width", i.width);
          $block.data("image-height", i.height);
          //console.log(i.src, $block.data("image-width"), $block.data("image-height"));

          if(! self.settings.resizer)
            self.apply_alignments($image, i);
          self.apply_effects($block);
        };
        i.onerror = function () {
          self.err_images++;
          $block.addClass("pb-ready-err");
          console.warn("Loading error", $block);
        };
        i.src = src;
      }      
    });
  };

  PhotoBlocks.prototype.setImageSrc = function($block) {
    var self = this;
    
    if($block.data("type") == "image" || $block.data("type") == "post") {
      var valign = $block.data("valign");
      var halign = $block.data("halign");
      var rowspan = $block.data("rowspan");
      var colspan = $block.data("colspan");
          
      var src = $block.find(".pb-image").data("pb-source");
      
      var width = self.getSnappedSize(
        colspan * self.geometry.squareSize
      );
      var height = self.getSnappedSize(
        rowspan * self.geometry.squareSize
      );      

      width *= self.settings.imageFactor;
      height *= self.settings.imageFactor;

      if(self.mode.type == "stack") {
        width = self.geometry.width;
        height = null; 
      }

      var resized_src = self.getImageUrl(
        src,
        width,
        height,
        valign,
        halign,
        self.settings.imageExtraWidth
      );

      $block.find(".pb-image").attr("src", resized_src).show();
    }  
  }

  PhotoBlocks.prototype.currentMode = function() {
    var self = this;
    var w = $(window).width();

    var mode = { type: "grid" };
    if(w < self.settings.disable_below)
      return { type: "stack" };
    
    
    return mode;
  }

  PhotoBlocks.prototype.build = function() {
    var self = this;
    var w = self.$grid.width();
    this.mode = self.currentMode();
    if(this.mode.type == "stack") {
      self.$grid.addClass("pb-disabled");
    } else {
      self.$grid.removeClass("pb-disabled");        
    }

    this.print_i("Gallery mode: " + this.mode.type);

    this.$blocks.not(".pb-filtered").each(function() {
      var $block = $(this);
      var rowspan = $block.data("rowspan");
      var colspan = $block.data("colspan");

      var width = self.getSnappedSize(
        colspan * self.geometry.squareSize
      );
      var height = self.getSnappedSize(
        rowspan * self.geometry.squareSize
      );

      //self.print_i("Calculated: " + width + "x" + height);

      if(self.mode.type == "stack") {        
        width = "100%";
        height = "auto";

        console.log("!");

        $block.css({
          position: "relative",
          marginBottom: self.settings.padding
        }).find(".pb-image").css({
          position: "static",
          width: "100%",
          height: "auto",
          position: "static"
        });
      }
      else if(self.mode.type == "grid") {
        
      } else if(self.mode.type == "mobile") {
        width = (w - ( (self.mode.layout.cols - 1) * self.settings.padding)) / self.mode.layout.cols;
        height = width; 
        /*if(self.mode.layout.square) {
          height = width;
        } else {
          height = w / width * height;
        }*/
        
        self.geometry.squareSize = width
      }

      self.print_i("build: " +  (width + "x" + height));

      $block.css({
        width: width,
        height: height
      });
      
      if(! self.settings.lazy)
        self.setImageSrc($block)
    });
    
  };

  PhotoBlocks.prototype.getImageUrl = function(
    src,
    width,
    height,
    valign,
    halign,
    exceeding_w
  ) {

    if(! this.settings.resizer)
      return src;


    var parts = [];

    var v = "c";
    if (valign == "top") v = "t";
    if (valign == "bottom") v = "b";

    var h = halign.substr(0, 1);

    //var w_ = colspan * 200 + exceeding_w;
    //var h_ = rowspan * 200;
    var w_ = width + exceeding_w;
    var h_ = height;

    parts.push("q=" + this.settings.image_quality);
    parts.push("src=" + src);

    parts.push("w=" + w_);
    if(h_)
      parts.push("h=" + h_);
    parts.push("a=" + v + h);
    parts.push("zc=4");
    return this.settings.resizer + "?" + parts.join("&");
  };

  PhotoBlocks.prototype.getSquareIndex = function(len) {
    return Math.round(len / this.geometry.squareSize);
  };

  PhotoBlocks.prototype.getSnappedSize = function(len) {    
    var x = this.getSquareIndex(len);
    return this.geometry.squareSize * x + this.settings.padding * (x - 1);
  };

  PhotoBlocks.prototype.init = function() {

    if(this.settings.on.before)
      this.settings.on.before();

    if (!this.settings.selector) {
      this.print_e("Null selector !");
      return false;
    }

    this.settings.imageFactor = parseFloat(this.settings.imageFactor);
    if(this.settings.imageFactor == 0 || isNaN(this.settings.imageFactor))
      this.settings.imageFactor = 1.5;

    this.$grid = $(this.settings.selector);

    if (this.$grid.length == 0) {
      this.print_e("Gallery element not found!");
      return;
    }
    
    this.$blocks = this.$grid.find(".pb-block");

    if (this.$blocks.length == 0) {
      this.print_w("Useless empty gallery?");
    }

    this.images_count = this.$blocks.find(".pb-image").length;

    this.$blocks.each(function () {
      var $block = $(this);
      $block.data("conf", {
        geometry: {
          colspan: $block.data("colspan"),
          rowspan: $block.data("rowspan"),
          col: $block.data("col"),
          row: $block.data("row")
        }
      });
      $block.data("previous_conf", $.extend({}, $block.data("conf")));

    });    

    //TODO if width is 0 delay and loop until width is gt 0
    this.geometry.width = this.$grid.width();
    this.geometry.squareSize = this.cellSize();

    this.print_i("width: " + this.geometry.width);
    this.print_i("squareSize: " + this.geometry.squareSize);

    var self = this;

    //self.build();
    if(location.hash.substr(0, 5) != "#pbf-" || location.hash == "#pbf-all")
      self.init_packery();

    $(window).resize(function() {
      self.resizeTO = setTimeout(function() {
        self.print_i("resizing gallery");
        var w = self.$grid.width();
        
        self.print_i("new gallery width: " + w);
        if (w != self.geometry.width) {
          clearInterval(self.resizeTO);
          self.print_i("gallery width changed, resizing");
          self.geometry.width = w;
          self.geometry.squareSize = self.cellSize();
          
          self.init_packery();

          if(self.settings.on.refresh)
            self.settings.on.refresh();
        }
      }, 500);
    });

    if(! self.settings.lazy) {
      self.load_images(self.$blocks);
    }
    

    if(this.settings.on.after)
      this.settings.on.after();

    return true;
  };
})(jQuery);
