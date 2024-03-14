/*global PhotoBlocks*/
/*global PBToast*/
/*global ajaxurl*/
/*global jQuery*/
;(function(f){"use strict";"function"===typeof define&&define.amd?define(["jquery"],f):"undefined"!==typeof module&&module.exports?module.exports=f(require("jquery")):f(jQuery)})(function($){"use strict";function n(a){return!a.nodeName||-1!==$.inArray(a.nodeName.toLowerCase(),["iframe","#document","html","body"])}function h(a){return $.isFunction(a)||$.isPlainObject(a)?a:{top:a,left:a}}var p=$.scrollTo=function(a,d,b){return $(window).scrollTo(a,d,b)};p.defaults={axis:"xy",duration:0,limit:!0};$.fn.scrollTo=function(a,d,b){"object"=== typeof d&&(b=d,d=0);"function"===typeof b&&(b={onAfter:b});"max"===a&&(a=9E9);b=$.extend({},p.defaults,b);d=d||b.duration;var u=b.queue&&1<b.axis.length;u&&(d/=2);b.offset=h(b.offset);b.over=h(b.over);return this.each(function(){function k(a){var k=$.extend({},b,{queue:!0,duration:d,complete:a&&function(){a.call(q,e,b)}});r.animate(f,k)}if(null!==a){var l=n(this),q=l?this.contentWindow||window:this,r=$(q),e=a,f={},t;switch(typeof e){case "number":case "string":if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(e)){e= h(e);break}e=l?$(e):$(e,q);case "object":if(e.length===0)return;if(e.is||e.style)t=(e=$(e)).offset()}var v=$.isFunction(b.offset)&&b.offset(q,e)||b.offset;$.each(b.axis.split(""),function(a,c){var d="x"===c?"Left":"Top",m=d.toLowerCase(),g="scroll"+d,h=r[g](),n=p.max(q,c);t?(f[g]=t[m]+(l?0:h-r.offset()[m]),b.margin&&(f[g]-=parseInt(e.css("margin"+d),10)||0,f[g]-=parseInt(e.css("border"+d+"Width"),10)||0),f[g]+=v[m]||0,b.over[m]&&(f[g]+=e["x"===c?"width":"height"]()*b.over[m])):(d=e[m],f[g]=d.slice&& "%"===d.slice(-1)?parseFloat(d)/100*n:d);b.limit&&/^\d+$/.test(f[g])&&(f[g]=0>=f[g]?0:Math.min(f[g],n));!a&&1<b.axis.length&&(h===f[g]?f={}:u&&(k(b.onAfterFirst),f={}))});k(b.onAfter)}})};p.max=function(a,d){var b="x"===d?"Width":"Height",h="scroll"+b;if(!n(a))return a[h]-$(a)[b.toLowerCase()]();var b="client"+b,k=a.ownerDocument||a.document,l=k.documentElement,k=k.body;return Math.max(l[h],k[h])-Math.min(l[b],k[b])};$.Tween.propHooks.scrollLeft=$.Tween.propHooks.scrollTop={get:function(a){return $(a.elem)[a.prop]()}, set:function(a){var d=this.get(a);if(a.options.interrupt&&a._last&&a._last!==d)return $(a.elem).stop();var b=Math.round(a.now);d!==b&&($(a.elem)[a.prop](b),a._last=this.get(a))}};return p});

var PBAdmin = null;

var PBConsole = (function() {
  return {
    log: function() {
      var args = Array.prototype.slice.call(arguments);

      if (args.length) {
        var text = args.join(" | ");
        console.log(text);
      }
    }
  };
})();

var PBTicker = (function () {
  var TxtRotate = function(el, toRotate, period) {
    this.toRotate = toRotate;
    this.el = el;
    this.loopNum = 0;
    this.period = parseInt(period, 10) || 2000;
    this.txt = '';
    this.tick();
    this.isDeleting = false;
  };

  TxtRotate.prototype.tick = function() {
    var i = this.loopNum % this.toRotate.length;
    var fullTxt = this.toRotate[i];
  
    if (this.isDeleting) {
      this.txt = fullTxt.substring(0, this.txt.length - 1);
    } else {
      this.txt = fullTxt.substring(0, this.txt.length + 1);
    }
  
    this.el.innerHTML = '<span class="pb-text-wrap">'+this.txt+'</span>';
  
    var that = this;
    var delta = 100 - Math.random() * 100;
  
    if (this.isDeleting) { delta /= 2; }
  
    if (!this.isDeleting && this.txt === fullTxt) {
      delta = this.period;
      this.isDeleting = true;
    } else if (this.isDeleting && this.txt === '') {
      this.isDeleting = false;
      this.loopNum++;
      delta = 500;
    }
  
    setTimeout(function() {
      that.tick();
    }, delta);
  };

  return {
    init: function () {
      window.onload = function() {
        var elements = document.getElementsByClassName('txt-rotate');
        for (var i=0; i<elements.length; i++) {
          var toRotate = elements[i].getAttribute('data-rotate');
          var period = elements[i].getAttribute('data-period');
          if (toRotate) {
            new TxtRotate(elements[i], JSON.parse(toRotate), period);
          }
        }        
      };
    }
  };
})();
PBTicker.init();


(function($) {
  "use strict";

  PBAdmin = (function() {

    var _data = {};
    var _serialized_blocks = "";
    var _unsaved_changes = false;
    var currentAddBlockAction = "append";

    function switch_save_alarm(mode) {
      if(mode == "on") {
        $("a.publish").addClass("warn");
      } else {
        $("a.publish").removeClass("warn");
      }
    }

    function unsaved_changes() {
      
      _unsaved_changes = false;
      serializeSerializables();

      var $f = $(".js-serialize");

      var settings = {};
      $f.each(function() {
        if ($(this).attr("type") == "checkbox") {            
          settings[$(this).attr("name")] = this.checked ? "1" : "0";
        } else if ($(this).attr("name")) {
          settings[$(this).attr("name")] = $.trim($(this).val());
        }
      });

      for(var key in _data) {
        if(settings[key] != _data[key])
          _unsaved_changes = true;
      }

      if(! _unsaved_changes) {
        if(_serialized_blocks != JSON.stringify(PhotoBlocks.getBlocks()))
          _unsaved_changes = true;
      }
    
      if(_unsaved_changes) {
        switch_save_alarm("on");
      }

      $(".js-gallery-settings").val(JSON.stringify(settings));
      $(".js-blocks-settings").val(_serialized_blocks);
    }

    function events() {
      var self = this;

      $(window).on("beforeunload", function () {
        if(_unsaved_changes)
          return("Exit ?");
      });

      $("#pb-builder").scroll(function () {        
        var scroll = $(this).scrollTop();
        if (scroll >= 180) {
            $("#pb-builder").addClass("fixed-menu");
        } else {
            $("#pb-builder").removeClass("fixed-menu");
        }
      });

      $(".pb-submenu a").click(function (e) {
        e.preventDefault();
        var target = $(this).attr("href");
        $("#pb-builder").scrollTo(target, 500, {
          offset: -100
        });
      });

      /**
       * Open drawer
       */
      $("#wpbody").on("click", ".open-drawer", function() {
        $(this).addClass("active");
        var panel = $(this).data("drawer");
        PBAdmin.openDrawer(panel);
      });

      /**
       * Close drawer
       */
      $("#pb-drawer .close-drawer").click(function() {
        $("#pb-main .submenu li a").removeClass("active");
        $("#pb-drawer").css({
          right: -10 - $(window).width(),
          opacity: 0
        });
        $("#pb-drawer fieldset").removeClass("open");
        setTimeout(function() {
          $("#pb-drawer fieldset").hide();
        }, 600);
        $("#pb-builder").removeClass("open");
        unsaved_changes();
      });

      //var $_submenu = $(".artboard-menu").eq(0);
      //var $_grid = $("#photoblocks-grid");

      /**
       * Toggle captions
       */
      $(".show_ab_captions").click(function() {
        if (this.checked) $("#photoblocks-grid").addClass("show-captions");
        else $("#photoblocks-grid").removeClass("show-captions");
      });

      /**
       * Update blocks with default values
       */
      $("select.js-serialize").change(function () {
        
        if(this.selectedOptions[0].label.indexOf("premium") > 0) {
          PBAdmin.showPremium();
          this.selectedIndex = 0;
        }
      });
      $("select.js-serialize").change(PhotoBlocks.updateAllBlocks);
      $('input[type="checkbox"].js-serialize').click(
        PhotoBlocks.updateAllBlocks
      );
      $('input[type="checkbox"].js-serialize').click(unsaved_changes);
      
      $("select.js-serialize").change(unsaved_changes);
      $("input.js-serialize").blur(function () {
        unsaved_changes();
      });

      /**
       * Toggle fields
       */
      $("select.js-serialize").change(function () {
        
        $(".field").each(function () {

          /**
           * we need to find a field with a show rule
           * where the field code is in the left side of the rule
           */

          var field_code = $(this).data("code");
          var show_rule = $(this).data("show-if");

          if(show_rule) {            
            /**
             * I have a show rule
             */
            //console.log("I have a rule for " + field_code + ": ", show_rule);
            
            var sides = show_rule.split("==");
            var left = $.trim(sides[0]).split(" || ");
            var expected_value = $.trim(sides[1]);

            //console.log("expanded rule", left, expected_value);

            var selected_values = left.map(function (field) {
              return $("[name=\""+ field +"\"]").val();
            });

            //console.log("selected values", selected_values);
            if($.inArray(expected_value, selected_values) >= 0) {
              $(".field-code-" + field_code).removeClass("hidden");
            } else {
              $(".field-code-" + field_code).addClass("hidden");
            }
          }
        });

        $(".pb-section-group").each(function () {
          var hide = true;
          $(this).find(".field").each(function () {
            if(! $(this).hasClass("hidden")) {
              hide = false;
              return false;
            }
          });
          if(hide)
            $(this).hide();
          else
            $(this).show();
        });
      });      

      /**
       * Toggle drawer input groups
       */
      $(".toggle-group").click(function() {
        //var group = $(this).data("group");
        $(this).toggleClass("open");
        $(this)
          .parents(".group")
          .find(".field")
          .slideToggle();
      });

      /**
       * Add resolution
       */
      $(".js-res-add").click(function(e) {
        e.preventDefault();
      });      
    }

    function serializeSerializables() {
      serializeCheckboxes();
      serializeLists();
      serializeMobileLayouts();
    }

    function serializeCheckboxes() {
      PBConsole.log("serializeCheckboxes()");
      $(".js-checkbox-list").each(function() {
        var field = $(this).data("field");
        var all = [];
        $(this)
          .find(".js-checkbox")
          .each(function() {
            if(this.checked) {
              var val = $.trim($(this).val());
              if (val) all.push(val);
            }
          });

        $('[name="' + field + '"]').val(all.join(";"));        
      });
    }

    function serializeMobileLayouts() {  
      
    }

    function serializeLists() {
      PBConsole.log("serializeLists()");
      $(".js-dynamic-list").each(function() {
        var field = $(this).data("field");
        var all = [];
        $(this)
          .find(".js-list-item")
          .each(function() {
            var val = $.trim($(this).val());
            if (val) all.push(val);
          });

        $('[name="' + field + '"]').val(all.join(";"));
      });
    }

    function buildFilters() {
      var filters = $('[name="filters"]').val();
      if (filters) {
        filters.split(";").map(function(f) {
          var $li = $('<li class="pb-filter-item"></li>');
          $li.append(
            '<label><input name="image-filter" value="' +
              f +
              '" type="checkbox"> ' +
              f +
              "</label>"
          );
          $(".js-linked-list-filters").append($li);
        });
      }
    }

    function addFilter(field, value) {
      var v = value ? value : "";
      var $li = $('<li class="pb-list-item"></li>');
      $li.append(
        "<input class='js-list-item' type='text' value='" + v + "' />"
      );
      $li.append('<a href="#"><i class="pb-cancel-circled"></i></a>');
      $li.find("a").click(function(e) {
        e.preventDefault();
        $li.slideUp(function() {
          $(this).remove();
        });
        serializeSerializables();
      });
      $("#dynamic-list-" + field).append($li);
    }

    function addMobileLayout(field, value) {
        
    }

    function postLoadDuties() {
      $(".js-serialize-list").each(function() {
        var val = $(this).val();
        var code = $(this).attr("name");
        if (val) {
          val.split(";").map(function(v) {
            addFilter(code, v);
          });
        } else {
          addFilter(code);
        }        
      });

      $(".js-serialize-checkboxes").each(function () {
        var val = $(this).val();
        var $cnt = $(this).parents(".js-checkbox-list");
        if(val) {
          var values = val.split(";");
          values.map(function (v) {
            if($cnt.find("input[value=\"" + v + "\"]").get(0))
              $cnt.find("input[value=\"" + v + "\"]").get(0).checked = true;
          });
        }
      });

      $(".js-serialize-mobile-layout").each(function () {
        if(! $(this).val())
          return;
        var val = JSON.parse($(this).val());
        var field = $(this).attr("name");
        
        if(val) {
          $.each(val, function () {
            addMobileLayout(field, this)
          });
        }
      });

      $(".js-colpick").minicolors({
        inline: false,
        letterCase: "lowercase",
        opacity: true,
        position: "bottom left",
        format: "rgb",
        change: function(hex, opacity) {
          if ($(this).hasClass(".js-serialize")) PhotoBlocks.updateAllBlocks();
        },
        theme: "default"
      });

      /**
       * Toggle additional data
       */
      $(".js-update-additional-data").change(function () {
        var code = $(this).attr("name");
        var val = $(this).val();
        $(".additional-data-" + code + " > div").hide();
        $(".additional-data-" + code + " .additional-data-item-" + val).show();
      }).change();      

      events();

      buildFilters();
    }

    return {
      init: function() {
        if ($("#pb-builder").length) {
          PBAdmin.loadGallery();          
        }
      },
      setCurrentGrid: function(minWidth) {
        $("#photoblocks-grids .photoblocks-grid")
          .removeClass("selected")
          .each(function() {
            var conf = $(this).data(conf);
            if (conf.minWidth == minWidth) {
              $(this).addClass("selected");
            }
          });
      },
      addFilter: function(e) {
        var field = $(e).data("field");
        addFilter(field);
      },
      addMobileLayout: function(e) {
        var field = $(e).data("field");
        addMobileLayout(field)
      },
      switchTab: function(tab) {
        $(".pb-app-menu a.active").removeClass("active");
        $('a[data-target="#' + tab + '"]').addClass("active");

        $(".pb-form-slide").hide();
        $("#pb-panel-" + tab).show();
        $(".pb-submenu").hide();
        $(".pb-sub-" + tab).show();
        $("#pb-builder").scrollTo(0);
        return false;
      },
      popup: function(id, off) {
        if (!off) $("#modal-" + id).fadeIn(100);
        else $("#modal-" + id).fadeOut(100);
      },
      deleteAllBlocks: function() {
        PhotoBlocks.deleteAllBlocks();
        PBAdmin.popup("confirm-deletion", true);
      },
      save: function() {
        if ($(".publish").hasClass("loading")) return;

        $(".publish").addClass("loading");

        serializeSerializables();

        _serialized_blocks = JSON.stringify(PhotoBlocks.getBlocks());
        
        var data = {
          action: "pb_save_gallery",
          blocks: _serialized_blocks,
          photoblocks: $("#photoblocks").val()
        };

        var $f = $(".js-serialize");

        var settings = {};
        $f.each(function() {
          if ($(this).attr("type") == "checkbox") {            
            settings[$(this).attr("name")] = this.checked ? "1" : "0";
          } else if ($(this).attr("name")) {
            settings[$(this).attr("name")] = $.trim($(this).val());
          }
        });

        _data = settings;
        data.settings = JSON.stringify(settings);
        data.id = $("[name=\"id\"]").val();

        $.post(ajaxurl, data, function(r) {
          if (r.success) {
            $("[name=\"id\"]").val(r.id);
            $(".publish").removeClass("loading");
            _unsaved_changes = false;
            PBToast.confirm("Gallery saved");
            switch_save_alarm("off");
          } else {
            PBToast.error("Unable to save: " + r.message);
            $(".publish").removeClass("loading");
          }
        });
      },
      loadGallery: function() {
        PBConsole.log("loading gallery");
        var self = this;
        self.data = {};
        var data = {
          action: "pb_load_gallery",
          id: $("[name=\"id\"]").val(),
          photoblocks: $("#photoblocks").val()
        };
        $.post(ajaxurl, data, function(r) {
          PBConsole.log("gallery loaded");          
          _data = r.data;
          _serialized_blocks = JSON.stringify(r.blocks);
          
          for (var p in r.data) {
            var $el = $("[name=\"" + p + "\"]");
            if ($el.attr("type") == "checkbox") {
              $el.get(0).checked = r.data[p] == "1";
            }
            $el.val(r.data[p]);
          }

          if (!r.data.name) $("#modal-name").show();

          var $grid = $(".photoblocks-grid");
          PhotoBlocks.init($grid);
          PhotoBlocks.setBlocks(r.blocks);

          if(_data.google_font_key)
            PBAdmin.listGoogleFonts(_data.google_font_key);
            
          $(".js-gallery-settings").val(JSON.stringify(_data));
          $(".js-blocks-settings").val(_serialized_blocks);

          postLoadDuties();
        });
      },      
      setGalleryName: function() {
        var name = $.trim($(".wiz-gallery-name").val());
        if (name) {
          $(".pb-modal").fadeOut();
        }
        $('[name="name"]').val(name);
      },
      startupGallery: function() {
        var name = $.trim($(".wiz-gallery-name").val());
        var cols = $(".wiz-gallery-cols").val();
        if (name) {
          $(".pb-modal").fadeOut();
        }
        $('[name="name"]').val(name);
      },
      openDrawer: function(panel) {
        $("#pb-main .submenu li a").removeClass("active");

        $("#pb-drawer").css({
          display: "block",
          right: 0,
          opacity: 1
        });
        $("#" + panel)
          .addClass("open")
          .show();
        $("#pb-builder").addClass("open");
      },
      applyAlignments: function(align) {
        $("[name=\"caption_title_position_h\"]").val(align.title[0]);
        $("[name=\"caption_title_position_v\"]").val(align.title[1]);
        $("[name=\"caption_description_position_h\"]").val(align.description[0]);
        $("[name=\"caption_description_position_v\"]").val(align.description[1]);
        $("[name=\"social_position_h\"]").val(align.social[0]);
        $("[name=\"social_position_v\"]").val(align.social[1]);
        unsaved_changes();
        //PBToast.confirm("Alignments applied, changes not yet saved.");
      },
      showPremium: function () {
        PBAdmin.popup("premium");
      },
      deleteSelectedBlock: function () {
        PhotoBlocks.deleteSelectedBlock();
        PBAdmin.popup("bulk", true);
      },
      addFilters: function() {
       
        $("#modal-bulk .filters input:checked").each(function(i, f) {
          var filter = $(f).val();
          $(".pb-selected").each(function () {
            if(! $(this).data("conf").filters)
              $(this).data("conf").filters = [];

            if($.inArray(filter, $(this).data("conf").filters) < 0)
              $(this).data("conf").filters.push(filter);

            $(this).data("pb-set-image-filters")();
          });
        });

        PBAdmin.popup("bulk", true);
      },
      removeFilters: function () {
        $(".pb-selected").each(function () {
          $(this).data("conf").filters = [];
          $(this).data("pb-set-image-filters")();
        });
        PBAdmin.popup("bulk", true);
      },
      replaceFilters: function() {
        $("#modal-bulk .filters input:checked").each(function(i, f) {
          var filter = $(f).val();
          $(".pb-selected").each(function () {
            $(this).data("conf").filters = [];
            $(this).data("conf").filters.push(filter);
            $(this).data("pb-set-image-filters")();
          });
        });

        PBAdmin.popup("bulk", true);
      },      
      openBulk: function () {
        $("#modal-bulk .blocks").empty();
        
        


        $(".pb-selected").each(function (i) {
          $("#modal-bulk .without").hide();
          $("#modal-bulk .with").show();

          var $p = $("<div class='preview-block'></div>");
          var conf = $(this).data("conf");
          if(conf.type == "image") {
            $p.css({
              backgroundImage: "url(" + conf.thumbnail + ")"
            });
          }
          if(conf.type == "empty") {
            $p.text("<empty>");
          }
          if(conf.type == "text") {
            $p.text("Text...");
          }
          $("#modal-bulk .blocks").append($p);
          $("#modal-bulk .pb-count").text(i + 1);
        });         
        PBAdmin.popup("bulk");
      },
      addBlocks: function(type) {
        var position = $(".js-new-blocks-position").val();
        if(type == 'empty')
          PhotoBlocks.addEmptyBlock(position);
        if(type == 'image')
          PhotoBlocks.addImages(position);
        
        
      },

      
    };
  })();

  $(function() {
    PBAdmin.init();    

    $(".pb-badge").click(function () {
      location.href = pb_trial_url;
    });

    $("#premium-facts").click(function () {
      location.href = $(this).data("link");
    });

    $("table.photoblocks .trash a").click(function (e) {
      e.preventDefault();
      var del = $(this).attr("href");
      if(confirm("Confirm deleting the gallery?")) {
        location.href = del;
      } else {
        return false;
      }
    });

  });

  $(document).ready(function(){
    $('.copy-photoblocks-shortcode').click(function (e) {
      e.preventDefault();
      var gallery_shortcode = $(this).parent().find('input');
      gallery_shortcode.focus();
      gallery_shortcode.select();
      document.execCommand("copy");
      $(this).next('span').text('Shortcode copied');
      $('.copy-photoblocks-shortcode').not($(this)).parent().find('span').text('');

    });
  });

})(jQuery);
