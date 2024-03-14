/**
 * @desc Permet de copie un élément dans le presse-papier avec la librairie ClipboardJS
 */
var clipboard = new ClipboardJS('.hjqs-shortcode');
clipboard.on('success', function (e) {
    e.clearSelection();
    showTooltip(e.trigger, 'Copied!');
});


/**
 * Permet d'afficher un Tooltip durant 1s
 * @param elem
 * @param msg
 */
function showTooltip(elem, msg) {
    elem.setAttribute('class', 'hjqs-shortcode hjqs-tooltipped');
    elem.setAttribute('aria-label', msg);

    setTimeout(function () {
        elem.setAttribute('class', 'hjqs-shortcode');
    }, 1000)
}

/**
 * @desc Permet de faire l'équivalent du do_shortcode sur les formulaires du plugin
 * - Hook: wp_ajax_hjqs_ln_preview
 * @param elem // Bouton permettant de lancer l'action
 */
function previewForm(elem) {
    let form = elem.form
    let data = {
        action: 'hjqs_ln_preview',
        form: form.id
    }
    fetch(ajaxurl, {
        method: 'POST',
        body: new URLSearchParams(data)
    })
        .then(response => response.json())
        .then(response => {
            createModal(response.data.preview)
        })
}

/**
 * @desc Permet de masquer le popup
 * @param elem
 */
function closePreview(elem){
    elem.parentNode.parentNode.parentNode.classList.remove('active')
    document.body.style.overflow = ''
}

/**
 * @desc Permet de créer un popup
 * @param content // Texte à afficher
 */
function createModal(content){
    let wrapper = document.querySelector('.hjqs-ln-modal')
    if(!wrapper){
        console.error('.hjqs-ln-modal not found!')
        return
    }
    let contentElem = wrapper.querySelector('.hjqs-ln-modal-body')
    contentElem.innerHTML = content
    wrapper.classList.add('active')
    document.body.style.overflow = 'hidden'
}

/**
 * @desc Permet de supprimer les options sauvegardées et de recharger les valeurs par défaut des formulaire
 * - Hook: wp_ajax_hjqs_ln_clear
 * @param elem // Bouton permettant de lancer l'action
 * @param msg  // Message à afficher dans la boite de confirmation du navigateur
 */
function clearForm(elem, msg) {
    if (confirm(msg)) {
        // Add loader
        elem.querySelector('.hjqs-ln-spinner').classList.add('active')
        let form = elem.form
        let data = {
            action: 'hjqs_ln_clear',
            nonce: elem.dataset.nonce,
            form: form.id
        }
        fetch(ajaxurl, {
            method: 'POST',
            body: new URLSearchParams(data)
        })
            .then(response => response.json())
            .then(response => {
                let fields = response.data.form
                if (fields) {
                    Object.keys(fields).forEach(key => {
                        let input = form.querySelector(`*[name='${key}']`)
                        if (input) {
                            if (input.tagName.toLowerCase() === 'input') {
                                switch (input.getAttribute('type')) {
                                    case 'text' :
                                    case 'search':
                                        input.value = fields[key]
                                        break;
                                    case 'radio' :
                                        let value = fields[key]
                                        if (!value) {
                                            input = input.parentNode.parentNode.querySelector('input[type="radio"]:checked')
                                            if (input) {
                                                input.checked = false
                                            }
                                        } else {
                                            input = input.parentNode.parentNode.querySelector(`input[value='${value}']`)
                                            input.checked = true
                                        }
                                        break;
                                    default :
                                        break;
                                }
                            } else if (input.tagName.toLowerCase() === 'textarea') {
                                tinyMCE.get(tinyMCE.settings.selector.replace("#", "")).setContent(fields[key])
                                input.innerHTML = fields[key]

                            } else if (input.tagName.toLowerCase() === 'select') {
                                let value = fields[key]
                                input = input.querySelector(`option[value='${value}']`)
                                input.selected = true
                            }
                        } else {
                            let value = fields[key]
                            input = form.querySelectorAll(`*[name='${key}[]']`)
                            input.forEach((single_input) => {
                                if (value.includes(single_input.value)) {
                                    single_input.checked = true
                                } else {
                                    single_input.checked = false
                                }
                            })
                        }
                    });
                    let custom_fields = form.querySelectorAll('.hjqs-ln-input-bis')
                    custom_fields.forEach((input) => input.value = null)
                }
            })
            .finally(() => {
                // Remove loader
                elem.querySelector('.hjqs-ln-spinner').classList.remove('active')
            })
    }
}