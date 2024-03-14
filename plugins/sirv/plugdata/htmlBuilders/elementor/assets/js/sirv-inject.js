jQuery(function($){
    window.injectSirvJS = function(){
        /* let ifr = $('iframe#elementor-preview-iframe')[0];
        let scr = ifr.contentWindow.document.createElement('script');
        //scr.src = 'https://scripts.sirv.com/sirvjs/v3/sirv.js';
        scr.src = 'http://scripts.sirv.com/sirv.js';
        ifr.contentWindow.document.head.append(scr); */
        let $container = $('head');
        let scr = window.document.createElement('script');
        //scr.src = 'http://scripts.sirv.com/sirv.js';
        scr.src = 'https://scripts.sirv.com/sirvjs/v3/sirv.js';
        $container.append(scr);
        setTimeout(() => {
            if(!!window.Sirv){
                Sirv.start();
            }
        }, 1000);
    }


    function startSirvJS(){
        if($('.sirv-elementor-click-overlay').length && !!window.Sirv){
            Sirv.start();
        }
    }

    $(document).on('updateSh', startSirvJS);


    $(document).ready(function(){
        startSirvJS();

        $('body').on('click', '.sirv-elementor-click-overlay', function(){
            window.parent.runEvent(window.parent, 'renderShPanel');
        });

    }); // dom ready end

}); //closure end
