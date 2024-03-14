jQuery(document).ready(function ($) {
    
    function activateTooltips(){
        //enable tooltips on settings
        tippy('#board-settings label',{
            interactive: true,
            arrow: true,
            arrowType: 'sharp',
        });
    }
    activateTooltips();
    

    //make board sortable
    $( "#board-settings" ).sortable();
    
    //make cloning remember select values
    (function (original) {
        jQuery.fn.clone = function () {
          var result           = original.apply(this, arguments),
              my_textareas     = this.find('textarea').add(this.filter('textarea')),
              result_textareas = result.find('textarea').add(result.filter('textarea')),
              my_selects       = this.find('select').add(this.filter('select')),
              result_selects   = result.find('select').add(result.filter('select'));
      
          for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
          for (var i = 0, l = my_selects.length;   i < l; ++i) {
            for (var j = 0, m = my_selects[i].options.length; j < m; ++j) {
              if (my_selects[i].options[j].selected === true) {
                result_selects[i].options[j].selected = true;
              }
            }
          }
          return result;
        };
      }) (jQuery.fn.clone);


    
    
    //selectively hide and show integrations depending on selection
     $('#idea_push_integration_service :selected').each(function () {
        var selectValue = $(this).val();
        $("#integrations .integration-setting").hide();
        $("."+selectValue).show();
    });
    
    
    $('#idea_push_integration_service').change(function () {
        var selectValue = $(this).val();
        $("#integrations .integration-setting").hide();
        $("."+selectValue).show();  
    });
    
    
    

    
    

    
    
    
    
    
    
    
    //make tabs tabs
    $( "#tabs" ).tabs();
    
    
    //make the accordion an accordion
    $("#accordion").accordion({
        collapsible: true,
        autoHeight: false,
        heightStyle: "content",
        active: false,
        speed: "fast"
    });
    
    
    //make links go to particular tabs
    $('.wrap').on("click",".open-tab", function(){
        var tab = $(this).attr('href');
        var index = $(tab).index()-1;        
        $('#tabs').tabs({active: index});
        $('#idea_push_tab_memory').val(tab);
    });
    
    
    // //add link to hidden link setting when a tab is clicked
    // $('.wrap').on("click", ".nav-tab", function () {
    //     var tab = $(this).attr('href');
    //     $('#idea_push_tab_memory').val(tab);
    // });
    
    
    
    //load previous tab when opening settings page
    if($('#ideapush_settings_form').length) {

        //get tab memory
        var tab = $('#ideapush_settings_form').attr('data-tab-memory');

        if(tab.length > 1) {

        var index = $(tab).index() - 1;
        $('#tabs').tabs({
            active: index
        });
        }
    }

    //save tab memory via ajax
    $('.wrap').on("click", ".nav-tab", function () {

        var tab = $(this).attr('href');

        var data = {
            'action': 'save_tab_memory',
            'tab': tab,
        };

        jQuery.post(ajaxurl, data, function (response) {
            console.log(response);
        });

    });


    
    
    //makes shortcode buttons push values over
    $('.ideapush_append_buttons').click(function() { 
        $(this).parent().next().children().val($(this).parent().next().children().val() + $(this).attr("value")); 
        $(this).parent().next().children().focus();      
    });
    
    
    //make subject and email dissapear on change
    $( '.wrap' ).on( 'change' ,'.enable-email-notification-checkbox input' ,function () {
        
        if(this.checked){            
            $(this).parent().parent().parent().next().next().show();
            $(this).parent().parent().parent().next().next().next().next().show();
        } else {            
            $(this).parent().parent().parent().next().next().hide();
            $(this).parent().parent().parent().next().next().next().next().hide();   
        } 
    });
    
    
    //make subject and email dissapear on initial load
    $( ".enable-email-notification-checkbox input" ).each(function( index ) {
        
        if(this.checked){            
            $(this).parent().parent().parent().next().next().show();
            $(this).parent().parent().parent().next().next().next().next().show();
        } else {            
            $(this).parent().parent().parent().next().next().hide();
            $(this).parent().parent().parent().next().next().next().next().hide();   
        } 
    });
    
    

    //hides and then shows on click help tooltips
    $(".hidden").hide();
    
    
    $('#ideapush_settings_form').on("click", ".ideapush_settings_row i", function (event) {
//        console.log('I was clicked');        
        event.preventDefault();
        $(this).next(".hidden").slideToggle();
    });

    //instantiates the Wordpress colour picker
    $('.my-color-field').wpColorPicker();

    
    

    //save settings using ajax    
    $('#ideapush_settings_form').submit(function(event) {
        
        event.preventDefault();
        //we need to check whether the boards tab is active and if it is we are going to do some magic first
        
        var activeTab = $('.ui-tabs-active a').text();
        
        if(activeTab == "Boards "){
            runBoardSaveRoutine();
        }

        if(activeTab == "Idea Form "){

            //first lets check if all the names are unique before we do anything
            //lets store an array which contains all the titles
            var allTitles = [];
            var duplicateFound = false;

            $('#form-settings-container > li').each(function () {
                //get title
                var title = $(this).find('.form-setting-name').val();
                // console.log(title);

                if($.inArray(title, allTitles) !== -1 ){
                    duplicateFound = true; 
                    return false;  
                } else {
                    allTitles.push(title);
                }

            }); 
            
            if(duplicateFound == true){

                //hide existing dialog
                alertify.alert($('#dialog-duplicate-form-setting-found').attr('data'));
                return false; 

            } else {
                runFormSaveRoutine();    
            }

 
        }
          
        
        $('<div class="notice notice-warning is-dismissible settings-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we save the settings...</p></div>').insertAfter('.ideapush-save-all-settings-button');

        tinyMCE.triggerSave();

        //delete plugin update transient
        deletepluginupdatetransient();

        $(this).ajaxSubmit({
            success: function(){

                $('.settings-loading-message').remove();

                $('<div class="notice notice-success is-dismissible settings-saved-message"><p>The settings have been saved.</p></div>').insertAfter('.ideapush-save-all-settings-button');

                setTimeout(function() {
                    $('.settings-saved-message').slideUp();
                }, 3000);
                
                //if the page is the integrations tab refresh the page so that we can see the connector finalisation button
                if(activeTab == "Integrations " || activeTab == "Idea Form "){
                    location.reload();
                }

                

            }
        });

        return false; 

        $('.settings-loading-message').remove();

    });

    
    
    //creates taxonomy item when add button is clicked
    
    $("#idea_push_create_board_button").click(function (event) {
        event.preventDefault();
        
        var boardName = $('#idea_push_create_board').val();
        
        var nonce = $(this).attr('data-nonce');
        
        if(boardName.length>0){
        
            var data = {
                'action': 'add_taxonomy_item',
                'boardName': boardName,
                'nonce': nonce,
            };

            jQuery.post(ajaxurl, data, function (response) {
                
//                console.log(response);
                
                if(response == "TERM-EXISTS") {
                    
                    $('<div class="notice notice-error"><p>The board name already exists, please enter a unique value.</p></div>').insertAfter('#idea_push_create_board_button');

                    setTimeout(function() {
                        $('.notice-error').slideUp();
                    }, 3000);
                    
                } else if(response == "NOT-PRO"){
                    
                    $('<div class="notice notice-warning"><p>Please upgrade to <a href="https://northernbeacheswebsites.com.au/ideapush" target="_blank">Pro</a> to create multiple boards. If you have already purchased the pro version, please activate the plugin by entering your details in the <a class="open-tab" href="#ideapush_pro">Licence Activation</a> tab.</p></div>').insertAfter('#idea_push_create_board_button');
                    

                    setTimeout(function() {
                        $('.notice-warning').slideUp();
                    }, 7000);
                    
                } else {
                
                    $('<div class="notice notice-success"><p>The board has been created. Please feel free to change the settings of the board below and then click the save settings button.</p></div>').insertAfter('#idea_push_create_board_button');

                    setTimeout(function() {
                        $('.notice-success').slideUp();
                    }, 7000);
                    
                    //zero out existing setting
                    $('#idea_push_create_board').val('');
                               
                    //lets push the line item into the ul
                    $( "#board-settings" ).append(response);

                    //activate tooltips
                    activateTooltips();

                    //run dependency check to hide neccessary fields
                    doFieldDependcies();

                    //run the presave routine
                    runBoardSaveRoutine();

                    //lets also save the settings
                    $('#ideapush_settings_form').ajaxSubmit({
                        success: function(){

                            $('.settings-loading-message').remove();

                            $('<div class="notice notice-success is-dismissible settings-saved-message"><p>The settings have been saved.</p></div>').insertAfter('.ideapush-save-all-settings-button');

                            setTimeout(function() {
                                $('.settings-saved-message').slideUp();
                            }, 3000);

                        }
                    });

                    return false; 
                    

                    
                }
                
            });
 
        }
    });
 
    
    
    
    


    
    //deletes a board
    $('.wrap').on("click",".delete-board",function(event) {
        event.preventDefault();
        
        var deleteBoardButton = $(this);

        alertify
        .okBtn("Yes")
        .cancelBtn("No")
        .confirm($('#dialog-delete-board-confirmation').attr('data'), function (ev) {
            deleteBoardButton.parent().parent().parent().parent().parent().remove();
        }, function(ev) {


        });

    });
    
    
    
    
    
    //clipboard function
    new ClipboardJS('.copy-board-shortcode');
    $('.wrap').on("click",".copy-board-shortcode",function(event) {
        event.preventDefault(); 
        
        var shortcodeData = $(this).attr('data-clipboard-text');
 
        alertify.alert('The shortcode has now been copied to your clipboard. Or you can copy the following shortcode: <code style="font-weight: bold;">'+shortcodeData+'</code>. Now just put this shortcode onto any post or page (page recommended). It is recommended that you put this onto a page which is full width and doesn\'t have a sidebar.');

    });

    
    
    
    
    function runBoardSaveRoutine(){
        
        var data = '';
        var comparisonData = '';
        //we need to cycle through each list item
        
        //we need to get the respective values and separate by | and separate the arrays by ||
        
        $('#board-settings li').each(function (index, element) {
        
            //get board id
            var boardId = $(this).attr('data');
            var boardName = '';
            
            //start building of setting
            var singleBoardSetting = boardId;

            //loop through the settings
            var $this = $(this);
            var $items = $this.find('.ideapush-board-setting-field');

            //now next loop
            $.each($items, function(index, element){
                
                //get the value
                if($(this).is('select')){
                    // var fieldValue = $(this).find("option:selected").text();
                    var fieldValue = $(this).val(); 
                } else {
                    var fieldValue = $(this).val();    
                }

                //do specific logic for date
                if($(this).hasClass('challenge-date-input') && fieldValue == ''){
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth()+1; 
                    var yyyy = today.getFullYear();

                    if(dd<10) {
                        dd='0'+dd;
                    } 

                    if(mm<10){
                        mm='0'+mm;
                    } 

                    fieldValue = yyyy+'-'+mm+'-'+dd; 

                }

                //do specific logic for time
                if($(this).hasClass('challenge-time-input') && fieldValue == ''){

                    fieldValue = new Date().toLocaleTimeString('en-US', { hour12: false, hour: "numeric", minute: "numeric"}); 

                }  

                //do board name
                if($(this).hasClass('board-name-input')){
                    boardName += fieldValue;
                }

                singleBoardSetting += '|'+fieldValue;

            });


            singleBoardSetting += '^^^';

            data += singleBoardSetting;
            
            var singleComparisonData = boardId+'|'+boardName+'^^^';
            
            comparisonData += singleComparisonData;
            
        });


        console.log(data);

        $('#idea_push_board_configuration').val(data);  
        
        //lets send off the comparison data to see if we need to delete any existing terms or rename them
        var data = {
            'action': 'taxonomy_save_routine',
            'comparisonData': comparisonData,
        };

        jQuery.post(ajaxurl, data, function (response) {
            //there's no need to do anything in the response
//            console.log(response);
        });
          
    }



    function runFormSaveRoutine(){

        //console.log('the save routine was ran');

        //store the setting in this variable
        var data = '';

        $('#form-settings-container > li').each(function () {

            //console.log('I was ran');

            //get the name
            var name = $(this).find('.form-setting-name').val();

            //get the standard settings
            var formTitle = $(this).find('.form-title').val();
            var ideaTitle = $(this).find('.idea-title').val();
            var ideaDescription = $(this).find('.idea-description').val();
            var ideaTags = $(this).find('.idea-tags').val();
            var ideaAttachment = $(this).find('.idea-attachment').val();
            var submitButton = $(this).find('.submit-button').val();
            var submitIdeaButton = $(this).find('.submit-idea-button').val();

            //remove any pipes from values
            formTitle.replace('|','');
            ideaTitle.replace('|','');
            ideaDescription.replace('|','');
            ideaTags.replace('|','');
            ideaAttachment.replace('|','');
            submitButton.replace('|','');
            submitIdeaButton.replace('|','');

            if(formTitle.length == 0){
                formTitle = ' ';
            }
            if(ideaTitle.length == 0){
                ideaTitle = ' ';
            }
            if(ideaDescription.length == 0){
                ideaDescription = ' ';
            }
            if(ideaTags.length == 0){
                ideaTags = ' ';
            }
            if(ideaAttachment.length == 0){
                ideaAttachment = ' ';
            }
            if(submitButton.length == 0){
                submitButton = ' ';
            }
            if(submitIdeaButton.length == 0){
                submitIdeaButton = ' ';
            }

            
            //create output
            data += '^^^^'+name;
            data += '|||';
            data += formTitle + '||';
            data += ideaTitle + '||';
            data += ideaDescription + '||';
            data += ideaTags + '||';
            data += ideaAttachment + '||';
            data += submitButton + '||';
            data += submitIdeaButton;
            data += '|||';

            //only do if pro options found
            if($(this).find('.form-setting-pro-options').length){

                var amountOfRows = $(this).find('.form-setting-pro-options li').length;

                //get custom settings
                $(this).find('.form-setting-pro-options li').each(function (index,element) {

                    var fieldType = $(this).find('.custom-field-type').val();
                    var fieldName = $(this).find('.custom-field-name').val();
                    var fieldOptions = $(this).find('.custom-field-options').val();
                    var fieldRequired = $(this).find('.custom-field-required').val();
                    var fieldFilter = $(this).find('.custom-field-filter').val();

                    //remove any pipes in options        
                    fieldName.replace('|','');
                    fieldOptions.replace('|','');


                    
                    var errors = false;

                    if(fieldName.length < 1){
                        errors = true;
                    }

                    if(  (fieldType == 'select' || fieldType == 'radio' || fieldType == 'checkbox') && fieldOptions.length < 1 ){
                        errors = true;
                    }
                    

                    if(fieldOptions.length < 1){
                        fieldOptions = ' ';
                    }

                    //console.log(errors);


                    if(errors == false){
                        
                        if(index === (amountOfRows - 1)){
                            // console.log('last row found');
                            data += fieldType+'|'+fieldName+'|'+fieldOptions+'|'+fieldRequired+'|'+fieldFilter;
                        } else {
                            data += fieldType+'|'+fieldName+'|'+fieldOptions+'|'+fieldRequired+'|'+fieldFilter+'||';
                        }


                    }


                    

                }); 

                
            } else {
                data += ' ';    
            }
 
        });    

        // console.log(data);

        //if string contain 6 pipes make it 4
        //data = data.replace("||||||","||||");

        $('#idea_push_form_settings').val(data);

  
    }


    
    //adds shortcode button text to tinymce area  
    $('.ideapush_append_buttons_advanced').click(function () {
        
        var attributeValue = $(this).attr('value');
                
        var id = $(this).attr('data');
        
        var attributeValueWrapped = '<p>'+attributeValue+'</p>';
        
        $('#'+id+'_ifr').contents().find("#tinymce p").html( $('#'+id+'_ifr').contents().find("#tinymce p").html() + attributeValueWrapped);
        
        $('#'+id+'-editor-container').find("textarea").html( $('#'+id+'-editor-container').find("textarea").html() + attributeValueWrapped);
        

        
    });
    
    
    if($('.datepicker').length){    
        $('.datepicker').datepicker({  
        dateFormat:"yy-mm-dd",    
        });   
    }

    
    
    //get reports
    $('.wrap').on("click","#get-ideapush-reports",function(event) {
        event.preventDefault();
        
        
        $('.report-body').hide();
        
        //user table needs the following:
        
        //1. user name (first and last presented as a link to their userprofile), 2. the users role 3. the amount of ideas created 4. the amount of upvotes, 5. the amount of downvotes
        
        //note we will show all users
        
        var startDate = $('#idea_push_start_date').val();
        var endDate = $('#idea_push_end_date').val();
        
        
        var data = {
                'action': 'get_user_table',
                'startDate': startDate,
                'endDate': endDate,
            };

        jQuery.post(ajaxurl, data, function (response) {
            
            console.log(response);
            
            //split string into array
            
            var separaterDataIntoTableAndChart = response.split('$$$');
            
            var separateUsers = separaterDataIntoTableAndChart[0].split('||');
            
            var holdingArray = [];
            
            for (var i = 0; i < separateUsers.length; i++) {
                
                var separateData = separateUsers[i].split('|');
                
                if(separateData.length>4){
                    
                    var arrayToPush = [separateData[0],separateData[1],parseInt(separateData[2]),parseInt(separateData[3]),parseInt(separateData[4])];
                    
                    holdingArray.push(arrayToPush);    
                }
  
            }

            var translation = $('#report-translation');
            var language_text = translation.attr('data-site-language');
            
            google.charts.load('current', {'packages':['table'], 'language': language_text});
            google.charts.setOnLoadCallback(drawTable);

            function drawTable() {

                var translation = $('#report-translation');
                 var name_text = translation.attr('data-name');
                 var role_text = translation.attr('data-role');
                 var ideas_created_text = translation.attr('data-ideas-created');
                 var up_votes_text = translation.attr('data-up-votes');
                 var down_votes_text = translation.attr('data-down-votes');

                var data = new google.visualization.DataTable();
                data.addColumn('string', name_text);
                data.addColumn('string', role_text);
                data.addColumn('number', ideas_created_text);
                data.addColumn('number', up_votes_text);
                data.addColumn('number', down_votes_text);
                data.addRows(
                    holdingArray     
            );

            var table = new google.visualization.Table(document.getElementById('user-table'));

            table.draw(data, {showRowNumber: false, width: '100%', height: '100%'});
            }
            
            
            
         
            
            
            
            
            
            
            
            
            
            //what we want to do is show a line graph showing ideas, upvotes and downvotes over time
        
     
              var separateDates = separaterDataIntoTableAndChart[1].split('||'); 
            
                
                var lineHoldingArray = [];

                for (var i = 0; i < separateDates.length; i++) {

                    var separateData = separateDates[i].split('|');

                    if(separateData.length>3){
                        
                        var date = new Date(separateData[0].replace('-',','));
                        
                        var arrayToPush = [date,parseInt(separateData[1]),parseInt(separateData[2]),parseInt(separateData[3])];

                        lineHoldingArray.push(arrayToPush);    
                    }

                }

            
            

                console.log(separateDates);

            
                var translation = $('#report-translation');
                var language_text = translation.attr('data-site-language');

              google.charts.load('current', {'packages':['line'], 'language': language_text});
              google.charts.setOnLoadCallback(drawChart);

              function drawChart() {

                  var data = new google.visualization.DataTable();

                  var translation = $('#report-translation');
                  var date_text = translation.attr('data-date');
                  var ideas_created_text = translation.attr('data-ideas-created');
                  var up_votes_text = translation.attr('data-up-votes');
                  var down_votes_text = translation.attr('data-down-votes');

                  data.addColumn('date', date_text);
                  data.addColumn('number', ideas_created_text);
                  data.addColumn('number', up_votes_text);
                  data.addColumn('number', down_votes_text);


                  data.addRows(lineHoldingArray);





                var pageWidth = $('.report-header').width();  

                var options = {
        //          title: 'Company Performance',
        //          curveType: 'function',
                    width: pageWidth,
                    height: 700,
                  legend: { position: 'bottom' },
                    lineWidth: 10,
                    series: {
                    0: { color: '#4eb5e1',lineWidth: 10 },
                    1: { color: '#5eb46a',lineWidth: 10 },
                    2: { color: '#f05d55',lineWidth: 10 },
                  },
                    backgroundColor: 'transparent',

                };

        //        var chart = new google.visualization.LineChart(document.getElementById('activity-line-graph'));
        //
        //        chart.draw(data, options);

                  var chart = new google.charts.Line(document.getElementById('activity-line-graph'));

                  chart.draw(data, google.charts.Line.convertOptions(options));

              } //end line graph



              $('.report-body').show();      
            
      

        }); //end response of ajax
        
        
        
    }); //end get reports
    
    
    
    
    
    
    
    //permanently hide notice
    $('.wrap').on("click",".ideapush-welcome .notice-dismiss", function(event){
        
        event.preventDefault();
        
        
        //check the checkbox
        $('#idea_push_hide_admin_notice').prop('checked',true);
        
        //save the settings
        $('#ideapush_settings_form').ajaxSubmit({
            success: function(){
                console.log('Settings saved');
            }
        });
        
        
        
    });
    
    
    
   
    $('.wrap').on("click",".zendesk-finalisation",function(event) {
        event.preventDefault();
        
        
        $('<div class="notice notice-warning is-dismissible zendesk-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we carry out these actions on your Zendesk account...</p></div>').insertAfter('.zendesk-finalisation');
        
        var data = {
            'action': 'zendesk_finalisation',

        };

        jQuery.post(ajaxurl, data, function (response) {

            console.log(response);
  
            $('.zendesk-loading-message').remove();

            $('<div class="notice notice-success is-dismissible zendesk-saved-message"><p>The actions on your Zendesk account have been completed. There is no need to save the settings.</p></div>').insertAfter('.zendesk-finalisation');

            setTimeout(function() {
                $('.zendesk-saved-message').slideUp();
            }, 5000);
            

        }); //end jquery post

        
        
    
    }); //end zendesk finalisation


    $('.wrap').on("click",".jira-finalisation",function(event) {
        event.preventDefault();
        
        
        $('<div class="notice notice-warning is-dismissible jira-loading-message"><p><i class="fa fa-spinner" aria-hidden="true"></i> Please wait while we carry out these actions on your Jira account...</p></div>').insertAfter('.jira-finalisation');
        
        var data = {
            'action': 'jira_finalisation',

        };

        jQuery.post(ajaxurl, data, function (response) {

            console.log(response);
  
            $('.jira-loading-message').remove();

            $('<div class="notice notice-success is-dismissible jira-saved-message"><p>The actions on your Jira account have been completed. There is no need to save the settings.</p></div>').insertAfter('.jira-finalisation');

            setTimeout(function() {
                $('.jira-saved-message').slideUp();
            }, 5000);
            

        }); //end jquery post

        
        
    
    }); //end zendesk finalisation

    
    
    
    //this checks to make sure the unique phrase is unique
    $('body').on('keyup', '#idea_push_zendesk_unique_phrase',function(){
        
        var inputValue = $(this).val();
        var hasNumber = /\d/;
        var specialCharacters = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
        
        
        if(inputValue.length < 8 || inputValue.indexOf(' ') >= 0 || hasNumber.test(inputValue) == false || /[a-z]/i.test(inputValue) == false || specialCharacters.test(inputValue) == false){
            
            $('.unique-phrase-message').remove();
            
            $('<div class="notice notice-error is-dismissible unique-phrase-message inline"><p>Please ensure you enter a phrase which has letters, numbers, special characters (like an underscore for example) and is at least 8 characters long and does not have spaces.</p></div>').insertAfter('#idea_push_zendesk_unique_phrase');    
            
        } else {
            $('.unique-phrase-message').slideUp();
            
        }
     
    });

    $('body').on('keyup', '#idea_push_jira_unique_phrase',function(){
        
        var inputValue = $(this).val();
        var hasNumber = /\d/;
        
        
        if(inputValue.length < 8 || inputValue.indexOf(' ') >= 0 || hasNumber.test(inputValue) == false || /[a-z]/i.test(inputValue) == false){
            
            $('.unique-phrase-message').remove();
            
            $('<div class="notice notice-error is-dismissible unique-phrase-message inline"><p>Please ensure you enter a phrase which has letters, numbers and is at least 8 characters long and does not have spaces.</p></div>').insertAfter('#idea_push_jira_unique_phrase');    
            
        } else {
            $('.unique-phrase-message').slideUp();
            
        }
     
    });
    
    
 
    










    //do field dependencies
    function doFieldDependcies(){
        $( '#board-settings select' ).each(function( index ) {
           
            var isDependency = $(this).attr('data-dependencies');
            var selectValue = $(this).val();

            if (typeof isDependency !== typeof undefined && isDependency !== false) {


                var itemsToHide = isDependency.split(',');

                $.each(itemsToHide, function( index, value ) {
                    if(selectValue == 'Yes'){
                        $('.'+value).show();
                    } else {
                        $('.'+value).hide();   
                    }
                });

            }  
            
        });
    }
    //run initially
    doFieldDependcies();



    //run on change
    $('body').on('change','#board-settings select',function(event) {

        var isDependency = $(this).attr('data-dependencies');
        var selectValue = $(this).val();

        if (typeof isDependency !== typeof undefined && isDependency !== false) {

            var itemsToHide = isDependency.split(',');

            $.each(itemsToHide, function( index, value ) {
                if(selectValue == 'Yes'){
                    $('.'+value).show();
                } else {
                    $('.'+value).hide();   
                }
            });

        }

    });










    //on form settings delete, delete the list item
    $('.wrap').on("click",".delete-form-settings",function(event) {
        event.preventDefault();

        var deleteFormSetting = $(this);
        
        //hide existing dialog
        alertify
        .okBtn("Yes")
        .cancelBtn("No")
        .confirm($('#dialog-delete-form-setting-confirmation').attr('data'), function (ev) {

            deleteFormSetting.parent().parent().parent().remove();

        }, function(ev) {

        });




    }); 
    
    //on form settings edit, toggle the form setting options
    $('.wrap').on("click",".edit-form-settings",function(event) {
        event.preventDefault();

        $(this).parent().parent().parent().find('.form-setting-inner-expanded-setting').slideToggle();

    });     
    
    
    //on form settings duplicate, duplicate the form setting option
    $('.wrap').on("click",".duplicate-form-settings",function(event) {
        event.preventDefault();

        var thisListItem = $(this).parent().parent().parent();

        var thisListItemName = thisListItem.find('.form-setting-name').val();

        thisListItem.clone().insertAfter(thisListItem);

        thisListItem.next().find('.form-setting-name').val(thisListItemName+' (copy)');
        thisListItem.next().find('.form-setting-name').prop('readOnly',false);
        thisListItem.next().removeClass('default-form-setting');

        //make custom fields sortable
        $( ".form-setting-pro-options" ).sortable();

    }); 


    //on form settings duplicate, duplicate the form setting option
    $('.wrap').on("click",".add-custom-field",function(event) {
       
        event.preventDefault();

        var thisListItem = $(this).parent().parent();

        thisListItem.clone().insertAfter(thisListItem);

        thisListItem.next().find('.custom-field-type').val('text');
        thisListItem.next().find('.custom-field-name').val('');
        thisListItem.next().find('.custom-field-options').val('');
        thisListItem.next().find('.custom-field-type').val('no');

    }); 


    //on form settings duplicate, duplicate the form setting option
    $('.wrap').on("click",".delete-custom-field",function(event) {
       
        event.preventDefault();

        $(this).parent().parent().remove();

    }); 

    //make custom fields sortable
    $( ".form-setting-pro-options" ).sortable();

    //selectively hide and show custom field options
    function hideAndShowCustomFieldOptions(){

        $('.form-setting-pro-options li').each(function () {
            
            if( 
                $(this).find('.custom-field-type').val() == 'text' || 
                $(this).find('.custom-field-type').val() == 'textarea' || 
                $(this).find('.custom-field-type').val() == 'website' || 
                $(this).find('.custom-field-type').val() == 'video' || 
                $(this).find('.custom-field-type').val() == 'date' ||
                $(this).find('.custom-field-type').val() == 'image'     
            ){
                $(this).find('.custom-field-options-container').hide();
                $(this).find('.custom-field-required-container').show();
                $(this).find('.custom-field-filter-container').hide();
            } else if($(this).find('.custom-field-type').val() == 'checkbox' || $(this).find('.custom-field-type').val() == 'radio') {
                $(this).find('.custom-field-options-container').show();
                $(this).find('.custom-field-required-container').show();
                $(this).find('.custom-field-filter-container').hide();
            } else {
                //this leaves the dropdown option
                $(this).find('.custom-field-options-container').show();
                $(this).find('.custom-field-required-container').hide();
                $(this).find('.custom-field-filter-container').show();
            }
            
        });


    }
    hideAndShowCustomFieldOptions();

    $( '.wrap' ).on( 'change' ,'.custom-field-type' ,function () {
        hideAndShowCustomFieldOptions();
    });   
    
    


    function deletepluginupdatetransient(){
        //_site_transient_update_plugins

        var data = {
            'action': 'idea_push_delete_plugin_updates_transient',
        };

        jQuery.post(ajaxurl, data, function (response) {
            // console.log('HELLO WORLD');
        });    
    }



    
    







});