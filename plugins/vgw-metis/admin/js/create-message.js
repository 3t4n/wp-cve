/**
 * JS for create message form
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
(function ($, window, document) {
    'use strict';

    let ul_urls = document.getElementById('urls');
    let button_add_url = document.getElementById('add_url');

    function isValidUrl(urlString) {
        try {
            return Boolean(new URL(urlString));
        } catch (e) {
            return false;
        }
    }

    function add_click(event) {
        const result = prompt(wp_metis_create_message_obj.prompt_add_url);

        if (isValidUrl(result)) {
            add_url(result);
        } else {
            alert(wp_metis_create_message_obj.msg_no_valid_url);
        }
    }

    function check_if_at_least_one_author() {
        let has_author = false;

        const table = document.querySelector('#chosen-participants');

        const selects = table.getElementsByTagName('select');

        for(const select of selects) {
            if(select.options[select.selectedIndex].value === wp_metis_create_message_obj.author) {
                has_author = true;
            }
        }

        if (!has_author) {
            alert(wp_metis_create_message_obj.msg_at_least_one_author);
        }

        return has_author;
    }

    function add_url(url) {
        $(ul_urls).append(`
            <li>
                <output>${url}</output>
                <a class="action-remove-url">
                    <span class="dashicons dashicons-trash"></span>
                </a>
                <input type="hidden" name="urls[]" value="${url}" />
            </li>
        `);
    }

    function remove_participant(id) {
        const available_participant_element = $('#available-participant-' + id);
        available_participant_element.removeClass('invisible-row');

        $('tr#chosen-participant-' + id).remove();
    }

    function get_dropdown_options(pdata) {
        if (pdata.wp_user) {
            return `
            <option ${pdata.involvement === wp_metis_create_message_obj.author ? 'selected' : ''} value="${wp_metis_create_message_obj.author}">${wp_metis_create_message_obj.option_author}</option>
            <option ${pdata.involvement === wp_metis_create_message_obj.author ? 'selected' : ''} value="${wp_metis_create_message_obj.translator}">${wp_metis_create_message_obj.option_translator}</option>`;

        } else {
            return `
            <option ${pdata.involvement === wp_metis_create_message_obj.author ? 'selected' : ''} value="${wp_metis_create_message_obj.author}">${wp_metis_create_message_obj.option_author}</option>
            <option ${pdata.involvement === wp_metis_create_message_obj.translator ? 'selected' : ''} value="${wp_metis_create_message_obj.translator}">${wp_metis_create_message_obj.option_translator}</option>
            <option ${pdata.involvement === wp_metis_create_message_obj.publisher ? 'selected' : ''} value="${wp_metis_create_message_obj.publisher}">${wp_metis_create_message_obj.option_verlag}</option>`;

        }
    }

    function add_participant(participant) {
        const pdata = $(this).data('participant');

        const available_participant_element = $('#available-participant-' + pdata.id);
        available_participant_element.addClass('invisible-row');

        const chosen_participants = $('#chosen-participants');

        chosen_participants.append($(`
                <tr id="chosen-participant-${pdata.id}">
                    <td><span class="remove-participant dashicons dashicons-arrow-left-alt2"></span></td>
                    <td>${pdata.first_name}</td>
                    <td>${pdata.last_name}</td>
                    <td>${pdata.file_number}</td>
                    <td>
                        <select id="participant-function-select-${pdata.id}">
                            ${get_dropdown_options(pdata)}
                        </select>
                        <input type="hidden" name="participants[]" id="hidden-participant-${pdata.id}" />
                    </td>
                </tr>`));

        document.getElementById('hidden-participant-' + pdata.id).setAttribute('value', JSON.stringify(pdata));


        document.querySelector('#chosen-participant-' + pdata.id + ' span.remove-participant').addEventListener('click', () => {
            remove_participant(pdata.id);
        });

        $('#participant-function-select-' + pdata.id)
            .on('change', function () {
                const destination = document.getElementById('hidden-participant-' + pdata.id);
                const json = JSON.stringify({
                    id: pdata.id,
                    first_name: pdata.first_name,
                    last_name: pdata.last_name,
                    file_number: pdata.file_number,
                    involvement: this.value
                });

                destination.setAttribute('value', json);
            });

    }

    // execute when the DOM is ready
    $(document).ready(function () {

        ul_urls = document.getElementById('urls');
        $(ul_urls).on('click', '.action-remove-url', function () {
            $(this).parent().remove();
        })

        button_add_url = document.getElementById('add_url');
        button_add_url.addEventListener('click', add_click, false);

        $('.add-participant').on('click', add_participant);

        $('#create-message-form').submit(function () {
            return check_if_at_least_one_author();
        })
    });
}(jQuery, window, document));