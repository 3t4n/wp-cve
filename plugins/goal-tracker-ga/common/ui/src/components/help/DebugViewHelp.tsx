import DebugViewScreenshot from '../../assets/images/DebugViewHelp.png';

const DebugViewHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        DebugView
      </div>
      <span className="block pt-5">
        The Debug View Integration feature is a helpful tool that lets you
        enable the GA4 Debug View for your website. This feature allows you to
        see real-time data from your website's events in Google Analytics,
        making it easier to test and troubleshoot your tracking setup. With
        Debug View, you can confirm if your events are being sent correctly and
        ensure that your data is accurate before you start analyzing it.
        <p>We do not recommend toggling this off once you are done testing.</p>
      </span>
      <img className="pt-5" src={DebugViewScreenshot} />
      <span className="block pt-5 pb-5">
        To enable Debug View:
        <p></p>
        <ol className="list-decimal pt-5 list-inside ml-4">
          <li className="mb-2">Toggle the DebugView option</li>
          <li className="mb-2">
            Visit your website and interact with the elements you're tracking
          </li>
          <li className="mb-2">Go to your Google Analytics account </li>
          <li className="mb-2">
            Locate the "Admin" button at the bottom left corner and click on it
          </li>
          <li className="mb-2">
            Click on "DebugView" in the "Property" column
          </li>
          <li className="mb-2">Watch as events start showing on the screen</li>
        </ol>
      </span>
    </div>
  );
};

export default DebugViewHelp;
