const sirvUIContainerSelector = "sirv-ui-container";
const sirvUIConfirmDialogRootSelector = "sirv-ui-cdlg-containter";


function sirvUICreateContainer(){
  const body = document.querySelector("body");
  const div = document.createElement("div");

  div.classList.add(sirvUIContainerSelector);

  body.prepend(div);
}

function sirvUICreateConfirmDialog(){
  const confirmDialogTemplate = `
    <div class="sirv-ui-cdlg-containter">
      <div class="sirv-ui-cdlg-header">
        <div class="sirv-ui-cdlg-title">Confirm dialog</div>
        <div class="sirv-ui-cdlg-close-btn sirv-ui-cdlg-close">✕</div>
      </div>
      <div class="sirv-ui-cdlg-message">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora distinctio deleniti nesciunt. Minus, fugit voluptas, magnam maxime voluptatem modi, aspernatur nulla aliquid neque quidem tempore reprehenderit incidunt! Ducimus, eligendi vitae!
      </div>
      <div class="sirv-ui-cdlg-controls">
        <button class="button-secondary sirv-ui-cdlg-close">Cancel</button>
        <button class="button-primary sirv-ui-cdlg-continue-btn">Continue</button>
      </div>
    </div>
  `;

  const sirvUIContainer = document.querySelector(`.${sirvUIContainerSelector}`);
  sirvUIContainer.insertAdjacentHTML("beforeend", confirmDialogTemplate);
}


function sirvUIShowConfirmDialog(title, desc, yesCallback=null, yesCallbackOptions = [], dialogOptions = {}){

  let continueBtn = null;
  const continueBtnHandler = function(){
    yesCallback(...yesCallbackOptions);
    bpopup_ui_cdialog.close();
  }

  window["bpopup_ui_cdialog"] = jQuery(`.${sirvUIConfirmDialogRootSelector}`).bPopup({
    position: ["auto", "auto"],
    zIndex: 9999999,
    closeClass: "sirv-ui-cdlg-close",
    onOpen: function () {
      const titleEl = document.querySelector(".sirv-ui-cdlg-title");
      titleEl.innerText = title;

      const descEl = document.querySelector(".sirv-ui-cdlg-message");
      descEl.innerText = desc;

      if (!!yesCallback) {
        continueBtn = document.querySelector(".sirv-ui-cdlg-continue-btn");
        continueBtn.addEventListener("click", continueBtnHandler);
      }

      jQuery(`.${sirvUIConfirmDialogRootSelector}`).css("display", "flex");
    },
    onClose: function () {
      if (!!continueBtn){
        continueBtn.removeEventListener("click", continueBtnHandler);
      }
      jQuery(`.${sirvUIConfirmDialogRootSelector}`).hide();
    },
  });
}


function initializeSirvUI(){
  sirvUICreateContainer();

  sirvUICreateConfirmDialog();
}


document.addEventListener("DOMContentLoaded", function () {
  initializeSirvUI();
});
