const $ = jQuery;
if ($(".minimum-requirements-content").length > 0) {
  (async function () {
    const memoryLimit = document.getElementById("memory-limit").innerHTML;
    const valueMaxExecutionTime = document.getElementById("max-execution-time")
      .innerHTML;
    const constantMemoryLimit = parseInt(
      document.getElementById("constant-recommend-memory").innerHTML
    );
    const constantMaxExecutionTime = parseInt(
      document.getElementById("constant-max-execution").innerHTML
    );
    const hasTaxes = parseInt(document.getElementById("taxes").dataset.taxes);

    if (hasTaxes === 0) {
      document.getElementById("elementTaxes").style.color = "#e76e54";
    }
    if (memoryLimit < constantMemoryLimit) {
      document.getElementById("elementMemoryLimit").style.color = "#e76e54";
    }
    if (
      valueMaxExecutionTime < constantMaxExecutionTime &&
      parseInt(valueMaxExecutionTime) !== 0
    ) {
      document.getElementById("elementExecutionTime").style.color = "#e76e54";
    }
  })();

  function copyContentFromCronCommand() {
    const inputCronCommand = document.getElementById("cron-command-input");
    inputCronCommand.select();
    document.execCommand("copy");
    closeAllModalGeneric();
  }

  function copyContentFromCronCommandOnlyGoogleShopping() {
    const inputCronCommandInputGoogleShopping = document.getElementById(
      "cron-command-input-google-shopping"
    );
    inputCronCommandInputGoogleShopping.select();
    document.execCommand("copy");
    closeAllModalGeneric();
  }

  function showModalCronGeneric(elementToShow) {
    document.getElementById(elementToShow).style.display = "flex";
  }

  function hideModalCronGeneric(elementToHide) {
    document.getElementById(elementToHide).style.display = "none";
  }

  function closeAllModalGeneric() {
    document.getElementById("modal-cron-ssh-normal").style.display = "none";
    document.getElementById("modal-cron-ssh-google-shipping").style.display =
      "none";
  }

  function hideBlockMinimumRequirements() {
    document.getElementById("data-requirements").style.display = "none";
    document.getElementById("btn-show").style.display = "inline-flex";
    document.getElementById("btn-hide").style.display = "none";
  }

  function showBlockMinimumRequirements() {
    document.getElementById("data-requirements").style.display = "block";
    document.getElementById("btn-hide").style.display = "inline-flex";
    document.getElementById("btn-show").style.display = "none";
  }

  $('input[datajs-form-el="Option"]').on("change", () => {
    modifyStatusSendButton();
  });

  function modifyStatusSendButton() {
    let statusButtonSend = false;
    const formCarriers = $("#form-data").data("form-carriers");
    const formTagBlackFriday = $("#form-data").data("form-tag-black-friday");
    const formEmail = $("#form-data").data("form-email");
    const formProduct = $("#form-data").data("form-product");

    let canActivateButton =
      !testOptionSelected("tagOption", formTagBlackFriday) ||
      !testOptionSelected("sendEmail", formEmail) ||
      !testOptionSelected("bigbuyCarrierOption", formCarriers) ||
      !testOptionSelected("productOption", formProduct);
    if (!canActivateButton) {
      statusButtonSend = true;
    }
    let isError = $("#isErrorMessage").length === 0;
    if (!isError) {
      $("#send-form-button").prop("disabled", true);
    }
    $("#send-form-button").prop("disabled", statusButtonSend);
  }

  function testOptionSelected(name, formTag) {
    let active = 1;
    let options = $('input[name="' + name + '"]');
    let inputYes = options[0];
    let inputNo = options[1];
    if (inputNo.checked && !inputYes.checked) {
      active = 0;
    }
    return active === parseInt(formTag);
  }
}
