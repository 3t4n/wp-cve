let $ = jQuery;

window.addEventListener('load', () => {
    let main = document.getElementById('fattura24_deactivation_form');
    let wrapper = document.getElementById('wpwrap');
    let message = f24_scripts_data['message'];
    let fatt24popup = $('#fattura24_deactivation_form');
    let deactivation_link = '';
    let textarea = document.getElementById('other');
    let inputElementsHTML = document.getElementsByClassName('reason-input-text');
    let inputElements = [...inputElementsHTML];

    function fatt24ClosePopup() {
        fatt24popup.css("display", "none");
    }


    function showHideInput(value) {
        inputElements.map(element => {
            if (element.id === value) {
                element.removeAttribute('hidden');
            } else {
                element.setAttribute('hidden', true);
            }
            return element;
        }, textarea.setAttribute('hidden', true));
    }

    function toggleSubmitButton($param = '') {
        if ($param === 'show') {
            $('.fattura24-submit-deactivate').css("display", "inline-block");
        } else {
            $('.fattura24-submit-deactivate').css("display", "none");
        }
    }
    
    wrapper.addEventListener('click', (e) => {
        if (e.target === main) {
            fatt24ClosePopup();
        }
    });

    wrapper.addEventListener('keydown', (e) => {
        if (e.code === 'Escape') {
            fatt24ClosePopup();
        }
    });

    /**
     * Quando faccio click visualizzo il tasto submit
     */

    textarea.addEventListener('click', () => {
        toggleSubmitButton('show');
    });

    /**
     * Quando faccio click fuori se il testo è vuoto
     * nascondo il tasto submit
     */
    textarea.addEventListener('focusout', () => {
        if (textarea.value === '') {
            toggleSubmitButton('hide');
        } else {
            toggleSubmitButton('show');
        }
    });

    $('.fattura24-deactivate-link').on('click', function(e){
        e.preventDefault();
        deactivation_link = $(this).attr('href');
        fatt24popup.css("display", "block");
        fatt24popup.find('a.fattura24-deactivate').attr('href', deactivation_link);
    });
    
   fatt24popup.on('click', 'input[type="radio"]', function (e) {
          showHideInput(e.target.value);
          if (e.target.value == 'other') {
             textarea.removeAttribute('hidden');
             toggleSubmitButton('hide');
          } else {
             toggleSubmitButton('show')
          }
    });

    fatt24popup.on('click', '.fattura24-close', function () {
        fatt24ClosePopup();
    });

    fatt24popup.on('click', '.fattura24-submit-deactivate', function (e) {
        e.preventDefault();
        let button = $(this);
        if (button.hasClass('disabled')) {
            return;
        }
        
        let radio = $('.f24-deactivation-reason input[type="radio"]:checked');
        let parent_li = radio.parents('li:first');
        let parent_ul = radio.parents('ul:first');
        let input = parent_li.find('textarea, input[type="text"], input[type="hidden"]');
        let deactivation_nonce = parent_ul.data('nonce');

        $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                        action: 'fattura24_deactivation_reason',
                        reason: (0 === radio.length) ? 'none' : radio.val(),
                        comments: (0 !== input.length) ? input.val().trim() : '',
                        nonce: deactivation_nonce,
                },
                beforeSend: function () {
                        button.addClass('disabled');
                        button.text(message);
                },
                complete: function () {
                        window.location.href = deactivation_link;
                }
            });
        });
});