jQuery(function($){
  window.wcpt_feedback_anim = function(anim, $elm){
    if( ! $elm ){
      throw "Feedback anim: No $elm";
    }

    $elm.trigger('wcpt_feedback_anim_before', anim);    

    if( $elm.hasClass('wcpt-skip-animation') ){
      return;
    }    

    // reorder
    if( anim == 'move_row_up' || anim == 'move_row_down' ){

      var $elm2 = ( anim == 'move_row_up' ) ? $elm.next('[wcpt-model-key="[]"]') : $elm.prev('[wcpt-model-key="[]"]'),
          gap = parseInt( $elm.css('margin-bottom') ) ? parseInt( $elm.css('margin-bottom') ) : parseInt( $elm2.css('margin-bottom') ),
          offset = ( $elm2.outerHeight() + gap ) + 'px',
          offset2 = '-' + ( $elm.outerHeight() + gap ) + 'px';

      // re-flip positions

      // upper elm goes down by height of lower elm + margin
      $elm.css({
        'position': 'relative',
        'top': ( anim == 'move_row_up' ) ? offset : offset2,
        'z-index': '1',
      });

      // lower elm goes down by height of upper elm + margin
      $elm2.css({
        'position': 'relative',
        'top': ( anim == 'move_row_up' ) ? offset2 : offset,
        'z-index': '0',
      })

      $elm.add($elm2).animate({
        'top': 0,
      }, 500, function(){
        $elm.trigger('wcpt_feedback_anim_after', anim);
      })

      if( window.scrollY > $elm.offset().top - gap ){
        $(window).scrollTop( $(window).scrollTop() - ( $elm.outerHeight() + gap ) );
      }


    // add new
    }else if( anim == 'add_new_row' ){

      // real $elm
      $elm
        .hide()
        .css({transition: ''});

      // animation placeholder elm
      var $placeholder = $('<div class="wcpt-row-plc-hld wcpt-anim-new-row"></div>');
      $placeholder
        .insertBefore( $elm )
        // shrink placeholder with css till it's hidden
        .css({
          'height': '0px',
          'margin-bottom': $elm.css('margin-bottom'),
          'opacity': '0',
        })
        // expand placeholder w/ animate
        .animate({
          'height': $elm.outerHeight() + 'px',
          'opacity': 1,
        }, 300, function(){
          // animate call back
          // -- placeholder
          $placeholder
            // pull up the real $elm
            .css({
              'margin-bottom': '-' + $elm.outerHeight() + 'px',
            })
            // fade out placeholder
            .fadeOut(200, function(){
              $placeholder.remove();
            });
          // -- real $elm
          $elm.fadeIn(200, function(){
            $elm.css('display', '');
            $elm.trigger('wcpt_feedback_anim_after', anim);
          });
        });

    // delete
    }else if( anim == 'delete_row' ){
      var $placeholder = $('<div class="wcpt-row-plc-hld wcpt-anim-delete-row"></div>');
      $placeholder
        .insertBefore( $elm )
        .css({
          'height': $elm.outerHeight() + 'px',
          'margin-bottom': $elm.css('margin-bottom'),
          'opacity': '1',
        })
        .animate({
          'height': 0,
          'opacity': 0,
          'margin': 0,
        }, 500, function(){
          $placeholder.remove();
          $elm.trigger('wcpt_feedback_anim_after', anim);          
        });

    // duplicate
    }else if( anim == 'duplicate_row' ){
      var duration = 400;
      if( $elm.outerHeight() < 200 ){
        duration = 200;
      }

      var top = 0;
      if( $elm.prev().is(':visible') ){
        top = '-' + ( $elm.outerHeight() + parseInt( $elm.css('margin-bottom') ) ) + 'px';
      }else{
        top = '-200px';
        duration = 300;
      }

      $elm.css({
          'opacity': .75,
          'position': 'relative',
          'top': top,
          'z-index': '1',
      });
      $elm.animate({
        'opacity': 1,
        'top': 0,
      }, duration, function(){
        $elm.css('z-index', 0);
        $elm.trigger('wcpt_feedback_anim_after', anim);
      });
    }

  }
})
