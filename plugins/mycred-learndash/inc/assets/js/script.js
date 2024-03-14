jQuery(document).ready(() => {


     jQuery(document).on('click', '#widget-mycred-hook_hook_completing_quiz_learndash', function(e) {
        jQuery('select.user_select_quiz').trigger('change');

    });

    jQuery(document).on( 'click', '.mycred-add-specific-quiz-learndash-hook', function() {
        var hook = jQuery(this).closest('.quiz_custom_hook_class').clone();
        hook.find('input.mycred-learndash-quiz-creds').val();
        hook.find('input.mycred-learndash-quiz-log').val();
        hook.find('select.mycred-learndash-quiz').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.quiz_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-quiz').trigger('change');
    }); 

    jQuery(document).on( 'click', '.mycred-remove-quiz-specific-hook', function() {

        var container = jQuery(this).closest('.hook-instance');

        if ( container.find('.quiz_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.quiz_custom_hook_class').remove();
                jQuery('select.user_select_quiz').trigger('change');
            } 
        }
    }); 

     jQuery(document).on('change', 'select.user_select_quiz', function(){
        
        ml_user_quiz_enable_disable_options( jQuery(this) );

    });
    jQuery(document).on('change', 'select.mycred-learndash-quiz', function(){ 
        ml_quiz_enable_disable_options( jQuery(this) );
    });

      jQuery(document).on('click', '#widget-mycred-hook_hook_quiz_failed_learndash', function(e) {
        jQuery('select.user_select_quiz_failed').trigger('change');

    });

    jQuery(document).on( 'click', '.mycred-add-specific-quiz-failed-learndash-hook', function() {
        var hook = jQuery(this).closest('.quiz_failed_custom_hook_class').clone();
        hook.find('input.mycred-learndash-quiz-failed-creds').val();
        hook.find('input.mycred-learndash-quiz-failed-log').val();
        hook.find('select.mycred-learndash-quiz-failed').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.quiz_failed_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-quiz-failed').trigger('change');
    }); 
    jQuery(document).on( 'click', '.mycred-remove-quiz-failed-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.quiz_failed_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.quiz_failed_custom_hook_class').remove();
                jQuery('select.user_select_quiz_failed').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_quiz_failed', function(){ 
        ml_user_quiz_failed_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change', 'select.mycred-learndash-quiz-failed', function(){ 
        ml_quiz_fail_enable_disable_options( jQuery(this) );

    });

    jQuery(document).on('click', '#widget-mycred-hook_hook_complete_quiz_max_percentage_learndash', function(e) {
        jQuery('select.user_select_max_grade_quiz').trigger('change');

    });
    jQuery(document).on( 'click', '.mycred-add-specific-max-grade-quiz-learndash-hook', function() {
        var hook = jQuery(this).closest('.quiz_max_grade_custom_hook_class').clone();
        hook.find('input.mycred-learndash-max-grade-quiz-max-grade-creds').val();
        hook.find('input.mycred-learndash-quiz-max-grade-log').val();
        hook.find('select.mycred-learndash-max-grade-quiz').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.quiz_max_grade_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-max-grade-quiz').trigger('change');
    }); 
    jQuery(document).on( 'click', '.mycred-remove-max-grade-quiz-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.quiz_max_grade_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.quiz_max_grade_custom_hook_class').remove();
                jQuery('select.user_select_max_grade_quiz').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_max_grade_quiz', function(){  
        ml_user_quiz_max_grade_failed_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change', 'select.mycred-learndash-max-grade-quiz', function(){  
        ml_quiz_max_grade_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('click', '#widget-mycred-hook_hook_complete_quiz_percentage_range_learndash', function(e) {
        jQuery('select.user_select_percent_grade_quiz').trigger('change');

    });
    jQuery(document).on( 'click', '.mycred-add-specific-percent-grade-quiz-learndash-hook', function() {
        var hook = jQuery(this).closest('.quiz_percent_grade_custom_hook_class').clone();
        hook.find('input.mycred-learndash-percent-grade-quiz-percent-grade-creds').val();
        hook.find('input.mycred-learndash-quiz-percent-grade-log').val();
        hook.find('select.mycred-learndash-percent-grade-quiz').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.quiz_percent_grade_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-percent-grade-quiz').trigger('change');
    }); 

    jQuery(document).on( 'click', '.mycred-remove-percent-grade-quiz-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.quiz_percent_grade_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.quiz_percent_grade_custom_hook_class').remove();
                jQuery('select.user_select_percent_grade_quiz').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_percent_grade_quiz', function(){
        
        ml_user_quiz_percent_range_enable_disable_options( jQuery(this) );

    });
    jQuery(document).on('change', 'select.mycred-learndash-percent-grade-quiz', function(){
        
        ml_quiz_percent_range_enable_disable_options( jQuery(this) );

    });
     jQuery(document).on('click', '#widget-mycred-hook_hook_completing_lesson_learndash', function(e) {
        jQuery('select.user_select_lesson').trigger('change');

    });
    jQuery(document).on( 'click', '.mycred-add-specific-lesson-learndash-hook', function() {
        var hook = jQuery(this).closest('.lesson_custom_hook_class').clone();
        hook.find('input.mycred-learndash-lesson-creds').val();
        hook.find('input.mycred-learndash-lesson-log').val();
        hook.find('select.mycred-learndash-lesson').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.lesson_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-lesson').trigger('change');
    }); 
    jQuery(document).on( 'click', '.mycred-remove-lesson-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.lesson_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.lesson_custom_hook_class').remove();
                jQuery('select.user_select_lesson').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_lesson', function(){   
        ml_user_lesson_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change', 'select.mycred-learndash-lesson', function(){     
        ml_lesson_enable_disable_options( jQuery(this) );
    });
     jQuery(document).on( 'click', '.mycred-add-specific-group-learndash-hook', function() {
        var hook = jQuery(this).closest('.group_custom_hook_class').clone();
        hook.find('input.mycred-learndash-group-creds').val();
        hook.find('input.mycred-learndash-group-log').val();
        hook.find('select.mycred-learndash-group').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.group_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-group').trigger('change');
    }); 
    jQuery(document).on( 'click', '.mycred-remove-group-specific-hook', function() {
        var container = jQuery(this).closest('.group-hook-instance');
        if ( container.find('.group_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.group_custom_hook_class').remove();
                jQuery('select.mycred-learndash-group').trigger('change');
            } 
        }
    }); 
jQuery(document).on('change', 'select.mycred-learndash-group', function(){
        ml_group_enable_disable_options( jQuery(this) );
});
jQuery(document).on('click', '#widget-mycred-hook_hook_completing_topic_learndash', function(e) {
        jQuery('select.user_select_topic').trigger('change');

});  
jQuery(document).on( 'click', '.mycred-add-specific-topic-learndash-hook', function() {
        var hook = jQuery(this).closest('.topic_custom_hook_class').clone();
        hook.find('input.mycred-learndash-topic-creds').val();
        hook.find('input.mycred-learndash-topic-log').val();
        hook.find('select.mycred-learndash-topic').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.topic_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-topic').trigger('change');
}); 
jQuery(document).on( 'click', '.mycred-remove-topic-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.topic_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.topic_custom_hook_class').remove();
                jQuery('select.user_select_topic').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_topic', function(){ 
        ml_user_topic_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change', 'select.mycred-learndash-topic', function(){  
        ml_topic_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('click', '#widget-mycred-hook_hook_completing_course_learndash', function(e) {
        jQuery('select.user_select_course').trigger('change');

    });
    jQuery(document).on('click', '#widget-mycred-hook_hook_course_enrollment_learndash', function(e) {
        jQuery('select.mycred-learndash-course').trigger('change');

    });
    jQuery(document).on( 'click', '.mycred-add-specific-course-learndash-hook', function() {
        var hook = jQuery(this).closest('.course_custom_hook_class').clone();
        hook.find('input.mycred-learndash-course-creds').val();
        hook.find('input.mycred-learndash-course-log').val();
        hook.find('select.mycred-learndash-course').val('0');
        hook.find('input.mycred-learndash-percentage').val('0');  
        jQuery(this).closest('.course_custom_hook_class').after( hook );
        jQuery('select.mycred-learndash-course').trigger('change');
    }); 
    jQuery(document).on( 'click', '.mycred-remove-course-specific-hook', function() {
        var container = jQuery(this).closest('.hook-instance');
        if ( container.find('.course_custom_hook_class').length > 1 ) {
            var dialog = confirm("Are you sure you want to remove this hook?");
            if (dialog == true) {
                jQuery(this).closest('.course_custom_hook_class').remove();
                jQuery('select.user_select_course').trigger('change');
            } 
        }
    }); 
     jQuery(document).on('change', 'select.user_select_course', function(){   
        ml_user_course_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change', 'select.mycred-learndash-course', function(){  
        ml_course_enable_disable_options( jQuery(this) );
    });
    jQuery(document).on('change' ,'.mycred-learndash-quiz-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_quiz_for_users',
            quiz: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.quiz_custom_hook_class').find('.user_select_quiz');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'quiz' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } );   
        });
    });
     jQuery(document).on('change' ,'.mycred-learndash-quiz-fail-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_quiz_failed_for_users',
            quiz: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.quiz_failed_custom_hook_class').find('.user_select_quiz_failed');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'quiz' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } );
        });
    });
    jQuery(document).on('change' ,'.mycred-learndash-quiz-max-grade-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_quiz_max_grade_for_users',
            quiz: value,

        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.quiz_max_grade_custom_hook_class').find('.user_select_max_grade_quiz');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'quiz' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } );
        });
    });
    jQuery(document).on('change' ,'.mycred-learndash-quiz-percent-grade-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_quiz_percent_range_for_users',
            quiz: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.quiz_percent_grade_custom_hook_class').find('.user_select_percent_grade_quiz');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'quiz' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } ); 
        });
    });
    jQuery(document).on('change' ,'.mycred-learndash-topic-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_topic_for_users',
            topic: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.topic_custom_hook_class').find('.user_select_topic');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){  
                if ( value == 'topic' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } ); 
        });
    });
    jQuery(document).on('change' ,'.mycred-learndash-lesson-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_lesson_for_users',
            lesson: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.lesson_custom_hook_class').find('.user_select_lesson');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'lesson' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } );    
        });
    });
    jQuery(document).on('change' ,'.mycred-learndash-options', function(){
        var _this = jQuery(this);
        var value = _this.val();
        var data = {
            'action': 'mycred_specific_course_for_users',
            course: value,
        };
        jQuery.post( ajaxurl, data, function( response ) {      
            response = JSON.parse( response );
            var ele = _this.closest('.course_custom_hook_class').find('.user_select_course');
            ele.find('option').remove();
             if ( value == 'tags' ) {
            ele.html( '<option value="0">Any Tag</option>' );
            }
            jQuery.each( response, function(index, val){
                if ( value == 'course' )
                    ele.append( "<option value="+ val.ID +">"+val.post_title+"</option>" );             
                else
                    ele.append( "<option value="+ val.term_id +">"+val.name+"</option>" );             
            } );  
        });
    });
});

function ml_enable_disable_options( ele ) {
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-quiz').each(function () {
        container.find('select.mycred-learndash-quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () { 
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}

function ml_course_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-course').each(function () {
        container.find('select.mycred-learndash-course').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-course').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

}

function ml_user_course_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_course').each(function () {
        container.find('select.user_select_course').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_course').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}

function ml_topic_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-topic').each(function () {
        container.find('select.mycred-learndash-topic').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-topic').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}

function ml_user_topic_enable_disable_options( ele ) {   
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_topic').each(function () {
        container.find('select.user_select_topic').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {   
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_topic').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}


function ml_quiz_percent_range_enable_disable_options( ele ) { 
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-percent-grade-quiz').each(function () {
        container.find('select.mycred-learndash-percent-grade-quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {
        
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-percent-grade-quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}  

function ml_quiz_max_grade_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-max-grade-quiz').each(function () {
        container.find('select.mycred-learndash-max-grade-quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {   
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-max-grade-quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

} 

function ml_quiz_fail_enable_disable_options( ele ) {
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-quiz-failed').each(function () {
        container.find('select.mycred-learndash-quiz-failed').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-quiz-failed').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

} 

function ml_quiz_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-quiz').each(function () {
        container.find('select.mycred-learndash-quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {   
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

}


function ml_lesson_enable_disable_options( ele ) {    
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.mycred-learndash-lesson').each(function () {
        container.find('select.mycred-learndash-lesson').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {     
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-lesson').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

}

function ml_user_quiz_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_quiz').each(function () {
        container.find('select.user_select_quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}

function ml_user_quiz_percent_range_enable_disable_options( ele ) {   
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_percent_grade_quiz').each(function () {
        container.find('select.user_select_percent_grade_quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {  
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_percent_grade_quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}  

function ml_user_quiz_max_grade_failed_enable_disable_options( ele ) {  
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_max_grade_quiz').each(function () {
        container.find('select.user_select_max_grade_quiz').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {    
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_max_grade_quiz').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
} 

function ml_user_quiz_failed_enable_disable_options( ele ) {   
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_quiz_failed').each(function () {
        container.find('select.user_select_quiz_failed').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {  
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_quiz_failed').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });

} 

function ml_user_lesson_enable_disable_options( ele ) {
    
    var selected = [];
    var container = ele.closest('.hook-instance');
    container.find('select.user_select_lesson').each(function () {
        container.find('select.user_select_lesson').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });

    container.find('option').each(function () {     
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.user_select_lesson').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}


function ml_group_enable_disable_options( ele ) {
    
    var selected = [];
    var container = ele.closest('.group-hook-instance');
    container.find('select.mycred-learndash-group').each(function () {
        container.find('select.mycred-learndash-group').not(jQuery(this)).find('option[value="'+jQuery(this).val()+'"]').attr('disabled', 'disabled');
        selected.push( jQuery(this).val() );
    });
    container.find('option').each(function () {   
        if( ! selected.includes( jQuery(this).attr('value')) ) {
            container.find('select.mycred-learndash-group').find('option[value="'+jQuery(this).val()+'"]').removeAttr('disabled');
        }
    });
}
