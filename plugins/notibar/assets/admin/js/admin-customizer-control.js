jQuery( document ).ready(function()  {
  var adminCustomizer = function(){
    var selectBackgroundColor = new NjColorSelect({
      dom: document.getElementById('nj_color_select_bg'),
    });
    selectBackgroundColor.init();
  
    var selectTextColor = new NjColorSelect({
      dom: document.getElementById('nj_color_select_text'),
    });
    selectTextColor.init();
  
    var selectLbColor = new NjColorSelect({
      dom: document.getElementById('nj_color_select_lb'),
    });
    selectLbColor.init();
  
    var selectLbTextColor = new NjColorSelect({
      dom: document.getElementById('nj_text_color_select_lb'),
    });
    selectLbTextColor.init();
  
  
    //Start custom js
    jQuery('#nj_color_select_preset .nj_color_select').click(function () {
      jQuery('#nj_color_select_preset .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_preset_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active-first-time')
        }
      });
    });
  
    jQuery('#nj_color_select_bg .nj_color_select').click(function () {
      jQuery('#nj_color_select_bg .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_bg_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active-first-time')
        }
      });
    });
  
    jQuery('#nj_color_select_text .nj_color_select').click(function () {
      jQuery('#nj_color_select_text .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_text_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active-first-time')
        }
      });
    });
  
    jQuery('#nj_color_select_lb .nj_color_select').click(function () {
      jQuery('#nj_color_select_lb .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_lb_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active-first-time')
        }
      });
    });
  
    jQuery('#nj_text_color_select_lb .nj_color_select').click(function () {
      jQuery('#nj_text_color_select_lb .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_lb_text_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active-first-time')
        }
      });
    });
  
    jQuery('.nj-list-prese-color .type-circle').click(function () {
      jQuery('.nj-list-prese-color .type-circle').removeClass('type-circle-active')
      jQuery(this).addClass('type-circle-active')
      const dataColor = jQuery(this).data('value')
      const dataType = jQuery(this).data('type')
      const arrColor = dataColor.split(',')
  
      jQuery('#_customize-input-njt_nofi_preset_color').val(dataType).trigger('change')
  
      jQuery('#_customize-input-njt_nofi_bg_color').val(arrColor[0]).trigger('change')
      jQuery('#nj_color_select_bg .nj_color_button_select_bg').css({
        'background-color': arrColor[0]
      })
  
      jQuery('#_customize-input-njt_nofi_text_color').val(arrColor[2]).trigger('change')
      jQuery('#nj_color_select_text .nj_color_button_select_bg').css({
        'background-color': arrColor[2]
      })
  
      jQuery('#_customize-input-njt_nofi_lb_color').val(arrColor[1]).trigger('change')
      jQuery('#nj_color_select_lb .nj_color_button_select_bg').css({
        'background-color': arrColor[1]
      })
  
      jQuery('#_customize-input-njt_nofi_lb_text_color').val(arrColor[3]).trigger('change')
      jQuery('#nj_text_color_select_lb .nj_color_button_select_bg').css({
        'background-color': arrColor[3]
      })
  
    })
  
  
    jQuery('.nj_color_button_select_bg').click(function () {
      const idValue = jQuery(this).parent().parent().attr('id')
      jQuery("#" + idValue + " .nj_color_select").click()
    })
    // End custom js
    function NjColorSelect(options) {
      var defaults = {
        dom: null,
        defaultColor: null,
        text: {
          title: 'Choose a color:',
          buttonOk: 'Ok',
          buttonCancel: 'Cancel',
          buttonBack: '<span class="dashicons dashicons-arrow-left-alt"></span>'
        },
        colors: {
          "red": {
            default: '#f44336',
            list: {
              "red-50": "#ffebee",
              "red-100": "#ffcdd2",
              "red-200": "#ef9a9a",
              "red-300": "#e57373",
              "red-400": "#ef5451",
              "red-500": "#f44437",
              "red-600": "#e53935",
              "red-700": "#d33030",
              "red-800": "#c62929",
              "red-900": "#b71d1d",
              "red-a100": "#ff8a80",
              "red-a200": "#ff5353",
              "red-a400": "#ff1845",
              "red-a700": "#d50101",
            }
          },
          "pink": {
            default: '#e91e63',
            list: {
              "pink-50": "#fce4ec",
              "pink-100": "#f8bbd0",
              "pink-200": "#f48fb1",
              "pink-300": "#f06292",
              "pink-400": "#ec407a",
              "pink-500": "#E91E62",
              "pink-600": "#d81b60",
              "pink-700": "#c2185b",
              "pink-800": "#ad1457",
              "pink-900": "#880e4f",
              "pink-a100": "#ff80ab",
              "pink-a200": "#ff4081",
              "pink-a400": "#f50057",
              "pink-a700": "#c51162",
            }
          },
          "purple": {
            default: '#9c27b0',
            list: {
              "purple-50": "#f3e5f5",
              "purple-100": "#e1bee7",
              "purple-200": "#ce93d8",
              "purple-300": "#ba68c8",
              "purple-400": "#ab47bc",
              "purple-500": "#8c239e",
              "purple-600": "#8e24aa",
              "purple-700": "#7b1fa2",
              "purple-800": "#6a1b9a",
              "purple-900": "#4a148c",
              "purple-a100": "#ea80fc",
              "purple-a200": "#e040fb",
              "purple-a400": "#d500f9",
              "purple-a700": "#aa00ff",
            }
          },
          "deeppurple": {
            default: '#673ab7',
            list: {
              "deeppurple-50": "#ede7f6",
              "deeppurple-100": "#d1c4e9",
              "deeppurple-200": "#b39ddb",
              "deeppurple-300": "#9575cd",
              "deeppurple-400": "#7e57c2",
              "deeppurple-500": "#673AB8",
              "deeppurple-600": "#5e35b1",
              "deeppurple-700": "#512da8",
              "deeppurple-800": "#4527a0",
              "deeppurple-900": "#311b92",
              "deeppurple-a100": "#b388ff",
              "deeppurple-a200": "#7c4dff",
              "deeppurple-a400": "#651fff",
              "deeppurple-a700": "#6200ea",
            }
          },
          "indigo": {
            default: '#3f51b5',
            list: {
              "indigo-50": "#e8eaf6",
              "indigo-100": "#c5cae9",
              "indigo-200": "#9fa8da",
              "indigo-300": "#7986cb",
              "indigo-400": "#5c6bc0",
              "indigo-500": "#3F51B6",
              "indigo-600": "#3949ab",
              "indigo-700": "#303f9f",
              "indigo-800": "#283593",
              "indigo-900": "#1a237e",
              "indigo-a100": "#8c9eff",
              "indigo-a200": "#536dfe",
              "indigo-a400": "#3d5afe",
              "indigo-a700": "#304ffe",
            }
          },
          "blue": {
            default: '#2196F3',
            list: {
              "blue-50": "#E3F2FD",
              "blue-100": "#BBDEFB",
              "blue-200": "#90CAF9",
              "blue-300": "#64B5F6",
              "blue-400": "#42A5F5",
              "blue-500": "#2196F4",
              "blue-600": "#1E88E5",
              "blue-700": "#1976D2",
              "blue-800": "#1565C0",
              "blue-900": "#0D47A1",
              "blue-a100": "#82B1FF",
              "blue-a200": "#448AFF",
              "blue-a400": "#2979FF",
              "blue-a700": "#2962FF",
            }
          },
          "light": {
            default: '#03A9F4',
            list: {
              "light-50": "#E1F5FE",
              "light-100": "#B3E5FC",
              "light-200": "#81D4FA",
              "light-300": "#4FC3F7",
              "light-400": "#29B6F6",
              "light-500": "#03A9F5",
              "light-600": "#039BE5",
              "light-700": "#0288D1",
              "light-800": "#0277BD",
              "light-900": "#01579B",
              "light-a100": "#80D8FF",
              "light-a200": "#40C4FF",
              "light-a400": "#00B0FF",
              "light-a700": "#0091EA",
            }
          },
          "cyan": {
            default: '#00BCD4',
            list: {
              "cyan-50": "#E0F7FA",
              "cyan-100": "#B2EBF2",
              "cyan-200": "#80DEEA",
              "cyan-300": "#4DD0E1",
              "cyan-400": "#26C6DA",
              "cyan-500": "#00BCD5",
              "cyan-600": "#00ACC1",
              "cyan-700": "#0097A7",
              "cyan-800": "#00838F",
              "cyan-900": "#006064",
              "cyan-a100": "#84FFFF",
              "cyan-a200": "#18FFFF",
              "cyan-a400": "#00E5FF",
              "cyan-a700": "#00B8D4",
            }
          },
          "teal": {
            default: '#009688',
            list: {
              "teal-50": "#E0F2F1",
              "teal-100": "#B2DFDB",
              "teal-200": "#80CBC4",
              "teal-300": "#4DB6AC",
              "teal-400": "#26A69A",
              "teal-500": "#009688F2",
              "teal-600": "#00897B",
              "teal-700": "#00796B",
              "teal-800": "#00695C",
              "teal-900": "#004D40",
              "teal-a100": "#A7FFEB",
              "teal-a200": "#64FFDA",
              "teal-a400": "#1DE9B6",
              "teal-a700": "#00BFA5",
            }
          },
          "green": {
            default: '#4caf50',
            list: {
              "green-50": "#e8f5e9",
              "green-100": "#c8e6c9",
              "green-200": "#a5d6a7",
              "green-300": "#81c784",
              "green-400": "#66bb6a",
              "green-500": "#4CAF51",
              "green-600": "#43a047",
              "green-700": "#388e3c",
              "green-800": "#2e7d32",
              "green-900": "#1b5e20",
              "green-a100": "#b9f6ca",
              "green-a200": "#69f0ae",
              "green-a400": "#00e676",
              "green-a700": "#00c853",
            }
          },
          "lightgreen": {
            default: '#4caf50',
            list: {
              "lightgreen-50": "#F1F8E9",
              "lightgreen-100": "#DCEDC8",
              "lightgreen-200": "#C5E1A5",
              "lightgreen-300": "#AED581",
              "lightgreen-400": "#9CCC65",
              "lightgreen-500": "#8BC34A",
              "lightgreen-600": "#7CB342",
              "lightgreen-700": "#689F38",
              "lightgreen-800": "#558B2F",
              "lightgreen-900": "#33691E",
              "lightgreen-a100": "#CCFF90",
              "lightgreen-a200": "#B2FF59",
              "lightgreen-a400": "#76FF03",
              "lightgreen-a700": "#64DD17",
            }
          },
          "lime": {
            default: '#CDDC39',
            list: {
              "lime-50": "#F9FBE7",
              "lime-100": "#F0F4C3",
              "lime-200": "#E6EE9C",
              "lime-300": "#DCE775",
              "lime-400": "#D4E157",
              "lime-500": "#CDDC38",
              "lime-600": "#C0CA33",
              "lime-700": "#AFB42B",
              "lime-800": "#9E9D24",
              "lime-900": "#827717",
              "lime-a100": "#F4FF81",
              "lime-a200": "#EEFF41",
              "lime-a400": "#C6FF00",
              "lime-a700": "#AEEA00",
            }
          },
          "yellow": {
            default: '#FFEB3B',
            list: {
              "yellow-50": "#FFFDE7",
              "yellow-100": "#FFF9C4",
              "yellow-200": "#FFF59D",
              "yellow-300": "#FFF176",
              "yellow-400": "#FFEE58",
              "yellow-500": "#FFEB3C",
              "yellow-600": "#FDD835",
              "yellow-700": "#FBC02D",
              "yellow-800": "#F9A825",
              "yellow-900": "#F57F17",
              "yellow-a100": "#FFFF8D",
              "yellow-a200": "#FFFF00",
              "yellow-a400": "#FFEA00",
              "yellow-a700": "#FFD600",
            }
          },
          "amber": {
            default: '#FFC107',
            list: {
              "amber-50": "#FFF8E1",
              "amber-100": "#FFECB3",
              "amber-200": "#FFE082",
              "amber-300": "#FFD54F",
              "amber-400": "#FFCA28",
              "amber-500": "#FFC108",
              "amber-600": "#FFB300",
              "amber-700": "#FFA000",
              "amber-800": "#FF8F00",
              "amber-900": "#FF6F00",
              "amber-a100": "#FFE57F",
              "amber-a200": "#FFD740",
              "amber-a400": "#FFC400",
              "amber-a700": "#FFAB00",
            }
          },
          "orange": {
            default: '#FF9800',
            list: {
              "orange-50": "#FFF3E0",
              "orange-100": "#FFE0B2",
              "orange-200": "#FFCC80",
              "orange-300": "#FFB74D",
              "orange-400": "#FFA726",
              "orange-500": "#FF9801",
              "orange-600": "#FB8C00",
              "orange-700": "#F57C00",
              "orange-800": "#EF6C00",
              "orange-900": "#E65100",
              "orange-a100": "#FFD180",
              "orange-a200": "#FFAB40",
              "orange-a400": "#FF9100",
              "orange-a700": "#FF6D00",
            }
          },
          "deeporange": {
            default: '#FF5722',
            list: {
              "deeporange-50": "#FBE9E7",
              "deeporange-100": "#FFCCBC",
              "deeporange-200": "#FFAB91",
              "deeporange-300": "#FF8A65",
              "deeporange-400": "#FF7043",
              "deeporange-500": "#FF5723",
              "deeporange-600": "#F4511E",
              "deeporange-700": "#E64A19",
              "deeporange-800": "#D84315",
              "deeporange-900": "#BF360C",
              "deeporange-a100": "#FF9E80",
              "deeporange-a200": "#FF6E40",
              "deeporange-a400": "#FF3D00",
              "deeporange-a700": "#DD2C00",
            }
          },
          "brown": {
            default: '#795548',
            list: {
              "brown-50": "#EFEBE9",
              "brown-100": "#D7CCC8",
              "brown-200": "#BCAAA4",
              "brown-300": "#A1887F",
              "brown-400": "#8D6E63",
              "brown-500": "#795549",
              "brown-600": "#6D4C41",
              "brown-700": "#5D4037",
              "brown-800": "#4E342E",
              "brown-900": "#3E2723",
            }
          },
          "grey": {
            default: '#9E9E9E',
            list: {
              "grey-50": "#FAFAFA",
              "grey-100": "#F5F5F5",
              "grey-200": "#EEEEEE",
              "grey-300": "#E0E0E0",
              "grey-400": "#BDBDBD",
              "grey-500": "#9E9E9F",
              "grey-600": "#757575",
              "grey-700": "#616161",
              "grey-800": "#424242",
              "grey-900": "#212121",
            }
          },
          "bluegrey": {
            default: '#607D8B',
            list: {
              "white": '#ffffff',
              "bluegrey-50": "#ECEFF1",
              "bluegrey-100": "#CFD8DC",
              "bluegrey-200": "#B0BEC5",
              "bluegrey-300": "#90A4AE",
              "bluegrey-400": "#78909C",
              "bluegrey-500": "#607D8C",
              "bluegrey-600": "#546E7A",
              "bluegrey-700": "#455A64",
              "bluegrey-800": "#37474F",
              "bluegrey-900": "#263238",
            }
          },
          "black": '#000000'
        },
        customColors: {}
      }, st;
      if (options) {
        if (options.text) {
          defaults.text = Object.assign(defaults.text, options.text);
          options.text = defaults.text;
        }
        st = Object.assign(defaults, options);
      }
      st.colors = Object.assign(st.colors, st.customColors);
      if (!st.dom) return;
      /*---- BEGIN INIT ----*/
      var controller = document.createElement('div'),
        picker = document.createElement('div'),
        title = document.createElement('div'),
        color_list = document.createElement('div'),
        color_list_holder_wrap = document.createElement('div'),
        color_list_holder = document.createElement('div'),
        color_item = document.createElement('div'),
        color_item_child = document.createElement('div'),
        button_wrap = document.createElement('div'),
        button_ok = document.createElement('button'),
        button_cancel = document.createElement('button'),
        button_back = document.createElement('button'),
        button_back_wrap = document.createElement('div'),
        old_value = {
          colorValue: null,
          colorName: null,
          selector: null,
        },
        current_select = null,
        current_palette = null,
        largest_total_item = Object.keys(st.colors).length,
        open_popup_animation_name = 'openPopup' + (Math.floor((Math.random() * 100000) + 1)),
        animation = {
          popup: {
            id: Math.floor((Math.random() * 100000) + 1),
            name: {}
          }
        };
      animation.popup.dom = document.createElement('style');
      animation.popup.name.open = 'openPopup' + animation.popup.id;
      animation.popup.name.close = 'closePopup' + animation.popup.id;
      animation.popup.template = '@keyframes ' + animation.popup.name.open + '{0%{width: 0px; height: 0px; opacity: 0;visibility: visible;}100%{width: %width%;height: %height%;opacity: 1;visibility: visible;}}@keyframes ' + animation.popup.name.close + '{0%{width: %width%;height: %height%;opacity: 1;visibility: visible;}100%{width: 0px; height: 0px; opacity: 0;visibility: visible;}}';
      animation.popup.has_appended = false;
  
      controller.href = 'javascript:void(0)';
      controller.className = 'nj_color_select njt_nofi_none';
      picker.className = 'nj_color_popup nj_color_popup--hidden';
      title.className = 'nj_color_popup__title';
      color_list.className = 'nj_color_popup__list';
      color_list_holder_wrap.className = 'nj_color_popup__list_holder_wrap';
      color_list_holder.className = 'nj_color_popup__list nj_color_popup__list--holder';
      color_item.className = 'nj_color_popup__item';
      button_back_wrap.className = 'nj_color_popup__item';
      color_item_child.className = 'nj_color_popup__item_child';
      color_item.appendChild(color_item_child);
      button_wrap.className = 'nj_color_popup__buttons';
      button_ok.type = 'button';
      button_ok.className = 'nj_color_popup__button nj_color_popup__button--ok button button-primary';
      button_cancel.type = 'button';
      button_cancel.className = 'nj_color_popup__button nj_color_popup__button--cancel button button-small';
      button_back.type = 'button';
      button_back.className = 'nj_color_popup__button_back';
      button_ok.innerHTML = st.text.buttonOk;
      button_cancel.innerHTML = st.text.buttonCancel;
      button_back.innerHTML = st.text.buttonBack;
      button_back_wrap.appendChild(button_back);
  
      //Setup color
      color_list_holder_wrap.appendChild(color_list);
      var timing_delay = 0;
      Object.keys(st.colors).forEach(function (key) {
        if (typeof st.colors[key] == 'string') {
          var item = create_item(key, st.colors[key]);
          item.childNodes[0].addEventListener('click', choose_color);
          color_list.appendChild(item);
        } else {
          var d_color = (st.colors[key].default) ? st.colors[key].default : '',
            sub_list = document.createElement('div'),
            btn_back = button_back_wrap.cloneNode(true);
          sub_list.className = 'nj_color_popup__list nj_color_popup__list--sub';
          sub_list.appendChild(btn_back);
          btn_back.childNodes[0].addEventListener('click', function () { back_to_color_list(sub_list); });
          var sub_timing_delay = 0.03;
          Object.keys(st.colors[key].list).forEach(function (color_name) {
            var item = create_item(color_name, st.colors[key].list[color_name]);
            if (d_color == '') d_color = st.colors[key].list[color_name];
            sub_list.appendChild(item);
            item.childNodes[0].addEventListener('click', choose_color);
            sub_timing_delay += 0.03;
          });
          var item = create_item(key, d_color);
          color_list.appendChild(item);
          item.childNodes[0].addEventListener('click', function (e) { select_palette(sub_list) });
          color_list_holder_wrap.appendChild(sub_list);
          if (Object.keys(st.colors[key].list).length + 1 > largest_total_item) largest_total_item = Object.keys(st.colors[key].list).length + 1;
        }
        timing_delay += 0.03;
      });
  
      //Create holder for color list
      for (var i = 0; i < largest_total_item; i++) {
        color_list_holder.appendChild(color_item.cloneNode(true));
      }
  
      //Create popup
      button_wrap.appendChild(button_cancel);
      button_wrap.appendChild(button_ok);
      picker.appendChild(title);
      picker.appendChild(color_list_holder_wrap);
      color_list_holder_wrap.appendChild(color_list_holder);
      picker.appendChild(button_wrap);
      /*---- END INIT ----*/
  
      /*---- BEGIN PRIVATE EVENT ----*/
      button_ok.addEventListener('click', close_popup);
      button_cancel.addEventListener('click', function () {
        if (old_value.colorValue) {
          st.dom.dataset.colorName = old_value.colorName;
          st.dom.dataset.colorValue = old_value.colorValue;
          choose_color({
            target: {
              parentNode: old_value.selector
            }
          });
        }
        close_popup();
      });
      controller.addEventListener('click', function () {
        if (picker.className.split(' ').indexOf('nj_color_popup--hidden') > -1) {
  
          jQuery(this).parent().find('.nj_color_display_picker').show()
          jQuery(this).parent().find('.nj_color_display_picker').addClass('nj_color_display_show')
          open_popup();
        } else {
          jQuery(this).parent().find('.nj_color_display_picker').hide()
          jQuery(this).parent().find('.nj_color_display_picker').removeClass('nj_color_display_show')
          close_popup();
        }
      });
      // Code for Chrome, Safari and Opera
      picker.addEventListener("webkitAnimationEnd", open_completed);
      picker.addEventListener("animationend", open_completed);
  
      //When click on the color circle
      document.body.addEventListener('click', function (e) {
        if (!is_in_dom(e.target, st.dom)) {
          close_popup();
        }
      });
      function choose_color(e) {
        var parent = e.target.parentNode;
        if (current_select) current_select.className = remove_class('nj_color_popup__item--state-active', current_select);
        jQuery('.nj_color_popup__item').removeClass('nj_color_popup__item--state-active-first-time')
        parent.className = add_class('nj_color_popup__item--state-active', parent);
        st.dom.dataset.colorValue = parent.dataset.colorValue;
        st.dom.dataset.colorName = parent.dataset.colorName;
        current_select = parent;
        var selectBg = jQuery("#_customize-input-njt_nofi_bg_color").parents('#nj_color_select_bg').attr('data-color-value')
        var selectText = jQuery("#_customize-input-njt_nofi_text_color").parents('#nj_color_select_text').attr('data-color-value')
        var selectLb = jQuery("#_customize-input-njt_nofi_lb_color").parents('#nj_color_select_lb').attr('data-color-value')
        var selectTextLb = jQuery("#_customize-input-njt_nofi_lb_text_color").parents('#nj_text_color_select_lb').attr('data-color-value')
  
        if (selectBg) {
          jQuery('#_customize-input-njt_nofi_bg_color').val(selectBg).trigger('change')
  
          jQuery('#nj_color_select_bg .nj_color_button_select_bg').css({
            'background-color': selectBg
          })
        }
        if (selectText) {
          jQuery('#_customize-input-njt_nofi_text_color').val(selectText).trigger('change')
          jQuery('#nj_color_select_text .nj_color_button_select_bg').css({
            'background-color': selectText
          })
        }
        if (selectLb) {
          jQuery('#_customize-input-njt_nofi_lb_color').val(selectLb).trigger('change')
          jQuery('#nj_color_select_lb .nj_color_button_select_bg').css({
            'background-color': selectLb
          })
        }
        if (selectTextLb) {
          jQuery('#_customize-input-njt_nofi_lb_text_color').val(selectTextLb).trigger('change')
          jQuery('#nj_text_color_select_lb .nj_color_button_select_bg').css({
            'background-color': selectTextLb
          })
        }
      }
      //When click on the color circle has sub
      function select_palette(palette) {
        color_list.className = remove_class('nj_color_popup__list--visible', color_list);
        palette.className = add_class('nj_color_popup__list--visible', palette);
        current_palette = palette;
      }
      //When click on back button
      function back_to_color_list(palette) {
        color_list.className = add_class('nj_color_popup__list--visible', color_list);
        palette.className = remove_class('nj_color_popup__list--visible', palette);
        current_palette = null;
      }
      /*---- END PRIVATE EVENT ----*/
  
      /*---- BEGIN PRIVATE FUNCTION ----*/
      function create_item(color_name, color_value) {
        var new_item = color_item.cloneNode(true);
        new_item.dataset.colorName = color_name;
        new_item.dataset.colorValue = color_value;
        new_item.childNodes[0].innerHTML = color_name;
        new_item.childNodes[0].title = color_name;
        new_item.childNodes[0].style.backgroundColor = color_value;
        if (st.defaultColor && st.defaultColor == color_name) {
          st.dom.dataset.colorName = color_name;
          st.dom.dataset.colorValue = color_value;
          new_item.className = add_class('nj_color_popup__item--state-active', new_item);
          current_select = new_item;
        }
        return new_item;
      }
      function add_class(class_name, dom) {
        if (dom.className) {
          if (dom.className.split(' ').indexOf(class_name) > -1) {
            return dom.className;
          } else {
            return dom.className + ' ' + class_name;
          }
        } else {
          return class_name;
        }
      }
      function remove_class(class_name, dom) {
        if (dom.className) {
          if (dom.className.split(' ').indexOf(class_name) > -1) {
            var arr = dom.className.split(' ');
            arr.splice(arr.indexOf(class_name), 1);
            return arr.join(' ');
          } else {
            return dom.className;
          }
        } else {
          return dom.className;
        }
      }
      function is_in_dom(child, dom) {
        if (child != dom) {
          if ( child && child.tagName && child.tagName == 'BODY') {
            return false;
          } else {
            if ( child && child.parentNode ) {
              return is_in_dom(child.parentNode, dom);
            }
          }
        } else {
          return true;
        }
      }
      function open_popup() {
        picker.className = remove_class('nj_color_popup--hidden', picker);
        if (typeof st.dom.dataset.colorName !== 'undefined') {
          old_value.colorName = st.dom.dataset.colorName;
          old_value.colorValue = st.dom.dataset.colorValue;
          old_value.selector = current_select;
        }
        if (animation.popup.has_appended == false) {
          animation.popup.dom.innerHTML = animation.popup.template
            .replace('%width%', window.getComputedStyle(picker).getPropertyValue('width'))
            .replace('%width%', window.getComputedStyle(picker).getPropertyValue('width'))
            .replace('%height%', window.getComputedStyle(picker).getPropertyValue('height'))
            .replace('%height%', window.getComputedStyle(picker).getPropertyValue('height'));
          document.body.appendChild(animation.popup.dom);
          animation.popup.has_appended = true;
        }
        picker.style.animationName = animation.popup.name.open;
      }
      function close_popup() {
        if (current_palette) back_to_color_list(current_palette);
        picker.className = add_class('nj_color_popup--hidden', picker);
        title.className = remove_class('nj_color_popup__title--show', title);
        button_wrap.className = remove_class('nj_color_popup__buttons--show', button_wrap);
        color_list.className = remove_class('nj_color_popup__list--visible', color_list);
        picker.style.animationName = animation.popup.name.close;
      }
      function open_completed() {
        if (!picker.classList.contains('nj_color_popup--hidden')) {
          title.className = add_class('nj_color_popup__title--show', title);
          button_wrap.className = add_class('nj_color_popup__buttons--show', button_wrap);
          color_list.className = add_class('nj_color_popup__list--visible', color_list);
        }
      }
  
      function convertColor(color, format) {
        switch (format) {
          case 'rgb': return convertHexToRGB(color); break;
          default: return color; break;
        }
      }
  
      function convertHexToRGB(color) {
        return 'rgb(' + (parseInt(color.slice(1, 3), 16)) + ',' + (parseInt(color.slice(3, 5), 16)) + ',' + (parseInt(color.slice(5, 7), 16)) + ')';
      }
      /*---- END PRIVATE FUNCTION ----*/
  
      /*---- BEGIN PUCLIC ----*/
      return {
        init: function () {
          st.dom.className = add_class('nj_color_select__wrap', st.dom);
          st.dom.appendChild(controller);
          st.dom.appendChild(picker);
  
  
        },
        getColor: function (format) {
          if (format) {
            return {
              name: st.dom.dataset.colorName,
              value: convertColor(st.dom.dataset.colorValue, format)
            }
          } else {
            return {
              name: st.dom.dataset.colorName,
              value: st.dom.dataset.colorValue,
            }
          }
        }
      }
      /*---- END PUCLIC ----*/
    }
  
    /*---- Custom for some other handling----*/
  
    //Event Position Type
    jQuery(".njt-bt-position").on("click", function (e) {
      jQuery(".njt-bt-position").removeClass('active')
      jQuery('#_customize-input-njt_nofi_position_type').val(jQuery(this).data('position')).trigger('change')
      jQuery(this).addClass('active')
    })
  
    //Event Handle Button
  
    if (jQuery('#njt-handle-button').is(":checked")) {
      jQuery('#customize-control-njt_nofi_open_new_windown_control').show()
      jQuery('#customize-control-njt_nofi_lb_text_control').show()
      jQuery('#customize-control-njt_nofi_lb_url_control').show()
      jQuery('#customize-control-njt_nofi_lb_font_weight_control').show()
      jQuery('#_customize-input-njt_nofi_handle_button').val(1).trigger('change')
    } else {
      jQuery('#customize-control-njt_nofi_open_new_windown_control').hide()
      jQuery('#customize-control-njt_nofi_lb_text_control').hide()
      jQuery('#customize-control-njt_nofi_lb_url_control').hide()
      jQuery('#customize-control-njt_nofi_lb_font_weight_control').hide()
      jQuery('#_customize-input-njt_nofi_handle_button').val(0).trigger('change')
    }
  
    jQuery(".njt-handle-button-switch").on("click", function (e) {
      if (jQuery('#njt-handle-button').is(":checked")) {
        jQuery('#customize-control-njt_nofi_open_new_windown_control').show()
        jQuery('#customize-control-njt_nofi_lb_text_control').show()
        jQuery('#customize-control-njt_nofi_lb_url_control').show()
        jQuery('#customize-control-njt_nofi_lb_font_weight_control').show()
        jQuery('#_customize-input-njt_nofi_handle_button').val(1).trigger('change')
      } else {
        jQuery('#customize-control-njt_nofi_open_new_windown_control').hide()
        jQuery('#customize-control-njt_nofi_lb_text_control').hide()
        jQuery('#customize-control-njt_nofi_lb_url_control').hide()
        jQuery('#customize-control-njt_nofi_lb_font_weight_control').hide()
        jQuery('#_customize-input-njt_nofi_handle_button').val(0).trigger('change')
      }
    })
  
    jQuery(".njt-enable-bar-switch").on("click", function (e) {
      if (jQuery('#njt-enable-bar').is(":checked")) {
        jQuery('#_customize-input-njt_nofi_enable_bar').val(1).trigger('change')
      } else {
        jQuery('#_customize-input-njt_nofi_enable_bar').val(0).trigger('change')
      }
    })
  
    // action switch display mobile
    if (jQuery('#_customize-input-njt_nofi_content_mobile').is(":checked")) {
      jQuery("#customize-control-njt_nofi_text_mobile_control").show();
      jQuery("#customize-control-njt_nofi_handle_button_mobile").show();
      jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').show()
    } else {
      jQuery("#customize-control-njt_nofi_text_mobile_control").hide();
      jQuery("#customize-control-njt_nofi_handle_button_mobile").hide();
      jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').hide()
    }
  
    jQuery(".njt-content-mobile-button-switch").on("click", function (e) {
      if (jQuery('#_customize-input-njt_nofi_content_mobile').is(":checked")) {
        jQuery("#customize-control-njt_nofi_text_mobile_control").show();
        jQuery("#customize-control-njt_nofi_handle_button_mobile").show();
        jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').show()
        
        if (jQuery('#_customize-input-njt_nofi_handle_button_mobile').is(":checked")) {
          jQuery("#customize-control-njt_nofi_lb_text_mobile_control").show();
          jQuery("#customize-control-njt_nofi_lb_url_mobile_control").show();
          jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").show();
        }
      } else {
        jQuery("#customize-control-njt_nofi_text_mobile_control").hide();
        jQuery("#customize-control-njt_nofi_handle_button_mobile").hide();
        jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').hide()

        jQuery("#customize-control-njt_nofi_lb_text_mobile_control").hide();
        jQuery("#customize-control-njt_nofi_lb_url_mobile_control").hide();
        jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").hide();
      }
    })
  
    // action switch button mobile
    if (jQuery('#_customize-input-njt_nofi_handle_button_mobile').is(":checked") && jQuery('#_customize-input-njt_nofi_content_mobile').is(":checked")) {
      jQuery("#customize-control-njt_nofi_lb_text_mobile_control").show();
      jQuery("#customize-control-njt_nofi_lb_url_mobile_control").show();
      jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").show();
      jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').show()
    } else {
      jQuery("#customize-control-njt_nofi_lb_text_mobile_control").hide();
      jQuery("#customize-control-njt_nofi_lb_url_mobile_control").hide();
      jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").hide();
      jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').hide()
    }
  
    jQuery(".njt-handle-button-mobile-switch").on("click", function (e) {
      if (jQuery('#_customize-input-njt_nofi_handle_button_mobile').is(":checked")) {
        jQuery("#customize-control-njt_nofi_lb_text_mobile_control").show();
        jQuery("#customize-control-njt_nofi_lb_url_mobile_control").show();
        jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").show();
        jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').show()
      } else {
        jQuery("#customize-control-njt_nofi_lb_text_mobile_control").hide();
        jQuery("#customize-control-njt_nofi_lb_url_mobile_control").hide();
        jQuery("#customize-control-njt_nofi_open_new_windown_mobile_control").hide();
        jQuery('#customize-control-njt_nofi_lb_font_weight_mobile_control').hide()
      }
    })
  
  
    document.body.addEventListener('click', function (e) {
      if (jQuery('#nj_color_select_bg .nj_color_popup.nj_color_popup--hidden').length > 0) {
        jQuery('#nj_color_select_bg .nj_color_display_picker').hide()
      }
  
      if (jQuery('#nj_color_select_lb .nj_color_popup.nj_color_popup--hidden').length > 0) {
        jQuery('#nj_color_select_lb .nj_color_display_picker').hide()
      }
  
      if (jQuery('#nj_color_select_text .nj_color_popup.nj_color_popup--hidden').length > 0) {
        jQuery('#nj_color_select_text .nj_color_display_picker').hide()
      }
  
      if (jQuery('#nj_text_color_select_lb .nj_color_popup.nj_color_popup--hidden').length > 0) {
        jQuery('#nj_text_color_select_lb .nj_color_display_picker').hide()
      }
    })
  
    //Select default data
    jQuery("#nj_color_select_bg .nj_color_button_select_default").on("click", function (e) {
      const dataDefault = jQuery('#nj_color_select_bg #_customize-input-njt_nofi_bg_color').data('default')
      jQuery('#nj_color_select_bg .nj_color_button_select_bg').css({
        'background-color': dataDefault
      })
      jQuery('#nj_color_select_bg #_customize-input-njt_nofi_bg_color').val(dataDefault).trigger('change')
  
      jQuery('#nj_color_select_bg .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_bg_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active')
        } else {
          jQuery(this).removeClass('nj_color_popup__item--state-active')
        }
      });
    })
  
    jQuery("#nj_color_select_text .nj_color_button_select_default").on("click", function (e) {
      const dataDefault = jQuery('#nj_color_select_text #_customize-input-njt_nofi_text_color').data('default')
      jQuery('#nj_color_select_text .nj_color_button_select_bg').css({
        'background-color': dataDefault
      })
      jQuery('#nj_color_select_text #_customize-input-njt_nofi_text_color').val(dataDefault).trigger('change')
  
      jQuery('#nj_color_select_text .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_text_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active')
        } else {
          jQuery(this).removeClass('nj_color_popup__item--state-active')
        }
      });
    })
  
    jQuery("#nj_color_select_lb .nj_color_button_select_default").on("click", function (e) {
      const dataDefault = jQuery('#nj_color_select_lb #_customize-input-njt_nofi_lb_color').data('default')
      jQuery('#nj_color_select_lb .nj_color_button_select_bg').css({
        'background-color': dataDefault
      })
      jQuery('#nj_color_select_lb #_customize-input-njt_nofi_lb_color').val(dataDefault).trigger('change')
  
      jQuery('#nj_color_select_lb .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_lb_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active')
        } else {
          jQuery(this).removeClass('nj_color_popup__item--state-active')
        }
      });
    })

    jQuery("#nj_text_color_select_lb .nj_color_button_select_default").on("click", function (e) {
      const dataDefault = jQuery('#nj_text_color_select_lb #_customize-input-njt_nofi_lb_text_color').data('default')
      jQuery('#nj_text_color_select_lb .nj_color_button_select_bg').css({
        'background-color': dataDefault
      })
      jQuery('#nj_text_color_select_lb #_customize-input-njt_nofi_lb_text_color').val(dataDefault).trigger('change')
  
      jQuery('#nj_text_color_select_lb .nj_color_popup__item').each(function () {
        if (jQuery(this).attr('data-color-value') == jQuery('#_customize-input-njt_nofi_lb_text_color').val()) {
          jQuery(this).addClass('nj_color_popup__item--state-active')
        } else {
          jQuery(this).removeClass('nj_color_popup__item--state-active')
        }
      });
    })

    jQuery("#_customize-input-njt_nofi_bg_color").on('input',function(e){
      jQuery('#nj_color_select_bg .nj_color_button_select_bg').css({
        'background-color': jQuery(this).val()
      })
    });
   
    jQuery("#_customize-input-njt_nofi_text_color").on('input',function(e){
      jQuery('#nj_color_select_text .nj_color_button_select_bg').css({
        'background-color': jQuery(this).val()
      })
    });

    jQuery("#_customize-input-njt_nofi_lb_color").on('input',function(e){
      jQuery('#nj_color_select_lb .nj_color_button_select_bg').css({
        'background-color': jQuery(this).val()
      })
    });

    jQuery("#_customize-input-njt_nofi_lb_text_color").on('input',function(e){
      jQuery('#nj_text_color_select_lb .nj_color_button_select_bg').css({
        'background-color': jQuery(this).val()
      })
    });
  }

  var select2MultiplelogicDisplayPost = function(){
    const listPostSelected = wpNoFi.list_posts_selected
    listPostSelected.forEach(data => {
      let newOption = new Option(data.text, data.id, false, true);
      jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_post").append(newOption).trigger('change');
    });

    jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_post").select2({
      dropdownParent: jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post'),
      placeholder: "Select an option",
      ajax: {
        method: 'post',
        url: wpNoFi.admin_ajax,
        delay: 400,
        data: function (params) {
          var query = {
            action: "njt_nofi_query_page_post",
            nonce: wpNoFi.nonce,
            search: params.term,
            page: params.page || 1,
            type_query: 'post'
          }
          return query;
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
              results: data.data.results,
              pagination: {
                  more: (params.page * 10) < data.data.count_filtered
              }
          };
      }
      },
    });

    jQuery('.njt-nofi-select2-multiple-njt_nofi_list_display_post').on('change', function (e) {
      var data = jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_post").val();
      jQuery('#_customize-input-njt_nofi_list_display_post').val(data).trigger('change');
    });
  }

  var select2MultiplelogicDisplayPage= function(){
    const listPagesSelected = wpNoFi.list_pages_selected
    listPagesSelected.forEach(data => {
      let newOption = new Option(data.text, data.id, false, true);
      jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_page").append(newOption).trigger('change');
    });
   
    jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_page").select2({
      dropdownParent: jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page'),
      placeholder: "Select an option",
      ajax: {
        method: 'post',
        url: wpNoFi.admin_ajax,
        delay: 400,
        data: function (params) {
          var query = {
            action: "njt_nofi_query_page_post",
            nonce: wpNoFi.nonce,
            search: params.term,
            page: params.page || 1,
            type_query: 'page'
          }
          return query;
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          let results = data.data.results;
          if (data.data.count_posts <= 0) {
            results = [];
          }
          return {
              results: results,
              pagination: {
                  more: (params.page * 10) < data.data.count_filtered
              }
          };
      }
      }
    });

    jQuery('.njt-nofi-select2-multiple-njt_nofi_list_display_page').on('change', function (e) {
      var data = jQuery(".njt-nofi-select2-multiple-njt_nofi_list_display_page").val();
      jQuery('#_customize-input-njt_nofi_list_display_page').val(data).trigger('change');
    });
  }

  var select2logicDisplayPage = function(){
    jQuery('#_customize-input-njt_nofi_logic_display_page').on('change', function (e) {
      var data = jQuery("#_customize-input-njt_nofi_logic_display_page").val()

      if (data == 'dis_selected_page' || data == 'hide_selected_page' ) {
        jQuery('#customize-control-njt_nofi_list_display_page').show()
      } else {
        jQuery('#customize-control-njt_nofi_list_display_page').hide()
      }
    });

   const logicDisplayPageVal = jQuery("#_customize-input-njt_nofi_logic_display_page").val()
   jQuery("#_customize-input-njt_nofi_logic_display_page").val(logicDisplayPageVal).trigger('change')
    var data = jQuery("#_customize-input-njt_nofi_logic_display_page").val();
    if (data == 'dis_selected_page' || data == 'hide_selected_page' ) {
      jQuery('#customize-control-njt_nofi_list_display_page').show()
    } else {
      jQuery('#customize-control-njt_nofi_list_display_page').hide()
    }
  }

  var select2logicDisplayPost = function(){
    jQuery('#_customize-input-njt_nofi_logic_display_post').on('change', function (e) {
      var data = jQuery("#_customize-input-njt_nofi_logic_display_post").val()
      if (data == 'dis_selected_post' || data == 'hide_selected_post' ) {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post').show()
      } else {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post').hide()
      }
    });

    const logicDisplayPostVal = jQuery("#_customize-input-njt_nofi_logic_display_post").val()
    jQuery("#_customize-input-njt_nofi_logic_display_post").val(logicDisplayPostVal).trigger('change')

    var data = jQuery("#_customize-input-njt_nofi_logic_display_post").val();
    if (data == 'dis_selected_post' || data == 'hide_selected_post' ) {
      jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post').show()
    } else {
      jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post').hide()
    }
  }

  var select2devicesDisplay = function(){
    var data = [
      {
          id: 'all_devices',
          text: 'All devices'
      },
      {
          id: 'desktop',
          text: 'Only desktop'
      },
      {
          id: 'mobile',
          text: 'Only mobile'
      }
    ];

    jQuery(".njt-nofi-select2-njt_nofi_devices_display").select2({
      dropdownParent: jQuery('#njt-nofi-select2-modal-njt_nofi_devices_display'),
      data: data,
      placeholder: "Select an option",
    })

    jQuery('.njt-nofi-select2-njt_nofi_devices_display').on('change', function (e) {
      var data = jQuery(".njt-nofi-select2-njt_nofi_devices_display").val()
      jQuery('#_customize-input-njt_nofi_devices_display').val(data).trigger('change');
    });

    const logicDevicesDisplay = jQuery("#_customize-input-njt_nofi_devices_display").val()
    jQuery(".njt-nofi-select2-njt_nofi_devices_display").val(logicDevicesDisplay).trigger('change')

  }

  var select2IconDropdown = function(){
    jQuery('body').on('click', function (e) {
      if (!jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page .select2-container--default').hasClass('select2-container--open')) {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page .select2-container--default').addClass('njt-container-icon')
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page .select2-container--default').append('<span aria-hidden="true" class="njt-icon-dropdown"></span>')
      } else {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page .select2-container--default').removeClass('njt-container-icon')
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_page .njt-icon-dropdown').remove()
      }

      if (!jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post .select2-container--default').hasClass('select2-container--open')) {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post .select2-container--default').addClass('njt-container-icon')
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post .select2-container--default').append('<span aria-hidden="true" class="njt-icon-dropdown"></span>')
      } else {
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post .select2-container--default').removeClass('njt-container-icon')
        jQuery('#njt-nofi-select2-multiple-modal-njt_nofi_list_display_post .njt-icon-dropdown').remove()
      }
    });
  }
  
  adminCustomizer();
  select2MultiplelogicDisplayPage()
  select2logicDisplayPage()
  select2MultiplelogicDisplayPost()
  select2logicDisplayPost()
  //select2devicesDisplay()
  select2IconDropdown()
})