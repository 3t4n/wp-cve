import GoogleTagManagerScreenshot from '../../assets/images/GoogleTagManagerHelp.png';
const GTMSupportHelpSection = () => {
  return (
    <div>
      <div>
        <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
          Goal Tracker and Google Tag Manager
        </div>
        <span className="block pt-5">
          <p>
            If you are already using Google Tag Manager to track page views on
            the website, we recommend toggling the "Disable page_view Tracking "
            option in Goal Tracker.
          </p>
          <p>
            The reason is that we want to make sure that you are not sending two
            page view events every time the page loads.
          </p>
        </span>
        <span className="block pt-5 pb-5">
          How can you tell if Google Tag Manager is configured to track page
          views?
          <ol className="list-decimal pt-5 list-inside ml-4">
            <li className="mb-2">
              Open Google Tag Manager and go to the tags page
            </li>
            <li className="mb-2">
              Search for a tag of type "Google Analytics: GA4 Configuration" and
              click it
            </li>
            <li className="mb-2">Click on the tag configuration </li>
            <li className="mb-2">
              Check if the "Send a page view event when this configuration
              loads" box has been clicked. If it did then it means that you are
              using Google Tag Manager to track page views.
            </li>
          </ol>
        </span>
        <img className="pt-5" src={GoogleTagManagerScreenshot} />
      </div>
    </div>
  );
};

export default GTMSupportHelpSection;
