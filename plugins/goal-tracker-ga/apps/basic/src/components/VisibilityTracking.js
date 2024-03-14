import React, { useState, useEffect } from 'react';
import { Switch } from '@headlessui/react';
import EventsTable from 'ui/src/components/EventsTable';
import { usePromoContext } from 'ui/src/context/PromoContext';

const { apiFetch } = wp;

const { isEqual } = lodash;

function classNames(...classes) {
  return classes.filter(Boolean).join(' ');
}

import {
  useComponentDidMount,
  useComponentDidUpdate,
  useComponentWillUnmount,
} from '../utils/components';

const VisibilityTracking = () => {
  const [allCustomEvents, setAllVisibilityCustomEvents] = useState([
    {
      eventName: '',
      selector: '',
    },
  ]);

  const { showPromo, setShowPromo } = usePromoContext();

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

  const fetchAllVisibilityCustomEvents = async () => {
    let events = await getAllVisibilityCustomEvents();
    if (events.length >= 2) {
      setShowPromo(true);
    } else {
      setShowPromo(false);
    }
    setAllVisibilityCustomEvents(events);
  };

  useEffect(() => {
    fetchAllVisibilityCustomEvents();
  }, []);

  async function getAllVisibilityCustomEvents() {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/get_events?type=visibility',
    });
    return data;
  }

  async function deleteVisibilityCustomEvent(eventId) {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/delete_event?id=' +
        eventId,
      method: 'DELETE',
    });
    return data;
  }

  useComponentDidUpdate(() => {
    /*Nothing for now*/
  });

  useComponentWillUnmount(() => {
    /*Nothing for now*/
  });

  return (
    <>
      <EventsTable
        key={allCustomEvents.selector}
        type="visibility"
        customEvents={allCustomEvents}
        deleteEvent={deleteVisibilityCustomEvent}
        updateFunction={fetchAllVisibilityCustomEvents}
      />
    </>
  );
};

export default VisibilityTracking;
