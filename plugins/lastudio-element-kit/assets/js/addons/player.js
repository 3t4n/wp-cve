(function ($) {
    "use strict";

    const playerSVGs = {
        prev: 'M864 960c4.96 0 9.984-1.152 14.56-3.488A31.985 31.985 0 0 0 896 928V96c0-12.032-6.72-23.04-17.44-28.512-10.72-5.504-23.584-4.544-33.312 2.56l-576 416C260.928 492.064 256 501.728 256 512s4.928 19.936 13.248 25.952l576 416C850.816 957.984 857.408 960 864 960zm-32-94.592L342.656 512 832 158.592v706.816zM128 64v896c0 17.696 14.304 32 32 32s32-14.304 32-32V64c0-17.696-14.304-32-32-32s-32 14.304-32 32z',
        next: 'M145.44 67.488A31.985 31.985 0 0 0 128 96v832c0 12.032 6.72 23.04 17.44 28.512A32.086 32.086 0 0 0 160 960c6.592 0 13.184-2.016 18.752-6.048l576-416C763.072 531.936 768 522.272 768 512s-4.928-19.936-13.248-25.952l-576-416c-9.76-7.04-22.624-8-33.312-2.56zM192 158.592L681.344 512 192 865.408V158.592zM864 992c17.696 0 32-14.304 32-32V64c0-17.696-14.304-32-32-32s-32 14.304-32 32v896c0 17.696 14.304 32 32 32z',
        play: 'M224 960c6.4 0 12.8-3.2 19.2-6.4l576-416c9.6-6.4 12.8-16 12.8-25.6s-6.4-19.2-12.8-25.6l-576-416c-9.6-6.4-22.4-9.6-35.2-3.2-9.6 6.4-16 16-16 28.8v832c0 12.8 6.4 22.4 16 28.8 6.4 3.2 9.6 3.2 16 3.2zm32-800l489.6 352L256 864V160z',
        playFull: 'M850 430.002C878.6 447.602 896 478.602 896 512.002C896 545.402 878.6 576.402 850 592.202L274.06 944.202C244.42 964.002 207.32 964.802 177.04 947.802C146.754 930.802 128 898.802 128 864.002V160.002C128 125.282 146.754 93.262 177.04 76.262C207.32 59.282 244.42 59.982 274.06 78.082L850 430.002Z',
        pause: 'M384 928c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H160c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM192 160h160v704H192V160zm672 768c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H640c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM672 160h160v704H672V160z',
        autoplay: 'M64 576c17.664 0 32-14.304 32-32 0-141.152 114.848-256 256-256h530.752L745.376 425.376c-12.512 12.512-12.512 32.736 0 45.248 6.24 6.24 14.432 9.376 22.624 9.376s16.384-3.136 22.624-9.376l191.968-191.968c2.976-2.944 5.312-6.496 6.944-10.432a32.034 32.034 0 0 0 0-24.448c-1.632-3.936-3.968-7.456-6.944-10.432L790.624 41.376c-12.512-12.512-32.736-12.512-45.248 0s-12.512 32.736 0 45.248L882.752 224H352C175.552 224 32 367.552 32 544c0 17.696 14.336 32 32 32zM34.464 780.224c1.632 3.936 3.968 7.456 6.944 10.432l191.968 191.968c6.24 6.24 14.432 9.376 22.624 9.376s16.384-3.136 22.624-9.376c12.512-12.512 12.512-32.736 0-45.248L141.248 800H672c176.448 0 320-143.552 320-320 0-17.696-14.336-32-32-32s-32 14.304-32 32c0 141.152-114.848 256-256 256H141.248l137.376-137.376c12.512-12.512 12.512-32.736 0-45.248s-32.736-12.512-45.248 0L41.408 745.344c-2.976 2.944-5.312 6.496-6.944 10.432a32.034 32.034 0 0 0 0 24.448z',
        volume: 'M656 67.2005C646.4 60.8005 633.6 64.0005 624 70.4005L278.4 320H32C12.8 320 0 332.8 0 352V672C0 691.2 12.8 704 32 704H278.4L620.8 953.6C627.2 956.8 633.6 960 640 960C646.4 960 649.6 960 656 956.8C665.6 950.4 672 940.8 672 928V96.0005C672 83.2005 665.6 73.6005 656 67.2005ZM608 864L307.2 646.401C300.8 643.201 294.4 640 288 640H64V384H288C294.4 384 300.8 380.8 307.2 377.6L608 160V864Z',
        volumeFull: 'M656 67.2c-9.6-6.4-22.4-3.2-32 3.2L278.4 320H32c-19.2 0-32 12.8-32 32v320c0 19.2 12.8 32 32 32h246.4l342.4 249.6c6.4 3.2 12.8 6.4 19.2 6.4s9.6 0 16-3.2c9.6-6.4 16-16 16-28.8V96c0-12.8-6.4-22.4-16-28.8zM608 864L307.2 646.4c-6.4-3.2-12.8-6.4-19.2-6.4H64V384h224c6.4 0 12.8-3.2 19.2-6.4L608 160v704zm195.2-512c-12.8-12.8-32-12.8-44.8 0s-12.8 32 0 44.8c64 64 64 166.4 0 230.4-12.8 12.8-12.8 32 0 44.8 6.4 6.4 16 9.6 22.4 9.6s16-3.2 22.4-9.6c86.4-89.6 86.4-230.4 0-320zm64-108.8c-12.8 12.8-12.8 32 0 44.8C928 348.8 960 428.8 960 512s-32 163.2-92.8 224c-12.8 12.8-12.8 32 0 44.8 6.4 6.4 16 9.6 22.4 9.6s16-3.2 22.4-9.6c73.6-73.6 112-166.4 112-268.8s-38.4-198.4-112-268.8c-12.8-12.8-32-12.8-44.8 0z',
        volumeLower: 'M656 67.2005C646.4 60.8005 633.6 64.0005 624 70.4005L278.4 320H32C12.8 320 0 332.8 0 352V672C0 691.2 12.8 704 32 704H278.4L620.8 953.6C627.2 956.8 633.6 960 640 960C646.4 960 649.6 960 656 956.8C665.6 950.4 672 940.8 672 928V96.0005C672 83.2005 665.6 73.6005 656 67.2005ZM608 864L307.2 646.401C300.8 643.201 294.4 640 288 640H64V384H288C294.4 384 300.8 380.8 307.2 377.6L608 160V864ZM803.2 352C790.4 339.2 771.2 339.2 758.4 352C745.6 364.8 745.6 384 758.4 396.8C822.4 460.8 822.4 563.201 758.4 627.201C745.6 640.001 745.6 659.2 758.4 672C764.8 678.4 774.4 681.6 780.8 681.6C787.2 681.6 796.8 678.4 803.2 672C889.6 582.4 889.6 441.6 803.2 352Z',
        volumeMute: 'M656 67.2c-9.6-6.4-22.4-3.2-32 3.2L278.4 320H32c-19.2 0-32 12.8-32 32v320c0 19.2 12.8 32 32 32h246.4l342.4 249.6c6.4 3.2 12.8 6.4 19.2 6.4s9.6 0 16-3.2c9.6-6.4 16-16 16-28.8V96c0-12.8-6.4-22.4-16-28.8zM608 864L307.2 646.4c-6.4-3.2-12.8-6.4-19.2-6.4H64V384h224c6.4 0 12.8-3.2 19.2-6.4L608 160v704zm256-512c-89.6 0-160 70.4-160 160s70.4 160 160 160 160-70.4 160-160-70.4-160-160-160zm96 160c0 16-3.2 28.8-9.6 41.6l-128-128c12.8-6.4 25.6-9.6 41.6-9.6 54.4 0 96 41.6 96 96zm-192 0c0-16 3.2-28.8 9.6-41.6l128 128c-12.8 6.4-25.6 9.6-41.6 9.6-54.4 0-96-41.6-96-96z',
        music: 'M960 736V96c0-19.2-12.8-32-32-32H352c-19.2 0-32 12.8-32 32v608c-25.6-19.2-60.8-32-96-32-89.6 0-160 70.4-160 160s70.4 160 160 160 160-70.4 160-160V352h512v256c-25.6-19.2-60.8-32-96-32-89.6 0-160 70.4-160 160s70.4 160 160 160 160-70.4 160-160zM224 928c-54.4 0-96-41.6-96-96s41.6-96 96-96 96 41.6 96 96-41.6 96-96 96zm160-800h512v160H384V128zm320 608c0-54.4 41.6-96 96-96s96 41.6 96 96-41.6 96-96 96-96-41.6-96-96z',
        random: 'M982.592 265.344L822.624 105.376c-12.512-12.512-32.736-12.512-45.248 0s-12.512 32.736 0 45.248L882.752 256H728.32c-65.472 0-127.456 28.544-170.048 78.24L289.184 648.128C258.752 683.616 214.464 704 167.68 704H64c-17.696 0-32 14.304-32 32s14.304 32 32 32h103.68c65.472 0 127.456-28.544 170.048-78.24l269.088-313.888C637.248 340.384 681.536 320 728.32 320h154.432L777.376 425.376c-12.512 12.512-12.512 32.736 0 45.248 6.24 6.24 14.432 9.376 22.624 9.376s16.384-3.136 22.624-9.376l159.968-159.968c2.976-2.944 5.312-6.496 6.944-10.432a32.034 32.034 0 0 0 0-24.448c-1.632-3.936-3.968-7.488-6.944-10.432zM64 320h103.68c46.784 0 91.072 20.384 121.504 55.904l38.56 44.96c6.304 7.36 15.296 11.168 24.288 11.168 7.36 0 14.784-2.528 20.832-7.712 13.408-11.488 14.944-31.68 3.424-45.12l-38.528-44.928C295.168 284.544 233.152 256 167.68 256H64c-17.696 0-32 14.304-32 32s14.304 32 32 32zm925.536 403.776a32.675 32.675 0 0 0-6.944-10.464L822.624 553.344c-12.512-12.512-32.736-12.512-45.248 0s-12.512 32.736 0 45.248L882.752 704H728.32c-46.784 0-91.072-20.384-121.504-55.904l-38.56-44.96c-11.424-13.408-31.648-14.944-45.12-3.424-13.408 11.488-14.944 31.68-3.424 45.12l38.528 44.928C600.832 739.456 662.848 768 728.32 768h154.432L777.376 873.376c-12.512 12.512-12.512 32.736 0 45.248 6.24 6.24 14.432 9.376 22.624 9.376s16.384-3.136 22.624-9.376l159.968-159.968c2.976-2.944 5.312-6.496 6.944-10.432a32.034 32.034 0 0 0 0-24.448z'
    }
    const getSVGIcon = function ( icon ){
        return `<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1024" height="1024" viewBox="0 0 1024 1024" class="lakit-font-icon-svg" data-icon-name="${icon}" data-icon-type="dlicon"><path d="${playerSVGs[icon]}" fill="currentColor"/></svg>`;
    }
    const getFormatTime = function ( val ){
        let h = 0, m = 0, s;
        val = parseInt(val, 10);
        if (val > 60 * 60) {
            h = parseInt(val / (60 * 60), 10);
            val -= h * 60 * 60;
        }
        if (val > 60) {
            m = parseInt(val / 60, 10);
            val -= m * 60;
        }
        s = val;
        val = (h > 0) ? ( (h < 10 ? '0' : '') +  h + ':') : '';
        val = (m > 0) ? ( (m < 10 ? '0' : '') +  m + ':') : '00:';
        val += ((s < 10)? '0' : '') + s;
        return val;
    }
    const getRandom = function ( min, max ){
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    const maybeSetupHiddenPlayer = function ( albumID ){
        let $hiddenWrapper;
        if( $('.lakitplayer--playerhidden').length === 0 ){
            $('body').append('<div class="lakitplayer--playerhidden lakitplayer--hidden"></div>');
            $hiddenWrapper = $('.lakitplayer--playerhidden');
        }
        else{
            $hiddenWrapper = $('.lakitplayer--playerhidden');
        }
        if( $('audio.lakitplayer_id_' + albumID).length === 0 ){
            $('<audio preload="none" class="lakitplayer--hidden lakitplayer_id_'+albumID+'" data-id="'+albumID+'"><source type="audio/mpeg" src="" /></audio>').appendTo($hiddenWrapper);
        }

        let _sources = {};

        $('.lakitplayer[data-album_id="'+albumID+'"]:not(.player--initialized)').find('.lakitplayer_btn__playpause').each(function (){
            $(this).append('<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1024" height="1024" viewBox="0 0 1024 1024" class="lakit-font-icon-svg" data-icon-name="pause" data-icon-type="dlicon"><path d="M384 928c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H160c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM192 160h160v704H192V160zm672 768c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H640c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM672 160h160v704H672V160z" fill="currentColor"></path></svg>')
            $(this).closest('.lakitplayer').addClass('player--initialized');
            _sources = $(this).closest('.lakitplayer').data('config') || {}
        });
        if( typeof window.LAKIT_Players[albumID] === "undefined"){
            window.LAKIT_Players = {
                ...window.LAKIT_Players,
                ...{
                    [albumID]: {
                        currentIndex: 0,
                        isRunning: false,
                        isAutoPlay: true,
                        isShuffle: false,
                        isMuted: false,
                        opts: _sources
                    }
                }
            };
        }
    }
    const setupPlayerEvents = function ( player, $wrap, defaultConfigs ){
        const $ProgressBar = $('.lakitplayer_tracker__progress', $wrap);
        const $ProgressBarWrap = $ProgressBar?.closest('.lakitplayer_rangewrap');
        const $timeStart = $('.lakitplayer_time__start', $wrap);
        const $timeEnd = $('.lakitplayer_time__end', $wrap);

        let isAutoPlay = window.LAKIT_Players[defaultConfigs.album_id].isAutoPlay;
        let isShuffle = window.LAKIT_Players[defaultConfigs.album_id].isShuffle;
        let currentIndex = window.LAKIT_Players[defaultConfigs.album_id].currentIndex;

        player.muted = window.LAKIT_Players[defaultConfigs.album_id].isMuted;

        player.addEventListener('canplaythrough', () => {
            if(player.readyState === 4){
                $wrap.removeClass('v--loading');
            }
        })
        player.addEventListener('loadedmetadata', () => {
            const isStream = (player.duration.toString() === 'Infinity');
            $timeStart?.html('00:00');
            $timeEnd?.html(isStream ? '--:--' : getFormatTime(player.duration));
            if(isStream){ $wrap.addClass('v--isStream'); }
            else{ $wrap.removeClass('v--isStream'); }
        } );
        player.addEventListener('timeupdate', () => {
            let _val = player.duration.toString() !== 'Infinity' ? (player.currentTime / player.duration * 1000) : 0;
            if(isNaN(_val)){ _val = 0 }
            $ProgressBar?.val( _val);
            $ProgressBarWrap?.css('--value', _val);
            $timeStart?.html(getFormatTime(player.currentTime));
        } );
        player.addEventListener('ended', () => {
            $wrap.removeClass('isPlaying');
            if(isAutoPlay){
                let _max = defaultConfigs.sources.length,
                    _newIndex = currentIndex + 1;
                if(_max > 1){
                    if(_newIndex >= _max){
                        _newIndex = 0;
                    }
                    if(isShuffle){
                        _newIndex = getRandom(0, _max - 1);
                        if(_newIndex === currentIndex){
                            _newIndex = currentIndex + 1;
                            if(_newIndex >= _max){
                                _newIndex = 0;
                            }
                        }
                    }
                    window.LAKIT_Players[defaultConfigs.album_id].currentIndex = _newIndex;
                    window.LAKIT_Players[defaultConfigs.album_id].isRunning = false;
                    $('.lakitplayer[data-album_id="'+defaultConfigs.album_id+'"] .lakitplayer_btn__playpause').first().trigger('click');
                }
            }
        } );
        $ProgressBar?.on('input', function (){
            $ProgressBarWrap?.css('--value', $(this).val());
            player.currentTime = $(this).val() * player.duration / 1000;
        })
    }
    $(document).on('input', '.lakitplayer .lakitplayer_volume__progress', function (){
        const _volume = $(this).val() / 100;
        let albumID = $(this).closest('.lakitplayer').data('album_id'),
            $wrap = $('.lakitplayer[data-album_id="'+albumID+'"]'),
            $btnVolume = $wrap.find('.lakitplayer_btn__volume'),
            $VolumeProgressBarWrap = $wrap.find('.lakitplayer__control_volumes .lakitplayer_rangewrap');

        let player = $('.lakitplayer_id_' + albumID).get(0);

        let muted;

        if(_volume > 0.7){
            $wrap.removeClass('v--muted v--lower');
            $btnVolume.html( getSVGIcon('volumeFull') );
            muted = false;
        }
        else if(_volume > 0.4){
            $wrap.removeClass('v--muted').addClass('v--lower');
            $btnVolume.html( getSVGIcon('volumeLower') );
            muted = false;
        }
        else if(_volume > 0){
            $wrap.removeClass('v--muted');
            $btnVolume.html( getSVGIcon('volumeLower') );
            muted = false;
        }
        else{
            $wrap.removeClass('v--lower').addClass('v--muted');
            $btnVolume.html( getSVGIcon('volumeMute') );
            muted = true;
        }
        try{
            player.muted = muted;
            player.volume = _volume;
        }catch (ex) {}
        $VolumeProgressBarWrap?.css('--value', $(this).val());
    });
    $(document).on('click', '.lakitplayer .lakitplayer_btn__volume', function (e){
        e.preventDefault();
        let albumID = $(this).closest('.lakitplayer').data('album_id'),
            $wrap = $('.lakitplayer[data-album_id="'+albumID+'"]'),
            $btnVolume = $wrap.find('.lakitplayer_btn__volume'),
            $VolumeProgressBarWrap = $wrap.find('.lakitplayer__control_volumes .lakitplayer_rangewrap');
        let player = $('.lakitplayer_id_' + albumID).get(0);

        let isMuted = window.LAKIT_Players[albumID].isMuted;

        window.LAKIT_Players[albumID].isMuted = !isMuted;

        if(!isMuted){
            let bk_val = $VolumeProgressBarWrap.css('--value');
            $VolumeProgressBarWrap.data('bksvg', $btnVolume.html()).data('bkvalue', bk_val).css('--value', 0);
            $btnVolume.html( getSVGIcon('volumeMute') );
        }
        else{
            $VolumeProgressBarWrap.css('--value', $VolumeProgressBarWrap.data('bkvalue'));
            $btnVolume.html( $VolumeProgressBarWrap.data('bksvg') );
        }
        try{
            player.muted = !isMuted;
        }catch (ex) {}
    })
    $(document).on('click', '.lakitplayer .lakitplayer_playlist__item', function (e){
        e.preventDefault();
        let newIndex = parseInt($(this).data('trackindex')),
            albumID = $(this).closest('.lakitplayer').data('album_id'),
            oldIndex = window.LAKIT_Players[albumID].currentIndex;

        if(newIndex !== oldIndex){
            window.LAKIT_Players[albumID].currentIndex = newIndex;
            window.LAKIT_Players[albumID].isRunning = false;
            $('.lakitplayer[data-album_id="'+albumID+'"] .lakitplayer_btn__playpause').first().trigger('click');
        }
    });
    $(document).on('click', '.lakitplayer .lakitplayer_btn__playpause', function (e){
        e.preventDefault();
        let $parent = $(this).closest('.lakitplayer'),
            configs = $parent.data('config'),
            albumID = $parent.data('album_id');

        let $wrap = $('.lakitplayer[data-album_id="'+albumID+'"]'),
            $playlist = $wrap.find('.lakitplayer_playlists');

        let $otherWraps = $('.lakitplayer:not([data-album_id="'+albumID+'"])');
        let $otherPlayers = $('audio:not(.lakitplayer_id_' + albumID+')');

        let $preview = $wrap.find('.lakitplayer__control__preview_root')

        $otherWraps.removeClass('isPlaying');
        $otherPlayers.each( function (){
            let _oID = $(this).data('id');
            window.LAKIT_Players[_oID].isRunning = false;
            $(this).get(0).pause();
        } )

        maybeSetupHiddenPlayer(albumID);

        let currentTrackIndex = window.LAKIT_Players[albumID].currentIndex;
        let isRunning = window.LAKIT_Players[albumID].isRunning;
        if(currentTrackIndex < 0){
            currentTrackIndex = 0
        }

        if($preview.length){
            if(configs?.sources[currentTrackIndex]?.image){
                $preview.css('--previewURL', 'url("'+configs?.sources[currentTrackIndex]?.image+'")')
            }
            else if(configs?.preview){
                $preview.css('--previewURL', 'url("'+configs?.preview+'")')
            }
        }

        const _player = $('.lakitplayer_id_' + albumID);
        const _currentSrc = _player.find('source').attr('src');
        if(_currentSrc !== configs.sources[currentTrackIndex].src){
            _player.find('source').attr('src', configs.sources[currentTrackIndex].src);
            _player[0].load();
            $wrap.addClass('v--loading');
        }

        $playlist.find('.lakitplayer_playlist__item[data-trackindex="'+currentTrackIndex+'"]').addClass('active-track');
        $playlist.find('.lakitplayer_playlist__item:not([data-trackindex="'+currentTrackIndex+'"])').removeClass('active-track');

        setupPlayerEvents(_player[0], $wrap, configs);
        window.LAKIT_Players[albumID].isRunning = !isRunning;
        if(!isRunning){
            _player[0].play();
            $wrap.addClass('isPlaying');
        }
        else{
            _player[0].pause();
            $wrap.removeClass('isPlaying');
        }
    });
    $(document).on('click', '.lakitplayer .lakitplayer_btn__prev', function (e){
        e.preventDefault();
        let albumID = $(this).closest('.lakitplayer').data('album_id'),
            _max = window.LAKIT_Players[albumID].opts?.sources?.length || 0,
            currentIndex = window.LAKIT_Players[albumID].currentIndex,
            isShuffle = window.LAKIT_Players[albumID].isShuffle;

        if(currentIndex < 0){
            currentIndex = _max
        }

        if(_max < 1){
            return;
        }

        let _newIndex = currentIndex - 1;
        if(_newIndex < 0){
            _newIndex = _max - 1;
        }
        if(isShuffle){
            _newIndex = getRandom(0, _max - 1);
            if(_newIndex === currentIndex){
                _newIndex = currentIndex - 1;
                if(_newIndex < 0){
                    _newIndex = _max - 1;
                }
            }
        }
        window.LAKIT_Players[albumID].currentIndex = _newIndex;
        window.LAKIT_Players[albumID].isRunning = false;
        $('.lakitplayer[data-album_id="'+albumID+'"] .lakitplayer_btn__playpause').first().trigger('click');
    });
    $(document).on('click', '.lakitplayer .lakitplayer_btn__next', function (e){
        e.preventDefault();
        let albumID = $(this).closest('.lakitplayer').data('album_id'),
            _max = window.LAKIT_Players[albumID].opts?.sources?.length || 0,
            currentIndex = window.LAKIT_Players[albumID].currentIndex,
            isShuffle = window.LAKIT_Players[albumID].isShuffle;

        if(currentIndex < 0){
            currentIndex = -1
        }

        if(_max < 1){
            return;
        }

        let _newIndex = currentIndex + 1;
        if(_newIndex >= _max){
            _newIndex = 0;
        }
        if(isShuffle){
            _newIndex = getRandom(0, _max - 1);
            if(_newIndex === currentIndex){
                _newIndex = currentIndex + 1;
                if(_newIndex >= _max){
                    _newIndex = 0;
                }
            }
        }
        window.LAKIT_Players[albumID].currentIndex = _newIndex;
        window.LAKIT_Players[albumID].isRunning = false;
        $('.lakitplayer[data-album_id="'+albumID+'"] .lakitplayer_btn__playpause').first().trigger('click');
    })

    $(document).on('LaStudioKit:InitPlayers', '.lakitplayer', function (e){
        let albumID = parseInt($(this).data('album_id')),
            configs = $(this).data('config');

        let $preview = $(this).find('.lakitplayer__control__preview_root');
        if($preview.length && configs?.preview){
            $preview.css('--previewURL', 'url("'+configs?.preview+'")')
        }

        if( typeof window.LAKIT_Players === "undefined" ) {
            window.LAKIT_Players = {};
        }
        if( typeof window.LAKIT_Players[albumID] === "undefined"){
            window.LAKIT_Players = {
                ...window.LAKIT_Players,
                ...{
                    [albumID]: {
                        currentIndex: -1,
                        isRunning: false,
                        isAutoPlay: true,
                        isShuffle: false,
                        isMuted: false,
                        opts: configs
                    }
                }
            };
        }
    });
    $(function (){
        $('.lakitplayer').trigger('LaStudioKit:InitPlayers');
    });

}(jQuery));