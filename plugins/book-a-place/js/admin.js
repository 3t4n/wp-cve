(function ($) {
    "use strict";
    $(function () {

        var placeName = $("#scheme-place-name"),
            placeDescription = $("#scheme-place-description"),
            placePrice = $("#scheme-place-price"),
            scheme_id = $('#scheme').data('scheme-id'),
            buttonsObj;

        var dialogSubmitButtonHandler = function () {
            var cells = [],
                placeId;

            $(".scheme-cell-selected").each(function () {
                cells.push($(this).data('cell'));
                placeId = $(this).data('place-id');
            });

            var dialog = this,
                data = {
                    action: $('#scheme-place-dialog-form').find("input[name=action]").val(),
                    name: placeName.val(),
                    description: placeDescription.val(),
                    price: placePrice.val(),
                    type: '1',
                    cells: cells,
                    scheme_id: scheme_id,
                    color: $('.wp-color-picker').val(),
                    place_id: placeId
                };

            $.post(ajaxurl, data, function (response) {
                $('#scheme-container').empty().append(response);
                //addCellClickHandler();
                toggleSchemeControls(true);
                $(dialog).dialog("close");
            });
        };

        var statusSubmitButtonHandler = function () {
            var placeId;

            placeId = $($(".scheme-cell-selected")[0]).data('place-id');

            var dialog = this,
                data = {
                    action: 'update_place_status',
                    scheme_id: scheme_id,
                    status_id: $("#scheme-place-status").val(),
                    place_id: placeId
                };

            $.post(ajaxurl, data, function (response) {
                $('#scheme-container').empty().append(response);
                toggleSchemeControls(true);
                $(dialog).dialog("close");
            });
        };

        $( "#orders-search-date-from, #orders-search-date-to" ).datepicker({
            dateFormat: 'yy-mm-dd',
            firstDay: 1
        });

        $("#orders-search-toggle").click(function(e) {
            e.preventDefault();
            $('#orders-search').toggle();
            var text = $(this).text(),
                altText = $(this).data('alt-text');
            $(this).data('alt-text', text);
            $(this).text(altText);
        });

        $('#scheme-container').tooltip({
            items: '.scheme-cell',
            position: {
                my: "left top+5"
            },
            content: function () {
                return $('#tooltip-scheme-place-' + $(this).data('place-id')).html();
            }
        });

        addCellClickHandler();

        addButtons();


        var schemePlaceDialogFormProperties = {
            height: {
                min: 425,
                max: 650
            },
            isResized: false
        };

        $('.scheme-cell-color-field').wpColorPicker();
        $('.wp-color-result').on('click', function () {
            if (schemePlaceDialogFormProperties.isResized === false) {
                if ($(this).hasClass('wp-picker-open')) {
                    $("#scheme-place-dialog-form").dialog("option", "height", schemePlaceDialogFormProperties.height.max);
                } else {
                    $("#scheme-place-dialog-form").dialog("option", "height", schemePlaceDialogFormProperties.height.min);
                }
            }
        });

        buttonsObj = {};
        buttonsObj[bap_object.loc_strings.set_a_place] = dialogSubmitButtonHandler;
        buttonsObj[bap_object.loc_strings.cancel] = function () {
            $(this).dialog("close");
        };
        $("#scheme-place-dialog-form").dialog({
            autoOpen: false,
            height: schemePlaceDialogFormProperties.height.min,
            width: 350,
            modal: true,
            buttons: buttonsObj,
            resize: function () {
                schemePlaceDialogFormProperties.isResized = true;
            },
            close: function () {
                if (schemePlaceDialogFormProperties.isResized === false) {
                    $(this).dialog("option", "height", schemePlaceDialogFormProperties.height.min);
                }
            }
        });

        buttonsObj = {};
        buttonsObj[bap_object.loc_strings.change_place_status] = statusSubmitButtonHandler;
        buttonsObj[bap_object.loc_strings.cancel] = function () {
            $(this).dialog("close");
        };
        $("#scheme-place-status-form").dialog({
            autoOpen: false,
            height: 200,
            width: 350,
            modal: true,
            buttons: buttonsObj
        });


        $("#scheme-set-place").click(function (e) {
            e.preventDefault();
            var schemePlaceDialogForm = $("#scheme-place-dialog-form");
            schemePlaceDialogForm.find("input[name=action]").val('set_place');

            clearSchemePlaceDialogForm();

            buttonsObj = {};
            buttonsObj[bap_object.loc_strings.set_a_place] = dialogSubmitButtonHandler;
            buttonsObj[bap_object.loc_strings.cancel] = function () {
                $(this).dialog("close");
            };
            schemePlaceDialogForm.dialog("option", {
                title: bap_object.loc_strings.set_a_place,
                buttons: buttonsObj
            });
            schemePlaceDialogForm.dialog("open");

            return false;
        });

        $("#scheme-unset-place").click(function (e) {
            e.preventDefault();
            if (!confirm(bap_object.loc_strings.unset_this_place)) return false;

            var data = {
                action: 'unset_place',
                scheme_id: scheme_id,
                place_id: $($(".scheme-cell-selected")[0]).data('place-id')
            };

            $.post(ajaxurl, data, function (response) {
                $('#scheme-container').empty().append(response);
                toggleSchemeControls(false);
            });

            return false;
        });

        $("#scheme-edit-place").click(function (e) {
            e.preventDefault();

            var dialog = $("#scheme-place-dialog-form"),
                data = {
                    action: 'edit_place',
                    place_id: $($(".scheme-cell-selected")[0]).data('place-id')
                };

            dialog.find("input[name=action]").val('update_place');

            buttonsObj = {};
            buttonsObj[bap_object.loc_strings.update_a_place] = dialogSubmitButtonHandler;
            buttonsObj[bap_object.loc_strings.cancel] = function () {
                $(this).dialog("close");
            };
            dialog.dialog("option", {
                title: bap_object.loc_strings.update_a_place,
                buttons: buttonsObj
            });

            $.post(ajaxurl, data, function (response) {
                dialog.find("#scheme-place-name").val(response.name);
                dialog.find("#scheme-place-description").val(response.description);
                dialog.find("#scheme-place-price").val(response.price);
                $('.scheme-cell-color-field').wpColorPicker('color', response.color);

                dialog.dialog("open");
            }, 'json');


            return false;
        });

        $("#scheme-change-place-status").click(function (e) {
            e.preventDefault();

            var dialog = $("#scheme-place-status-form"),
                data = {
                    action: 'get_place_statuses',
                    place_id: $($(".scheme-cell-selected")[0]).data('place-id')
                };

            $.post(ajaxurl, data, function (response) {
                $('#scheme-place-status').empty().append(response);

                $(dialog).dialog("option", "height", 150);
                dialog.dialog("open");
            });


            return false;
        });

        $( "#settings-tabs" ).tabs();

        $('.submitdelete').click(function() {
            if (!confirm(bap_object.loc_strings.delete_this_item)) {
                return false;
            }
        });


        function clearSchemePlaceDialogForm() {
            var dialog = $("#scheme-place-dialog-form");
            dialog.find("#scheme-place-name").val('');
            dialog.find("#scheme-place-description").val('');
            dialog.find("#scheme-place-price").val('');
            $('.scheme-cell-color-field').wpColorPicker('color', '#bada55');
        }

        function addButtons() {
            $("#scheme-set-place").button({
                disabled: true
            });
            $("#scheme-edit-place").button({
                disabled: true
            });
            $("#scheme-unset-place").button({
                disabled: true
            });
            $("#scheme-change-place-status").button({
                disabled: true
            });
        }

        function refreshScheme() {

            var data = {
                action: 'refresh_scheme',
                scheme_id: scheme_id
            };

            $.post(ajaxurl, data, function (response) {
                //console.log(response);
                $('#scheme-container').empty().append(response);
                //addCellClickHandler()
                $("#scheme-place-dialog-form").dialog("close");
            });

        }

        function addCellClickHandler() {
            $(document).on('click', '.scheme-cell-selectee', function () {

                toggleSchemeControls(true);

                var self = $(this),
                    placeId = self.data('place-id'),
                    isCellSetted = self.hasClass('scheme-cell-setted'),
                    isCellSelected = self.toggleClass('scheme-cell-selected').hasClass('scheme-cell-selected');

                unselectOthers(self);

                if (isCellSetted) {
                    toggleAllCellsOfPlace(placeId, isCellSelected);
                    toggleSchemeControls(false);
                } else {
                    checkThePossibilityOfUnselecting(self);
                    toggleSchemeControls(false);
                    setAllowedCells();
                }

            });
        }


        function checkThePossibilityOfUnselecting(targetCell) {

            return true; // verification is currently not used

            if (targetCell.hasClass('scheme-cell-selected')) {

                return true;

            } else {

                var adjacentCells = getAdjacentCells(targetCell),
                    selectedCellsX = [],
                    selectedCellsY = [],
                    unselectingIsPossible = true,
                    adjacentSelectedCells = [];

                $(adjacentCells).each(function () {
                    if ($(this).hasClass('scheme-cell-selected')) {
                        adjacentSelectedCells.push($(this));
                    }
                });

                $(adjacentSelectedCells).each(function () {
                    selectedCellsX.push($(this).data('x'));
                    selectedCellsY.push($(this).data('y'));
                });
                selectedCellsX.sort(function (a, b) {
                    return a - b;
                });
                selectedCellsY.sort(function (a, b) {
                    return a - b;
                });

                for (var i = 1; i < selectedCellsX.length; ++i) {
                    if (Math.abs(selectedCellsX[i - 1] - selectedCellsX[i]) > 1) {
                        unselectingIsPossible = false;
                    }
                }

                for (var j = 1; j < selectedCellsY.length; ++j) {
                    if (Math.abs(selectedCellsY[j - 1] - selectedCellsY[j]) > 1) {
                        unselectingIsPossible = false;
                    }
                }

                if (!unselectingIsPossible) {
                    targetCell.addClass('scheme-cell-selected');
                }

                return false;
            }
        }

        function getAdjacentCells(targetCell) {
            var cellSet = [];
            $('.scheme-cell').each(function () {
                var cell = $(this);
                if (Math.abs(cell.data('x') - targetCell.data('x')) > 1 || Math.abs(cell.data('y') - targetCell.data('y')) > 1) {
                    // do nothing
                } else {
                    cellSet.push(cell);
                }
            });

            return cellSet;
        }

        function unselectOthers(cell) {
            var selectedCells = $('.scheme-cell-selected'),
                unselect = false;

            selectedCells.each(function () {
                var self = $(this);
                if (self.data('place-id') && cell.data('place-id') != self.data('place-id')) {
                    unselect = true;
                }
            });

            if (unselect) {
                selectedCells.each(function () {
                    $(this).removeClass('scheme-cell-selected');
                });
                cell.addClass('scheme-cell-selected');
            }
        }

        function toggleAllCellsOfPlace(placeId, isCellsOfPlaceSelected) {
            $(".scheme-place-" + placeId).each(function () {
                var cell = $(this);
                if (isCellsOfPlaceSelected) {
                    cell.addClass('scheme-cell-selected');
                } else {
                    cell.removeClass('scheme-cell-selected');
                }
            });
        }

        function toggleSchemeControls(disableAll) {
            var selectedCells = $('.scheme-cell-selected');

            if (selectedCells.length == 0 || disableAll === true) {
                $("#scheme-set-place").button("option", "disabled", true);
                $("#scheme-edit-place").button("option", "disabled", true);
                $("#scheme-unset-place").button("option", "disabled", true);
                $("#scheme-change-place-status").button("option", "disabled", true);
            } else {
                if ($(selectedCells[0]).hasClass('scheme-cell-setted')) {
                    $("#scheme-edit-place").button("option", "disabled", false);
                    $("#scheme-unset-place").button("option", "disabled", false);
                    $("#scheme-change-place-status").button("option", "disabled", false);
                } else {
                    $("#scheme-set-place").button("option", "disabled", false);
                }
            }
        }

        function setAllowedCells(onlySelected) {

            var selectedCells = $('.scheme-cell-selected');

            if (onlySelected) {
                $('.scheme-cell').each(function () {
                    if (!$(this).hasClass('scheme-cell-selected')) {
                        $(this).removeClass('scheme-cell-selectee');
                    }
                });
            } else {
                if (selectedCells.length == 0) {
                    $('.scheme-cell').each(function () {
                        $(this).addClass('scheme-cell-selectee');
                    });
                } else {
                    $('.scheme-cell').each(function () {
                        $(this).removeClass('scheme-cell-selectee');
                    });

                    selectedCells.each(function () {
                        var currentCell = $(this);
                        $('.scheme-cell').each(function () {
                            var cell = $(this);
                            if (cell.hasClass('scheme-cell-setted') || Math.abs(cell.data('x') - currentCell.data('x')) > 1 || Math.abs(cell.data('y') - currentCell.data('y')) > 1) {
                                // do nothing
                            } else {
                                cell.addClass('scheme-cell-selectee');
                            }
                        });

                    });
                }
            }

            //addCellClickHandler();

        }

    });
}(jQuery));