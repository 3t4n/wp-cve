function handleBlikCodeInput(event) {
    const blikCodeInput = document.getElementById('js-cx-blik-code-input');
    const oldValue = blikCodeInput.getAttribute('oldvalue');
    const newValue = event.target.value;
    const blikCode = event.inputType === 'deleteContentBackward' && oldValue.slice(-1) === '_'
                     ? resolveBlikCode(oldValue).slice(0, -1)
                     : resolveBlikCode(newValue);
    blikCodeInput.value = formatBlikCode(blikCode);
    blikCodeInput.setAttribute('oldvalue', blikCodeInput.value);
}

function resolveBlikCode(value) {
    let valueArray = value.split('');
    valueArray = valueArray.filter(element => /^\d$/.test(element));
    if (valueArray.length > 6) {
        valueArray = valueArray.slice(0, 6);
    }
    return valueArray.join('');
}

function formatBlikCode(blikCode) {
    if (blikCode.length > 3) {
        blikCode = blikCode.slice(0, 3) + ' ' + blikCode.slice(3);
    }
    for (let index = blikCode.length; index < 7; index++) {
        blikCode += index === 3 ? ' ' : '_';
    }
    return blikCode;
}
