import React, { useState, useEffect } from 'react';
import EventsTable from 'ui/src/components/EventsTable.tsx';
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

const ClickTracking = () => {
  const [allEvents, setAllClickEvents] = useState([
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

  const fetchAllClickEvents = async () => {
    let events = await getAllClickEvents();
    if (events.length >= 2) {
      setShowPromo(true);
    } else {
      setShowPromo(false);
    }
    setAllClickEvents(events);
  };

  useEffect(() => {
    fetchAllClickEvents();
  }, []);

  async function getAllClickEvents() {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/get_events?type=click',
    });
    return data;
  }

  async function deleteClickEvent(eventId) {
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/delete_event?id=' +
        eventId,
      method: 'DELETE',
    });
    fetchAllClickEvents();
    return data;
  }

  useComponentDidUpdate(() => {
    /*Nothing for now*/
  });

  useComponentWillUnmount(() => {
    /*Nothing for now*/
  });

  return (
    <EventsTable
      key={allEvents.selector}
      type="click"
      customEvents={allEvents}
      deleteEvent={deleteClickEvent}
      updateFunction={fetchAllClickEvents}
    />
  );
};

export default ClickTracking;
