import MeasurementIdScreenshot from '../../assets/images/help-measurement-id.png';

const MeasurementIdHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Measurement Id
      </div>
      <span className="block pt-5">
        The GA4 Measurement ID is a special code that helps Goal Tracker for
        Google Analytics plugin connect your WordPress website to Google
        Analytics 4. This code allows you to collect important information about
        how people interact with your website. The Measurement ID is a short
        code that begins with "G-" and is followed by a mix of numbers and
        letters.
      </span>
      <img className="block pt-5" src={MeasurementIdScreenshot} />
      <span className="block pt-5 pb-5">
        To find your GA4 Measurement ID, follow these steps:
        <ol className="list-decimal pt-5 list-inside ml-4">
          <li className="mb-2">Sign in to your Google Analytics account</li>
          <li className="mb-2">
            Locate the "Admin" button at the bottom left corner and click on it
          </li>
          <li className="mb-2">Choose the appropriate account and property</li>
          <li className="mb-2">
            Click on "Data Streams" in the "Property" column
          </li>
          <li className="mb-2">Select the data stream labeled "Web"</li>
          <li className="mb-2">
            Find your Measurement ID at the top of the opened page
          </li>
          <li className="mb-2">
            Copy the code and paste it into the Goal Tracker for Google
            Analytics plugin settings on your WordPress site
          </li>
        </ol>
      </span>
    </div>
  );
};

export default MeasurementIdHelp;
