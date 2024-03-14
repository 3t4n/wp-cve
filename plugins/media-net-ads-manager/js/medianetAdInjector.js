window._mNHandle = window._mNHandle || {};
window._mNHandle.queue = window._mNHandle.queue || [];
medianet_versionId = 4121199;

window.addEventListener('load', injectMnetScript);

function injectMnetScript() {
  if (
    !window._mNDetails
    || !window._mNDetails.loadTag
    || typeof window._mNDetails.loadTag !== 'function'
  ) {
    const mnetScript = document.createElement('script');
    mnetScript.src = `//contextual.media.net/dmedianet.js?cid=${mnetCustomerData.cid}`;
    mnetScript.async = 'async';
    document.body.appendChild(mnetScript);
  }
}
