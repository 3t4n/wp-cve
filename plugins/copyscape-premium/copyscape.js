/*
Copyright (c) 2013 Copyscape / Indigo Stream Technologies (www.copyscape.com)
License: MIT

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
document.addEventListener('DOMContentLoaded', function () {
    var checkExist = setInterval(function() {
        if (document.getElementById("minor-publishing-actions") != null) {
           appendCopyScapeButton(document.getElementById("minor-publishing-actions"));
           clearInterval(checkExist);
        }
     }, 100); // check every 100ms
     

     var checkExist2 = setInterval(function() {
        if (document.querySelector(".edit-post-header__settings") != null) {
           appendCopyScapeButton(document.querySelector(".edit-post-header__settings"), true);
           clearInterval(checkExist2);
        }
     }, 100); // check every 100ms


    var copyscapeCheck = document.createElement('button');
    copyscapeCheck.id = 'copyscape_check';
    copyscapeCheck.name = 'save';
    copyscapeCheck.innerHTML = 'Copyscape Check';
    copyscapeCheck.className = 'preview button';
    copyscapeCheck.style.minHeight = '33px';
    copyscapeCheck.onclick = function () {
        document.getElementById('copyscape_check').innerHTML = '<span style = "color:green"><b>Checking with Copyscape...</b></span>';
        document.getElementById('copyscape_check').style.border = 'none';
        document.getElementById('copyscape_button').click();
    };

    var copyscapeButton = document.createElement('input');
    copyscapeButton.type = 'submit';
    copyscapeButton.id = 'copyscape_button';
    copyscapeButton.name = 'save';
    copyscapeButton.value = 'Copyscape Check';
    copyscapeButton.className = 'preview button';
    copyscapeButton.style.display = "none";

    var copyscapeClear = document.createElement('div');
    copyscapeClear.className = 'clear';

    var copyscapeDiv = document.createElement('div');
    copyscapeDiv.id = 'copyscape-action';

    var copyscapeP = document.createElement('p');


    var checkExist3 = setInterval(function() {
        if (document.querySelector(".components-button") != null) {
            if (jQuery('.components-button').length && wp.data.select('core/editor') != null) {
                wp.data.subscribe(function () {
                    var isSavingPost = wp.data.select('core/editor').isSavingPost();
                    var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
        
                    if (isSavingPost && !isAutosavingPost && window.publishUpdateButtonClicked) {
        
                        var docContent = getPlainPostContent();
                        var updateType = window.publishUpdateButtonClicked;
        
        
                        window.publishUpdateButtonClicked = false;
        
                        // console.log("Going to send request: " + updateType + "; docContent: " + docContent + "; post_id:" + copyscape_info.post_id);
        
                        jQuery.post(copyscape_info.ajax_url + "?action=copyscape_check", {
                            "caller_button": updateType,
                            "copyscape_post_id": copyscape_info.post_id,
                            "post_content": docContent
                        }, function (response) {
                            // console.log(response);
        
                            showCopyscapeNotice(response);
        
                        });
                    }
                });
        
            }
           clearInterval(checkExist3);
        }
     }, 100); // check every 100ms


    jQuery(document).on("click","#copyscape_check",function(e) {
        e.preventDefault();
        var docContent = getPlainPostContent();
        
        if(docContent.length < 45){
            var tooshort = '{"message":"At least 15 words are required to perform a search"}';
            showCopyscapeNotice(tooshort);
            document.getElementById('copyscape_check').innerHTML = 'Copyscape Check';
            return;
        }

        jQuery.post(copyscape_info.ajax_url + "?action=copyscape_check", {
            "caller_button": "check",
            "copyscape_post_id": copyscape_info.post_id,
            "post_content": docContent
        }, function (response) {
            showCopyscapeNotice(response);
            document.getElementById('copyscape_check').innerHTML = 'Copyscape Check';

        });
    });



    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('editor-post-publish-button') && !e.target.classList.contains('already-clicked')) {

            var copyscapeUpdateType = e.target.innerText.toLowerCase();
            window.publishUpdateButtonClicked = copyscapeUpdateType;

        }
        else if (e.target && e.target.classList.contains('copyscape-revert-button')) {
            e.preventDefault();
            copyscapeRevertToDraft();
        }
        else if (e.target && e.target.matches('input[id^=editor-post-private]')) {
            if (wp.data.select('core/editor').isCurrentPostPublished())
                window.publishUpdateButtonClicked = 'upd-private';
            else
                window.publishUpdateButtonClicked = 'pub-private';
        }

    }, true);





    function appendCopyScapeButton(pubDiv, newWP = false) {
        if (pubDiv != null) {
            if (pubDiv.childNodes.length > 0)
                copyscapeDiv = pubDiv.insertBefore(copyscapeDiv, pubDiv.childNodes[0]);
            else copyscapeDiv = pubDiv.appendChild(copyscapeDiv);

            if (copyscapeDiv != null) {
                copyscapeDiv.appendChild(copyscapeButton);
                copyscapeDiv.appendChild(copyscapeCheck);
                if (!newWP) {
                    copyscapeDiv.appendChild(copyscapeP);
                    copyscapeP.appendChild(copyscapeClear);
                }
            }
        }
    }

    function showCopyscapeNotice(response) {
        if (!response) return;
        response = JSON.parse(response);
        if (response.length == 0)
            return;

        (function (wp) {
            wp.data.dispatch('core/notices').removeNotice("copyscaperesults");

            var actions = [];
            if(response["link_url"] !== undefined){
                actions.push(
                    {
                        "url":response['link_url'],
                        'label':'View Matches',
                    }
                )
            }

            response_message = response['message'];

            if(response["back_to_drafts"]){
                response_message += ' Your post has been published, but you can move it back to Drafts';
                actions.push(
                    {
                        'className':'copyscape-revert-button',
                        'label':'Move to Drafts',
                    }
                )
            }

            wp.data.dispatch('core/notices').createNotice(
                'success', // Can be one of: success, info, warning, error.
                response_message + ' ',
                {
                    id: "copyscaperesults",
                    isDismissible: true,
                    actions: actions,

                }

            );
        })(window.wp);

    }

    function copyscapeRevertToDraft() {
        wp.data.dispatch('core/editor').editPost({
            status: 'draft'
        });
        wp.data.dispatch('core/editor').savePost();
    }

    function getPlainPostContent() {
        if (jQuery('.components-button').length) {
            var docContent = wp.data.select("core/editor").getEditedPostContent();
            var newNode = document.createElement('span');
            newNode.innerHTML = docContent;
            return newNode.innerText;
        } else {
            return tinymce.activeEditor.getContent({ format: 'text' });
        }

    }
});

