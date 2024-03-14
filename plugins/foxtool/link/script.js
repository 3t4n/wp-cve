// tab
function fttab(evt, tabname) {
  var i, x, sotab;
  x = document.getElementsByClassName("ftbox");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  sotab = document.getElementsByClassName("sotab");
  for (i = 0; i < x.length; i++) {
    sotab[i].className = sotab[i].className.replace(" sotab-select", "");
  }
  document.getElementById(tabname).style.display = "block";
  evt.currentTarget.className += " sotab-select";
  localStorage.setItem('selectedRank', tabname);
}
function ftSelectedRank() {
  var selectedRank = localStorage.getItem('selectedRank');
  if (selectedRank) {
    var sotab = document.querySelector('[onclick="fttab(event, \'' + selectedRank + '\')"]');
    if (sotab) {
      sotab.click();
    }
  }
}
window.onload = ftSelectedRank;
// check font chu
const fontSelect = document.getElementById('foxtool-font');
if(fontSelect !== null){
const fontDemo = document.getElementById('ft-font-demo');
fontDemo.style.fontFamily = fontSelect.value;
fontSelect.addEventListener('change', function() {
  const font = fontSelect.value;
  fontDemo.style.fontFamily = font;
});
}
// display none
function getStyle(x, styleProp) {
    if (x.currentStyle) {
        var y = x.currentStyle[styleProp];
    }
    else if (window.getComputedStyle) {
        var y = document.defaultView.getComputedStyle(x, null).getPropertyValue(styleProp);
    }
    return y;
}
function ftnone(e, div_name) {
    var el = document.getElementById(div_name);
    var display = el.style.display || getStyle(el, 'display');
    el.style.display = (display == 'none') ? 'block' : 'none';
    ftnone.el = el;
    if (e.stopPropagation) e.stopPropagation();
    e.cancelBubble = true;
    return false;
}
// lay images tu media
jQuery(document).ready(function($) {
    $('.ft-selec').click(function(e) {
        e.preventDefault();
        var inputId = $(this).data('input-id');
        openMediaUploader(inputId);
    });

    function openMediaUploader(inputId) {
        var customUploader = wp.media({
            title: 'Chọn hình ảnh',
            button: {
                text: 'Chọn'
            },
            multiple: false
        });

        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            var imageUrl = attachment.url;
            $('#' + inputId).val(imageUrl);
        });

        customUploader.open();
    }
});
// add code color editor
jQuery(document).ready(function($) {
    $('.fox-codex').each(function() {
        wp.codeEditor.initialize($(this), cm_settings);
    });
});
// kiem tra trang thai check
jQuery(document).ready(function($) {
    $('.toggle-checkbox').each(function() {
        var targetDiv = $('#' + $(this).data('target'));
        if ($(this).is(':checked')) {
            targetDiv.removeClass('noon');
        } else {
            targetDiv.addClass('noon');
        }
        $(this).change(function() {
            if ($(this).is(':checked')) {
                targetDiv.removeClass('noon');
            } else {
                targetDiv.addClass('noon');
            }
        });
    });
});
// thay ray keo qua lai
var sliders = document.querySelectorAll(".ftslide");
sliders.forEach(function (slider) {
  var output = document.getElementById("demo" + slider.dataset.index);
  output.innerHTML = slider.value;
  slider.oninput = function () {
    output.innerHTML = this.value;
  };
});
