declare var wp: any;
declare var wpGoalTrackerGa: any;

import {
  createContext,
  useContext,
  useState,
  useEffect,
  ReactNode,
} from 'react';
import { TabContext } from 'ui/src/context/TabContext';

const { apiFetch } = wp;

interface GeneralSettingsContextValue {
  trackLinks: boolean;
  setTrackLinks: (value: boolean) => void;
  trackLinksType: string;
  setTrackLinksType: (value: string) => void;
  trackEmailLinks: boolean;
  setTrackEmailLinks: (value: boolean) => void;
  disableTrackingForAdmins: boolean;
  setDisableTrackingForAdmins: (value: boolean) => void;
  measurementID: string;
  setMeasurementID: (value: string) => void;
  gaDebug: boolean;
  setGaDebug: (value: boolean) => void;
  disablePageView: boolean;
  setDisablePageView: (value: boolean) => void;
  noSnippet: boolean;
  setNoSnippet: (value: boolean) => void;
  showTutorial: boolean;
  setShowTutorial: (value: boolean) => void;
  setSettings: (event: React.FormEvent) => Promise<void>;
  getSettings: () => Promise<void>;
  updateSettings: (data: any) => void;
}

const GeneralSettingsContext = createContext<
  GeneralSettingsContextValue | undefined
>(undefined);

export const useGeneralSettings = (): GeneralSettingsContextValue => {
  const context = useContext(GeneralSettingsContext);

  if (!context) {
    throw new Error(
      'useGeneralSettings must be used within a GeneralSettingsProvider',
    );
  }

  return context;
};

interface GeneralSettingsProviderProps {
  children: ReactNode;
}

interface Data {
  measurementID?: string;
  trackLinks?: {
    enabled?: boolean;
    type?: string;
  };
  trackEmailLinks?: boolean;
  disableTrackingForAdmins?: boolean;
  gaDebug?: boolean;
  disablePageView?: boolean;
  noSnippet?: boolean;
  hideGeneralSettingsTutorial?: boolean;
}

export const GeneralSettingsProvider = ({
  children,
}: GeneralSettingsProviderProps): JSX.Element => {
  const [trackLinks, setTrackLinks] = useState(false);
  const [trackLinksType, setTrackLinksType] = useState('');
  const [trackEmailLinks, setTrackEmailLinks] = useState(false);
  const [disableTrackingForAdmins, setDisableTrackingForAdmins] =
    useState(false);
  const [measurementID, setMeasurementID] = useState('');
  const [gaDebug, setGaDebug] = useState(false);
  const [disablePageView, setDisablePageView] = useState(false);
  const [noSnippet, setNoSnippet] = useState(false);
  const [showTutorial, setShowTutorial] = useState(false);

  const tabs = useContext(TabContext);
  if (!tabs) {
    throw new Error('ChildComponent must be used within a TabContextProvider');
  }

  const { updateHasIssueByName } = tabs;

  useEffect(() => {
    const fetchSettings = async () => {
      await getSettings();
    };
    fetchSettings();
  }, []);

  const updateSettings = (data: Data) => {
    if (data) {
      setMeasurementID(data.measurementID);

      setTrackLinks(data.trackLinks.enabled);
      if (data.trackLinks && data.trackLinks.enabled) {
        if (data.trackLinks.type) setTrackLinksType(data.trackLinks.type);
      }

      setTrackEmailLinks(data.trackEmailLinks);
      setDisableTrackingForAdmins(data.disableTrackingForAdmins);
      setGaDebug(data.gaDebug);
      setDisablePageView(data.disablePageView);
      setNoSnippet(data.noSnippet);

      if (!data.hideGeneralSettingsTutorial) {
        setShowTutorial(!data.hideGeneralSettingsTutorial);
      }
    }

    // Do we need to show a badge
    if (data.measurementID === '' && data.noSnippet === false) {
      updateHasIssueByName('Settings', true);
    }
  };

  const getSettings = async () => {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/get_general_settings',
    });
    if (data) {
      updateSettings(data);
      // setNeedSave(true);
    } else {
      updateSettings({});
    }
    return data;
  };

  const setSettings = async (event: any) => {
    event.preventDefault();
    // setIsSaving(true);
    // setNeedSave(false);
    const generalSettings = {
      measurementID: measurementID,
      gaDebug: gaDebug,
      disablePageView: disablePageView,
      noSnippet: noSnippet,
      trackLinks: {
        enabled: trackLinks,
        type: trackLinksType ? trackLinksType : 'all',
      },
      trackEmailLinks: trackEmailLinks,
      disableTrackingForAdmins: disableTrackingForAdmins,
    };

    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/set_general_settings',
      method: 'POST',
      data: { generalSettings: generalSettings },
    });

    // if (isEqual(generalSettings, data)) {
    //   setError(false);
    //   setIsSaving(false);
    //   setNeedSave(false);
    // } else {
    //   setIsSaving(false);
    //   setError(true);
    //   setNeedSave(true);
    //   updateSettings(data);
    // }
    // setNotice(true);
  };

  return (
    <GeneralSettingsContext.Provider
      value={{
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
        setNoSnippet,
        showTutorial,
        setShowTutorial,
        setSettings,
        getSettings,
        updateSettings,
      }}
    >
      {children}
    </GeneralSettingsContext.Provider>
  );
};
