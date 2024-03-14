/**
 *  ajax
 */

jQuery( document ).ready( function( $ ) {

    var history = [];
    var back = false;

    /**
     *  Add "Back" button 
     */
    if ( $('ul#choices li').length ) {
        // 戻るボタン
        display_back_button();

        var qid = parseInt( $('.yesno_q').attr('id').replace('q', '') );
        history.push( qid );
    }

    $( document ).on('click', '#choices button', function( event ){ 

        event.preventDefault();
 
        var items = {
            'action' : 'YESNO_next_question',
            'qid' : $( this ).val()
        }

        if ( $( this ).attr('id') == 'back_button') {
            back = true;
            if ( history.length > 1 ) {
                history.pop();
            }
            else {
                return false;
            }
        }
        else {
            back = false;
        }
 
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: items,
            success: function( response ){
            	;
            }
        }).done( function( result ) {
            // Redirection
            if ( result.url != '' ) {
                $('#question_wrap, #choices').animate({opacity: '0.0'}, 1000, 'swing', function(){
                    window.location.href = result.url;
                });
            }
            else {
                if ( back ) {
                    // Back Question
                    $('#question_wrap').css({opacity: '0.0'}, 1000);
                    $('.yesno_q').attr('id', 'q'+result.qid );
                  // Title is given priority
                    if ( result.title != '' ) {
                        $('.yesno_q dt span').text( result.title );
                    }
                    // If it is not, numbers will be used
                    else {
                        $('.yesno_q dt span').text('Q'+result.qnum );
                    }
                    $('.yesno_q dd').html( result.question );
                    $('#question_wrap').animate({ marginLeft: -1200}, 0);
                    $('#question_wrap').animate({opacity: '1'}, 0 );

                    $('#question_wrap').animate({ marginLeft: 0}, 500, 'swing', function(){
                        // Prepare a branch for the next question
                        if ( result.choices.length > 0 ) {
                            $('#choices').children().remove();
                            for (var i = 0; i < result.choices.length; i++ ) {
                                $('#choices').append('<li><button value="'+result.choices[ i ].goto+'">'+result.choices[ i ].label+'</button></li>');
                            };
                            // 戻るボタン
                            display_back_button();
                        }
                        // Result (It has no branch)
                        else {
                            $('#choices').animate({opacity: '0.0'}, 1000, function(){
                                $('#choices').children().remove();
                            });
                        }
                        scroll_to_yseno_head();
                    });
                }
                else {

                    // Next Question
        		    $('#question_wrap').animate({ marginLeft: -1200}, 500, 'swing', function(){
                        $('.yesno_q').attr('id', 'q'+result.qid );
                        // Title is given priority
                         history.push( parseInt( result.qid ) );
                       if ( result.title != '' ) {
                            $('.yesno_q dt span').text( result.title );
                        }
                        // If it is not, numbers will be used
                        else {
                            $('.yesno_q dt span').text('Q'+result.qnum );
                        }
                        // Next question
                        $('.yesno_q dd').html( result.question );
                        $('#question_wrap').css({opacity: '0.0'});
                        $('#question_wrap').animate({ marginLeft: 0}, 0);
                        $('#question_wrap').animate({opacity: '1'}, 1000 );
                        // Prepare a branch for the next question
                        if ( result.choices.length > 0 ) {
                            $('#choices').children().remove();
                            for (var i = 0; i < result.choices.length; i++ ) {
                                $('#choices').append('<li><button value="'+result.choices[ i ].goto+'">'+result.choices[ i ].label+'</button></li>');
                            };
                            // 戻るボタン
                            display_back_button();
                        }
                        // Result (It has no branch)
                        else {
                            $('#choices').animate({opacity: '0.0'}, 1000, function(){
                                $('#choices').children().remove();
                            });
                        }
                        scroll_to_yseno_head();
                    });
                }
            }
        });
    })

    /**
     *  戻るボタンの表示
     */
    function display_back_button() {
        $('ul#choices').append('<li id="back">');
        $('ul#choices li#back').hide();
        $('ul#choices li#back').append('<button id="back_button" value="">' + yesno_text.back +'</button>');
        if ( history.length > 1 ) {
            $('#back_button').val( history[ history.length-2 ] );
            $('ul#choices li#back').show();
        }
    }

    /**
     *  設問の位置に戻る（標準ではOFFです。ONにする場合は下記 return; を削除します）
     */
    function scroll_to_yseno_head() {
        return;
        var yn_wrap = $('#yesno_wrap').offset();
        console.log( yn_wrap );
        $('html, body').animate({
            scrollTop: yn_wrap.top - 32
        }, 'slow', 'swing');
    }
})