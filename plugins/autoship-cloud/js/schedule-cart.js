function autoshipScheduleCartOpenDialog(defaultFrequencyType, defaultFrequency, cartItems, nextOccurrence) {
    autoshipOpenSelectFrequencyDialog(function (frequencyType, frequency) {
        var url = AUTOSHIP_AJAX_URL + '?action=autoship_change_cart_frequency'
            + '&frequency_type=' + encodeURIComponent(frequencyType)
            + '&frequency=' + encodeURIComponent(frequency)
            + '&' + cartItems.map(function (item) {
                return 'cart_item_keys[]=' + encodeURIComponent(item);
            }).join('&')
            + '&next_occurrence=' + encodeURIComponent(nextOccurrence);
        window.location = url;
    }, defaultFrequencyType, defaultFrequency);
}

function autoshipScheduleCartOpenSelectNextOccurrenceDialog(nextOccurrence, frequencyType, frequency, cartItems) {
    autoshipOpenSelectNextOccurrenceDialog(function (nextOccurrence) {
        var url = AUTOSHIP_AJAX_URL + '?action=autoship_change_cart_frequency'
            + '&frequency_type=' + encodeURIComponent(frequencyType)
            + '&frequency=' + encodeURIComponent(frequency)
            + '&' + cartItems.map(function (item) {
                return 'cart_item_keys[]=' + encodeURIComponent(item);
            }).join('&')
            + '&next_occurrence=' + encodeURIComponent(nextOccurrence);
        window.location = url;
    }, nextOccurrence);
}