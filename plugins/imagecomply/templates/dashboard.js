function onFormSubmit(e) {
  const me = this;
  const form = e.target;
  const formData = new FormData(form);

  const url = imagecomply_data.ajax_url;

  const options = {
    method: "POST",
    body: formData,
  };

  fetch(url, options)
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        me.lastSaved.imagecomply_license_key =
          me.formData.imagecomply_license_key;

        if (!res.data.verification) return;

        me.credits = res.data.verification.credits || 0;
        me.plan = res.data.verification.plan || false;
      } else {

        alert(
          "Error with this action. " + (res.data.error ?? 'Please make sure you have entered a valid license key. ')
        );
      }
    })
    .catch((err) => {
      alert("Error saving settings. Please try again.");
    });
}

function onSettingsFormSubmit(e) {
  const me = this;
  const form = e.target;
  const formData = new FormData(form);

  formData.set(
    "imagecomply_generate_on_upload",
    JSON.stringify(me.settings.imagecomply_generate_on_upload)
  );
  formData.set(
    "imagecomply_medialibrary_show_status",
    JSON.stringify(me.settings.imagecomply_medialibrary_show_status)
  );
  formData.set(
    "imagecomply_medialibrary_show_alt_text",
    JSON.stringify(me.settings.imagecomply_medialibrary_show_alt_text)
  );

  const url = imagecomply_data.ajax_url;

  const options = {
    method: "POST",
    body: formData,
  };

  fetch(url, options)
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        me.lastSavedSettings.imagecomply_generate_on_upload =
          me.settings.imagecomply_generate_on_upload;
        me.lastSavedSettings.imagecomply_medialibrary_show_status =
          me.settings.imagecomply_medialibrary_show_status;
        me.lastSavedSettings.imagecomply_medialibrary_show_alt_text =
          me.settings.imagecomply_medialibrary_show_alt_text;

        me.lastSavedSettings.imagecomply_alt_text_language = me.settings.languageSelect !== 'Other' ? me.settings.languageSelect : me.settings.languageOther;
        me.lastSavedSettings.imagecomply_alt_text_keywords = me.settings.imagecomply_alt_text_keywords;
        me.lastSavedSettings.imagecomply_alt_text_neg_keywords = me.settings.imagecomply_alt_text_neg_keywords;

        console.log(res);

        alert("Settings saved.");
      } else {
        alert("Error saving settings. Please try again.");
      }
    })
    .catch((err) => {
      alert("Error saving settings. Please try again.", err);
    });
}

// function onOptimizeImages() {
// 	const url = '{{ajax_url}}';

// 	if (!confirm('This will optimize all images on your site. This may use a lot of credits. Are you sure you want to continue?')) {
// 		return;
// 	}

// 	const formData = new FormData();

// 	formData.append('action', 'imagecomply_optimize_all_images');

// 	const options = {
// 		method: 'POST',
// 		body: formData,
// 	};

// 	fetch(url, options)
// 		.then(res => res.json())
// 		.then(res => {

// 			console.log(res)

// 			if (res.success) {
// 				alert(`${res.data.attachments_queued} images have been queued for processing.`);
// 			} else {
// 				alert('Error optimizing images: ', res.data.error);
// 			}
// 		})
// 		.catch(err => {
// 			alert('Error optimizing images. Please try again.');
// 		});
// }

function onGenerateAllImages() {
  const me = this;
  const url = imagecomply_data.ajax_url;

  if (
    !me.plan &&
    !confirm(
      'This will generate ALT text for all images on your site. This may use a lot of credits. Are you sure you want to continue? If yes, please do not close this page until the process is complete. There will be a "Complete!" message.'
    )
  ) {
    return;
  }
  const formData = new FormData();

  formData.append("action", "imagecomply_generate_all_alt_text");
  formData.append("per_page", me.settings.numberPerBatch);

  me.inProgress.alt_text = true;

  const options = {
    method: "POST",
    body: formData,
  };

  function fetchWPApi() {
    fetch(url, options)
      .then((res) => res.json())
      .then((res) => {

        if(res.data.error) {
          me.inProgress.alt_text = false;
          me.progress.alt_text = "Error: " + res.data.error;
          return;
        }

        if (res.data.max_num_pages > 0) {
          me.progress.alt_text = res.data.max_num_pages + " pages left";
          fetchWPApi();
        } else {
          me.inProgress.alt_text = false;
          me.progress.alt_text = "Complete!";
        }
      })
      .catch((err) => {
        me.inProgress.alt_text = false;
        me.progress.alt_text = "";
        alert("Error generating ALT text. Please try again.");
      });
  }

  fetchWPApi();
}

async function removeApiKey() {
  this.formData.imagecomply_license_key = "";

  const event = new Event("submit", { bubbles: true });

  // next tick so that the form is updated with the new value
  await this.$nextTick();

  this.$refs.licenseKeyForm.dispatchEvent(event);
}

function getData() {

  const invalidLicenseKey = imagecomply_data?.invalid_license_key === "1";
  const imagecomply_license_key = invalidLicenseKey ? '' : imagecomply_data?.imagecomply_license_key;
  const imagecomply_generate_on_upload = imagecomply_data?.imagecomply_generate_on_upload === "true";
  const imagecomply_medialibrary_show_status = imagecomply_data?.imagecomply_medialibrary_show_status === "true";
  const imagecomply_medialibrary_show_alt_text = imagecomply_data?.imagecomply_medialibrary_show_alt_text === "true";

  const languageOptions = [
    'English',
    'Chinese',
    'Spanish',
    'Arabic',
    'Bengali',
    'French',
    'Russian',
    'Portuguese',
    'Indonesian',
    'Urdu',
    'German',
    'Japanese',
    'Swahili',
    'Telugu',
    'Marathi',
    'Turkish',
    'Tamil',
    'Vietnamese',
    'Italian',
    'Hindi',
  ];

  return {
    formData: {
      imagecomply_license_key,
    },
    lastSaved: {
      imagecomply_license_key,
    },
    settings: {

      languageOptions,
      languageSelect: imagecomply_data?.imagecomply_alt_text_language ? (languageOptions.indexOf(imagecomply_data.imagecomply_alt_text_language) > -1 ? imagecomply_data?.imagecomply_alt_text_language : 'Other') : 'English',
      languageOther: imagecomply_data?.imagecomply_alt_text_language ? (languageOptions.indexOf(imagecomply_data.imagecomply_alt_text_language) > -1 ? '' : imagecomply_data?.imagecomply_alt_text_language) : '',

      imagecomply_alt_text_language: imagecomply_data?.imagecomply_alt_text_language || 'English',
      imagecomply_alt_text_keywords: imagecomply_data?.imagecomply_alt_text_keywords ?? '',
      imagecomply_alt_text_neg_keywords: imagecomply_data?.imagecomply_alt_text_neg_keywords ?? '',
      imagecomply_generate_on_upload,
      imagecomply_medialibrary_show_status,
      imagecomply_medialibrary_show_alt_text,
      numberPerBatch: 50,
    },
    lastSavedSettings: {
      
      languageOptions,
      languageSelect: imagecomply_data?.imagecomply_alt_text_language ? (languageOptions.indexOf(imagecomply_data.imagecomply_alt_text_language) > -1 ? imagecomply_data?.imagecomply_alt_text_language : 'Other') : 'English',
      languageOther: imagecomply_data?.imagecomply_alt_text_language ? (languageOptions.indexOf(imagecomply_data.imagecomply_alt_text_language) > -1 ? '' : imagecomply_data?.imagecomply_alt_text_language) : '',


      imagecomply_alt_text_language: imagecomply_data?.imagecomply_alt_text_language || 'English',
      imagecomply_alt_text_keywords: imagecomply_data?.imagecomply_alt_text_keywords ?? '',
      imagecomply_alt_text_neg_keywords: imagecomply_data?.imagecomply_alt_text_neg_keywords ?? '',
      imagecomply_generate_on_upload,
      imagecomply_medialibrary_show_status,
      imagecomply_medialibrary_show_alt_text,
    },
    progress: {
      alt_text: "",
    },
    inProgress: {
      alt_text: imagecomply_data?.imagecomply_alt_text_in_progress === "true",
      // optimization: {{imagecomply_optimization_in_progress|default(0)}},
    },
    credits: isNaN(parseInt(imagecomply_data?.credits)) ? 0 : parseInt(imagecomply_data?.credits),
    plan: imagecomply_data?.plan,
    invalidLicenseKey,
  };
}
