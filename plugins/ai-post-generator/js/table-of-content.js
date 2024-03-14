document.querySelectorAll('.ai-table-of-contents .toggle-toc, .ai-table-of-contents .toc-headline').forEach(toggler => {
  toggler.addEventListener('click', function() {
    let tocList = document.querySelectorAll('.ai-table-of-contents ul')[0];
    let toggler = document.querySelectorAll('.ai-table-of-contents .toggle-toc')[0];
    if(tocList.style.display == 'none') {
      tocList.style.display = 'block';
      toggler.innerHTML = '-';
    } else {
      tocList.style.display = 'none';
      toggler.innerHTML = '+';
    }
  });
});
