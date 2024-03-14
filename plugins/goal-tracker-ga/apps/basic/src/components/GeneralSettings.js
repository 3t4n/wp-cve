import { Switch } from '@headlessui/react';
import classNames from 'classnames';
import React, { useEffect, useState } from 'react';
import { GeneralSettingsTutorial } from 'ui';

const { apiFetch } = wp;

const { isEqual } = lodash;

import {
  useComponentDidUpdate,
  useComponentWillUnmount,
} from '../../../pro/src/utils/components';

const GeneralSettings = () => {
  const {
    trackLinks,
    setTrackLinks,
    trackLinksType,
    setTrackLinksType,
    trackEmailLinks,
    setTrackEmailLinks,
    measurementID,
    setMeasurementID,
    gaDebug,
    setGaDebug,
    disablePageView,
    setDisablePageView,
    noSnippet,
    showTutorial,
    setShowTutorial,
    setNoSnippet,
    setSettings,
    getSettings,
    updateSettings,
  } = useGeneralSettings();

  // const [trackLinks, setTrackLinks] = useState(false);
  // const [trackLinksType, setTrackLinksType] = useState(false);
  // const [trackEmailLinks, setTrackEmailLinks] = useState(false);
  // const [measurementID, setMeasurementID] = useState('');
  // const [gaDebug, setGaDebug] = useState(false);
  // const [disablePageView, setDisablePageView] = useState(false);
  // const [noSnippet, setNoSnippet] = useState(false);
  // const [showTutorial, setShowTutorial] = useState(false);

  const [isSaving, setIsSaving] = useState(false),
    [hasNotice, setNotice] = useState(false),
    [hasError, setError] = useState(false),
    [needSave, setNeedSave] = useState(false);

  const SettingNotice = () => (
    <Notice
      onRemove={() => setNotice(false)}
      status={hasError ? 'error' : 'success'}
    >
      <p>
        {hasError && __('An error occurred.', 'wp-goal-tracker-ga')}
        {!hasError && __('Saved Successfully.', 'wp-goal-tracker-ga')}
      </p>
    </Notice>
  );

  useEffect(() => {
    const fetchSettings = async () => {
      await getSettings();
    };
    fetchSettings();
  }, []);

  useEffect(() => {
    setNeedSave(true);
  }, [trackLinks, trackLinksType, trackEmailLinks, measurementID]);

  // const updateSettings = data => {
  //   if (data) {
  //     if (data.measurementID) {
  //       setMeasurementID(data.measurementID);
  //     }

  //     if (data.trackLinks && data.trackLinks.enabled) {
  //       setTrackLinks(data.trackLinks.enabled);
  //       setTrackLinksType(data.trackLinks.type);
  //     }

  //     if (data.trackEmailLinks) {
  //       setTrackEmailLinks(data.trackEmailLinks);
  //     }

  //     if (data.gaDebug) {
  //       setGaDebug(data.gaDebug);
  //     }

  //     if (data.disablePageView) {
  //       setDisablePageView(data.disablePageView);
  //     }

  //     if (data.noSnippet) {
  //       setNoSnippet(data.noSnippet);
  //     }

  //     if (!data.hideGeneralSettingsTutorial) {
  //       setShowTutorial(!data.hideGeneralSettingsTutorial);
  //     }
  //   }
  // };

  // async function getSettings() {
  //   let data = await apiFetch({
  //     path:
  //       wpGoalTrackerGa.rest.namespace +
  //       wpGoalTrackerGa.rest.version +
  //       '/get_general_settings',
  //   });
  //   if (data) {
  //     updateSettings(data);
  //     setNeedSave(true);
  //   } else {
  //     updateSettings({});
  //   }
  // }

  // async function setSettings(event) {
  //   event.preventDefault();
  //   setIsSaving(true);
  //   setNeedSave(false);
  //   const generalSettings = {
  //     measurementID: measurementID,
  //     gaDebug: gaDebug,
  //     disablePageView: disablePageView,
  //     noSnippet: noSnippet,
  //     trackLinks: {
  //       enabled: trackLinks,
  //       type: trackLinksType ? trackLinksType : 'all',
  //     },
  //     trackEmailLinks: trackEmailLinks,
  //   };

  //   let data = await apiFetch({
  //     path:
  //       wpGoalTrackerGa.rest.namespace +
  //       wpGoalTrackerGa.rest.version +
  //       '/set_general_settings',
  //     method: 'POST',
  //     data: { generalSettings: generalSettings },
  //   });

  //   if (isEqual(generalSettings, data)) {
  //     setError(false);
  //     setIsSaving(false);
  //     setNeedSave(false);
  //   } else {
  //     setIsSaving(false);
  //     setError(true);
  //     setNeedSave(true);
  //     updateSettings(data);
  //   }
  //   setNotice(true);
  // }

  useComponentDidUpdate(() => {
    /*Nothing for now*/
  });

  useComponentWillUnmount(() => {
    /*Nothing for now*/
  });

  // if (!Object.keys(domain).length) {
  //   return (
  //     <Spinner />
  //   )
  // }

  return (
    <div className="p-10 bg-white/75">
      <GeneralSettingsTutorial
        showTutorial={showTutorial}
        showCloseButton={true}
      />
      <form
        onSubmit={setSettings}
        className={classNames(
          // 'max-w-5xl',
          'w-full',
          'py-10 px-6',
          'space-y-8 divide-y divide-gray-200 rounded-md',
          'border border-gray-200',
          // 'mt-10 ml-5 mb-10',
          'bg-white shadow',
        )}
      >
        <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5">
          <div>
            <div>
              <h3 className="text-2xl leading-6 font-medium text-gray-900">
                Connect With Google Analytics 4
              </h3>
              <p className="mt-2 max-w-2xl text-sm text-gray-500">
                Let's connect with Google Analytics GA4. Our plugin will install
                the Google Analytics GA4 code on your website using these
                settings.
              </p>
            </div>

            <div className="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
              <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                <label
                  htmlFor="measurementID"
                  className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                >
                  MEASUREMENT ID
                </label>
                <div className="mt-1 sm:mt-0 sm:col-span-2">
                  <div className="max-w-lg flex rounded-md shadow-sm">
                    <input
                      type="text"
                      name="measurementID"
                      placeholder="G-XXXXXXXXXX"
                      id="measurementID"
                      value={measurementID}
                      onChange={e => setMeasurementID(e.target.value)}
                      className="flex-1 block w-full focus:ring-brand-priamry focus:border-brand-primary-focus min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200">
            <div className="pt-6 sm:pt-5">
              <div role="group" aria-labelledby="label-track-Links">
                <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                  <div>
                    <div
                      className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                      id="label-debug-view"
                    >
                      Enable GA4 Debug View
                    </div>
                  </div>
                  <div className="mt-4 sm:mt-0 sm:col-span-2">
                    <Switch
                      id="gaDebug"
                      name="gaDebug"
                      checked={gaDebug}
                      onChange={setGaDebug}
                      className={classNames(
                        gaDebug ? 'bg-brand-primary' : 'bg-gray-200',
                        'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                      )}
                    >
                      <span className="sr-only">Use setting</span>
                      <span
                        aria-hidden="true"
                        className={classNames(
                          gaDebug ? 'translate-x-5' : 'translate-x-0',
                          'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                        )}
                      />
                    </Switch>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="divide-y divide-gray-200 pt-8 space-y-6 sm:pt-10 sm:space-y-5">
            <div>
              <h3 className="text-2xl pt-10 leading-6 font-medium text-gray-900">
                Google Tag Manager / Third party Google Analytics plugins
              </h3>
              <p className="mt-2 max-w-2xl text-sm text-gray-500">
                If you are using Google Tag Manager or another Google Analytics
                plugin, you may want to check one of the following.
              </p>
            </div>
            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-debug-view"
                      >
                        Disable page_view Tracking
                        <p className="text-gray-500">
                          Useful when tracking page views through Google Tag
                          Manager
                        </p>
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2">
                      <Switch
                        id="disablePageView"
                        name="disablePageView"
                        checked={disablePageView}
                        onChange={setDisablePageView}
                        className={classNames(
                          disablePageView ? 'bg-brand-primary' : 'bg-gray-200',
                          'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        )}
                      >
                        <span className="sr-only">Use setting</span>
                        <span
                          aria-hidden="true"
                          className={classNames(
                            disablePageView ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                          )}
                        />
                      </Switch>
                    </div>
                  </div>
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-debug-view"
                      >
                        Don't add the "gtag" code snippet
                        <p className="text-gray-500">
                          Useful when using third-party Google Analytics plugins
                        </p>
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2">
                      <Switch
                        id="noSnippet"
                        name="noSnippet"
                        checked={noSnippet}
                        onChange={setNoSnippet}
                        className={classNames(
                          noSnippet ? 'bg-brand-primary' : 'bg-gray-200',
                          'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        )}
                      >
                        <span className="sr-only">Use setting</span>
                        <span
                          aria-hidden="true"
                          className={classNames(
                            noSnippet ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                          )}
                        />
                      </Switch>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="divide-y divide-gray-200 pt-8 space-y-6 sm:pt-10 sm:space-y-5">
            <div>
              <h3 className="text-2xl pt-10 leading-6 font-medium text-gray-900">
                Custom Events Tracking
              </h3>
              <p className="mt-2 max-w-2xl text-sm text-gray-500">
                Select the Custom Events that you want to track with Google
                Analytics GA4:
              </p>
            </div>
            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-track-links"
                      >
                        Track Links
                        <p className="text-gray-500">
                          Track Links on your website
                        </p>
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2">
                      <Switch
                        id="trackLinks"
                        name="trackLinks"
                        checked={trackLinks}
                        onChange={setTrackLinks}
                        className={classNames(
                          trackLinks ? 'bg-brand-primary' : 'bg-gray-200',
                          'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        )}
                      >
                        <span className="sr-only">Use setting</span>
                        <span
                          aria-hidden="true"
                          className={classNames(
                            trackLinks ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                          )}
                        />
                      </Switch>
                      <div
                        className={classNames(
                          trackLinks ? 'block' : 'hidden',
                          'duration-700,ease-in, overflow-hidden,transition-all',
                        )}
                      >
                        <select
                          id="trackLinksType"
                          name="trackLinksType"
                          value={trackLinksType}
                          onChange={e => setTrackLinksType(e.target.value)}
                          className="max-w-lg block mt-2 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                        >
                          <option value="all">Track All Links</option>
                          <option value="external">Track External Links</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="space-y-6 sm:space-y-5 divide-y divide-gray-200">
              <div className="pt-6 sm:pt-5">
                <div role="group" aria-labelledby="label-track-Links">
                  <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                    <div>
                      <div
                        className="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                        id="label-track-email-links"
                      >
                        Track Email Links
                        <p className="text-gray-500">
                          Tracking for mailto: links
                        </p>
                      </div>
                    </div>
                    <div className="mt-4 sm:mt-0 sm:col-span-2">
                      <Switch
                        id="trackEmailLinks"
                        name="trackEmailLinks"
                        checked={trackEmailLinks}
                        onChange={setTrackEmailLinks}
                        className={classNames(
                          trackEmailLinks ? 'bg-brand-primary' : 'bg-gray-200',
                          'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                        )}
                      >
                        <span className="sr-only">Use setting</span>
                        <span
                          aria-hidden="true"
                          className={classNames(
                            trackEmailLinks ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                          )}
                        />
                      </Switch>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="pt-5 pr-5 pb-5">
          <div className="flex justify-end">
            <button
              type="button"
              className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={!needSave}
              className="disabled:opacity-30 ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Save
            </button>
          </div>
        </div>
      </form>
    </div>
  );
};

export default GeneralSettings;
