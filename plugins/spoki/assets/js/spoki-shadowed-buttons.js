document.addEventListener('DOMContentLoaded', function () {
    (() => Array.from(document.getElementsByClassName('spoki-shadowed-button')).forEach(el => {
        const content = document.importNode(el, true);
        const shadowRoot = el.attachShadow({mode: 'open'});
        el.innerHTML = '';
        el.className = el.className.replace('spoki-shadowed-button', '');
        const style = document.createElement('style');
        style.innerHTML = document.getElementById('spoki-style-buttons').innerHTML.replace(/(\\r\\n|\\n|\\r)/gm, '');
        shadowRoot.appendChild(style);
        shadowRoot.appendChild(content.firstChild);
    }))();
})