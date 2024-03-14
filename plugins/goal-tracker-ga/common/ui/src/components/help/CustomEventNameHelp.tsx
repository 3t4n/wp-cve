const CustomEventNameHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Custom Event Name
      </div>
      <span className="block pt-5">
        In the "Custom Event Name" field, enter a descriptive label that
        reflects the action users take on your website, such as "download_ebook"
        or "play_video".
        <p className="pt-2">
          This label becomes the event's name in Google Analytics GA4, allowing
          you to track and analyze these specific user interactions.
        </p>
        <p className="pt-2">
          Choose clear and consistent names to simplify your event tracking and
          reporting.
        </p>
      </span>
    </div>
  );
};

export default CustomEventNameHelp;
