var clipboardSnippets = new Clipboard('[data-clipboard-snippet]', {
  target: function(trigger) {
    return trigger.nextElementSibling;
  }
});
clipboardSnippets.on('success', function(e) {
  e.clearSelection();
  showTooltip(e.trigger, 'Copied!');
});
clipboardSnippets.on('error', function(e) {
  showTooltip(e.trigger, fallbackMessage(e.action));
}); 

var btns = document.querySelectorAll('.scwp-clippy-icon');
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener('mouseleave', function(e) {
    e.currentTarget.setAttribute('class', 'scwp-clippy-icon');
    e.currentTarget.removeAttribute('aria-label');
  });
}

function showTooltip(elem, msg) {
  elem.setAttribute('class', 'scwp-clippy-icon tooltipped tooltipped-s');
  elem.setAttribute('aria-label', msg);
}

function fallbackMessage(action) {
    var actionMsg = '';
    var actionKey = (action === 'cut' ? 'X' : 'C');
    if (/iPhone|iPad/i.test(navigator.userAgent)) {
        actionMsg = 'No support :(';
    } else if (/Mac/i.test(navigator.userAgent)) {
        actionMsg = 'Press ⌘-' + actionKey + ' to ' + action;
    } else {
        actionMsg = 'Press Ctrl-' + actionKey + ' to ' + action;
    }
    return actionMsg;
}
hljs.initHighlightingOnLoad();
