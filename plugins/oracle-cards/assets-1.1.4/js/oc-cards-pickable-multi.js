wrp = jQuery('.oracle-cards-wrapper');
winW = jQuery(window).width();
eos_cards_js = 'undefined' !== typeof (eos_cards_js) ? eos_cards_js : wrp[0].dataset;
window.picked_n = 0;
if (jQuery('#cards-eosb-custom-css').length < 1) {
    jQuery('head').append('<style id="cards-eosb-custom-css">' + eos_cards_js.css + '</style>')
} else {
    jQuery('#cards-eosb-custom-css').append(eos_cards_js.css)
}
jQuery('body').on('click', '.card-back-img.picked', function() {
  eos_show_card(jQuery(this).parent());
  return false;
});
jQuery('body').on('click', '.refresh-cards', function() {
    var wrp = jQuery(this).closest('.oracle-cards-wrapper');
    if (wrp.length < 1) {
        return
    }
    wrp.addClass('eos-cards-progress');
    wrp.css('min-height', wrp.height() + 'px');
    wrp.html('');
    jQuery.ajax({
        type: "POST",
        url: eos_cards_js.ajaxurl,
        data: {
            "site_code": jQuery('body').attr('data-site-code'),
            "data": JSON.stringify(wrp[0].dataset),
            "action": "eos_mix_cards"
        },
        success: function(response) {
            if (response !== '0' && response !== '') {
                wrp.html(response);
                setTimeout(function() {
                    wrp.removeClass('eos-cards-progress');
                    eos_prepare_cards()
                }, 1000)
            }
        }
    })
});
jQuery(document).ready(function() {
    if (wrp.length < 1) {
        return
    }
    jQuery.ajaxSetup({
        cache: false
    });
    eos_prepare_cards();
    jQuery('.eos-cards-deck-wrp').css('visibility', 'visible');
    if ('1' !== eos_cards_js.is_admin_preview) {
        jQuery(window).on('resize', function() {
            if (jQuery(window).width() !== winW) {
                jQuery('.refresh-cards').trigger('click')
            }
        })
    }
});
function eos_prepare_cards() {
    eos_cards_js = 'undefined' !== typeof (eos_cards_js) ? eos_cards_js : wrp[0].dataset;
    deck_from = parseInt(eos_cards_js.deck_from);
    var btnWrp = jQuery('#eos-card-btn-wrp')
      , cardsInDeck = jQuery('.card-in-deck')
      , inDeckParent = cardsInDeck.parent()
      , deckWrp = btnWrp.closest('.oracle-cards-wrapper')
      , first_card = cardsInDeck.first()
      , last_card = cardsInDeck.last()
      , first_rotation = eos_get_rotation(first_card.find('img').first())
      , last_rotation = eos_get_rotation(last_card.find('img').first())
      , spaceLeft = first_card[0].offsetLeft + parseInt(first_card.height() / 2 * Math.tan(first_rotation))
      , spaceRight = parseInt(last_card.css(eos_cards_js.direction)) + parseInt(last_card.css('margin-' + eos_cards_js.direction)) + last_card.width() - deckWrp.width() + parseInt(last_card.height() / 2 * Math.tan(last_rotation));
    counter = 0;
    if (winW > (deck_from - 1)) {
        cardsInDeck.closest('.eos-cards-deck-wrp').css('margin-' + eos_cards_js.direction, ((-spaceRight - spaceLeft) / 2) + 'px');
        parentMarginLeft = parseInt(inDeckParent.css('margin-' + eos_cards_js.direction));
        inDeckParent.css('margin-' + eos_cards_js.direction, parentMarginLeft + ((-spaceRight - spaceLeft) / 2) + 'px')
    }
    inDeckParent.css('visibility', 'visible');
    var cardsInterval = setInterval(function() {
        var card = cardsInDeck.eq(counter);
        if (winW < deck_from) {
            card.css('margin-' + eos_cards_js.direction, counter + 'px').css('transform', 'translateX(-50%)').find('img').css('transform', 'rotate(' + Math.floor(Math.random() * 2) + 'deg)')
        }
        card.css('visibility', 'visible');
        counter += 1;
        if (counter > cardsInDeck.length) {
            clearInterval(cardsInterval)
        }
    }, 50);
    if (winW > (deck_from - 1)) {
        btnWrp[0].style.display = 'none'
    } else {
        jQuery('#eos-card-btn-wrp').removeClass('eos-hidden');
        jQuery('.card-back-wrp ').css('margin-left', 'auto').css('margin-right', 'auto')
    }
    jQuery('#' + btnWrp.attr('data-card-id')).append('<div id="eos-card-btn-wrp" style="position:absolute;top:77%;left:0;right:0;text-align:center;transform:translateY(-50%);z-index:9;">' + btnWrp.html() + '</div>');
    jQuery('.take-a-card,.eos-cards-fan .eos-card').on('click', function() {
        var take_btn = jQuery(this)
          , cards_wrp = take_btn.closest('.oracle-cards-wrapper')
          , cards = cards_wrp.find('.card-in-deck')
          , card_fan = take_btn.filter('.eos-card')
          , card = card_fan.length > 0 ? card_fan : cards.eq(parseInt(cards.length / 2))
          , front_img = new Image()
          , h = card.height()
          , w = card.width()
          , m = card.closest('.eos-cards-deck-wrp').width() / 2 - w
          , animData = {
            'left': '+=0px',
            'right': '-=0px'
        };
        jQuery.ajax({
          type : "POST",
          url : eos_cards_js.ajaxurl,
          data : {
            "id" : 'undefined' !== typeof(eos_cards_js.preview_id) && '' !== eos_cards_js.preview_id ? eos_cards_js.preview_id : rnd_card[2],
            "action" : "eos_cards_get_data"
          },
          success : function (response) {
            if(response && '' !== response){
              cards_wrp.find('#oc-card-content').fadeIn().html(response);
            }
          }
        });
        if (take_btn.hasClass('refresh-cards')) {
            return
        }
        // cards.not(card).fadeOut('fast', 'swing');
        front_img.src = card.find('.card-front-img').attr('src');
        cards_wrp.attr('data-clicked', parseInt(cards_wrp.attr('data-clicked')) + 1);
        jQuery('.eos-card-content').css('margin-top',card.find('img').height() + 30 + 'px');
        card.animate({marginTop: card.find('img').height() + 30 + 'px'});
        card.removeClass('card-in-deck').css('z-index', parseInt(cards_wrp.attr('data-clicked')) + 1).animate(animData, 500, function() {
            step = '0px';
            timeStep = winW < deck_from ? 500 : 1000;
            card.css('height', h).css('width', w).animate({
                'left': '-=' + step,
                'top': '-=2px'
            }, {
                step: function(now, fx) {
                    // setTimeout(function() {
                    //     card.find('.card-back-img').css('display', 'none');
                    //     card.find('.card-front-img').css('display', 'inline-block')
                    // }, timeStep);
                    card.css('transition', 'all ease 2s, margin-' + eos_cards_js.direction + ' 1ms')
                    // card.css('transform', 'rotate3d(0, 1, 0, 90deg)').css('transition', 'all ease 2s, margin-' + eos_cards_js.direction + ' 1ms')
                },
                duration: 'slow',
                done: function() {
                    cards_wrp.find('#eos-card-btn-wrp').addClass('eos-hidden');
                    transform = winW < deck_from || parseInt(eos_cards_js.pickable_n) > 1 ? 'rotate(0)' : 'rotate3d(0, 1, 0, 180deg)';
                    card.animate({
                        'left': '+=' + step,
                        'top': '+=2px'
                    }, {
                        step: function(now, fx) {
                            // card.css('transform', transform).css('transition', 'all ease 2s')
                            card.css('transition', 'all ease 2s')
                        },
                        duration: 'slow',
                        done: function() {
                            if (winW < deck_from) {
                                card.css('transition', 'all easy 2s, margin-' + eos_cards_js.direction + '1ms').css('margin-' + eos_cards_js.direction, (-card.width() / 2) + 'px')
                            }
                            else if(parseInt(eos_cards_js.pickable_n) < 2){
                                cardsInDeck.closest('.card-back-wrp').css('transition', 'all ease 2s').css('margin-' + eos_cards_js.direction, '0');
                                cardsInDeck.closest('.eos-cards-deck-wrp').css('transition', 'all ease 1s').css('margin-' + eos_cards_js.direction, '0');
                                card.css(eos_cards_js.direction, '50%').css('margin-' + eos_cards_js.direction, -card.width() / 2 + 'px');
                                // card.find('.card-front-img').css('transition', 'transform ease 3s').css('transform', 'rotate(0) scaleX(-1)')
                            }
                            else if(parseInt(eos_cards_js.pickable_n) > 1){
                                // cardsInDeck.closest('.card-back-wrp').css('transition', 'all ease 2s').css('margin-' + eos_cards_js.direction, '0');
                                // cardsInDeck.closest('.eos-cards-deck-wrp').css('transition', 'all ease 1s').css('margin-' + eos_cards_js.direction, '0');
                                ++window.picked_n;
                                var max_margin = parseInt(eos_cards_js.pickable_n) * card.width()/2,margin_offset = parseInt(window.picked_n)*10;
                                card.css(eos_cards_js.direction, '50%').css('margin-' + eos_cards_js.direction,-(max_margin - window.picked_n * card.width() - margin_offset) + 'px');
                                // card.find('.card-front-img').css('transition', 'transform ease 3s').css('transform', 'rotate(0) scaleX(-1)');
                                card.find('.card-back-img').addClass('picked').css('transition', 'transform ease 3s').css('transform', 'rotate(0)');
                            }
                            cards_wrp.find('.eos-mix-cards-wrp').removeClass('eos-hidden')
                        }
                    }, 'linear', );
                    var cardHtml = 'false' === eos_cards_js.show_title ? '' : '<h1 id="oc-card-title" style="font-size:32px;text-align:' + eos_cards_js.title_alignment + '" class="eos-font-subtitles">' + rnd_card[3] + '</h1>';
                    cardHtml += '<p id="oc-card-content" style="display:none"></p>';
                    cardHtml += '' !== rnd_card[1] ? '<div class="center"><a class="button btn" target="_blank" href="' + rnd_card[1] + '">' + eos_cards_js.read_more + '</a></div>' : '';
                    cards_wrp.find('.eos-card-content').html(cardHtml);
                    jQuery('.cards-content-title').removeClass('eos-hidden');
                    var z_index = 999 + window.picked_n;
                    take_btn.removeClass('take-a-card').css('z-index',z_index).text(take_btn.attr('data-mix-text'));
                    if (winW > 767) {
                        take_btn.addClass('refresh-cards')
                    }
                }
            }, 'linear', )
        })
    })
}
function eos_get_rotation(el) {
    var matrix = el.css("-webkit-transform") || el.css("-moz-transform") || el.css("-ms-transform") || el.css("-o-transform") || el.css("transform");
    if (matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',')
          , a = values[0]
          , b = values[1]
          , angle = Math.atan2(b, a)
    } else {
        var angle = 0
    }
    if( angle > 0.17 ) angle = 0.17;
    if( angle < -0.17 ) angle = -0.17;
    return angle
}

function eos_show_card(card){
  var front = card.find('.card-front-img'),back = card.find('.card-back-img');
  front.css('transform', 'rotateY(90deg)').css('transition', 'transform ease 2s');
  back.css('transform', 'rotateY(90deg)').css('transition', 'transform ease 1s');
  setTimeout(function() {
      back.removeClass('picked').css('transform', 'rotateY(0deg)').css('display','none');
      front.css('display', 'inline-block').css('transform', 'rotateY(0deg) scale(1)');
  },1000);
  return false;
}
