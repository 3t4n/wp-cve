import { useContext } from "react";
import EventsTableContext from '../context/EventsTableContext'

function classNames(...classes) {
  return classes.filter(Boolean).join(' ')
}


const EventFormFooter = () => {
  const { toggleAddCustomEventForm } = useContext(EventsTableContext);

  return (
    <div className="pt-5 pr-5 pb-5 bg-gray-50" data-component="form-footer">
      <div className="flex justify-end">
        <button
          type="button"
          onClick={toggleAddCustomEventForm}
          className="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-java-500"
        >
          Cancel2
        </button>
        <button
          type="submit"
          className="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus"
        >
          Save
        </button>
      </div>
    </div>
  )
}


export default EventFormFooter;