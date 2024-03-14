/**
 * Initialize the formbricks survey.
 *
 * @see https://github.com/formbricks/setup-examples/tree/main/html
 */
window.addEventListener('themeisle:survey:loaded', function () {
    window?.tsdk_formbricks?.init?.({
        environmentId: "clskhdqhz8qevpodw3om6y3fw",
        apiHost: "https://app.formbricks.com",
        ...(window?.mpgSurveyData ?? {})
    });
});
