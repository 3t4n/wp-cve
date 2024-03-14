declare var wp: any;
declare var lodash: any;
declare var wpGoalTrackerGa: any;

import { Switch } from '@headlessui/react';
import classNames from 'classnames';
import React, { useEffect, useState } from 'react';
import {
  GeneralSettingsTutorial,
  Fieldset,
  FieldsetGroup,
  Section,
  HeaderTitle,
} from 'ui';
import { useGeneralSettings } from 'ui/src/context/GeneralSettingsContext';
import { usePromoContext } from 'ui/src/context/PromoContext';
import ConnectWithGAHelpSection from 'ui/src/components/ConnectWithGAHelpSection';
import GTMSupportHelpSection from 'ui/src/components/help/GTMSupportHelpSection';
import ThirdPartyPluginsHelpSection from 'ui/src/components/help/ThirdPartyPluginsHelpSection';
import CustomEventsTrackingHelpSection from 'ui/src/components/help/CustomEventTrackingHelpSection';
import ConfigBackup from 'ui/src/components/ConfigBackup';
import ImportNotification from 'ui/src/components/ImportNotification';
import InlineCTA from 'ui/src/components/buttons/InlineCTA';

const { apiFetch } = wp;

const { isEqual } = lodash;

import {
  useComponentDidUpdate,
  useComponentWillUnmount,
} from '../../../pro/src/utils/components';

type LinkTrackingSettings = {
  enabled: boolean;
  type: string;
};

const Settings = () => {
  const {
    trackLinks,
    setTrackLinks,
    trackLinksType,
    setTrackLinksType,
    trackEmailLinks,
    setTrackEmailLinks,
    disableTrackingForAdmins,
    setDisableTrackingForAdmins,
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

  const [isSaving, setIsSaving] = useState(false),
    [hasNotice, setNotice] = useState(false),
    [hasError, setError] = useState(false),
    [needSave, setNeedSave] = useState(false),
    [showImportNotification, setShowImportNotification] = useState('');

  const { showPromo, setShowPromo } = usePromoContext();

  // const SettingNotice = () => (
  //   <Notice
  //     onRemove={() => setNotice(false)}
  //     status={hasError ? 'error' : 'success'}
  //   >
  //     <p>
  //       {hasError && __('An error occurred.', 'wp-goal-tracker-ga')}
  //       {!hasError && __('Saved Successfully.', 'wp-goal-tracker-ga')}
  //     </p>
  //   </Notice>
  // );

  useEffect(() => {
    const fetchSettings = async () => {
      await getSettings();
      if (measurementID === '' && noSnippet) {
        setShowPromo(false);
      } else {
        setShowPromo(true);
      }
    };
    fetchSettings();
  }, []);

  useEffect(() => {
    setNeedSave(true);
  }, [
    trackLinks,
    trackLinksType,
    trackEmailLinks,
    disableTrackingForAdmins,
    measurementID,
  ]);

  useComponentDidUpdate(() => {
    /*Nothing for now*/
  });

  useComponentWillUnmount(() => {
    /*Nothing for now*/
  });

  return (
    <div className="bg-white/75">
      <GeneralSettingsTutorial
        showTutorial={showTutorial}
        showCloseButton={true}
      />
      <form
        onSubmit={setSettings}
        className="bg-white/50 p-5 rounded shadow-xl"
      >
        <div
          data-component="SectionContainer"
          className="space-y-8 sm:space-y-5"
        >
          <Section>
            <HeaderTitle
              title={`Connect With Google Analytics 4`}
              titleHelper={`Let's connect with Google Analytics GA4.
              Our plugin will install the Google Analytics GA4 code on your website using these settings.`}
              helpComponent={ConnectWithGAHelpSection}
              helpTitle={`Connecting with Google Analytics`}
            />
            <FieldsetGroup>
              <Fieldset
                label={`MEASUREMENT ID`}
                id="measurementID"
                description={`A Measurement ID is a Google Analytics unique identifier (e.g., G-12345) for your website`}
                isPrimary={false}
              >
                <div className="max-w-xs flex rounded-md shadow-sm">
                  <input
                    type="text"
                    name="measurementID"
                    placeholder="G-XXXXXXXXXX"
                    id="measurementID"
                    value={measurementID}
                    onChange={e => setMeasurementID(e.target.value)}
                    className={classNames(
                      'flex-1 block w-full',
                      'focus:ring-brand-primary focus:border-brand-primary-focus',
                      'min-w-0 rounded-none',
                      'text-sm md:text-2xl',
                      'border-gray-300',
                    )}
                  />
                </div>
              </Fieldset>
              <Fieldset
                label={'Enable GA4 Debug View'}
                id=""
                description=""
                isPrimary={false}
              >
                <Switch
                  id="gaDebug"
                  name="gaDebug"
                  checked={gaDebug}
                  onChange={setGaDebug}
                  className={classNames(
                    gaDebug ? 'bg-brand-primary' : 'bg-slate-500',
                    'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                  )}
                >
                  <span className="sr-only">GA4 Debug View</span>
                  <span
                    aria-hidden="true"
                    className={classNames(
                      gaDebug ? 'translate-x-5' : 'translate-x-0',
                      'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                    )}
                  />
                </Switch>
              </Fieldset>
            </FieldsetGroup>
          </Section>

          <Section>
            <HeaderTitle
              title={`Google Tag Manager`}
              titleHelper={`If you are using Google Tag Manager, you may want to check one of the following.`}
              helpComponent={GTMSupportHelpSection}
              helpTitle={`Google Tag Manager`}
            />
            <FieldsetGroup>
              <Fieldset
                label={'Disable page_view Tracking'}
                description={
                  'Useful when tracking page views through Google Tag Manager'
                }
                id=""
                isPrimary={false}
              >
                <Switch
                  id="disablePageView"
                  name="disablePageView"
                  checked={disablePageView}
                  onChange={setDisablePageView}
                  className={classNames(
                    disablePageView ? 'bg-brand-primary' : 'bg-slate-500',
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
              </Fieldset>
            </FieldsetGroup>
          </Section>

          <Section>
            <HeaderTitle
              title={`Third party Google Analytics plugins`}
              titleHelper={`If you are using other Google Analytics plugins, you may want to check one of the following.`}
              helpComponent={ThirdPartyPluginsHelpSection}
              helpTitle={`Third party Google Analytics plugins`}
            />
            <FieldsetGroup>
              <Fieldset
                label={`Don't add the "gtag" code snippet`}
                description={`Useful when using third-party GA plugins`}
                id=""
                isPrimary={false}
              >
                <Switch
                  id="noSnippet"
                  name="noSnippet"
                  checked={noSnippet}
                  onChange={setNoSnippet}
                  className={classNames(
                    noSnippet ? 'bg-brand-primary' : 'bg-slate-500',
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
              </Fieldset>
              <Fieldset
                label={`Use a separate data stream`}
                description={`If you need multiple trackers for your website`}
                id=""
                isPrimary={false}
                isPro={true}
              >
                <InlineCTA ctaURL="https://www.wpgoaltracker.com/7ps7" />
              </Fieldset>
            </FieldsetGroup>
          </Section>

          <Section>
            <HeaderTitle
              title={`Custom Events Tracking`}
              titleHelper={`Select the Custom Events that you want to track with Google
                Analytics GA4:`}
              helpComponent={CustomEventsTrackingHelpSection}
              helpTitle={`CustomEventTracking`}
            />

            <FieldsetGroup>
              <Fieldset
                label={`Track Links`}
                description={`Auto track all links`}
                id=""
                isPrimary={false}
              >
                <Switch
                  id="trackLinks"
                  name="trackLinks"
                  checked={trackLinks}
                  onChange={setTrackLinks}
                  className={classNames(
                    trackLinks ? 'bg-brand-primary' : 'bg-slate-500',
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
                    className="max-w-lg block mt-4 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="all">Track All Links</option>
                    <option value="external">Track External Links</option>
                  </select>
                </div>
              </Fieldset>
            </FieldsetGroup>

            <FieldsetGroup>
              <Fieldset
                label={`Track Email Links`}
                description={`Tracking for mailto: links`}
                id=""
                isPrimary={false}
              >
                <Switch
                  id="trackEmailLinks"
                  name="trackEmailLinks"
                  checked={trackEmailLinks}
                  onChange={setTrackEmailLinks}
                  className={classNames(
                    trackEmailLinks ? 'bg-brand-primary' : 'bg-slate-500',
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
              </Fieldset>
            </FieldsetGroup>

            <FieldsetGroup>
              <Fieldset
                label={`Don't track admin users`}
                description={`Disable Google Analytics tracking for logged in administrators`}
                id=""
                isPrimary={false}
              >
                <Switch
                  id="disableTrackingForAdmins"
                  name="disableTrackingForAdmins"
                  checked={disableTrackingForAdmins}
                  onChange={setDisableTrackingForAdmins}
                  className={classNames(
                    disableTrackingForAdmins
                      ? 'bg-brand-primary'
                      : 'bg-slate-500',
                    'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                  )}
                >
                  <span className="sr-only">Use setting</span>
                  <span
                    aria-hidden="true"
                    className={classNames(
                      disableTrackingForAdmins
                        ? 'translate-x-5'
                        : 'translate-x-0',
                      'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                    )}
                  />
                </Switch>
              </Fieldset>
            </FieldsetGroup>
          </Section>

          <Section>
            <HeaderTitle
              title={`User Tracking`}
              titleHelper={`Track Logged-In Users:`}
              helpTitle={`Track Users in Google Analytics`}
            />

            <FieldsetGroup>
              <Fieldset
                label={`Track Users`}
                description={`Tracking for WP logged-in users`}
                id=""
                isPrimary={false}
                isPro={true}
              >
                <InlineCTA ctaURL="https://www.wpgoaltracker.com/2aax" />
              </Fieldset>
            </FieldsetGroup>
          </Section>

          <ConfigBackup
            getSettings={getSettings}
            setShowImportNotification={setShowImportNotification}
          />
          <ImportNotification showNotification={showImportNotification} />
        </div>

        <footer className="px-5 py-5 bg-white/50 shadow-2xl">
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
        </footer>
      </form>
    </div>
  );
};

export default Settings;
