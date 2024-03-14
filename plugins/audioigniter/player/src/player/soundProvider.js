import React from 'react';
import PropTypes from 'prop-types';
import Sound from 'react-sound';

import SoundCloud from '../utils/soundcloud';
import multiSoundDisabled from '../utils/multi-sound-disabled';
import { getInitialTrackQueueAndIndex } from '../utils/getInitialTrackIndex';
import playerStorage from '../utils/playerStorage';
import aiEvents, { EVENT, getEventMeta } from './services/events';
import throttle from '../utils/throttle';

const PLAYBACK_RATES = [0.5, 0.75, 1, 1.25, 1.5, 2, 3];

const soundProvider = (Player, events) => {
  class EnhancedPlayer extends React.Component {
    constructor(props) {
      super(props);

      const {
        playerId,
        volume,
        cycleTracks,
        defaultShuffle,
        shuffleEnabled,
      } = this.props;

      const initialData = playerStorage.get(playerId);

      this.state = {
        tracks: [],
        activeIndex: 0, // Determine active track by index

        // trackQueue: List of track indexes that represents the order of the playlist
        // i.e. [0, 1, 2, 3, 4] will play the 1st, 2nd, 3rd, etc track.
        // [5, 4, 3, 2, 1] will play the tracks reversed.
        // [4, 2, 0, ...] will play the 5th track first, 3rd second, then the 1st, etc.
        trackQueue: [],
        playStatus: Sound.status.STOPPED,
        position: initialData?.position ?? 0,
        duration: 0,
        playbackRate: 1,
        volume: volume == null ? 100 : volume,
        cycleTracks,
        repeatingTrackIndex: null,
        isMultiSoundDisabled: multiSoundDisabled(),
        buffering: false,
        shuffle: shuffleEnabled && defaultShuffle,
      };

      this.playTrack = this.playTrack.bind(this);
      this.pauseTrack = this.pauseTrack.bind(this);
      this.togglePlay = this.togglePlay.bind(this);
      this.nextTrack = this.nextTrack.bind(this);
      this.prevTrack = this.prevTrack.bind(this);
      this.setPosition = this.setPosition.bind(this);
      this.setVolume = this.setVolume.bind(this);
      this.skipPosition = this.skipPosition.bind(this);
      this.setPlaybackRate = this.setPlaybackRate.bind(this);
      this.toggleTracklistCycling = this.toggleTracklistCycling.bind(this);
      this.toggleShuffle = this.toggleShuffle.bind(this);
      this.setTrackCycling = this.setTrackCycling.bind(this);
      this.reverseTracks = this.reverseTracks.bind(this);
      this.getFinalProps = this.getFinalProps.bind(this);
      this.onPlaying = this.onPlaying.bind(this);
      this.onFinishedPlaying = this.onFinishedPlaying.bind(this);
      this.aiEventTrackThrottled = throttle(options => {
        aiEvents.eventTrack(options);
      }, 60 * 1000);
    }

    componentDidMount() {
      const {
        playerId,
        tracksUrl,
        soundcloudClientId,
        reverseTrackOrder,
        initialTrack,
        rememberLastPosition,
        track,
      } = this.props;
      const { shuffle } = this.state;
      const initialData = playerStorage.get(playerId);

      // We have a standalone track (from the shortcode).
      if (track) {
        try {
          this.setState({
            tracks: [JSON.parse(track)],
          });

          return;
          // eslint-disable-next-line no-empty
        } catch {}
      }

      const tracksPromised = fetch(tracksUrl).then(res => res.json());

      if (!soundcloudClientId) {
        tracksPromised.then(tracks => {
          const { trackQueue, activeIndex } = getInitialTrackQueueAndIndex({
            tracks,
            initialTrack:
              initialData?.activeIndex && rememberLastPosition
                ? initialData.activeIndex + 1
                : initialTrack,
            reverseTrackOrder,
            shuffle,
          });

          this.setState(
            {
              tracks,
              activeIndex,
              trackQueue,
            },
            () => {
              if (reverseTrackOrder) {
                this.reverseTracks();
              }
            },
          );
        });

        return;
      }

      const sc = new SoundCloud(soundcloudClientId);
      const scTracks = tracksPromised
        .then(tracks => sc.fetchSoundCloudStreams(tracks))
        .catch(err => console.error(err)); // eslint-disable-line no-console

      // Make sure if SoundCloud fetching fails
      // we delegate and load our tracks anyway
      const promiseArray = [tracksPromised, scTracks].map(p =>
        p.catch(error => ({
          status: 'error',
          error,
        })),
      );

      Promise.all(promiseArray).then(res => {
        if (res[1].status === 'error') {
          return this.setState({ tracks: res[0] });
        }

        const tracks = sc.mapStreamsToTracks(...res);
        const { trackQueue, activeIndex } = getInitialTrackQueueAndIndex({
          tracks,
          initialTrack,
          reverseTrackOrder,
          shuffle,
        });

        return this.setState(
          () => ({
            tracks,
            activeIndex,
            trackQueue,
          }),
          () => {
            if (reverseTrackOrder) {
              this.reverseTracks();
            }
          },
        );
      });
    }

    // Events
    onPlaying({ duration, position }) {
      const { activeIndex } = this.state;
      const { playerId, rememberLastPosition } = this.props;

      if (position > 60000) {
        // Only start calling this after 1 minute into the track
        this.aiEventTrackThrottled({
          event: EVENT.PLAYING,
          ...getEventMeta(this.state, this.props),
        });
      }

      this.setState(
        () => ({ duration, position }),
        () => {
          if (events && events.onPlaying) {
            events.onPlaying(this.getFinalProps());
          }

          if (
            playerId &&
            rememberLastPosition &&
            // Store last position on every 5th second or at the beginning of the track (tiny position num).
            (position % 5000 < 300 || position < 350)
          ) {
            playerStorage.set(playerId, {
              position,
              activeIndex,
            });
          }
        },
      );
    }

    onFinishedPlaying() {
      const { stopOnTrackFinish, delayBetweenTracks = 0 } = this.props;
      const delayBetweenTracksMs = delayBetweenTracks * 1000;
      this.setState(
        () => ({ playStatus: Sound.status.STOPPED }),
        () => {
          aiEvents.eventTrack({
            event: EVENT.STOP,
            ...getEventMeta(this.state, this.props),
          });
        },
      );

      if (stopOnTrackFinish) {
        return;
      }

      if (events && events.onFinishedPlaying) {
        setTimeout(() => {
          events.onFinishedPlaying(this.getFinalProps());
        }, delayBetweenTracksMs);
      }
    }

    getFinalProps() {
      const { tracks, activeIndex } = this.state;
      const currentTrack = tracks[activeIndex] || {};

      return {
        playTrack: this.playTrack,
        pauseTrack: this.pauseTrack,
        togglePlay: this.togglePlay,
        nextTrack: this.nextTrack,
        prevTrack: this.prevTrack,
        setPosition: this.setPosition,
        skipPosition: this.skipPosition,
        setPlaybackRate: this.setPlaybackRate,
        setVolume: this.setVolume,
        toggleTracklistCycling: this.toggleTracklistCycling,
        setTrackCycling: this.setTrackCycling,
        toggleShuffle: this.toggleShuffle,
        currentTrack,
        ...this.props,
        ...this.state,
      };
    }

    setVolume(volume) {
      this.setState(() => ({ volume }));
    }

    setPosition(position) {
      const currentPosition = this.state.position;

      this.setState(
        () => ({ position }),
        () => {
          aiEvents.eventTrack({
            event: EVENT.SEEK,
            ...getEventMeta(this.state, this.props),
            oldPosition: currentPosition,
          });
        },
      );
    }

    setTrackCycling(index, event) {
      if (event) {
        event.preventDefault();
      }

      const { activeIndex, cycleTracks } = this.state;

      if (cycleTracks && index != null) {
        this.toggleTracklistCycling();
      }

      this.setState(
        ({ repeatingTrackIndex }) => ({
          repeatingTrackIndex: repeatingTrackIndex === index ? null : index,
        }),
        () => {
          if (index != null && activeIndex !== index) {
            this.playTrack(index);
          }
        },
      );
    }

    setPlaybackRate() {
      this.setState(({ playbackRate }) => {
        const currentIndex = PLAYBACK_RATES.findIndex(
          rate => rate === playbackRate,
        );
        const nextIndex =
          (PLAYBACK_RATES.length + (currentIndex + 1)) % PLAYBACK_RATES.length;

        return {
          playbackRate: PLAYBACK_RATES[nextIndex],
        };
      });
    }

    toggleShuffle() {
      const { initialTrack, reverseTrackOrder } = this.props;
      const { tracks } = this.state;

      this.setState(
        prev => ({
          shuffle: !prev.shuffle,
        }),
        () => {
          this.setState(() => {
            const { trackQueue } = getInitialTrackQueueAndIndex({
              tracks,
              initialTrack,
              reverseTrackOrder,
              shuffle: this.state.shuffle,
            });

            return {
              trackQueue,
            };
          });

          if (this.state.shuffle) {
            // Shuffle track queue
          } else {
            // Unshuffle track queue
          }
        },
      );
    }

    skipPosition(direction = 1) {
      const { position } = this.state;
      const { skipAmount } = this.props;
      const amount = parseInt(skipAmount, 10) * 1000;

      this.setPosition(position + amount * direction);
    }

    playTrack(index, event) {
      if (event) {
        event.preventDefault();
      }

      const {
        repeatingTrackIndex,
        isMultiSoundDisabled,
        playStatus,
      } = this.state;

      if (isMultiSoundDisabled) {
        window.soundManager.pauseAll();
      }

      if (playStatus === Sound.status.PLAYING) {
        aiEvents.eventTrack({
          event: EVENT.STOP,
          ...getEventMeta(this.state, this.props),
        });
      }

      this.setState(
        () => ({
          activeIndex: index,
          position: 0,
          playStatus: Sound.status.PLAYING,
        }),
        () => {
          aiEvents.eventTrack({
            event: EVENT.PLAY,
            ...getEventMeta(
              {
                ...this.state,
                duration: null,
              },
              this.props,
            ),
          });
        },
      );

      // Reset repeating track index if the track is not the active one.
      if (index !== repeatingTrackIndex && repeatingTrackIndex != null) {
        this.setTrackCycling(null);
      }
    }

    pauseTrack(event) {
      if (event) {
        event.preventDefault();
      }

      const { playStatus } = this.state;

      if (playStatus === Sound.status.PLAYING) {
        this.setState(
          () => ({ playStatus: Sound.status.PAUSED }),
          () => {
            aiEvents.eventTrack({
              event: EVENT.PAUSE,
              ...getEventMeta(this.state, this.props),
            });
          },
        );
      }
    }

    togglePlay(index, event) {
      if (event) {
        event.preventDefault();
      }

      const { activeIndex } = this.state;

      if (typeof index === 'number' && index !== activeIndex) {
        this.playTrack(index);
        return;
      }

      this.setState(
        ({ playStatus, isMultiSoundDisabled }) => {
          if (playStatus !== Sound.status.PLAYING && isMultiSoundDisabled) {
            window.soundManager.pauseAll();
          }

          return {
            playStatus:
              playStatus === Sound.status.PLAYING
                ? Sound.status.PAUSED
                : Sound.status.PLAYING,
          };
        },
        () => {
          aiEvents.eventTrack({
            event:
              this.state.playStatus === Sound.status.PLAYING
                ? EVENT.PLAY
                : EVENT.PAUSE,
            ...getEventMeta(this.state, this.props),
          });
        },
      );
    }

    nextTrack() {
      const { trackQueue, activeIndex } = this.state;
      const currentQueueIndex = trackQueue.indexOf(activeIndex);
      const nextQueueIndex = (currentQueueIndex + 1) % trackQueue.length;
      const nextTrackIndex = trackQueue[nextQueueIndex];

      this.playTrack(nextTrackIndex);
    }

    prevTrack() {
      const { trackQueue, activeIndex } = this.state;
      const currentQueueIndex = trackQueue.indexOf(activeIndex);
      const prevQueueIndex =
        (currentQueueIndex + trackQueue.length - 1) % trackQueue.length;
      const prevTrackIndex = trackQueue[prevQueueIndex];

      this.playTrack(prevTrackIndex);
    }

    toggleTracklistCycling() {
      const { repeatingTrackIndex } = this.state;

      if (repeatingTrackIndex !== null) {
        this.setTrackCycling(null);
      }

      this.setState(state => ({
        cycleTracks: !state.cycleTracks,
      }));
    }

    reverseTracks() {
      this.setState(state => ({
        tracks: state.tracks.slice().reverse(),
      }));
    }

    render() {
      const { tracks, playStatus, position, volume, playbackRate } = this.state;
      const finalProps = this.getFinalProps();

      return (
        <div className="ai-audioigniter">
          <Player {...finalProps} />

          {tracks.length > 0 && (
            <Sound
              url={finalProps.currentTrack.audio}
              playStatus={playStatus}
              position={position}
              volume={volume}
              onPlaying={this.onPlaying}
              onFinishedPlaying={this.onFinishedPlaying}
              onPause={() => this.pauseTrack()}
              playbackRate={playbackRate}
              onBufferChange={buffering => {
                this.setState({ buffering });
              }}
            />
          )}
        </div>
      );
    }
  }

  EnhancedPlayer.propTypes = {
    playerId: PropTypes.string,
    volume: PropTypes.number,
    cycleTracks: PropTypes.bool,
    tracksUrl: PropTypes.string,
    track: PropTypes.string,
    soundcloudClientId: PropTypes.string,
    reverseTrackOrder: PropTypes.bool,
    skipAmount: PropTypes.number,
    stopOnTrackFinish: PropTypes.bool,
    delayBetweenTracks: PropTypes.number,
    initialTrack: PropTypes.number,
    shuffleEnabled: PropTypes.bool,
    defaultShuffle: PropTypes.bool,
    rememberLastPosition: PropTypes.bool,
  };

  return EnhancedPlayer;
};

export default soundProvider;
