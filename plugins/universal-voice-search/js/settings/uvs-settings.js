
/**
 * Function to reset auto end mic listening timeout
 *
 * @param this- DOMElement Object
 * @param evt - Event 
 */
function uvsResetTimeoutDefaultValue(el, evt) {
    if (typeof (el) == 'undefined') return;

    if (el.value.length == 0) {
        el.value = "8";
    } else if (parseInt(el.value) > 20) {
        el.value = "20";
    } else if (parseInt(el.value) < 8) {
        el.value = "8";
    }
}

/**
 * Function to validate length of timeout value
 *
 * @param this- DOMElement Object
 * @param evt - Event 
 */
function uvsValidateTimeoutValue(el, evt) {
    if (typeof (el) == 'undefined') return;

    if (el.value.length == 2 && parseInt(el.value) > 20) {
        evt.preventDefault();
    }
}

/**
 * Function to handel keyboard special key or normal key
 * 
 * @param Key - string : Selected key type
 */
function uvstoggleInputFieldOtherKey(Key = 'OtherKey') {
    try {
        let uvsaOtherKey = document.querySelector('input#uvsKeyBoardSwitch');
        let uvsaOtherInput = document.getElementsByClassName('uvsShowOtherInput')[0];
        let warningField = document.getElementsByClassName('uvsWarningInputKey')[0];
        if (Key === 'OtherKey') {
            uvsaOtherKey.removeAttribute('disabled');
            uvsaOtherInput.classList.remove('uvs-hide');
            uvsaOtherKey.setAttribute('required', 'required');
        } else {
            uvsaOtherKey.setAttribute('disabled', 'disabled');
            uvsaOtherInput.classList.add('uvs-hide');
            uvsaOtherKey.removeAttribute('required');
        }
        warningField.innerHTML = "";
    } catch (error) {

    }
}

/**
 * Function for validate input keyboard key that can store only single char from a-z
 * 
 * @param {el: HTMLDomObject} 
 * @param {evt} event  
 */
function uvsValidateValueForOtherKey(el, evt) {
    let warningField = document.getElementsByClassName('uvsWarningInputKey')[0];
    if (evt.data == null) {
        warningField.innerHTML = "";
    } else if (evt.data.charCodeAt(0) >= 97 && evt.data.charCodeAt(0) <= 122) {
        el.value = evt.data;
        warningField.innerHTML = "";
    } else {
        warningField.innerHTML = `<span style="color: red;"><b>&#9888;</b> Please enter lowercase letters only (a-z) </span>`;
        el.value = '';
    }
}

// ########################################################################
//
// For Window and Document load and Unload Events
//
// ########################################################################
window.addEventListener('load', function () {

    if (this.document.getElementById('uvsSpecialKeyOtherKey')) {
        if (this.document.getElementById('uvsSpecialKeyOtherKey').checked) {
            this.document.getElementsByClassName('uvsShowOtherInput')[0].classList.remove('uvs-hide');
            this.document.getElementById('uvsKeyBoardSwitch').setAttribute('required', 'required');
        }
    }
});
