'use strict';

window.onload = function () {
  const copyContent = async (text) => {
    try {
      await navigator.clipboard.writeText(text);
      // console.log('Content copied to clipboard');
    } catch (err) {
      console.error('Failed to copy: ', err);
    }
  }

  document.querySelectorAll('.kadence-hook input[type=text]').forEach(el => {
    el.addEventListener('click', function(e){
      // Store the original input's text
      let inputText = e.target.value;

      // Copy the input's text
      copyContent(inputText);

      // Show confirmation
      e.target.value = 'Copied ✓';

      // Restore the original text
      setTimeout(() => {
        e.target.value = inputText;
      }, 1000)
    });
  });
}
