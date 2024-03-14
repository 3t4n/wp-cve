function autoshipProductScheduleOptionsOpenDialog(defaultFrequencyType, defaultFrequency) {
    autoshipOpenSelectFrequencyDialog(function (frequencyType, frequency, displayName) {
        var frequencyTypeInput = document.querySelector('.autoship-product-schedule-options-frequency-type');
        var frequencyInput = document.querySelector('.autoship-product-schedule-options-frequency');
        var isScheduled = document.querySelector('.autoship-product-schedule-options-is-scheduled');
        var notScheduled = document.querySelector('.autoship-product-schedule-options-not-scheduled');

        if (null != frequencyTypeInput) {
            frequencyTypeInput.value = frequencyType;
        }
        if (null != frequencyInput) {
            frequencyInput.value = frequency;
        }
        if (null != frequencyType && null != frequency) {
            if (null != isScheduled) {
                isScheduled.style.display = 'block';
                isScheduled.querySelector('.display-name').innerText = 'Schedule: ' + displayName;
            }
            if (null != notScheduled) {
                notScheduled.style.display = 'none';
            }
        } else {
            if (null != isScheduled) {
                isScheduled.style.display = 'none';
                isScheduled.querySelector('.display-name').innerText = '';
            }
            if (null != notScheduled) {
                notScheduled.style.display = 'block';
            }
        }
    }, defaultFrequencyType, defaultFrequency);
}

function autoshipClearProductScheduleOptions() {
    var frequencyTypeInput = document.querySelector('.autoship-product-schedule-options-frequency-type');
    var frequencyInput = document.querySelector('.autoship-product-schedule-options-frequency');
    var isScheduled = document.querySelector('.autoship-product-schedule-options-is-scheduled');
    var notScheduled = document.querySelector('.autoship-product-schedule-options-not-scheduled');

    if (null != frequencyTypeInput) {
        frequencyTypeInput.value = '';
    }
    if (null != frequencyInput) {
        frequencyInput.value = '';
    }
    if (null != isScheduled) {
        isScheduled.style.display = 'none';
        isScheduled.querySelector('.display-name').innerText = '';
    }
    if (null != notScheduled) {
        notScheduled.style.display = 'block';
    }
}
