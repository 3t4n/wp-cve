'use strict';

function ytplayer_render_playlist(what, howMany) {
    let x,
        ytplayer_playlist = [],
        ytplayer_playitem = 0,
        varr = what.toString(),
        varr1 = varr.split(',');

    for (x = 0; x < howMany; x++) {
        ytplayer_playlist.push(varr1[x]);
    }

    for (let i = 0; i < ytplayer_playlist.length; i++) {
        let img = document.createElement('img'),
            a = document.createElement('a');

        img.src = 'https://img.youtube.com/vi/' + ytplayer_playlist[i].trim() + '/hqdefault.jpg';
        a.href = 'https://www.youtube.com/embed/' + ytplayer_playlist[i].trim() + '?rel=0&hd=1&version=3&iv_load_policy=3&showinfo=0&autoplay=1';
        a.rel = ytplayer_playlist[i].trim();
        a.target = 'ytpl-frame';

        a.onclick = (j => {
            return function () {
                ytplayer_playitem = j;

                let o = document.getElementById('ytplayer_object');
                if (o) {
                    o.loadVideoById(ytplayer_playlist[ytplayer_playitem]);
                }


                let els = document.querySelectorAll('#ytplayer_div2 a');
                for (var n = 0; n < els.length; n++) {
                    els[n].classList.remove('active');
                }

                this.classList.add('active');
            };
        })(i);

        a.appendChild(img);

        if (howMany > 1) {
            document.getElementById('ytplayer_div2').appendChild(a);
        }
    }
}



/**
 * Create a playlist based on an array of YouTube video IDs
 *
 * @version V3
 */
function ytCreatePlaylist() {
    let ytApiKey = document.querySelector('.yt-api-container').dataset.apikey,
        videoId = document.querySelector('.yt-api-container').dataset.vdid,
        httpRequest = new XMLHttpRequest();

    // https://developers.google.com/youtube/v3/getting-started
    httpRequest.open('GET', "https://www.googleapis.com/youtube/v3/videos?part=snippet&fields=items(id,snippet)&id=" + videoId + "&key=" + ytApiKey, true);
    httpRequest.setRequestHeader('Content-Type', 'application/json');
    httpRequest.onreadystatechange = (data) => {
        if (httpRequest.readyState === 4) {
            data = JSON.parse(httpRequest.responseText);

            let videoId = document.querySelector('.yt-api-container').dataset.vdid,
                videoArray = videoId.split(','),
                videoElement;

            videoArray.forEach((item, index) => {
                videoElement = '<div class="yt-api-video-item yt-' + data.items[index].id + '" data-id="' + data.items[index].id + '"><div class="yt-api-video-thumb"><img src="' + data.items[index].snippet.thumbnails.high.url + '" alt="' + data.items[index].snippet.title + '" data-id="' + data.items[index].id + '"></div><div class="yt-api-video-description">' + data.items[index].snippet.title + '</div></div>';

                document.querySelector('.yt-api-video-list').innerHTML += videoElement;
            });
        }
    };
    httpRequest.send();
}



document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('yt-container')) {
        let playListContainer = document.getElementById('ytpp-playlist-container'),
            playList = playListContainer.dataset.playlist,
            ytpp_novd = playList.split(',').length;

        ytplayer_render_playlist(playList, ytpp_novd);
    }



    // V3
    if (document.querySelector('.yt-api-container')) {
        ytCreatePlaylist();
    }

    document.addEventListener('click', function () {
        if (event.target.matches('.yt-api-video-item .yt-api-video-thumb img')) {
            let ytUri = 'https://www.youtube.com';

            document.getElementById('vid_frame').src = ytUri + '/embed/' + event.target.dataset.id + '?autoplay=1&rel=0&showinfo=1&autohide=1';
        }
    });



    // YouTube Channel Feed
    if (document.querySelector('.ytpp-video-thumbnail')) {
        const thumbnails = document.querySelectorAll('.ytpp-video-thumbnail');

        thumbnails.forEach((thumbnail) => {
            thumbnail.addEventListener("click", function(event) {
                event.preventDefault();

                const videoId = this.dataset.videoId;
                const videoUrl = `https://www.youtube.com/embed/${videoId}`;

                const lightboxContainer = document.getElementById('ytpp-lightbox-container');
                const lightboxVideo = document.getElementById('ytpp-lightbox-video');

                lightboxVideo.innerHTML = `<iframe width="560" height="315" src="${videoUrl}" frameborder="0" allowfullscreen></iframe>`;
                lightboxContainer.style.display = 'flex';
            });
        });

        const lightboxContainer = document.getElementById('ytpp-lightbox-container');

        lightboxContainer.addEventListener('click', function(event) {
            // Check if the clicked element is the lightbox container or its descendant elements
            if (
                event.target === lightboxContainer ||
                event.target === lightboxCloseBtn ||
                lightboxContainer.contains(event.target)
            ) {
                lightboxContainer.style.display = 'none';
                const lightboxVideo = document.getElementById('ytpp-lightbox-video');
                lightboxVideo.innerHTML = '';
            }
        });

        const lightboxCloseBtn = document.getElementById('ytpp-lightbox-close-btn');

        lightboxCloseBtn.addEventListener('click', function() {
            lightboxContainer.style.display = 'none';
            const lightboxVideo = document.getElementById('ytpp-lightbox-video');
            lightboxVideo.innerHTML = '';
        });
    }
});
