jQuery(document).ready( () => {
    if(user_can_close_top_bar == 1){
        document.getElementById('cb-top-bar-close').addEventListener('click',() => {
            document.getElementById('top-bar').style.display='none';
        });
    }

    if(sticky_top_bar==1){
        document.getElementById('top-bar').classList.add('cb-sticky-top-bar');
    }else{
        document.getElementById('top-bar').classList.remove('cb-sticky-top-bar');
    }
})