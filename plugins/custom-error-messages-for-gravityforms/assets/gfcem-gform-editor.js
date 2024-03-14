gform.addFilter( 'gform_editor_field_settings', function( settings, field ) {
    if (gfcem_object.gfcem_settings.indexOf(field['type']) !== -1) {
        settings.push('.gfcemAllowed_field_setting')
    }

    return settings;
} );

jQuery(document).on("gform_load_field_settings", function(event, field, form){
    document.getElementById('field_gfcemAllowed').checked = Boolean(rgar(field, 'gfcemAllowed'));

    document.getElementById("field_gfcem_container").innerHTML = '';

    GFCEMCreateInputs(field);
    GFCEMToggleInputs(true);

    GFCEMSetValues(field, 'field_gfcem_message_required', 'inputGFCEMMessageRequired');
    GFCEMSetValues(field, 'field_gfcem_message_valid_email', 'inputGFCEMMessageValidEmail');
    GFCEMSetValues(field, 'field_gfcem_message_confirm_email', 'inputGFCEMMessageConfirmEmail');
    GFCEMSetValues(field, 'field_gfcem_message_unique', 'inputGFCEMMessageUnique');
}).on('input propertychange change', '.gfcem_input', function(){
    GFCEMSetInput(this.value, jQuery(this).data('key'));
});

function GFCEMSetValues(field, id, key){
    let input = document.getElementById(id);

    if (null !== input) {
        input.value = rgar(field, key);
    }
}

function GFCEMSetInput(value, key){
    let field = GetSelectedField();

    if (value) {
        value = value.trim();
    }

    field[key] = value;
}


function GFCEMToggleInputs(){
    if (document.getElementById('field_gfcemAllowed').checked) {
        document.getElementById('field_gfcem_container').style.display = "block";
    } else {
        document.getElementById('field_gfcem_container').style.display = "none";
        document.querySelectorAll("#field_gfcem_container input").forEach(function(currentElement, index) { currentElement.value = '' });
    }
}

function GFCEMCreateInputs(field) {
    document.getElementById("field_gfcem_container").appendChild(GFCEMCreateInputElements('field_gfcem_message_required', gfcem_object.gfcem_rem_title, 'inputGFCEMMessageRequired'));

    if (gfcem_object.gfcem_not_unique.indexOf(field['type']) === -1) {
        document.getElementById("field_gfcem_container").appendChild(GFCEMCreateInputElements('field_gfcem_message_unique', gfcem_object.gfcem_uem_title, 'inputGFCEMMessageUnique'));
    }

    if ('email' === field['type']) {
        document.getElementById("field_gfcem_container").appendChild(GFCEMCreateInputElements('field_gfcem_message_valid_email', gfcem_object.gfcem_evem_title, 'inputGFCEMMessageValidEmail'));
        document.getElementById("field_gfcem_container").appendChild(GFCEMCreateInputElements('field_gfcem_message_confirm_email', gfcem_object.gfcem_ecem_title, 'inputGFCEMMessageConfirmEmail'));
    }
}

function GFCEMCreateInputElements(lFor, lInner, iKey) {
    let element = document.createElement('div');
    element.classList.add('gfcem-input-row');

    let label = document.createElement('label');
    label.setAttribute('for', lFor)
    label.innerHTML = lInner;

    element.appendChild(label);

    let input = document.createElement('input');
    input.classList.add('gfcem_input');
    input.setAttribute('type', 'text')
    input.setAttribute('id', lFor)
    input.dataset.key = iKey;

    element.appendChild(input);

    return element;
}