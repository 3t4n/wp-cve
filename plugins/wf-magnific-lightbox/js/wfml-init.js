(function($) {

  'use strict';

  if (typeof(WfmlOptions) !== 'undefined' && WfmlOptions.lang) {
    var WfmlTexts;
    // Load translations (if any). Loaded after jquery.magnific-popup.js and before first initialization code.
    if (WfmlOptions.lang == 'de') {
      WfmlTexts = {
        tClose: 'Schließen (Esc)',
        tLoading: 'Laden...',
        tPrev: 'Vorheriges Bild (Linke Pfeiltaste)',
        tNext: 'Nächstes Bild (Rechte Pfeiltaste)',
        tCounter: '%curr% von %total%',
        tImageError: '<a href="%url%">Das Bild</a> konnte nicht geladen werden.',
        tAjaxError: '<a href="%url%">Der Inhalt</a> konnte nicht geladen werden.'
      };
    } else if (WfmlOptions.lang == 'it') {
      WfmlTexts = {
        tClose: 'Chiudi (Esc)',
        tLoading: 'Caricamento...',
        tPrev: 'Precedente',
        tNext: 'Successiva',
        tCounter: '%curr% di %total%',
        tImageError: 'Non è possiblie caricare <a href="%url%">l\'immagine</a>.',
        tAjaxError: 'Non è possiblie caricare <a href="%url%">il contenuto</a>.'
      };
    } else if (WfmlOptions.lang == 'fr') {
      WfmlTexts = {
        tClose: 'Fermer (Esc)',
        tLoading: 'Chargement...',
        tPrev: 'Image précédente (flèche gauche)',
        tNext: 'Image suivante (flèche droite)',
        tCounter: '%curr% de %total%',
        tImageError: '<a href="%url%">L\'image</a> n\'a pas pu être chargée.',
        tAjaxError: '<a href="%url%">Le contenu</a> n\'a pas pu être chargée.'
      };
    } else if (WfmlOptions.lang == 'pt') {
      WfmlTexts = {
        tClose: 'Fechar',
        tLoading: 'Carregando...',
        tPrev: 'Anterior',
        tNext: 'Próxima',
        tCounter: '%curr% de %total%',
        tImageError: '<a href="%url%">A imagem</a> não pode ser carregada.',
        tAjaxError: '<a href="%url%">Arquivo</a> não encontrado.'
      };
    } else if (WfmlOptions.lang == 'ru') {
      WfmlTexts = {
        tClose: 'Закрыть',
        tLoading: 'Загрузка...',
        tPrev: 'Предыдущее изображение',
        tNext: 'Следующее изображение',
        tCounter: '%curr% из %total%',
        tImageError: 'Не удалось загрузить <a href="%url%">изображение</a>.',
        tAjaxError: 'Не удалось загрузить <a href="%url%">содержимое страницы</a>.'
      };
    } else {
      WfmlTexts = {
        tClose: 'Close (Esc)',
        tLoading: 'Loading...',
        tPrev: 'Previous (Left arrow key)',
        tNext: 'Next (Right arrow key)',
        tCounter: '%curr% of %total%',
        tImageError: '<a href="%url%">The image</a> could not be loaded.',
        tAjaxError: '<a href="%url%">The content</a> could not be loaded.'
      };
    }

    $.extend(true, $.magnificPopup.defaults, {
      tClose: WfmlTexts.tClose, // Alt text on close button
      tLoading: WfmlTexts.tLoading, // Text that is displayed during loading. Can contain %curr% and %total% keys
      gallery: {
        tPrev: WfmlTexts.tPrev, // Alt text on left arrow
        tNext: WfmlTexts.tNext, // Alt text on right arrow
        tCounter: WfmlTexts.tCounter // Markup for "1 of 7" counter
      },
      image: {
        tError: WfmlTexts.tImageError // Error message when image could not be loaded
      },
      ajax: {
        tError: WfmlTexts.tAjaxError // Error message when ajax request failed
      }
    });
  }

  var imageMarkup = '<div class="mfp-figure">' +
    '<div class="mfp-close"></div>' +
    '<div class="mfp-img"></div>' +
    '<div class="mfp-bottom-bar">' +
    '<div class="mfp-title"></div>' +
    '<div class="mfp-description"></div>' +
    '<div class="mfp-copyright"></div>' +
    '<div class="mfp-counter"></div>' +
    '</div>' +
    '</div>';

  var imgSelector = 'a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"]';

  function generateRelImgSelector(rel) {
    return 'a[href*=".jpg"][rel=' + rel + '], a[href*=".jpeg"][rel=' + rel + '], a[href*=".png"][rel=' + rel + '], a[href*=".gif"][rel=' + rel + ']';
  }

  function titleSrc(item) {
    if (item.el.find('img').attr('data-headline')) {
      return item.el.find('img').attr('data-headline');
    } else if (item.el.find('img').attr('data-image-title')) {
      return item.el.find('img').attr('data-image-title');
    } else if (item.el.attr('data-headline')) {
      return item.el.attr('data-headline');
    } else if (item.el.attr('data-image-title')) {
      return item.el.attr('data-image-title');
    }
    return item.el.find('img').attr('alt');
  }

  function descriptionSrc(item) {
    if (item.el.find('img').attr('data-description')) {
      return item.el.find('img').attr('data-description');
    } else if (item.el.find('img').attr('data-image-description')) {
      return item.el.find('img').attr('data-image-description');
    } else if (item.el.attr('data-description')) {
      return item.el.attr('data-description');
    }
  }

  function updateDescription() {
    $('.mfp-description').html('');
    $('.mfp-description').html(descriptionSrc(this.currItem));
    $('.mfp-copyright').html(this.currItem.el.find('img').attr('data-copyright'));
  }

  $(document).ready(function() {

    var galleryRelAttrs = {};

    // Single Image
    $(imgSelector).each(function() {

      //check that it's not disabled for this single image
      if($(this).attr('data-wfml') === 'disabled') {
        return;
      }

      //check for grouping by rel attribute and replace replace white spaces from rel
      if ($(this).attr('rel')) {
        if (!galleryRelAttrs[$(this).attr('rel').replace(/ /g, '_')]) {
          galleryRelAttrs[$(this).attr('rel').replace(/ /g, '_')] = generateRelImgSelector($(this).attr('rel').replace(/ /g, '_'));
        }
      }

      //check that it's not part of a gallery
      if ($(this).parents('.gallery, .tiled-gallery, .wp-block-gallery').length !== 0) {
        return; //it's part of a gallery
      }

      //check that it's not a link with download attribute
      if($(this).attr('download') === '') {
        return;
      }

      $(this).addClass('wfml-single'); //Add a class

      if ($('.woocommerce .product .images a')) { // Make sure not to add to woocommerce product images
        $('.woocommerce .product .images a').removeClass('wfml-single'); //remove a class
      }

      if (!$(this).hasClass('wfml-single')) {
        return;
      }

      $(this).magnificPopup({
        type: 'image',
        callbacks: {
          open: updateDescription,
          afterChange: updateDescription
        },
        image: {
          markup: imageMarkup,
          titleSrc: titleSrc
        }
      });

    });

    $.each(galleryRelAttrs, function(rel, selector) {
      $(selector)
        .addClass('wfml-gallery-img')
        .magnificPopup({
          type: 'image',
          gallery: {
            enabled: true
          },
          callbacks: {
            open: updateDescription,
            afterChange: updateDescription
          },
          image: {
            markup: imageMarkup,
            titleSrc: titleSrc
          },
        });
    });


    // Gallery Images
    $('.gallery, .tiled-gallery, .wp-block-gallery').each(function() {
      $(this).magnificPopup({
        delegate: imgSelector,
        type: 'image',
        gallery: {
          enabled: true
        },
        callbacks: {
          open: updateDescription,
          afterChange: updateDescription
        },
        image: {
          markup: imageMarkup,
          titleSrc: titleSrc
        },
      });
    });
  });

})(jQuery);
