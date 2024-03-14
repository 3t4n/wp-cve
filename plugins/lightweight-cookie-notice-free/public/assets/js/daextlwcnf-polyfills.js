/**
 * This polyfill the remove() method in Internet Explorer 9 and higher.
 *
 * For more information see:
 *
 * - https://developer.mozilla.org/en-US/docs/Web/API/ChildNode/remove#Polyfill
 * - https://github.com/jserz/js_piece/blob/master/DOM/ChildNode/remove()/remove().md
 */
(function(arr) {

  'use strict';

  arr.forEach(function(item) {
    if (item.hasOwnProperty('remove')) {
      return;
    }
    Object.defineProperty(item, 'remove', {
      configurable: true,
      enumerable: true,
      writable: true,
      value: function remove() {
        this.parentNode.removeChild(this);
      },
    });
  });

})([Element.prototype, CharacterData.prototype, DocumentType.prototype]);