declare var wpGoalTrackerGa: any;
declare var wp: any;

import React, { useEffect, useState, useRef } from 'react';
import { Fieldset, FieldsetGroup, Section, HeaderTitle } from 'ui';
import { DownloadIcon, UploadIcon } from '@heroicons/react/outline';

const { apiFetch } = wp;

export default function ConfigBackup({
  getSettings,
  setShowImportNotification,
}: any) {
  const [isOpen, setIsOpen] = useState(false);
  const fileInputRef = useRef(null);

  const getConfig = async () => {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/get_entire_config',
    });
    return data;
  };

  const setConfig = async (configuration: any) => {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/set_entire_config',
      method: 'POST',
      data: { config: configuration },
    });
    await getSettings();
    return data;
  };

  const exportConfiguration = async () => {
    // Stringify the configuration data

    const configurationData = await getConfig();
    const configurationJson = JSON.stringify(configurationData, null, 2);

    // Create a blob with the configuration data
    const blob = new Blob([configurationJson], { type: 'application/json' });

    // Create a URL for the blob
    const url = URL.createObjectURL(blob);

    const now = new Date();
    const date = now.toISOString().slice(0, 10); // returns date in the format 'YYYY-MM-DD'

    // Replace ':' and '-' to prevent potential file naming issues
    const formattedDate = date.replace(/-/g, '');

    const config_filename = `goal-tracker-config-${formattedDate}.json`;

    // Create a link and click it to download the file
    const link = document.createElement('a');
    link.href = url;
    link.download = config_filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const importConfiguration = () => {
    // ... load configuration logic ...
    fileInputRef.current.click();
  };

  const handleFileChange = event => {
    // Get the selected file
    const file = event.target.files[0];

    // Check if a file was selected
    if (!file) {
      return;
    }

    // Read the file and load the configuration
    const reader = new FileReader();
    reader.onload = async e => {
      try {
        const configuration = JSON.parse(e.target.result);
        // ... load the configuration ...
        const data = await setConfig(configuration);
        setShowImportNotification('success');
      } catch (error) {
        setShowImportNotification('failure');
      }
    };
    reader.readAsText(file);
  };

  return (
    <>
      <Section>
        <HeaderTitle
          title={`Configuration backup`}
          titleHelper={`Export / import configuration file`}
          children={undefined}
        />
        <FieldsetGroup>
          <Fieldset
            label={`Export Configuration`}
            description={`Back up your plugin's settings`}
            id=""
            isPrimary={false}
          >
            <button
              className="flex-1 flex items-center text-white bg-brand-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-bold py-2 px-4 rounded"
              onClick={exportConfiguration}
            >
              <DownloadIcon
                className="h-5 w-5 text-white-400 mr-2"
                aria-hidden="true"
              />
              <span>Download Settings</span>
            </button>
          </Fieldset>
          <Fieldset
            label={`Import Configuration`}
            description={`Restore configuration from backup file`}
            id=""
            isPrimary={false}
          >
            {' '}
            <button
              className="flex-1 flex items-center text-white bg-brand-primary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-bold py-2 px-4 rounded"
              onClick={importConfiguration}
            >
              <UploadIcon
                className="h-5 w-5 text-white-400 mr-2"
                aria-hidden="true"
              />
              <span>Upload Settings</span>
            </button>
            <input
              type="file"
              ref={fileInputRef}
              style={{ display: 'none' }}
              onChange={handleFileChange}
            />
          </Fieldset>
        </FieldsetGroup>
      </Section>
    </>
  );
}
