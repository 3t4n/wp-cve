/******************************************************************************
 Galleria Fullscreen Theme
 Copyright (c) 2012, Petri Damstén
 Licensed under the MIT license.
******************************************************************************/

(function($) {

var fsg_last_post_id = "";
var fsg_map = undefined;
var fsg_map_layer = undefined;
var fsg_map_marker = undefined;

$(document).ready(function() { // DOM ready
  $("[data-imgid]", this).each(function() {
    $(this).click(fsg_show_galleria);
  });
  if($(".galleria-photobox").length != 0) {
    randomize_photos();
  }
  if($(".galleria-photolist").length != 0) {
    list_photos();
  }
  var hash = window.location.hash;
  if (hash.length == 0 && fullscreen_galleria_attachment) {
    hash = "#0";
  }
  if (hash.length > 0) {
    var postid = 'fsg_post_' + fullscreen_galleria_postid;
    var imgid = hash.substring(1);
    $('[data-imgid="' + imgid + '"][data-postid="' + postid + '"]', this).first().click();
  }
  $(document.body).on('post-load', function() {
    //console.log('post-load');
    $("[data-imgid]", this).each(function() {
      $(this).off('click').on('click', fsg_show_galleria);
    });
  });
});

fsg_resize = function(event) {
  var galleria = $("#galleria").data('galleria');
  if (galleria != undefined) {
    galleria.resize();
  }
  galleria = $(".galleria-photolist");
  if (galleria != undefined) {
    list_photos();
  }
}

$(window).on("orientationchange", function(event) {
  fsg_resize();
});

$(window).resize(function() { // window resized
  fsg_resize();
});

fsg_set_keyboard = function(event) {
  var galleria = $("#galleria").data('galleria');
  galleria.attachKeyboard({
    escape: function() {
      if ($('#galleria-map').is(":visible")) {
        $('.galleria-map-close').click();
      } else if ($('#galleria').is(":visible")) {
        $('.galleria-close').click();
      }
    },
    left: function() {
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.prev();
      galleria.exitIdleMode();
    },
    80: function() { // P = Previous
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.prev();
      galleria.exitIdleMode();
    },
    right: function() {
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.next();
      galleria.exitIdleMode();
    },
    space: function() {
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.next();
      galleria.exitIdleMode();
    },
    78: function() {  // N = Next
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.next();
      galleria.exitIdleMode();
    },
    83: function() { // S = Slideshow
      if (fsg_settings['image_nav']) {
        return;
      }
      galleria.setPlaytime(1500);
      galleria.playToggle();
    },
    77: function() { // M = Open map
      $('#fsg_map_btn').click();
    },
    70: function() { // F = Fullscreen
      galleria.toggleFullscreen();
      fsg_set_keyboard();
    }
  });
}

fsg_on_show = function(event) {
  var gallery = $("#galleria").data('galleria');

  if (fsg_settings['true_fullscreen']) {
    gallery.enterFullscreen();
  }
  if (fsg_settings['auto_start_slideshow']) {
    gallery.play();
  }
}

fsg_on_close = function(event) {
  var gallery = $("#galleria").data('galleria');

  if (gallery.isFullscreen()) {
    gallery.exitFullscreen();
  }
  if (gallery.isPlaying()) {
    gallery.pause();
  }
}

fsg_show_galleria = function(event) {
  //console.log("fsg_show_galleria");
  event.preventDefault();
  var elem = $("#galleria");
  var close = $("#close-galleria");
  elem.toggle();
  close.toggle();
  var postid = $(this).attr("data-postid");
  var imgid = $(this).attr("data-imgid");

  id = 0;
  for (var i = 0; i < fsg_json[postid].length; ++i) {
    if (fsg_json[postid][i]['id'] == imgid) {
      id = i;
      break;
    }
  }
  if (postid != fsg_last_post_id) {
    if (elem.data('galleria')) {
      // Set new data
      // Bit of a hack, but load does not have show param and show function
      // works purely after load
      elem.data('galleria')._options.show = id;
      elem.data('galleria').load(fsg_json[postid]);
      fsg_set_keyboard();
      fsg_on_show();
    } else {
      // Init galleria
      if  (!fsg_settings['show_thumbnails']) {
        var sheet = document.createElement('style')
        sheet.innerHTML = ".galleria-stage {bottom: 10px !important; } \
                           .galleria-thumbnails-container {height: 0px !important;}";
        document.body.appendChild(sheet);
      }
      elem.galleria({
        css: (fsg_settings['w3tc']) ? $('link').attr('href') : 'galleria-fs-' + fsg_settings['theme'] + '.css',
        dataSource: fsg_json[postid],
        show: id,
        showCounter: false,
        fullscreenDoubleTap: false,
        imageCrop: false,
        fullscreenCrop: false,
        maxScaleRatio: 1.0,
        showInfo: false,
        idleTime: Math.max(1000, parseInt(fsg_settings['overlay_time'])),
        thumbnails: fsg_settings['show_thumbnails'],
        autoplay: fsg_settings['auto_start_slideshow'],
        transition: fsg_settings['transition'],
        trueFullscreen: fsg_settings['true_fullscreen'],
        showImagenav: !fsg_settings['image_nav'],
        extend: function() {
          fsg_set_keyboard();
        }
      });
      fsg_on_show();
    }
    fsg_last_post_id = postid;
  } else {
    elem.data('galleria').show(id);
    fsg_set_keyboard();
    fsg_on_show();
  }
}

open_map = function(lat, long)
{
  $('#galleria-map').show();
  if (typeof fsg_map == 'undefined') {
    fsg_map = new ol.Map({
        target: 'galleria-map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          })
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([long, lat]),
          zoom: 16
        })
    });
    fsg_map.addControl(new ol.control.ZoomSlider());
  } else {
    fsg_map.getView().setZoom(16);
    fsg_map.getView().setCenter(ol.proj.fromLonLat([long, lat]));
  }

  if (typeof fsg_map_layer == 'undefined') {
    style = new ol.style.Style({
      image: new ol.style.Icon({
        anchor: [0.5, 1.0],
        src: '/wp-content/plugins/fullscreen-galleria/marker.svg'
      })
    });

    fsg_map_marker  = new ol.Feature({
      geometry: new ol.geom.Point(ol.proj.fromLonLat([long, lat]))
    });

    fsg_map_layer = new ol.layer.Vector({
      source: new ol.source.Vector({
        features: [fsg_map_marker],
      }),
      style: style,
   });
   fsg_map.addLayer(fsg_map_layer);
 } else {
   fsg_map_marker.setGeometry(new ol.geom.Point(ol.proj.fromLonLat([long, lat])));
 }
}

generate_small_cols = function(cols)
{
  var small = [];
  for (c in cols) {
    var v = Math.floor(cols[c] / 2);
    small.push(v);
    small.push((cols[c] % 2.0 == 0) ? v : v + 1);
  }
  return small;
}

list_photos = function()
{
  $(".galleria-photolist").each(function() {
      var ID = $(this).attr("id");
      var BORDER = fsg_photolist[ID]['border'];
      var TILE = fsg_photolist[ID]['tile'];
      var EXTLINKS = fsg_photolist[ID]['extlinks'];
      var FIXED = fsg_photolist[ID]['fixed'];
      var width = $(this).parent().width();
      var height = $(this).parent().height();
      var box = 0;
      var left = BORDER;
      var imgx = 0;
      var imgy = 0;
      var prev = 0;
      var max_col = 0;
      var COLS = fsg_photolist[ID]['cols'].split(',');
      var SMALLCOLS = ('smallcols' in fsg_photolist[ID]) ? 
          fsg_photolist[ID]['smallcols'].split(',') : generate_small_cols(COLS);
      COLS = (width <= 750) ? SMALLCOLS : COLS;

      $(this).html("");
      for(i in COLS) {
        COLS[i] = +COLS[i];
        max_col = Math.max(COLS[i], max_col);
      }

      if (FIXED == 'width') {
        box = (width - BORDER) / max_col - BORDER;
        if (box < TILE) {
          box = TILE;
        }
        left = (width - (max_col * (box + BORDER)) + BORDER) / 2;
      } // TODO: TILE for FIXED == 'height'

      var col_bottoms = new Array(max_col);
      col_bottoms.fill(0);

      var row = 0;
      var pic = 0;
      for (i in fsg_json[ID]) {
          var img = fsg_json[ID][i]['image'];
          var imgid = fsg_json[ID][i]['id'];
          var w = fsg_json[ID][i]['full'][1];
          var h = fsg_json[ID][i]['full'][2];
          var extlink = fsg_json[ID][i]['extlink'];

          if (row < COLS.length) {
            columns = COLS[row];
          } else {
            columns = COLS[COLS.length - 1];
          }

          if (FIXED == 'width') {
            var min = 1000000;
            var mini = 0;
            for (j = 0; j < columns; ++j) {
              if (col_bottoms[j] < min) {
                mini = j;
                min = col_bottoms[j];
              }
            }
            imgx = left + (mini * (box + BORDER));
          } else {
            var first = i - pic;
            var last = first + columns;
            for (var fullwidth = 0, j = first; j < last && j < fsg_json[ID].length; ++j) {
              fullwidth += fsg_json[ID][j]['full'][1] / fsg_json[ID][j]['full'][2];
            }
            if (last > fsg_json[ID].length) {
              n = last - fsg_json[ID].length;
              fullwidth += n;
            }
            box = (width - ((columns + 1) * BORDER)) * (w / h / fullwidth);
            mini = pic;
            min = col_bottoms[mini];
            //console.log(row, pic, columns, box, fsg_json[ID][i]);
            if (mini == 0) {
              imgx = BORDER;
            } else {
              imgx += BORDER + prev;
            }
            prev = box;
          }
          // - Find best img
          var a = ["thumbnail", "medium", "large", "full"];
          for (var s in a) {
            if (fsg_json[ID][i][a[s]][1] > box * window.devicePixelRatio) {
              img = fsg_json[ID][i][a[s]][0];
              w = fsg_json[ID][i][a[s]][1];
              h = fsg_json[ID][i][a[s]][2];
              break;
            }
          }

          imgy = col_bottoms[mini];
          //console.log(imgy, mini, box, w, h, BORDER, (box / w) * h + BORDER);
          col_bottoms[mini] += (box / w) * h + BORDER;

          if (EXTLINKS) {
            var $a = $('<a href="' + extlink + '">');
          } else {
            var $a = $('<a data-postid="' + ID + '" data-imgid="' + imgid + '" href="' + img + '">');
            $($a).click(fsg_show_galleria);
          }
          var d = 'animation-delay: ' + i /20 +'s; ';
          //console.log(row, pic, columns, imgx, imgy);
          var $img = $('<img style="'+ d +'left: ' + imgx + 'px; top: ' + imgy + 'px;" width="' + box +
          '" src="' + img + '">');
          $a.append($img);
          $(this).append($a);

          if (pic == columns - 1) {
            ++row;
            for (var j = pic + 1; j < max_col; ++j) {
              col_bottoms[j] = col_bottoms[pic];
              //console.log('xx', col_bottoms[j], j, col_bottoms[pic], pic, max_col);
            }
            pic = 0;
          } else {
            ++pic;
          }
      }
  });
}

randomize_photos = function()
{
  $(".galleria-photobox").each(function() {
    var ID = $(this).attr("id");
    var BORDER = fsg_photobox[ID]['border'];
    var COLS = fsg_photobox[ID]['cols'];
    var ROWS = fsg_photobox[ID]['rows'];
    var MAXTILES = fsg_photobox[ID]['maxtiles'];
    var TILE = fsg_photobox[ID]['tile'];
    var REPEAT = fsg_photobox[ID]['repeat'];
    var x = 0;
    var y = 0;
    var BOX = 0;
    var width = $(this).parent().width();
    var height = $(this).parent().height();
    //console.log(width, height, window.width, window.height);

    if (TILE > 0) {
      // calc rows and cols
      BOX = TILE + BORDER;
      COLS = Math.floor(width / BOX);
      ROWS = Math.floor(height / BOX);
      $(this).width(width + BORDER);
      $(this).height(height + BORDER);
      y = Math.floor(($(this).height() - (ROWS * BOX)) / 2);
    } else {
      $(this).width(width + BORDER);
      var BOX = Math.floor($(this).width() / COLS);
      $(this).height((BOX * ROWS) + BORDER);
      y = -BORDER;
    }
    x = Math.floor(($(this).width() - (COLS * BOX)) / 2);
    //console.log(TILE, $(this).width(), $(this).height(), COLS, ROWS, x, y, BOX);
    $(this).css('top', y);
    $(this).css('left', x);
    $(this).html('');

    //console.log(ID, BORDER, 'x', COLS, ROWS, BOX, MAXTILES);
    // init array
    var array = new Array(COLS);
    for (var i = 0; i < COLS; i++) {
      array[i] = new Array(ROWS);
      for (var j = 0; j < ROWS; j++) {
        array[i][j] = -1;
      }
    }
    x = 0;
    y = 0;
    var d = 0;
    var tiles = {};
    while (1) {
      // next free cell
      stop = false;
      while (array[x][y] != -1) {
        ++x;
        if (x >= COLS) {
          x = 0;
          ++y;
          if (y >= ROWS) {
            stop = true;
            break;
          }
        }
      }
      if (stop) {
        break;
      }
      // find max size
      var mx = 0;
      while ((x + mx) < COLS && array[x + mx][y] == -1) {
        ++mx;
      }
      var my = 0;
      while ((y + my) < ROWS && array[x][y + my] == -1) {
        ++my;
      }
      // mark array
      var m = Math.min(mx, my);
      var box = Math.min(MAXTILES, Math.floor(Math.random() * m) + 1);
      for (var i = 0; i < box; i++) {
        for (var j = 0; j < box; j++) {
          array[x + i][y + j] = d;
        }
      }
      tiles[d] = [box, x, y];
      ++d;
    }

    /* print array
    for (var i = 0; i < ROWS; i++) {
      s = i + '. - ';
      for (var j = 0; j < COLS; j++) {
        s += array[j][i] + ' ';
      }
      console.log(s);
    }
    */

    var p = 0;
    var b = 0;
    while (1) {
      // Get random tile starting from biggest
      r = Math.floor(Math.random() * d);
      b = r;
      all = (tiles[b][0] == -1);
      for (j = 0; j < d; ++j) {
        n = (r + j) % d;
        if (tiles[n][0] > tiles[b][0]) {
          b = n;
          all = false;
        }
      }
      if (all) {
        break;
      }
      // No random photo just next one...
      if (p + 1 >= fsg_json[ID].length) {
        if (!REPEAT) {
          break;
        }
        p = 0;
      }
      // Add photo div
      var photo = p + 1;
      var box = tiles[b][0];
      var x = tiles[b][1];
      var y = tiles[b][2];
      var size = Math.floor(box * BOX - 2 * BORDER);
      var img = fsg_json[ID][photo]['image'];
      var imgid = fsg_json[ID][photo]['id'];
      var w = fsg_json[ID][photo]['full'][1];
      var h = fsg_json[ID][photo]['full'][2];
      //console.log(b, box, y, x);
      var $div = $('<div style="width: ' + size + 'px; height: ' + size + 'px; top: ' + y * BOX +
                  'px; left: ' + x * BOX + 'px; margin: ' + BORDER + 'px;">');
      var $a = $('<a data-postid="' + ID + '" data-imgid="' + imgid + '" href="' + img + '">');
      $($a).click(fsg_show_galleria);
      // - Find best img
      var a = ["thumbnail", "medium", "large", "full"];
      for (var s in a) {
        min = Math.min(fsg_json[ID][photo][a[s]][1],
                      fsg_json[ID][photo][a[s]][2]);
        if (min > size) {
          img = fsg_json[ID][photo][a[s]][0];
          w = fsg_json[ID][photo][a[s]][1];
          h = fsg_json[ID][photo][a[s]][2];
          break;
        }
      }
      var min = Math.min(w, h);
      var m = size / min;
      w = w * m;
      h = h * m;
      var imgx = -Math.floor((w - size) / 2.0);
      var imgy = -Math.floor((h - size) / 2.0);
      var $img = $('<img style="left: ' + imgx + 'px; top: ' + imgy + 'px;" width="' + w +
                  '" height="' + h + '" src="' + img + '">');
      $a.append($img);
      $div.append($a);
      $(this).append($div);
      ++p;
      tiles[b][0] = -1;
    }
  });
  //$(window).resize();
}

}(jQuery));
