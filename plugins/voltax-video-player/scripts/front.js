(function(window, document) {

    document.addEventListener('DOMContentLoaded', (e) => {
        let videos = document.getElementsByClassName('mm-video-embed');
        for(let i=videos.length -1; i > -1; i--) {
            if(videos[i].hasAttribute('data-content-id') && videos[i].hasAttribute('data-player-id')) {
                let s = document.createElement('script');
                s.src = mm_video_data.endpointUrl + '/' + videos[i].getAttribute('data-player-id') + '.js';
                s.setAttribute('data-content-id', videos[i].getAttribute('data-content-id'));

                if (videos[i].hasAttribute('data-extra-content-id')) {
                    s.setAttribute('data-extra-content-id', videos[i].getAttribute('data-extra-content-id'));
                }

                videos[i].parentNode.replaceChild(s, videos[i]);
            }
            else {
                console.warn('Voltax Embed was missing a required attribute');
                console.log(videos[i]);
            }
        }
    });


})(window, document);