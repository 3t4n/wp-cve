import { useEffect, useState } from 'react';
import EventsTableHeader from 'ui/src/components/EventsTableHeader';
import { HeaderTitle } from 'ui/src/components/HeaderTitle';

const { apiFetch } = wp;

const { isEqual } = lodash;

function classNames(...classes) {
  return classes.filter(Boolean).join(' ');
}

const VideoTracking = () => {
  return (
    <div
      data-component="EventsTable"
      className={classNames('pb-6', 'bg-white/50', 'shadow-xl')}
    >
      <EventsTableHeader />
      <div className="mt-8 flex flex-col px-4">
        <div
          className={classNames(
            // 'max-w-5xl',
            'w-full',
            'pt-10 px-6',
            'space-y-8 divide-y divide-gray-200 rounded-md',
            'border border-gray-200',
            'bg-white shadow',
          )}
        >
          <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <HeaderTitle
              title={`Video Tracking`}
              titleHelper={`Track YouTube, Vimeo, and Self-Hosted Videos`}
              // helpComponent={}
              proLabel={true}
              ctaURL="https://www.wpgoaltracker.com/7iha"
            />
            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200 opacity-60">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-youtube-video-tracking"
                      >
                        Track YouTube Videos
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2"></div>
                  </div>
                </div>
              </div>
            </div>

            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200 opacity-60">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-vimeo-video-tracking"
                      >
                        Track Vimeo Videos
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2"></div>
                  </div>
                </div>
              </div>
            </div>

            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200 opacity-60">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-media-video-tracking"
                      >
                        Track Self-Hosted Media Videos
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5 pt-10">
            <HeaderTitle
              title={`Audio Tracking`}
              titleHelper={`Track Hosted Audio Files (Media Library)`}
              // helpComponent={}
              proLabel={true}
              ctaURL="https://www.wpgoaltracker.com/1m3n"
            />

            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200 opacity-60">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-media-audio-tracking"
                      >
                        Track Self Hosted Audio
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <footer className="px-5 py-5 bg-gray-100 shadow-2xl -mx-5">
            <div className="flex justify-end space-x-3"></div>
          </footer>
        </div>
      </div>
    </div>
  );
};

export default VideoTracking;
