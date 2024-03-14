/*
 thin-out-revisions.js
 Copyright 2013, 2014 Hirokazu Matsui (blogger323)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 for the revision screen (wp-admin/revision.php)
 */
/*
 -------------------------------------------
 CSS classes and IDs (under construction)

[Basic]
 #tor-delete-oldest
   A checkbox to indicate if delete the From revision.

 #tor-thin-out
   A button to execute deletion

 #tor-msg
   A message area

 #tor-rm-N
   A button to delete a spesified revision

 .tor-rm
   Buttons

 #hm-tor-copy-memo
 #hm-tor-memo-current

[for Memo Editor]
 .hm-tor-modal-background
 #hm-tor-memo-editor
 #hm-tor-memo-input
 #hm-tor-memo-input-ok
 #hm-tor-memo-input-cannel


[For memo editing (Attach following classes and ID)]
 .hm-tor-old-memo
 #hm-tor-memo-N

 */

// TODO using the minimized version

(function ($, window, document, undefined) {

	$(document).ready(function () {



        $('.post-revisions a').each(function () {
            var parse_url = /(post|revision)=([0-9]+)/;
            var result = parse_url.exec($(this).attr('href'));
            if (result && result[2] != hm_tor_params.latest_revision) {
                $(this).parent().append('<input id="tor-rm-' + result[2] + '" class="button button-primary tor-rm" type="submit" value="' + hm_tor_params.msg_delete + '" style="margin: 0 10px"/>');
            }
        }); // '.post-revisions a' each

        // clicked button to remove a single revision
        $('.tor-rm').click(function () {
            var rev = $(this).attr("id");
            var btn = this; // we need to reserve this
            rev = rev.replace('tor-rm-', '');
            if ($(btn).parent().css('text-decoration') == 'line-through') {
                return false;
            }
            if (confirm(hm_tor_params.msg_thinout_comfirmation) != true) {
                return false;
            }
            $(btn).attr('value',  hm_tor_params.msg_processing);
            $.ajax({
                url     : hm_tor_params.ajaxurl,
                dataType: 'json',
                data    : {
                    action  : 'hm_tor_do_ajax',
                    posts   : rev,
                    security: hm_tor_params.nonce
                }
            })
                .success(function (response) {
                    $(btn).parent().css('text-decoration', 'line-through');
                    $(btn).attr('disabled', 'disabled');
                    $(btn).attr('value', hm_tor_params.msg_deleted);
                })
                .error(function () {
                    alert(hm_tor_params.msg_ajax_error);
                    $(btn).attr('value', 'Delete'); /* reset */
                });

            return false;
        }); // '.tor-rm' click

        // for Revision Memo

        /*
         Memo Copy
         copy the previous memo for the next update
         */
        $('#hm-tor-copy-memo').click(function () {
            var new_memo = $('#hm-tor-memo-current').html().replace(/^ \[(.*)\]$/, "$1"); // one space...
            $('#hm-tor-memo').val(new_memo);

            return false;
        }); // #hm-tor-copy-memo click

        var memo_edited = ''; // a subject to edit

        /*
         Memo Editor
         editor for old memos
         */
        $('body').append('<div class="hm-tor-modal-background"><div id="hm-tor-memo-editor"><input id="hm-tor-memo-input" type="text" /><input id="hm-tor-memo-input-ok" class="button" type="button" value="OK" /><input id="hm-tor-memo-input-cancel" class="button" type="button" value="Cancel" /></div></div>');

         $('body').on("click", ".hm-tor-old-memo", function () {
            $('#hm-tor-memo-editor').show();
            $('.hm-tor-modal-background').show();
            $('#hm-tor-memo-editor').position({
                my: "left top",
                at: "left top",
                of: $(this)
            });
            $('#hm-tor-memo-input').val($(this).text().replace(/^ *\[(.*)\]$/,"$1"));
            $('#hm-tor-memo-input').focus();
            memo_edited = $(this).attr('id').replace(/hm-tor-memo-/, '');
        });

        $('body').on("mouseenter", ".hm-tor-old-memo", function() { $(this).css("cursor", "pointer"); });
        $('body').on("mouseleave", ".hm-tor-old-memo", function() { $(this).css("cursor", "default"); });

        $('#hm-tor-memo-input-ok').click(function () {
            edit_ok();
        });

        $('#hm-tor-memo-input-cancel').click(function () {
            edit_cancel();
        });

        $('#hm-tor-memo-editor').keypress(function(e) {
            // It seems that the background cannot handle keypress events.

            if (e.keyCode == $.ui.keyCode.ENTER) {
                // To avoid multiple requests, do not use edit_ok().
                $('#hm-tor-memo-input-ok').click();
            }
            else if (e.keyCode == $.ui.keyCode.ESCAPE) {
                $('#hm-tor-memo-input-cancel').click();
            }
        });

        function edit_ok() {
            var editor = $('#hm-tor-memo-editor');
            var new_memo = $('#hm-tor-memo-input').val();

            editor.children().css('cursor', 'wait');
            $('#hm-tor-memo-editor input').attr('disabled', 'disabled');

            function reset_attr() {
                // reset attributes
                editor.children().css('cursor', 'default');
                $('#hm-tor-memo-editor input').removeAttr('disabled');
                editor.hide();
                $('.hm-tor-modal-background').hide();
            }

            // execution
            $.ajax({
                url: hm_tor_params.ajaxurl,
                dataType: 'json',
                data: {
                    action: 'hm_tor_do_ajax_update_memo',
                    revision: memo_edited,
                    memo: new_memo,
                    security: hm_tor_params.nonce
                }
            })
                .success (function(response) {

                if (response.result == 'success') {
                    // set memo
                    $('#hm-tor-memo-' + memo_edited).text( '[' + new_memo + ']');

                    if (typeof hm_tor_memos !== 'undefined') {
                        hm_tor_memos[memo_edited] = new_memo;
                    }
                }
                else {
                    alert(response.msg);
                }

                reset_attr();
            })
                .error (function() {
                alert(hm_tor_params.msg_ajax_error);

                reset_attr();
            });

        }

        function edit_cancel() {
            $('#hm-tor-memo-editor').hide();
            $('.hm-tor-modal-background').hide();
        }

        // -----------------------------------------------------------------
        // for the Revision Comparison Screen
        // Add some functionality to a Backbone.js based view
        var current_url = document.URL;
        if (current_url.indexOf('/revision.php') >= 0) { // for revision.php
            setTimeout(function () {
                if (wp && wp.revisions && wp.revisions.view && wp.revisions.view.Metabox &&
                    wp.revisions.view.Controls && wp.revisions.view.frame.model && wp.revisions.view.frame.model.get('from')) {
                    var slider_model = 0;
                    TOR = {
                        View: wp.revisions.view.Metabox.extend({
                            events: {},

                            initialize: function () {
                                wp.revisions.view.Metabox.prototype.initialize.apply(this);
                                _.bindAll(this, 'render');
                                this.events = _.extend({'click #tor-thin-out': 'thinOut'},
                                    wp.revisions.view.Metabox.prototype.events);
                                this.listenTo(this.model, 'change:compareTwoMode', this.render);

                                if (slider_model) {
                                    this.listenTo(slider_model, 'update:slider', this.render);
                                }
                            },

                            render: function () {

                                wp.revisions.view.Metabox.prototype.render.apply(this);

                                if (this.model.get('compareTwoMode')) {
                                    this.$el.html(this.$el.html() + '<div id="tor-div" class="diff-header"><div class="diff-title"><strong>&nbsp;</strong><span id="tor-msg" style="margin: 0 10px;">'
                                    + hm_tor_params.msg_thin_out + '</span><input id="tor-thin-out" class="button button-primary" type="submit" value="Thin Out" /><label for="tor-delete-oldest"><input name="hm_tor_delete_oldest" type="checkbox" id="tor-delete-oldest" value="enabled" style="margin: 0 5px 0 30px;"/>'
                                    + hm_tor_params.msg_include_from + '</label></div></div>');

                                }

                                var fromid = (this.model.get('from') ? this.model.get('from').get('id') : '');
                                var toid = (this.model.get('to') ? this.model.get('to').get('id') : '');

                                if (fromid && typeof(hm_tor_memos) !== 'undefined' && hm_tor_memos[fromid] !== 'undefined') {
                                    var $f = $('.diff-meta-from .diff-title');
                                    if (!/\[/.test($f.text())) { // avoid duplicated memos
                                        $f.append('<div><span class="hm-tor-old-memo" id="hm-tor-memo-' + fromid + '">[' + hm_tor_memos[fromid] + ']</span></div>');
                                    }
                                }
                                if (toid && typeof(hm_tor_memos) !== 'undefined' && hm_tor_memos[toid] !== 'undefined') {
                                    var $t = $('.diff-meta-to .diff-title');
                                    if (!/\[/.test($t.text())) {
                                        $t.append('<div><span class="hm-tor-old-memo" id="hm-tor-memo-' + toid + '">[' + hm_tor_memos[toid] + ']</span></div>');
                                    }
                                }
                                return this;
                            },

                            thinOut: function () {
                                var revs = '';
                                var revs_disp = '';
                                var from = this.model.revisions.indexOf(this.model.get('from')) + 1;
                                var to = this.model.revisions.indexOf(this.model.get('to'));

                                if ($('#tor-delete-oldest').attr('checked') == 'checked') {
                                    revs = this.model.get('from').get('id');
                                    revs_disp = this.model.get('from').get('id');
                                }

                                for (var i = from; i < to; i++) {
                                    revs = revs + (revs === '' ? '' : '-') + this.model.revisions.at(i).get('id');
                                    revs_disp = revs_disp + (revs_disp === '' ? '' : ',') + this.model.revisions.at(i).get('id');
                                }

                                if (revs === '') {
                                    alert(hm_tor_params.msg_nothing_to_remove);
                                    return false;
                                }
                                if (confirm(hm_tor_params.msg_thinout_comfirmation + ' (ID: ' + revs_disp + ')') != true) {
                                    return false;
                                }

                                $('#tor-thin-out').attr('value', hm_tor_params.msg_processing).attr('disabled', 'disabled');

                                $.ajax({
                                    url: hm_tor_params.ajaxurl,
                                    dataType: 'json',
                                    data: {
                                        action: 'hm_tor_do_ajax',
                                        posts: revs,
                                        security: hm_tor_params.nonce
                                    }
                                })
                                    .success(function (response) {
                                        alert(hm_tor_params.msg_remove_completed);
                                        //location.replace('./revision.php?from=' + from + '&to=' + to); // it doesn't work...
                                        location.replace('./revision.php?revision=' + hm_tor_params.latest_revision);
                                    })
                                    .error(function () {
                                        alert(hm_tor_params.msg_ajax_error);
                                    });

                                return false;
                            }
                        }) //* View */
                    };
                    /* TOR */

                    var cv = (wp.revisions.view.frame.views.get('.revisions-control-frame'))[0];
                    var mv = 0;
                    var sv = 0;

                    var i;
                    for (i = 0; i < cv.views._views[''].length; i++) {
                        if (cv.views._views[''][i].className === 'revisions-meta') {
                            mv = cv.views._views[''][i];
                        }
                        else if (cv.views._views[''][i].className === 'wp-slider') {
                            sv = cv.views._views[''][i];
                        }
                    }
                    if (mv) {
                        mv.remove();
                    }
                    if (sv) {
                        slider_model = sv.model;
                    }

                    var torview = new TOR.View({
                        model: wp.revisions.view.frame.model
                    });
                    cv.views.add(torview);
                    torview.render();
                }
                else {
                    setTimeout(arguments.callee, 300);
                }
            }, 300);
        } // end of code for revision.php
	}); // ready

})(jQuery, window, document);
