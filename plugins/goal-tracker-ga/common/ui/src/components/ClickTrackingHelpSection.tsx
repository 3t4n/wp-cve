export const ClickTrackingHelpSection = () => {
  return (
    <>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Need Help with Click Tracking?
      </div>
      <span className="block pt-5 pb-5">
        Our Click Tracking feature allows you to easily monitor clicks on
        buttons, links, and other elements on your WordPress site. Check out
        this brief guide and the accompanying video tutorial for a clear,
        step-by-step explanation on setting up and using Click Tracking to
        enhance your Google Analytics insights.
      </span>

      <div>
        <iframe
          width="576"
          height="360"
          src="https://www.youtube.com/embed/lpG4VNVpemo"
          title="YouTube video player"
          frameBorder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowFullScreen
        ></iframe>
      </div>
    </>
  );
};

export default ClickTrackingHelpSection;
