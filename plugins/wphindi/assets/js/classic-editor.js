(() => { 
var contentTransliterator   = null;
contentTextTransliterator   = new WPHindi.writer('#content', true)
titleTransliterator         = new WPHindi.writer('#title', true);

contentTextTransliterator.load();
titleTransliterator.load();

var transliterationStatus       = true;



function toggleTransliterator(e){
    e.preventDefault();

    contentTransliterator.toggle();
    titleTransliterator.toggle();
    contentTextTransliterator.toggle();

    this.classList.toggle('enabled');
    this.classList.toggle('active');

    if(transliterationStatus){
        transliterationStatus = false;
    }else{
        transliterationStatus = true;
    }
    
    var text = this.querySelector('.text');
    
    if(transliterationStatus){
        text.innerText = 'Disable WPHindi';
    }else{
        text.innerText = 'Enable WPHindi';
    }
}

jQuery( document ).on('tinymce-editor-init',function(){
    if(contentTransliterator === null){
        contentTransliterator = new WPHindi.writer('#content_ifr', transliterationStatus);
        contentTransliterator.load();
    }
});

var transliterateToggle = document.querySelector('#toggle-transliterator');
if(transliterateToggle != null){
    transliterateToggle.addEventListener('click',toggleTransliterator);
}
})()