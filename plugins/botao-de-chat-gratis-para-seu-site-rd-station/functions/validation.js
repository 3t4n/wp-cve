const phoneInput = document.getElementById("rd_button_chat_phone");
const emailInput = document.getElementById("rd_button_chat_email");
const emailSetupInput = document.getElementById("rd_button_chat_setup_email");
const messageInput = document.getElementById("rd_button_chat_message");
const showButtonInput = document.getElementById("rd_button_chat_show_button");
const submitBtn = document.querySelector(".rdwpp_container > form .button");
const submitSetupBtn = document.querySelector(".rdwpp_setup-content #submit");


// Phone Mask
if (phoneInput) {
  phoneInput.onkeyup = (e) => {
    const input = e.target;

    input.value = input.value
      .replace(/\D/g, "")
      .replace(/^(\d{2})(\d)/g, "($1) $2");

    const numberArray = input.value.split(" ");

    const ddd = numberArray[0].replace(`${"("}`, "").replace(`${")"}`, "");

    const phoneNumber = numberArray[1];
    const phoneNumberArray = phoneNumber && phoneNumber.split("");

    if (phoneNumberArray) {
      const numberInputValue = `(${ddd}) ${phoneNumberArray
        .slice(0, 9)
        .join("")}`;

      input.value = numberInputValue.replace(/(\d)(\d{4})$/, "$1-$2");
    }
  };
}

// Show Button
if (showButtonInput) {
  function verifyButton() {
    if (showButtonInput.checked === false) {
      phoneInput.disabled = true;
      emailInput.disabled = true;
      messageInput.disabled = true;      
    } else {
      phoneInput.disabled = false;
      emailInput.disabled = false;
      messageInput.disabled = false;
    }
  }

  verifyButton();
  showButtonInput.addEventListener("change", verifyButton);
}

// Validation
const validateEmail = (email) => {
  return email.match(
    /^(([^<>()[\]\\.'~^,;:\s@{}#[\]\$"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
};

function createFeedback(feedback, field, text) {
  feedback.innerText = text;
  feedback.classList.add("rdwpp_feedback");
  feedback.style.display = "block";
  field.classList.add("rdwpp_input-error");
  field.after(feedback);
}

function destroyFeedback(feedback, field) {
  feedback.style.display = "none";
  field.classList.remove("rdwpp_input-error");
}

// Setup Validation
let setupValidation = {
  email: false,
};

if (emailSetupInput) {
  const emailSetupFeedback = document.createElement("p");

  emailSetupInput.addEventListener("keyup", ({ target }) => {
    if (!validateEmail(target.value)) {
      setupValidate.email = false;
      createFeedback(emailSetupFeedback, emailSetupInput, "Email inválido");
    } else {
      setupValidate.email = true;
      destroyFeedback(emailSetupFeedback, emailSetupInput);
    }
    setupValidate();
  });

  function setupValidate() {
    if (setupValidate.email === true) {
      submitSetupBtn.disabled = false;
    } else {
      submitSetupBtn.disabled = true;
    }
  }

  setupValidate();
}

// Form Validation
if (phoneInput && emailInput && messageInput) {
  const phoneFeedback = document.createElement("p");
  const emailFeedback = document.createElement("p");
  const messageFeedback = document.createElement("p");

  let formValidation = {
    phone: false,
    email: false,
    message: false,
  };

  function verifyHasValues() {
    if (phoneInput.value.length > 0) {
      formValidation.phone = true;
    }
    if (emailInput.value.length > 0) {
      formValidation.email = true;
    }
    if (messageInput.value.length > 0) {
      formValidation.message = true;
    }
  }

  verifyHasValues();

  function createFeedback(feedback, field, text) {
    feedback.innerText = text;
    feedback.classList.add("rdwpp_feedback");
    feedback.style.display = "block";
    field.classList.add("rdwpp_input-error");
    field.after(feedback);
  }

  function destroyFeedback(feedback, field) {
    feedback.style.display = "none";
    field.classList.remove("rdwpp_input-error");
  }

  phoneInput.addEventListener("keyup", ({ target }) => {
    if (target.value.length <= 13) {
      formValidation.phone = false;
      createFeedback(phoneFeedback, phoneInput, "Número de telefone inválido");
    } else {
      formValidation.phone = true;
      destroyFeedback(phoneFeedback, phoneInput);
    }
    validate();
  });

  emailInput.addEventListener("keyup", ({ target }) => {
    if (!validateEmail(target.value)) {
      formValidation.email = false;
      createFeedback(emailFeedback, emailInput, "Email inválido");
    } else {
      formValidation.email = true;
      destroyFeedback(emailFeedback, emailInput);
    }
    validate();
  });

  messageInput.addEventListener("keyup", ({ target }) => {
    if (target.value.length === 0) {
      formValidation.message = false;
      createFeedback(
        messageFeedback,
        messageInput,
        "O campo mensagem precisa ser preenchido"
      );
    } else {
      formValidation.message = true;
      destroyFeedback(messageFeedback, messageInput);
    }
    validate();
  });

  function validate() {
    if (Object.values(formValidation).every((v) => v === true)) {
      submitBtn.disabled = false;
    } else {
      submitBtn.disabled = true;
    }
  }

  validate();
}
