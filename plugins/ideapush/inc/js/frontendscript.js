jQuery(document).ready(function ($) {

    function isValidURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
          '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
          '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
          '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
          '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
          '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!pattern.test(str);
    }
    
    //button to reveal form
    $('body').on('click', '.create-idea-form-reveal',function(event){
       event.preventDefault();
       $('.ideapush-form-inner').slideToggle(); 
    });
    
    
    //button to reveal user edit form
    $('body').on('click', '.user-profile-edit',function(event){
       event.preventDefault();
       $('.user-profile-edit-form').slideToggle(); 
    });
    
    
    //update-user-profile
    $('body').on('click', '.update-user-profile',function(event){
        event.preventDefault();
        
//        console.log('I was clicked');
        
//        $('.user-profile-loading').slideDown();
        
        
        var firstName = $('.ideapush-form-first-name-edit').val();
        var lastName = $('.ideapush-form-last-name-edit').val();
        var email = $('.ideapush-form-email-edit').val();
        // var password = $('.ideapush-form-password-edit').val();
        var boardNumber = $('.ideapush-container').attr('data');
        
        
        if(firstName.length > 0 && lastName.length > 0 && email.length > 0 && email.indexOf('@') > -1){
                    

            //do query
            // var data = {
            //     'action': 'update_user_profile',
            //     'firstName': firstName,
            //     'lastName': lastName,
            //     'email': email,
            //     'password': password,
            //     'boardNumber': boardNumber,
            // }; 
            
  
            //get attachment
            if($('.ideapush-user-profile-attachment').length){
                var attachment = $('.ideapush-user-profile-attachment').prop("files")[0];    
            }
            
            
            var form_data = new FormData();
            form_data.append("action", 'update_user_profile');
            form_data.append("firstName", firstName);
            form_data.append("lastName", lastName);
            form_data.append("email", email);
            // form_data.append("password", password);
            form_data.append("boardNumber", boardNumber);
            //only send the attachment if it exists
            if($('.ideapush-user-profile-attachment').length){
                form_data.append("attachment", attachment);
            }
        
            //do query
            jQuery.ajax({
            url: update_user_profile.ajaxurl,
            type: "POST",
            data: form_data,     
            context: this,
            processData: false,
            contentType: false,
            cache: false,
            }).done(function(data, textStatus, jqXHR) {


               console.log(data);
                
                reRenderHeader('');
                reRenderForm();
                commonReOrderAndReRenderingOfIdeas('');
                
                
                $('.user-profile-edit-form').slideToggle();
                

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
            })
            .always(function() {
            });
            

        } else {
            alertify.alert($('#dialog-user-profile-error').attr('data'));
        }
        
        
        
        
        
        
        
        
        

        
        
        
         
    
    });
    
    
    
    //enable readmore
    
    function readMoreFunctionality(){
        $('.idea-item-content-read-more').readmore({
            moreLink: '<a href="#">'+$('#ideaReadMoreText').text()+'</a>', // (raw HTML)
            lessLink: '<a href="#">'+$('#ideaReadLessText').text()+'</a>', // (raw HTML)
            sectionCSS: 'display: block; width: 100%;', // (sets the styling of the blocks)
            heightMargin: 30, // (in pixels, avoids collapsing blocks that are only slightly larger than maxHeight)
            collapsedHeight: 90

        });  
        
    }
    readMoreFunctionality();
    
    
 
    //do scroll reveal for ideas
    
    //only do if there's no pagination
    if(!$('.idea-pagination').length){
        if($('.ideapush-scrollable-container').length){
            window.sr = ScrollReveal({container: '.ideapush-scrollable-container'});    
        } else {
            window.sr = ScrollReveal();     
        }

        sr.reveal('.idea-item',{ duration: 300,scale: 1, });
    }
    
    
    
    
    
    
    //indicate that the attachment has a file
    $('body').on('change', '.ideapush-form-idea-attachment, .ideapush-form-idea-image',function(e){
        
        var fileName = e.target.value.split( '\\' ).pop();
        
//        console.log(fileName);
        
        $(this).next().html('<i class="ideapush-icon-Image"></i> '+fileName);
        
//        console.log($(this).val());
    });
    
    
    //indicate that the attachment has a file
    $('body').on('change', '.ideapush-user-profile-attachment',function(e){
        
        var fileName = e.target.value.split( '\\' ).pop();
        
//        console.log(fileName);
        
        $('.ideapush-user-profile-attachment-label').html('<i class="ideapush-icon-Image"></i> '+fileName);
        
//        console.log($(this).val());
    });
    
    




  
    //we are going to depcreate this function as we no longer want to autosize things..
    function resizeHeaderSelectItems(thisObj){

        // var $this = thisObj;
    
        // // create test element
        // var text = $this.find("option:selected").text();
        // var $test = $("<span>").html(text).css({
        //     "font-size": $this.css("font-size"), // ensures same size text
        //     "visibility": "hidden"               // prevents FOUC
        // });


        // // add to parent, get width, and get out
        // $test.appendTo($this.parent());
        // var width = $test.width();
        // $test.remove();

        // // set select width
        // $this.width(width + 30); 

    }  
    
    function resizeAllHeaderSelect(){
        resizeHeaderSelectItems($('.ideapush-tags-filter'));
        resizeHeaderSelectItems($('.ideapush-status-filter'));
        resizeHeaderSelectItems($('.ideapush-sort'));
    }
    
    //run on start
    resizeAllHeaderSelect();
    

    
    




    
    //search functionality
    $('body').on('keyup change', '.ideapush-search-input',function(){
    
        //show all ideas
        $(".idea-item").removeClass('hidden-idea'); 
        
        //get value of search input and make it uppercase
        var searchInput = $(this).val().toUpperCase();

        //render html
        var renderHtmlActive = $('.ideapush-container').attr('data-render-html');

        
        //only do filter if the search input isn't blank
        if(searchInput !== '' && searchInput.length > 0){
        
            //now lets cycle through the list items
            $(".idea-item").each(function( index ) {

                var ideaTitle = $(this).find('.idea-title').text();
                var ideaContent = $(this).find('.idea-item-content').text();
                var ideaAuthor = $(this).find('.idea-author').text();

                var ideaTag = '';
                
                $(this).find('.idea-item-tags a').each(function( index ) {
                    
                    var tagText = $(this).text();
                    ideaTag += tagText; 
                });
                
                var ideaCustomFields = $(this).find('.custom-field-content').text();
                //remove tags
                ideaCustomFields = ideaCustomFields.replace(/<\/?[^>]+(>|$)/g, "");

                
                
                var ideaTitleUpper = ideaTitle.toUpperCase();
                var ideaContentUpper = ideaContent.toUpperCase();
                var ideaAuthorUpper = ideaAuthor.toUpperCase();
                var ideaTagUpper = ideaTag.toUpperCase();
                var customFieldsUpper = ideaCustomFields.toUpperCase();


                // console.log(customFieldsUpper);
//                console.log(ideaTitle);
//                console.log(ideaContent);
//                console.log(searchInput);
                
                if(ideaTitleUpper.indexOf(searchInput) >= 0 || ideaContentUpper.indexOf(searchInput) >= 0 || ideaAuthorUpper.indexOf(searchInput) >= 0 || ideaTagUpper.indexOf(searchInput) >= 0 || customFieldsUpper.indexOf(searchInput) >= 0){
                    //a match was found
                    //lets highlight the match to be super cool
                    
                    //do title replacement
                    var titleHighlighting = new RegExp(searchInput, 'gi');
                    var replacedTitle = ideaTitle.replace(titleHighlighting, function(str) {return '<span class="search-match-found">'+str+'</span>'});
                    $(this).find('.idea-title').html(replacedTitle);
                    
                    
                    //do content replacement
                    //dont do this if HTML is being rendered otherwise it will stuff thigns up
                    if(renderHtmlActive == 'false'){
                        var contentHighlighting = new RegExp(searchInput, 'gi');
                        var replacedContent = ideaContent.replace(contentHighlighting, function(str) {return '<span class="search-match-found">'+str+'</span>'});
                        $(this).find('.idea-item-content').html(replacedContent);
                    }
                    
                    //do author replacement
                    var authorHighlighting = new RegExp(searchInput, 'gi');
                    var replacedAuthor = ideaAuthor.replace(authorHighlighting, function(str) {return '<span class="search-match-found">'+str+'</span>'});
                    $(this).find('.idea-author').html(replacedAuthor);
                    
                    
                    //do tag replacement
                    $(this).find('.idea-item-tags a').each(function( index ) {
                        
                        var thisText = $(this).text();
                        
                        var tagHighlighting = new RegExp(searchInput, 'gi');
                        var replacedTag = thisText.replace(tagHighlighting, function(str) {return '<span class="search-match-found">'+str+'</span>'});
                        $(this).html(replacedTag);
                    });

                    //do custom field replacement
                    var customFieldHighlighting = new RegExp(searchInput, 'gi');
                    var replacedCustomField = ideaCustomFields.replace(customFieldHighlighting, function(str) {return '<span class="search-match-found">'+str+'</span>'});
                    $(this).find('.custom-field-content').html(replacedCustomField);

                       
                    
                    
                } else {
                    //no match was made
                    $(this).addClass('hidden-idea'); 
                    
                    if(!$('.idea-pagination').length){
                        sr.sync();    
                    }
                    
                    
                }
                

            });
            
        } else {
            
            
            
            
            
            //show appropiate page items
            if($('.idea-pagination').length){

                var activePageNumber = parseInt($('.idea-page-number.active').text());

                showPageItems(activePageNumber);    
                
            } else {
                //show everything
                $(".idea-item").removeClass('hidden-idea');
            }
            
            
            
            
            //creat variable for all classes we need to remove highlighting from
            var searchMatchesFound = $('.search-match-found');

            searchMatchesFound.contents().unwrap();

        }
        
        
    });
    
    
    
    
    
    //when any of the sorting selects change re-render the ideas
    
    $('body').on('change', '.ideapush-sort, .ideapush-status-filter, .ideapush-tags-filter, .ideapush-custom-field-filter',function(){
        
        commonReOrderAndReRenderingOfIdeas('');
        resizeAllHeaderSelect();
    
    });
    
    
    
    
    
    
    function commonReOrderAndReRenderingOfIdeas(showing){
        
        //lets get the values of all select options
        
        //console.log(showing);
        
        //sort values
        if(showing == null || showing.length < 1){
            var sortFilter = $('.ideapush-sort').val();
        } else {
            var sortFilter = showing;    
        }
        
                
        //status values
        var statusFilter = $('.ideapush-status-filter').val();
        
        //tag values
        //because the tags might now be displayed on the page we need to run this check and set the default to all
        if($('.ideapush-tags-filter option:selected').length){
            var tagFilter = $('.ideapush-tags-filter option:selected').val();
            var tagFilterName = $('.ideapush-tags-filter option:selected').text();      
        } else {
            var tagFilter = 'all';
            var tagFilterName = 'All';     
        }

        //we need to loop through custom field values also

        //create initial variable which will hold all custom field data
        var customFieldFilter = [];

        if($('.ideapush-custom-field-filter').length){
            //loop through custom fields
            $( '.ideapush-custom-field-filter' ).each(function( index ) {
                var selectedOption = $(this).val();
                var filterName = $(this).attr('data');
                //add item to array
                customFieldFilter.push(filterName+'|'+selectedOption);

            });
        }
        
        //turn into string list
        customFieldFilter = customFieldFilter.join('||');
        
        
        //lets change the url query string parameters to make it easier for people to share certain filters
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?showing='+sortFilter+'&status='+statusFilter+'&tag='+tagFilterName+'&custom='+customFieldFilter;
        
        window.history.pushState({path:newurl},'',newurl);
        

        var boardNumber = $('.ideapush-container').attr('data');
        
//        console.log(sortFilter);
//        console.log(statusFilter);
//        console.log(tagFilter);
//        console.log(boardNumber);
    //    console.log(customFieldFilter);
        
 
        var data = {
            'action': 'get_new_ideas',
            'boardNumber': boardNumber,
            'sortFilter': sortFilter,
            'statusFilter': statusFilter,
            'tagFilter': tagFilter, 
            'customFieldFilter': customFieldFilter, 
        }; 

        jQuery.ajax({
        url: get_new_ideas.ajaxurl,
        type: "POST",
        data: data,
//        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
            
            
            if($('.idea-pagination').length){
                var activePageNumber = parseInt($('.idea-page-number.active').text());
            } else {
                //we need to do this else condition in case the previous filter brought no results and hence no pagination was created
                var activePageNumber = 1;    
            }
            
            
            
            //console.log(data);
            
            //we need to do these long lookups just in case they have multiple shortcodes on the one page!
            //in success response lets clear all list items in the ul
            $('.dynamic-idea-listing').empty();
            
            //clear search input
            $('.ideapush-search-input').val('');
    
            //reload scrollreveal
            $('.dynamic-idea-listing').append(data);
            
            //show page one items
            if($('.idea-pagination').length){
                
//                console.log(activePageNumber);
                
                showPageItems(activePageNumber);
                $('.idea-page-number').removeClass('active');
                $('.idea-page-'+activePageNumber).addClass('active');
                
                
            } 

            //call read more
            readMoreFunctionality();

            //do event
            $('body').trigger('ideasRendered');

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
        })
        .always(function() {
            /* ... */
            
            
        if(!$('.idea-pagination').length){
            // sr.sync();    
        }
            
            
        });

        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //show number of characters remaining on form description area
    $('.ideapush-form-idea-description').on('keyup', function() {
//        console.log('I was pressed');
        
        var valueOfDescription = $(this).val();
        var lengthOfDescription = valueOfDescription.length;

        var maxLength = parseInt($('.ideapush-form-idea-description').attr('maxlength'));
        
        //only show warning if we are getting close to the limit
        if(lengthOfDescription > (maxLength-1000)){
            
            $('.ideapush-form-idea-description-counter').css( "display", "block" );
            
            //first lets empty any existing value
            $('.counter-number').empty();

            $('.counter-number').append(lengthOfDescription);

            if(lengthOfDescription == maxLength){
                $('.ideapush-form-idea-description-counter').addClass('too-many-characters-warning');   
            } else {
                $('.ideapush-form-idea-description-counter').removeClass('too-many-characters-warning');        
            }
        } else {
            
            $('.ideapush-form-idea-description-counter').hide();    
        }
        
    });
    
    

    
    
    
    //do voting functionality
    $('.ideapush-container').on("click", ".ideapush-form-idea-tags", function (event) {
        
        event.preventDefault();
        $('.ideapush-form-idea-tags-input').focus();
        
    });
    
    


    function enterNewTag(){
        var data = {
            'action': 'is_person_able_to_add_tag',
        };

        jQuery.ajax({
        url: is_person_able_to_add_tag.ajaxurl,
        type: "POST",
        data: data,
        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
//                console.log(data);   
            
            if(data!=1){
                                    
                //get input value
                var input = $('.ideapush-form-idea-tags-input');

                //we are going to comment out the below line to crylic characters are respected
                // var inputValue = input.val().replace(/[^a-z A-Z0-9\u0400-\u04FF]/g,'');
                var inputValue = input.val();
                
                console.log(inputValue);
                
                //check for duplicate tags
                
                var duplicates = false;
                
                $( ".successful-tag" ).each(function( index ) {
                    
                    var existingTag = $(this).text().toUpperCase();
                    
                    if(inputValue.toUpperCase() == existingTag){
                        duplicates = true;    
                    }
                    
                });

                
                
                if(inputValue !== '' && duplicates == false){
                    
                    input.before( '<span class="successful-tag">'+inputValue+'<i class="ideapush-icon-Delete delete-idea-tag"></i></span>' );
                
                    //clear input
                    $('.ideapush-form-idea-tags-input').val('');   
                    
                    //clear suggestions
                    if($(".suggested-tags").length){
                        $( ".suggested-tags" ).empty();    
                    }
                    
                    
                }
                
                if(duplicates == true){
                    //display message
                    
                    //remove previous message
                    $('.tag-error-message').remove();
                    
                    var errorMessageDuplicate = $('.ideapush-form-idea-tags').attr('dataDuplicateError');
                    
                    $( '.ideapush-form-idea-tags' ).before( '<p class="tag-error-message">'+errorMessageDuplicate+'</p>' );


                    setTimeout(function() {
                        $('.tag-error-message').slideUp();
                    }, 3000);    
                    
                }
                
                
                
                
                
            } else {
                
                //remove previous message
                $('.tag-error-message').remove();
                
                //clear input
                $('.ideapush-form-idea-tags-input').val('');
                
                var errorMessage = $('.ideapush-form-idea-tags').attr('dataError');
                
                //display message
                $( '.ideapush-form-idea-tags' ).before( '<p class="tag-error-message">'+errorMessage+'</p>' );
                
                
                setTimeout(function() {
                    $('.tag-error-message').slideUp();
                }, 3000);
                
                
            }
            
            
        }); //end done
    }



    //detect comma or enter function for tag creation
    $('.ideapush-container').on("keyup", '.ideapush-form-idea-tags-input', function (e) {
        if (e.keyCode == 188 || e.keyCode == 13) {
            
            enterNewTag();        

        } //end key code check
    });



    //when someone leaves the tag field
    $('.ideapush-container').on('blur', '.ideapush-form-idea-tags-input', function (event) {

        enterNewTag();

    });


    
   
    //remove tag
    $('.ideapush-container').on("click", ".delete-idea-tag", function (event) {
        
        event.preventDefault();
        
        $(this).parent().remove();
    
    });
    
    
    
    //do voting functionality
    $('.ideapush-container').on("click", ".idea-vote-container i", function (event) {
        
        event.preventDefault();
                                
        //assess whether negative or positive click was made
        if($(this).hasClass('vote-up-unvoted')){
            var voteIntent = 'up';       
        } else {
            var voteIntent = 'down';        
        }
        
        
        //now we need to get the post id  (lets not get the threshold now because it could be compromised we can get the board number and threshold in php)
        var ideaId = $(this).parent().attr('data');
        
        
        //lets do another query
        var data = {
            'action': 'submit_vote',
            'voteIntent': voteIntent,
            'ideaId': ideaId,
        }; 

        jQuery.ajax({
        url: submit_vote.ajaxurl,
        type: "POST",
        data: data,
        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
            
            console.log(data);

            if(data == 'NOT LOGGED IN'){

                //the user is not logged in
                //prompt them to log in first before voting

                var translatedFirstName = $('#create-user-form').attr('data-first');
                var translatedLastName = $('#create-user-form').attr('data-last');
                var translatedEmail = $('#create-user-form').attr('data-email');
                var translatedEmailConfirm = $('#create-user-form').attr('data-email-confirm');
                var translatedPassword = $('#create-user-form').attr('data-password');

                alertify
                .okBtn($('#ok-cancel-buttons').attr('data-submit'))
                .cancelBtn($('#ok-cancel-buttons').attr('data-cancel'))
                .confirm($('#dialog-login-to-vote').attr('data')+'<input style="display:none;" type="text" class="ideapush-form-middle-name-popup" placeholder="Middle name" maxlength="100" required><input style="margin-top:20px;" type="text" class="ideapush-form-first-name-popup" placeholder="'+translatedFirstName+'" maxlength="100" required><input type="text" class="ideapush-form-last-name-popup" placeholder="'+translatedLastName+'" maxlength="100" required><input type="email" class="ideapush-form-email-popup" placeholder="'+translatedEmail+'" maxlength="150" required><input type="email" class="ideapush-form-email-confirm-popup" placeholder="'+translatedEmailConfirm+'" maxlength="150" required><input type="password" class="ideapush-form-password-popup" placeholder="'+translatedPassword+'" required>', function (ev) {

                    //remove previous error messages
                    $('.popup-entry-error').remove();
                            
                    //get inputs
                    var honey = $('.alertify .ideapush-form-middle-name-popup').val();
                    var firstName = $('.alertify .ideapush-form-first-name-popup').val();
                    var lastName = $('.alertify .ideapush-form-last-name-popup').val();
                    var email = $('.alertify .ideapush-form-email-popup').val();
                    var emailConfirm = $('.alertify .ideapush-form-email-confirm-popup').val();
                    var password = $('.alertify .ideapush-form-password-popup').val();
                    
                    
                    if(password.length > 0 && firstName.length > 0 && lastName.length > 0 && email.length > 0 && email == emailConfirm && email.indexOf('@') > -1 && honey.length < 1){
                        
                        //do query
                        var data = {
                            'action': 'create_user',
                            'firstName': firstName,
                            'lastName': lastName,
                            'email': email,
                            'password': password,
                        }; 
                        

                        jQuery.ajax({
                        url: create_user.ajaxurl,
                        type: "POST",
                        data: data,
                        context: this,    
                        })
                        .done(function(data, textStatus, jqXHR) {
                                                        
                            reRenderHeader('');
                            reRenderForm();
                               
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                        })
                        .always(function() {
                        });

                        
                    } else {

                        $( ".ideapush-form-email-confirm-popup" ).after('<p style="color: #f05d55;" class="popup-entry-error">'+$('#dialog-login-to-vote').attr('data')+'</p>');  
                        
                        ev.preventDefault();
                    }

                }, function(ev) {


                });

            } else if (data == 'FAILURE'){

                alertify.alert($('#dialog-no-vote').attr('data'));

            } else if(data == 'NO VOTES IN VOTE BANK'){

                alertify.error($('#no-votes-in-bank').attr('data'));

            } else {

                //example return 1|3|1|false|

                //convert string into array
                var dataAsArray = data.split('|');

                var voteScoreChange = dataAsArray[0];
                var scenarioUtilised = dataAsArray[1];
                var scoreNow = parseInt(dataAsArray[2]);
                var statusChange = dataAsArray[3];

                //lets change the score
                var ideaId = $(this).parent().find('.idea-vote-number').text(scoreNow);

                //lets change the icons
                if(scenarioUtilised == 1 || scenarioUtilised == 2){
        
                    $(this).parent().find('.vote-up-unvoted').removeClass('ideapush-icon-Up-Vote-Solid').addClass('ideapush-icon-Up-Vote');
                    $(this).parent().find('.vote-down-unvoted').removeClass('ideapush-icon-Down-Vote-Solid').addClass('ideapush-icon-Down-Vote');

                }


                if(scenarioUtilised == 3 || scenarioUtilised == 5){
                    
                    $(this).parent().find('.vote-up-unvoted').removeClass('ideapush-icon-Up-Vote').addClass('ideapush-icon-Up-Vote-Solid');                           
                    $(this).parent().find('.vote-down-unvoted').removeClass('ideapush-icon-Down-Vote-Solid').addClass('ideapush-icon-Down-Vote');
                }


                if(scenarioUtilised == 4 || scenarioUtilised == 6){
                    
                    $(this).parent().find('.vote-up-unvoted').removeClass('ideapush-icon-Up-Vote-Solid').addClass('ideapush-icon-Up-Vote');                         
                    $(this).parent().find('.vote-down-unvoted').removeClass('ideapush-icon-Down-Vote').addClass('ideapush-icon-Down-Vote-Solid');
                }

                
                
                //if the view is 'My Voted' then if the vote is negative remove it from the view
                if($('.ideapush-sort').val() == 'my-voted' && (scenarioUtilised == 4 || scenarioUtilised == 6 || scenarioUtilised == 1 || scenarioUtilised == 2)){
                    $(this).parent().parent().parent().slideUp();          
                    
                }
                


                //if status changed remove the item
                //only do this on the all idea page otherwise it will make the header disapear on the single page
                if(statusChange == 'true' && $('body').hasClass('idea-push')){
                    $(this).parent().parent().parent().slideUp();     
                }


                //this for the single page display so we refresh the header because when an idea has reached reviewed we want to show this and prevent them from voting any further
                if(statusChange == 'true' && !$('body').hasClass('idea-push')){


                    var ideaId = $('.idea-vote-container').attr('data');



                    //get new header
                    //get new vote counter
                    var data = {
                    'action': 'below_title_header',
                    'ideaId': ideaId,
                    }; 

                    jQuery.ajax({
                        url: below_title_header.ajaxurl,
                        type: "POST",
                        data: data,
                        context: this,    
                        })
                        .done(function(data, textStatus, jqXHR) {

                            //replace vote counter countents
                            $('.ideapush-container').html(data);


                        }).fail(function(jqXHR, textStatus, errorThrown) {
                        })
                        .always(function() {
                        }); 


                }



                //rerender if vote above or below is different
                //only do this if they are on the recent tab otherwise there's no need to reorder becuse the other tabs aren't sorted by popularity
                if($('.ideapush-sort').val() == 'popular'){

                    if(voteScoreChange > 0){


                        var previousScore = parseInt($(this).parent().parent().parent().prev().find('.idea-vote-number').text());

                        if(scoreNow > previousScore){

                            //reorder
                            commonReOrderAndReRenderingOfIdeas('');
                        }



                    } else {
                        //they downvoted so check below
                        var nextScore = parseInt($(this).parent().parent().parent().next().find('.idea-vote-number').text());


                        if(scoreNow < nextScore){

                            //reorder
                            commonReOrderAndReRenderingOfIdeas('');
                        }

                    }    

                }

            }


        });


    });
    
    










    
 
    //make the search input wider
    $('body').on('focus', '.ideapush-search-input',function(){
        $(this).animate({ width: '+=30' }, 'medium');
        $(this).next().removeClass('ideapush-icon-Search').addClass('ideapush-icon-Delete');
    })
        
    
    
    
    $('body').on('mousedown', '.ideapush-idea-search .ideapush-icon-Delete',function(){
        
//        console.log('I was clicked!!!!!!!');
        
        $(this).prev().val('');
        $(this).removeClass('ideapush-icon-Delete').addClass('ideapush-icon-Search');
        
        
                
        //show appropiate page items
        if($('.idea-pagination').length){

            var activePageNumber = parseInt($('.idea-page-number.active').text());

            showPageItems(activePageNumber);    

        } else {
            //show everything
            $(".idea-item").removeClass('hidden-idea');
        }

        
        
        var searchMatchesFound = $('.search-match-found');
        searchMatchesFound.contents().unwrap();
    });
    
    
    $('body').on('blur', '.ideapush-search-input',function(event){
        
//        console.log(event.target);
        
        $(this).animate({ width: '-=30' }, 'medium');
        $(this).next().removeClass('ideapush-icon-Delete').addClass('ideapush-icon-Search'); 
    });
    
    
    
    $('body').on('keyup', '.ideapush-search-input',function(){
        
        var inputValue = $(this).val();
        
        if(inputValue == ""){
            $(this).next().removeClass('ideapush-icon-Delete').addClass('ideapush-icon-Search');  
        } else {
            $(this).next().removeClass('ideapush-icon-Search').addClass('ideapush-icon-Delete');       
        }

    });
    
    
    
    
    
    //function to rerender the header
    function reRenderHeader(showing){
        
        //get board number
         var boardNumber = $('.ideapush-container').attr('data');
        
        //do query
        var data = {
            'action': 'header_render',
            'boardNumber': boardNumber,
            'showing': showing,
        }; 

        jQuery.ajax({
        url: header_render.ajaxurl,
        type: "POST",
        data: data,
        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
            
            $('.ideapush-container-idea-header').empty();
            $('.ideapush-container-idea-header').append(data);

            //this seems to be stuffing things up so I need to look at this later
            resizeAllHeaderSelect();

            $('body').trigger('headerRendered');
            
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
        })
        .always(function() {

            
        });

        
 
    }
    
    
    
    //function to rerender the header
    function reRenderForm(){
        
        //get board number
        var boardNumber = $('.ideapush-container').attr('data');
        
        //do query
        var data = {
            'action': 'form_render',
            'boardNumber': boardNumber,
        }; 

        jQuery.ajax({
        url: form_render.ajaxurl,
        type: "POST",
        data: data,
        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
            
            $('.ideapush-container-form').empty();
            $('.ideapush-container-form').append(data);
            
            $('body').trigger('formRendered');
     
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
        })
        .always(function() {
        });
 
    }
    
    
    
    
    
    //submit new idea functionality
    $('body').on('click', '.submit-new-idea',function(event){

        event.preventDefault();

        if($('.idea-publish-loading').css('display') == 'none'){

            $('.idea-publish-loading').slideDown();

            function standardFieldCheck(){
                
                var errors = 0;
                
                var title = $('.ideapush-form-idea-title').val();
                // var description = $('.ideapush-form-idea-description').val();
                
                if(title.length < 1){
                // if(title.length < 1 || description.length < 1){
                    errors++; 
                }
                
                
                
                if($('.ideapush-form-first-name').length){
                    
                    var firstName = $('.ideapush-form-first-name').val();
                    var lastName = $('.ideapush-form-last-name').val();
                    var email = $('.ideapush-form-email').val();
                    var password = $('.ideapush-form-password').val();
                    
                    
                    if(firstName.length < 1 || lastName.length < 1 || email.length < 1 || password.length < 1){ 
                        errors++;
                    }  
                }
                
                return errors;
                
            }
            
            
            
            
            
            
            function attachmentCheck(){
                
                var errors = 0;
                        
                if($('.ideapush-form-idea-attachment').length && $('.ideapush-form-idea-attachment').get(0).files.length !== 0){
                
                    var attachment = $('.ideapush-form-idea-attachment').prop("files")[0];

                    var acceptedFiles = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif','image/x-icon','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation','	application/vnd.openxmlformats-officedocument.presentationml.slideshow','application/vnd.oasis.opendocument.text','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','image/vnd.adobe.photoshop','audio/mpeg3','audio/wav','video/mp4','video/quicktime','video/x-ms-wmv','video/x-msvideo','video/3gpp'];
                    
                    var attachmentFileType = attachment['type'];
                    
    //                console.log(attachmentFileType);
                    
                    var attachmentSize = attachment['size'];
                    
                    var maxSize = parseInt($('#max-file-size').attr('data'));
                    maxSize = maxSize * 1000000;

                    if(attachmentSize <= maxSize && acceptedFiles.indexOf(attachmentFileType) > -1){
    
                    } else { 
                        errors++;    
                    }
                    
                }   
                    
                return errors;    

            }


            function privacyCheck(){
                
                var errors = 0;
                    
                if($('.ideapush-form-privacy-confirmation').length){
                    if (!$('.ideapush-form-privacy-confirmation').is(':checked')) {
                        //the checkbox is not checked
                        errors++;  
                    }
                }
                    
                return errors;    
            }
            
            
        
            function showErrorPopup(message){
                alertify.error(message);
            }
            
        
            
            
            var allClearOfErrors = true;
            
            //do math and honeypot
            if($('.ideapush-form-math-problem').length){
                
                
                //get submitted value
                var submittedMathValue = parseInt($('.ideapush-form-math-problem').val());
                
                //get first number
                var firstNumber = $('.ideapush-form-math-problem').attr('data-number-one');
                
                //get second number
                var secondNumber = $('.ideapush-form-math-problem').attr('data-number-two');
                
                //get sign
                var sign = $('.ideapush-form-math-problem').attr('data-sign');
                
                //if the sign is an x make it a multiply symbol for calculation
                if(sign == 'x'){
                    sign = '*';    
                }
                
                var calculation = eval(firstNumber+sign+secondNumber); 
                
    //            console.log(calculation);
    //            console.log(submittedMathValue);
                
                
                if(submittedMathValue !== calculation){
                    allClearOfErrors = false;
                    $('.idea-publish-loading').slideUp();
                    showErrorPopup($('#dialog-math-fail').attr('data'));
                    
                } 
                
                
                var honeyPot = $('.ideapush-form-extended-description').val();
                
                // console.log(honeyPot.length);

                if(honeyPot.length > 0){
                    allClearOfErrors = false;
                    $('.idea-publish-loading').slideUp();
                    showErrorPopup($('#dialog-honey-fail').attr('data'));        
                }

            }
            
            function customFieldCheck(){
                var errors = 0;

                //cycle through each custom field
                $(".ideapush-form-custom-field").each(function() {

                    //only raise an error if the field is required
                    var requiredCustomField = $(this).attr('data-required');
                    var typeCustomField = $(this).attr('data-type');

                    if(requiredCustomField == 'yes' && typeCustomField != 'select'){

                        //do input check
                        if(
                            typeCustomField == 'text' || 
                            typeCustomField == 'video' || 
                            typeCustomField == 'website' ||
                            typeCustomField == 'date' ||
                            typeCustomField == 'image'
                        ){
                            if($(this).find('input').val().length < 1){
                                errors++; 
                            }  
                        }    

                        //do textarea check
                        if(typeCustomField == 'textarea'){
                            if($(this).find('textarea').val().length < 1){
                                errors++; 
                            }      
                        } 

                        //do radio check
                        if(typeCustomField == 'radio' || typeCustomField == 'checkbox'){
                            var secondaryCount = 0;

                            $(this).find('input').each(function() {
                                if($(this).is(':checked')){
                                    secondaryCount++;   
                                }
                            });   
                            
                            if(secondaryCount == 0){
                                errors++;     
                            }
                        } 

                    }

                    //also do check for urls
                    if(typeCustomField == 'website'){
                        if(!isValidURL($(this).find('input').val()) && $(this).find('input').val().length > 0){
                            errors++;
                        }
                    }


                });    

                return errors;
            }



            //do custom field check
            //only do field check if there are custom fields
            if($('.ideapush-form-custom-field').length){
                if(customFieldCheck()>0){
                    allClearOfErrors = false;
                    $('.idea-publish-loading').slideUp();
                    showErrorPopup($('#dialog-file-fail').attr('data'));     
                }
            }
                

            //do file check
            if(standardFieldCheck()>0){
                allClearOfErrors = false;
                $('.idea-publish-loading').slideUp();
                showErrorPopup($('#dialog-file-fail').attr('data'));     
            }
            
            //do attachment check
            if(attachmentCheck()>0){
                allClearOfErrors = false;
                $('.idea-publish-loading').slideUp();
                showErrorPopup($('#dialog-attachment-fail').attr('data'));     
            }

            //privacy check
            if(privacyCheck()>0){
                allClearOfErrors = false;
                $('.idea-publish-loading').slideUp();
                showErrorPopup($('#dialog-privacy-fail').attr('data'));     
            }
            
            
            

            
            if(allClearOfErrors == true){

                //get inputs
                var title = $('.ideapush-form-idea-title').val();
                var description = $('.ideapush-form-idea-description').val();
                
                //get logged out fields
                if($('.ideapush-form-first-name').length){
                    var firstName = $('.ideapush-form-first-name').val();
                    var lastName = $('.ideapush-form-last-name').val();
                    var email = $('.ideapush-form-email').val();   
                    var password = $('.ideapush-form-password').val();    
                } else {
                    var firstName = 'LOGGED-IN';
                    var lastName = 'LOGGED-IN';
                    var email = 'LOGGED-IN';  
                    var password = 'LOGGED-IN';  
                }
                            
                //get tags
                
                var tags = '';
                
                $( ".successful-tag" ).each(function( index ) {
                    
                    tags += $(this).text()+',';
                    
                });
                
                

                //get board number
                var boardNumber = $('.ideapush-container').attr('data');
                
                //get attachment
                if($('.ideapush-form-idea-attachment').length){
                    var attachment = $('.ideapush-form-idea-attachment').prop("files")[0];    
                }

                
                
                var form_data = new FormData();
                form_data.append("action", 'create_idea');
                form_data.append("boardNumber", boardNumber); 
                form_data.append("title", title);
                form_data.append("description", description);
                form_data.append("firstName", firstName);
                form_data.append("lastName", lastName);
                form_data.append("email", email);
                form_data.append("password", password);
                form_data.append("tags", tags);
                //only send the attachment if it exists
                if($('.ideapush-form-idea-attachment').length){
                    form_data.append("attachment", attachment);
                }

                //add custom fields to formdata
                //only do if custom fields exist
                if($('.ideapush-form-custom-field').length){
                    //append custom fields
                    var customFieldData = '';
                    var customImageData = '';

                    //cycle through each custom field
                    $(".ideapush-form-custom-field").each(function() {

                        //we only want to add the field if there is a value
                        //this means for text, textarea, checkbox and radio we may not be sending anything

                        //do input
                        if(
                        $(this).attr('data-type') == 'text' || 
                        $(this).attr('data-type') == 'video' || 
                        $(this).attr('data-type') == 'website' ||
                        $(this).attr('data-type') == 'date'
                        ){
                            if($(this).find('input').val().length>0){
                                customFieldData += '||||'+$(this).attr('data-field-name')+'|||'+$(this).find('input').val();   

                            }
                        }

                        //do textarea
                        if($(this).attr('data-type') == 'textarea'){
                            if($(this).find('textarea').val().length>0){
                                customFieldData += '||||'+$(this).attr('data-field-name')+'|||'+$(this).find('textarea').val();   
                            }
                        }

                        //do select
                        if($(this).attr('data-type') == 'select'){
                            customFieldData += '||||'+$(this).attr('data-field-name')+'|||'+$(this).find('select option:selected').text();   
                        }

                        //do radio
                        //do checkbox
                        if($(this).attr('data-type') == 'radio' || $(this).attr('data-type') == 'checkbox'){

                            var valueBuilder = '';

                            $(this).find('input').each(function() {
                                if($(this).is(':checked')){
                                    valueBuilder += $(this).val()+', ';
                                }
                            });   

                            //remove any final commas from value builder
                            valueBuilder = valueBuilder.replace(/,\s*$/, "");

                            customFieldData += '||||'+$(this).attr('data-field-name')+'|||'+valueBuilder;

                        }

                        //do image
                        if($(this).attr('data-type') == 'image'){
                            if($(this).find('input').val().length>0){
                                var imageData = $(this).find('input').prop("files")[0];
                                
                                //we need to make the field name more standard
                                var imageName = $(this).attr('data-field-name').replace(/\s+/g, '-').toLowerCase();

                                
                                // form_data.append($(this).attr('data-field-name'), imageData);
                                form_data.append(imageName, imageData);
                                customImageData += '||||'+$(this).attr('data-field-name');
                            }
                        }



                    });    
                    
                    //what we need is the name of the custom field and the value(s)
                    form_data.append("customFields", customFieldData);
                    form_data.append("customImageFields", customImageData);

                } else {
                    form_data.append("customFields", '');    
                }    
            
                // console.log(customFieldData);

                //do query
                jQuery.ajax({
                url: create_idea.ajaxurl,
                type: "POST",
                data: form_data,     
                context: this,
                processData: false,
                contentType: false,
                cache: false,
                })
                .done(function(data, textStatus, jqXHR) {

                    // console.log(data);
                    
                    //hide loading
                    $('.idea-publish-loading').slideUp();
                    
                    if(data == 'FAILURE'){
                            
                        //the person is not allowed to create an idea or idea creation has been disabled so show a popup

                        alertify.error($('#dialog-no-idea').attr('data'));

                    } else if(data == 'DUPLICATE'){

                        //the person already has an account
                        alertify.error($('#dialog-existing-account').attr('data'));

                    } else {
                        
                        var returnedData = data.split('|');
                        var holdIdeas = returnedData[0];
                        var userID = returnedData[1];
        //                console.log(data);

                        //first re-render the header that way it doesn't interfere with the change that will happen

                        if(holdIdeas !== 'Yes'){
                            //we are going to show the my ideas after an idea has been published so the person can see it
                            //console.log(userID);
                            
                            commonReOrderAndReRenderingOfIdeas(userID);
                            reRenderHeader(userID);

                        }

                        reRenderForm();


                        if(holdIdeas == 'Yes'){
                            var message = $('#dialog-idea-reviewed').attr('data');    
                        } else {
                            var message = $('#dialog-idea-published').attr('data');
                        }


                        $(document).one("ajaxStop", function() {
                            alertify.alert(message);
                        });
        
                    }

                    

                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                })
                .always(function() {
                });
                
                
                
                
                
                
                
    
            } //end of error check
        }

    }); //end create idea function
    
    






    
    
    $('.ideapush-container').on("click", ".idea-item-delete", function (event) {
        event.preventDefault();

        //yes was clicked
        //get the idea id    
        var ideaId = $('.idea-item-delete').attr('data');  
    
        alertify
        .okBtn($('#ok-cancel-buttons').attr('data-yes'))
        .cancelBtn($('#ok-cancel-buttons').attr('data-no'))
        .confirm($('#dialog-idea-delete').attr('data'), function (ev) {

            //do query
            var data = {
                'action': 'delete_idea',
                'ideaId': ideaId,
            }; 

            jQuery.ajax({
            url: delete_idea.ajaxurl,
            type: "POST",
            data: data,
            context: this,    
            })
            .done(function(data, textStatus, jqXHR) {

                alertify.success($('#dialog-idea-deleted').attr('data'));

            });       
                
        }, function(ev) {

            //no was clicked
        

        });

    });
    
    
    
    
    
    
    //change status
    $('.ideapush-container').on("click", ".idea-item-statuses", function (event) {
        event.preventDefault();
//        console.log('I was clicked');
        
        //get data attribute
        var statusNameToChangeTo = $(this).attr('data');
        
        var ideaId = $('.ideapush-container').attr('data');
        
        //do query
        var data = {
            'action': 'change_status',
            'status': statusNameToChangeTo,
            'ideaId': ideaId,
        }; 

        jQuery.ajax({
        url: change_status.ajaxurl,
        type: "POST",
        data: data,
        context: this,    
        })
        .done(function(data, textStatus, jqXHR) {
            
//            console.log(data);
            
            var responseAsArray = data.split('|');
            
            
            //lets replace existing status first
            $('.status-container').html(responseAsArray[0]);
            
            $('.status-listing-container').html(responseAsArray[1]);
            
            //now we are going to be super fancy and re-render the vote counter when status changes because we don't want people to vote for non-open ideas
            //hide voting if changing to a non open status
            
            var data = {
            'action': 'update_vote_counter',
            'status': statusNameToChangeTo,
            'ideaId': ideaId,
            }; 
            
            jQuery.ajax({
                url: update_vote_counter.ajaxurl,
                type: "POST",
                data: data,
                context: this,    
                })
                .done(function(data, textStatus, jqXHR) {
                    
                    //replace vote counter countents
                    $('.idea-item-left').html(data);
                
                
                }).fail(function(jqXHR, textStatus, errorThrown) {
                })
                .always(function() {
                });        
                
            
            
            
            
        
        }).fail(function(jqXHR, textStatus, errorThrown) {
        })
        .always(function() {
        });
         
    });
    
    
    
  
    
    
    
    
    
    
    
    
   $('.ideapush-container').on("click", ".idea-item-file", function (event) {
       
       event.preventDefault();
       
       var imageUrl = $(this).attr('href');
       
       alertify.alert('<img style="margin-bottom: 15px;" src="'+imageUrl+'">');
       
   });
    
    
    
    //remove comments from post if no permission granted
    if($('#remove-comments').length){
        $('#comments').remove();
        
    }
    
    
    
    
    
    
    //show page one of list items initially
    function showPageItems(pageNumber){
        
        //hide all ideas
//        $(".idea-item").addClass('hidden-idea'); 

        
//        console.log(pageNumber);
        
        $(".idea-item").each(function() {
            
            
//            $(this).css('display','none');
            
            
            if($(this).attr('data-page') == pageNumber){
                
//                console.log('a match was found');
//                $(this).slideDown();
                $(this).removeClass('hidden-idea');
//                $(this).css( "display", "table" );
            } else {
                
                $(this).addClass('hidden-idea');
//                $(this).css( "display", "none" );
                
            }
            
        });    
        
    }
    
    
    
    if($('.idea-pagination').length){
        showPageItems(1);    
    }
    
    
    
    
    
    
    
    
    
    
    if($('.ideapush-container').length){
        //do page number click
        jQuery.fn.extend(
            {
            scrollTo : function(speed, easing)
            {
                return this.each(function()
                {
                var targetOffset = $(this).offset().top;
                $('html,body').animate({scrollTop: targetOffset}, speed, easing);
                });
            }
            });

    }
    

    
    
    
    $('body').on('click', '.idea-page-number',function(event){
        event.preventDefault();
        
        //scroll to top
        $('.ideapush-container-idea-header').scrollTo(300);
        
        //get target page number
        
        var targetPage = parseInt($(this).text());
        
        
        showPageItems(targetPage);

        
        //remove existing active class
        $('.idea-page-number').removeClass('active');
        
        //add active class to clicked item
        
        $(this).addClass('active');
        
   
    });
    
    
  
    
    
    //do countdown if countdown exists
    if($('#challenge-countdown').length){
        
        var countDownTimeAndDate = $('#challenge-countdown').attr('data-expiry');
        var currentTimeAndDate = $('#challenge-countdown').attr('data-now');
        
        //console.log(countDownTimeAndDate);
        
        var countDown = new Date(countDownTimeAndDate).getTime();
        var now = new Date(currentTimeAndDate).getTime();
        
        
        
        var x = setInterval(function() {

            
            
            //console.log(now);

            // Find the distance between now an the count down date
            var distance = countDown - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            var message = '';
            
            if(days != 0){
                message += days + "d ";       
            }
            
            if(hours != 0){
                message += hours + "h ";       
            }
            
            if(minutes != 0){
                message += minutes + "m ";       
            }
            
            message += seconds + "s";
                
            
            $('#challenge-countdown').empty();
            $('#challenge-countdown').append(message);    
            
            now = now+1000;
            //console.log(now);
            
        }, 1000);
        
        
        
    }

    

});