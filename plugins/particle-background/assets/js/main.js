/**
 * Particleground demo
 * @author Jonathan Nicol - @mrjnicol
 */

document.addEventListener('DOMContentLoaded', function () {
  particleground(document.getElementById('particles'), {
    dotColor: full.dotcolor,
    lineColor: full.linecolor,
    density:9000
  });
  var intro = document.getElementById('pg-inner');
  intro.style.marginTop = - intro.offsetHeight / 2 + 'px';
}, false);



// jQuery plugin example:
/*$(document).ready(function() {
  $('#particles').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });
  $('.intro').css({
    'margin-top': -($('.intro').height() / 2)
  });
});
*/