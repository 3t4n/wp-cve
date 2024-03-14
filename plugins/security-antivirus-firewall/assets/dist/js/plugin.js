/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

jQuery.fn.dataTable.Api.register('row.addByPos()', function(data, index) {
    var currentPage = this.page();

    //insert the row
    this.row.add(data);

    //move added row to desired index
    for (var i = this.data().length - 1, tempRow = null; i > index; i--) {
        tempRow = this.row(i-1).data();
        this.row(i).data(tempRow);
    }
    this.row(index).data(data);

    //refresh the current page
    this.page(currentPage).draw(false);
});

;(function (window, $) {
    var wptsafDataAction = function () {
        this.init();
    };

    wptsafDataAction.prototype.init = function () {
        var self = this;

        $(document).ready(function () {
            $('body').on('click', '[data-action]', function (event) {
                var $target = $(this),
                    action = $target.attr('data-action'),
                    data = {};

                if ('submit' == $target.attr('type')) {
                    $.each($target.closest('form').serializeArray(), function (i, field) {
                        data[field.name] = field.value;
                    });
                }

                self.processAction($target, action, data);

                return false;
            });
        });

        self.$loader = $('#wptsaf-popup-loader');
        self.$loader.popup({
            background: true,
            backgroundactive: false,
            color: '#fff',
            scrolllock: false,
            setzindex: false
        });
        $('body').on('click', '#wptsaf-popup-loader_wrapper', function () {
            return false;
        });
    };

    wptsafDataAction.prototype.processAction = function ($target, action, data, isShowLoader, complete) {
        var self = this;

        data = data || {};
        data.nonce = wptsafSecurity.ajaxNonce;

        if (undefined === isShowLoader || isShowLoader) {
            self.$loader.popup('show');
        }

        $.ajax({
            url: decodeURIComponent(wptsafSecurity.ajaxUrl + '?' + action),
            method: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                self.$loader.popup('hide');

                $.each(response.jsCallbacks, function (i, callback) {
                    if ($.isArray(callback)) {
                        self.invokeFunctionByName(callback[0], callback.slice(1));
                    } else {
                        self.invokeFunctionByName(callback, [$target, response.response]);
                    }
                });

                if (response.messages && response.messages.length) {
                    wptsafCallback.showMessages(response.messages);
                }

                if ('function' === typeof complete) {
                    complete($target, response.response);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var error = null,
                    actionAddErrorMessage = 'action=wptsaf_security&extension=system-log&method=addMessage';

                self.$loader.popup('hide');

                if ( 403 == jqXHR.status ) {
                    error = wptsafSecurity.translations.ajax_forbidden;
                } else if ( 404 == jqXHR.status ) {
                    error = wptsafSecurity.translations.ajax_not_found;
                } else if ( 500 == jqXHR.status ) {
                    error = wptsafSecurity.translations.ajax_server_error;
                } else {
                    error = wptsafSecurity.translations.ajax_unknown;
                }

                if (!error) {
                    error = wptsafSecurity.translations.ajax_invalid
                }

                wptsafCallback.showMessages([{'text': error, 'type': 'danger'}]);

                // prevent unlimited loop
                if (action != actionAddErrorMessage) {
                    error += "\n\n";
                    error += "jqXHR.responseText: " + jqXHR.responseText + "\n";
                    error += "jqXHR.status: " + jqXHR.status + "\n";
                    error += "jqXHR.statusText: " + jqXHR.statusText + "\n";
                    error += "textStatus: " + textStatus + "\n";
                    error += "errorThrown: " + errorThrown + "\n";

                    // send error message
                    self.processAction(
                        null,
                        actionAddErrorMessage,
                        {
                            args: {
                                message: error,
                                type: 'danger'
                            }
                        }
                    );
                }
            }
        });
    };

    wptsafDataAction.prototype.invokeFunctionByName = function(name, args) {
        var sections = name.split('.'),
            context = window,
            callFunction = sections.pop();

        $.each(sections, function (i, section) {
            context = context[section];
            if (undefined === context) {
                return false;
            }
        });

        if ('function' === typeof context[callFunction]) {
            context[callFunction].apply(context, args)
        }
    };

    window.wptsafDataAction = new wptsafDataAction();

})(window, jQuery);

;(function (window, $) {
    var wptsafCallback = function () {
        var self = this;

        self.$popup = $('#wptsaf-popup');
        self.$popup.popup({
            background: true,
            scrolllock: true,
            onclose: function () {
                self.$popup.find('.content').html('');
            }
        });
        self.$popup.on('click', '.btn-popup-close', function () {
            self.$popup.popup('hide');
            return false;
        });

        self.$form = $('#wptsaf-popup-form');
        self.$form.popup({
            background: true,
            scrolllock: false,
            onopen: function () {
            	self.onOpen();
            },
            onclose: function () {
                self.$form.find('.content').html('');
            },
            closetransitionend: function () {
            	self.closeTransitionEnd();
            },
        });
        self.$form.on('click', '.btn-popup-close', function () {
            self.$form.popup('hide');
            return false;
        });

        self.$dialog = $('#wptsaf-popup-dialog');
        self.$dialog.popup({
            background: true,
            scrolllock: false,
            onopen: function () {
                self.onOpen();
            },
            onclose: function () {
                self.$dialog.find('.content').html('');
            },
             closetransitionend: function () {
                self.closeTransitionEnd();
             },
        });
        self.$dialog.on('click', '.btn-popup-close', function () {
            self.$dialog.popup('hide');
            return false;
        });

        self.$message = $('#wptsaf-popup-message');
        self.$message.popup({
            background: true,
            scrolllock: false,
            onopen: function () {
            	self.onOpen();
			},
            onclose: function () {
                self.$message.find('.content').html('');
            },
            closetransitionend: function () {
            	self.closeTransitionEnd();
            },
        });
        self.$message.on('click', '.btn-popup-close', function () {
            self.$message.popup('hide');
            return false;
        });
    };

	wptsafCallback.prototype.onOpen = function () {
		if (1 == $('.popup_wrapper:visible').not('#wptsaf-popup-loader_wrapper').length) {
			$('body').css('overflow', 'hidden');
		}
	};

	wptsafCallback.prototype.closeTransitionEnd = function () {
		if (0 == $('.popup_wrapper:visible').not('#wptsaf-popup-loader_wrapper').length) {
			setTimeout(function() {
				$('body').css('overflow', 'visible');
			}, 10); 
		}
	};


    wptsafCallback.prototype.initContent = function ($context) {
        if ($("input.flat", $context)[0]) {
            $(document).ready(function () {
                $('input.flat', self.$popup).iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
            });
        }
        if ($(".js-switch", $context)[0]) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {
                    color: '#26B99A'
                });
            });
        }

        $('.daterangepicker', $context).daterangepicker($.extend(
            {},
            {
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerIncrement": 1,
                "parentEl": "#" + $context.attr('id') + " .content",
                "opens": "left"
            },
            wptsafSecurity.daterangepicker.settings
        ));
    };

    wptsafCallback.prototype.targetShowContent = function ($target, content) {
        var self = this;

        $target.removeAttr('data-action');
        $target.html(content);
        self.initContent($target);
    };

    wptsafCallback.prototype.updateWidget = function (extensionName) {
        var $widget = $('.extension-widget.' + extensionName);

        if (0 === $widget.length) {
            return;
        }

        wptsafDataAction.processAction($widget.children().first(), 'action=wptsaf_security&extension=' + extensionName + '&method=widget', null, false);
    };

    wptsafCallback.prototype.updateWidgetContent = function ($target, content) {
        $target.closest('.extension-widget').html(content);
    };
    
    wptsafCallback.prototype.popupShowContent = function ($target, content) {
        var self = this;

        self.$popup.find('.content').html(content);
        self.$popup.popup('show');
        self.initContent(self.$popup);
    };

    wptsafCallback.prototype.popupHide = function () {
        this.$popup.find('.content').html('');
        this.$popup.popup('hide');
    };

    wptsafCallback.prototype.popupUpdateDataTableRow = function (data) {
       	var $table = this.$popup.find('table.dataTable'),
    		$row = this.$popup.find('tr#' + data.id);

        if (0 === $table.length) {
            return;
        }

		if (0 === $row.length) {
			$table.dataTable().api().row.addByPos(data, 0);
			$($table.find('tbody > tr').get(0)).attr('id', data.id);
       	} else {
            $table.dataTable().api().row($row).data(data).draw();
        }
    };

    wptsafCallback.prototype.formShowContent = function ($target, content) {
        var self = this;

        self.$form.find('.content').html(content);
        self.$form.popup('show');
        self.initContent(self.$form);
    };

    wptsafCallback.prototype.formHide = function () {
        this.$form.find('.content').html('');
        this.$form.popup('hide');
    };

    wptsafCallback.prototype.dialogShowContent = function ($target, content) {
        var self = this;

        self.$dialog.find('.content').html(content);
        self.$dialog.popup('show');
        self.initContent(self.$dialog);
    };

    wptsafCallback.prototype.dialogHide = function () {
        this.$dialog.find('.content').html('');
        this.$dialog.popup('hide');
    };

    wptsafCallback.prototype.pageRefresh = function () {
    	/*this.$dialog.find('.content').html('');
        this.$dialog.popup('hide');*/

    	this.$loader = $('#wptsaf-popup-loader');
        this.$loader.popup({
            background: true,
            backgroundactive: false,
            color: '#fff',
            scrolllock: false,
            setzindex: false
        });

      	this.$loader.popup('show');  
      	
        $('body').on('click', '#wptsaf-popup-loader_wrapper', function () {
            return false;
        });
        
        window.location.reload(false);
    };

    wptsafCallback.prototype.showMessages = function (messages) {
        var content = [];

        $.each(messages, function (i, message) {
            content.push('<div class="alert alert-' + message.type + '">' + message.text + '</div>');
        });

        if (0 === content.length) {
            return;
        }

        this.$message.find('.content').html(content.join(''));
        this.$message.popup('show');
    };

    window.wptsafCallback = new wptsafCallback();
})(window, jQuery);

;(function (window, $) {
    var wptsafTableScan = function () {
        var self = this;

    };

    wptsafTableScan.prototype.insertRows = function ($target, content) {
        content = JSON.parse(content);

        $.each(content, function (i, row) {
            var $tr = $('<tr></tr>');

            $.each(row, function (i, value) {
                $tr.append('<td class="' + i + '">' + value + '</td>');
            });
            $target.append($tr);
        });
    };

    window.wptsafTableScan = new wptsafTableScan();

})(window, jQuery);
