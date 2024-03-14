class MediaElem {
    /**
	 * Wrapper Element ID.
	 */
	id = null;

    /**
	 * Wrapper Element.
	 */
	wrapper = null;

    /**
     * Audio Element Object.
     */
    media = null;

    /**
     * Controls Element.
     */
    controls = null;

    /**
     * Static property to keep track of the currently playing instance.
     */
    static currentlyPlayingInstance = null;

    /**
     * Create custom media element for audio files.
     *
     * @since 6.7.0
     */
    constructor(id) {
        this.id = id;
        this.media = document.querySelector(`#${id}`);
        if (!this.media) return false;
        if ('AUDIO' !== this.media.tagName) {
            if ( 'MEDIAELEMENTWRAPPER' === this.media.tagName ) {
                this.removeMeJs();
                this.media = document.querySelector(`#${id}`);
                if (!this.media) return false;
            } else {
                return false;
            }
        }
        this.wrapper = this.media.parentElement;
        this.createAudioMarkup();
        this.events();
        return this;
    }

    events() {
        const _this = this;
        const seekSlider = this.controls.querySelector('.ppjs__seek-slider');
        const currentTimeContainer = _this.controls.querySelector('.ppjs__currenttime');
        let percentBuffered = 0;

        if (this.media.readyState > 0) {
            _this.displayDuration();
            _this.setSliderMax();
            _this.sliderEvents();
        } else {
            this.media.addEventListener('loadedmetadata', () => {
                _this.displayDuration();
                _this.setSliderMax();
                _this.sliderEvents();
            });
        }

        seekSlider.addEventListener('input', () => {
            if (this.media.readyState <= 0) {
                seekSlider.value = 0;
                return;
            }
            currentTimeContainer.textContent = _this.calculateTime(seekSlider.value);
        });

        seekSlider.addEventListener('change', () => {
            if (this.media.readyState <= 0) {
                seekSlider.value = 0;
                return;
            }
            this.media.currentTime = seekSlider.value;
        });

        this.media.addEventListener('play', () => {
            if (MediaElem.currentlyPlayingInstance && MediaElem.currentlyPlayingInstance !== _this) {
                MediaElem.currentlyPlayingInstance.media.pause();
            }
            MediaElem.currentlyPlayingInstance = _this;
        });

        this.media.addEventListener('timeupdate', () => {
            seekSlider.value = Math.floor(this.media.currentTime);
            currentTimeContainer.textContent = _this.calculateTime(this.media.currentTime);
        });

        this.media.addEventListener('timeupdate', () => {
            if (100 == percentBuffered) return;
            const buffered = this.media.buffered;
            const duration = this.media.duration;
            if (duration > 0) {
                percentBuffered = (buffered.length > 0) ? (buffered.end(buffered.length - 1) / duration) * 100 : 0;
                seekSlider.style.setProperty('--buffered-width', `${percentBuffered}%`);
            }
        });
    }

    /**
     * Seek Slider Events.
     */
    sliderEvents() {
        const _this = this;
        const seekSlider = _this.controls.querySelector('.ppjs__seek-slider');
    }

    /**
     * Create audio markup.
     */
    createAudioMarkup() {
        const markup = `
        <div class="ppjs__offscreen">Audio Player</div>
        <div id="${this.id}-html5" class="ppjs__container pp-podcast-episode ppjs__audio">
            <div class="ppjs__inner">
                <div class="ppjs__mediaelement"></div>
                <div class="ppjs__controls">
                    <div class="ppjs__time ppjs__currenttime-container" role="timer" aria-live="off"><span class="ppjs__currenttime">00:00</span></div>
                    <div class="ppjs__time ppjs__duration-container"><span class="ppjs__duration">00:00</span></div>
                    <div class="ppjs__audio-time-rail"><input type="range" class="ppjs__seek-slider" max="100" value="0"></div>
                </div>
            </div>
        </div>
        `;
        this.wrapper.insertAdjacentHTML('beforeend', markup);
        const mediaContainer = this.wrapper.querySelector(`.ppjs__mediaelement`);
        mediaContainer.appendChild(this.media);
        this.controls = this.wrapper.querySelector('.ppjs__controls')
    }

    /**
     * Set the source URL for the audio element.
     * @param {string} url - The URL of the audio file.
     */
    setSrc(url) {
        this.media.src = url;
    }

    /**
     * Get media src.
     */
    getSrc() {
        return this.media.src;
    }

    /**
     * Load the audio.
     */
    load() {
        this.media.load();
    }

    calculateTime(secs) {
        if (!secs || isNaN(secs)) return '00:00';
        const hours = Math.floor(secs / 3600);
        const minutes = Math.floor((secs % 3600) / 60);
        const seconds = Math.floor(secs % 60);
      
        const formattedHours = hours > 0 ? `${hours}:` : '';
        const formattedMinutes = minutes < 10 ? `0${minutes}` : `${minutes}`;
        const formattedSeconds = seconds < 10 ? `0${seconds}` : `${seconds}`;
      
        return `${formattedHours}${formattedMinutes}:${formattedSeconds}`;
    };

    displayDuration() {
        const durationContainer = this.controls.querySelector('.ppjs__duration');
        if (durationContainer) {
            durationContainer.textContent = this.calculateTime(this.media.duration);
        }
    };
    
    setSliderMax() {
        const secs = this.media.duration;
        if (isNaN(secs)) return;
        const seekSlider = this.controls.querySelector('.ppjs__seek-slider');
        seekSlider.max = Math.floor(secs);
    }

    removeMeJs() {
        const allMeJs = window?.mejs?.players ?? null;
        if (! allMeJs) return;
        const mejs = this.media.closest('.mejs__container');
        if (! mejs) return;
        const mejsid = mejs.getAttribute('id');
        if (! mejsid) return;
        const meJsInst = allMeJs?.[mejsid] ?? null;
        if (! meJsInst) return;
        if (meJsInst.remove) {
            meJsInst.remove();
        }
    }
}
export default MediaElem;
