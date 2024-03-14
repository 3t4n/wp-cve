(function($){
    
    $(document).ready(function () {
        var WPelement = wp.element;
        var WPdata = wp.data;
        var WPplugins = wp.plugins;
        var WPeditPost = wp.editPost;
        var WPi18n = wp.i18n;
        
        var el = WPelement.createElement;
        var registerPlugin = WPplugins.registerPlugin;
        var __ = WPi18n.__;
    
        var panel = WPeditPost.PluginDocumentSettingPanel;

        var settings = configureSettings();
        
        var loaderEl = el(
            'svg',
            {
                width: 20,
                height: 20,
                viewBox: "0 0 38 38",
                stroke: "#fff",
                className: "ays-chatgpt-assistant-title-loader"
            },
            el(
                'g',
                {
                    fill: 'none',
                    fillRule: "evenodd"
                },
                el(
                    'g',
                    {
                        transform: 'translate(1 1)',
                        strokeWidth: 2
                    },
                    el(
                        'circle',
                        {
                            strokeOpacity: ".5",
                            cx: 18,
                            cy: 18,
                            r: 18
                        },
                        ''
                    ),
                    el(
                        'path',
                        {
                            d: "M36 18c0-9.94-8.06-18-18-18"
                        },
                        el(
                            'animateTransform',
                            {
                                attributeName: "transform",
                                type: "rotate",
                                from: "0 18 18",
                                to: "360 18 18",
                                dur: "1s",
                                repeatCount: "indefinite",
                            },
                            ''
                        ),
                    ),
                )
            )
        );

        registerPlugin( 'ays-chatgpt-assistant-panel', { render: aysPanelGenerate } );
        function aysPanelGenerate () {
            return (
                el(
                    panel,
                    {
                        name: "chatgpt-assistant-custom-panel",
                        title:  "Ays ChatGPT Assistant",
                        className: "ays-chatgpt-assistant-panel-body",
                    },
                    el(
                        "button",
                        {
                            className: 'ays-chatgpt-assistant-panel-suggest-title'
                        },
                        loaderEl,
                        __("Suggest Title", "chatgpt-assistant")
                    )
                )
            );
        }

        $(document).on('click', 'button.ays-chatgpt-assistant-panel-suggest-title', function (e) {
            var loader = $(document).find('.ays-chatgpt-assistant-title-loader');
            loader.show();

            var title = WPdata.select('core/editor').getEditedPostAttribute('title');
            
            if (title.trim() === '') {
                loader.hide();
                swal({
                    type: 'error',
                    title: __('Empty post title', "chatgpt_assistant"),
                });
            } else {
                var prompt = `Suggest 5 meaningful alternative titles that convey the same meaning as the following title: '` + title + `'. Try to understand the meaning of the title, only after that generate alternatives. Do not include the original title. The language should the same as the title above. Present the titles in a JSON format as shown: '{"titles": ["title1", "title2", "title3", "title4", "title5"]}'. Only include the object of titles in the response, not a single symbol more.`;
                if (settings.readyK != '') {
                    switch(settings.chatModel){
                        case 'gpt-3.5-turbo':
                        case 'gpt-3.5-turbo-16k':
                        case 'gpt-4':
                        case 'gpt-4-1106-preview':
                            requestUrl = "https://api.openai.com/v1/chat/completions";
                            break;
                        default:
                            requestUrl = "https://api.openai.com/v1/completions";
                            break;
                    }
        
                    var sendData = {
                        requestUrl : requestUrl,
                        apiKey : settings.readyK,
                        prompt : prompt,
                        chatConversation : [],
                    }
                    
                    makeRequest(sendData , settings)
                    .then(data => {
                        var checkError = (typeof data.error == "object" ) ? false : true;
                        if(checkError){

                            switch(settings.chatModel){
                                case 'gpt-3.5-turbo':
                                case 'gpt-3.5-turbo-16k':
                                case 'gpt-4':
                                case 'gpt-4-1106-preview':
                                    var response = JSON.parse(data.choices[0].message.content.replace(/^\n+/, ''));
                                    break;
                                default:
                                    var response = JSON.parse(data.choices[0].text.replace(/^[^:]+:\s*/, '').replace(/^\n+/, ''));
                                    break;
                            }

                            if (response.titles.length > 0) {
                                var content = '<div class="ays-chatgpt-assistant-select-title-suggestion-container">';

                                response.titles.forEach(res => {
                                    if (res != '') {
                                        content += '<button class="ays-chatgpt-assistant-select-title-suggestion">' + res + '</button>';
                                    }
                                });

                                content += "</div>";

                                loader.hide();
                                swal({
                                    title: '<span class="ays-chatgpt-assistant-select-title-popup-title">' + "New title for '" + title + "'</span>",
                                    html: content,
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                });
                            } else {
                                loader.hide();
                                swal({
                                    type: 'error',
                                    title: __('Something went wrong', "chatgpt_assistant"),
                                });
                            }
                        } else {
                            var errorMessage = '';
                            if(data.error.type == "insufficient_quota"){
                                errorMessage = " <a href='https://platform.openai.com/account/usage'> https://platform.openai.com/account/usage </a>";
                            }
                            
                            loader.hide();
                            swal({
                                type: 'error',
                                title: __('Something went wrong', "chatgpt_assistant"),
                                text: data.error.message + errorMessage
                            });
                        }
                    });
                } else {
                    loader.hide();
                    swal({
                        type: 'error',
                        title: __('Invalid OpenAI API Key', "chatgpt_assistant"),
                        text: __('Please connect to OpenAI first', "chatgpt_assistant")
                    });
                }
            }
        });
        $(document).on('click', '.ays-chatgpt-assistant-select-title-suggestion', function () {
            swal.close();
            WPdata.dispatch( 'core/editor' ).editPost( { title: $(this).text() } )
        });
        
        function configureSettings () {
            var settings = {};

            var key = AysChatGPTChatSettings.translations.ka ? atob(AysChatGPTChatSettings.translations.ka) : '';
            var index = key.indexOf("chgafr");
            settings.readyK = (index !== -1) ? key.substring(0, index) : '';

            settings.chatModel = AysChatGPTChatSettings.chatModel ? AysChatGPTChatSettings.chatModel : 'gpt-3.5-turbo-16k';
            
            return settings;
        }
    });

})(jQuery);