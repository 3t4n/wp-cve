const VisibilityTrackingHelpSection = () => {
  return (
    <>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Need Help with Visibility Tracking?
      </div>
      <span className="block pt-5 pb-5">
        Our Visibility Tracking feature enables you to effectively track when
        specific elements, such as ads, banners, or forms, come into view for
        your site visitors. Consult this concise guide and the accompanying
        video tutorial for a comprehensive, step-by-step breakdown on
        configuring and utilizing Visibility Tracking to improve your Google
        Analytics data.
      </span>

      <div>
        <iframe
          width="576"
          height="360"
          src="https://www.youtube.com/embed/9nhR6W5w060"
          title="YouTube video player"
          frameBorder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowFullScreen
        ></iframe>
      </div>
    </>
  );
};

export default VisibilityTrackingHelpSection;
