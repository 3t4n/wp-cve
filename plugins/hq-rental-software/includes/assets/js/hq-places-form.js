dayjs.extend(window.dayjs_plugin_customParseFormat)

function initPlacesForm() { // eslint-disable-line no-unused-vars
  let addressField
  let addressFieldReturn
  const lat = (googleMapCenter) ? googleMapCenter.split(',')[0] : null
  const lng = (googleMapCenter) ? googleMapCenter.split(',')[1] : null
  const country = (googleCountry) ? googleCountry : "us"
  const center = (lat && lng) ? { lat, lng} : null
  const defaultBounds = (googleMapAddressRadius) ? {
    north: parseFloat(lat) + parseFloat(googleMapAddressRadius),
    south: parseFloat(lat) - parseFloat(googleMapAddressRadius),
    east: parseFloat(lng) + parseFloat(googleMapAddressRadius),
    west: parseFloat(lng) - parseFloat(googleMapAddressRadius)
  } : null
  addressField = document.querySelector("#pick-up-location-custom")
  addressFieldReturn = document.querySelector("#return-location-custom")
  let autoConfig = {
    bounds: defaultBounds,
    origin: center,
    strictBounds: true,
    componentRestrictions: { country: [country] },
    fields: ["address_components"],
    types: ["address"]
  }
  new google.maps.places.Autocomplete(addressField, autoConfig)
  autocompleteReturn = new google.maps.places.Autocomplete(addressFieldReturn, autoConfig)
  if(addressField){
    addressField.focus()
  }
  if(addressFieldReturn){
    addressFieldReturn.focus()
  }
}
jQuery(document).ready(function(){
  const format = flatpickrDateTimeFormat
  const minDays = minimumDayRentals
  const formatAsArray = format.split(' ')
  const dateFormat = formatAsArray.shift()
  const timeFormat = formatAsArray.join(' ')
  const dateConfig  = {
    enableTime: true,
    dateFormat: dateFormat + ' ' + timeFormat,
    minDate: dayjs().toDate(),
    step: 15,
    validateOnBlur: false,
    disableMobile: true,
    locale: getLocale(),
    minuteIncrement: timeStep
  }
  setDefaults(hqMomentDateFormat, minDays)
  jQuery("#hq-times-pick-up-date").flatpickr(dateConfig)
  var returnDate = jQuery("#hq-times-return-date").flatpickr(dateConfig)
  jQuery("#hq-times-pick-up-date").on("change",function(){
    const dateFormatMoment = hqMomentDateFormat
    var newDate = dayjs( getField("#hq-times-pick-up-date"), dateFormatMoment ).add(minimumDayRentals, 'day')
    returnDate.setDate(newDate.toDate())
  })
  jQuery("#hq-times-pick-up-time").on("change",function(){
    jQuery("#hq-times-return-time").val(getField("#hq-times-pick-up-time"))
  })
  jQuery("#hq-pick-up-location").on("change",function() {
    checkCustomPickupLocation()
    if(String(hqCarRentalSettingReturnLocationSameAsPickup.setting) === '1'){
      jQuery('#hq-return-location').val(jQuery(this).val())
      checkCustomReturnLocation()
    }
  })
  jQuery("#hq-return-location").on("change",function(){
    checkCustomReturnLocation()
  })
  jQuery('#hq-form').on('submit', function(event){
    if(!canSubmitRentalPeriod()){
      event.preventDefault()
      alert("Minimal Rental Period is " + minimumDayRentals + "days. Please update the return date.")
    }
  })

  checkCustomPickupLocation()
  checkCustomReturnLocation()
})
function canSubmitRentalPeriod(){
  var pickupDate = dayjs(getField('#hq-times-pick-up-date'), hqMomentDateFormat)
  var returnDate = dayjs(getField('#hq-times-return-date'), hqMomentDateFormat)
  return parseInt(minimumDayRentals) !== 0 && returnDate.diff(pickupDate, 'day') >= parseInt(minimumDayRentals)
}
function checkCustomPickupLocation(){
  if (getField('#hq-pick-up-location') === "custom") {
    jQuery('.hq-pickup-custom-location').slideDown()
    jQuery('#pick-up-location-custom').attr('required', 'required')
  }else{
    jQuery('.hq-pickup-custom-location').slideUp()
    jQuery('#pick-up-location-custom').removeAttr('required')
  }
}
function checkCustomReturnLocation(){
  if (getField("#hq-return-location") === "custom") {
    jQuery('.hq-return-custom-location').slideDown()
    jQuery('#return-location-custom').attr('required', 'required')
  }else{
    jQuery('.hq-return-custom-location').slideUp()
    jQuery('#return-location-custom').removeAttr('required')
  }
}
function setDefaults(dateFormat,minimumDayRentals){
  var newDate = dayjs().add(15, 'minutes').format(dateFormat)
  var tomorrowDate = ''
  if(hqRentalsTenantDatetimeFormat && hqCarRentalSettingDefaultReturnTime){
    newDate = newDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultPickupTime.setting
    tomorrowDate = tomorrowDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultReturnTime.setting
  }
  var overrideWithNowPickup = hqCarRentalSettingSetDefaultPickupTimeToCurrentTime.setting === '1'
  var overrideWithNowReturn = hqCarRentalSettingSetDefaultReturnTimeToCurrentTime.setting === '1'
  if(overrideWithNowPickup){
    newDate = dayjs().add(5,'minutes').format(dateFormat)
  }
  if(overrideWithNowReturn){
    tomorrowDate = dayjs().add(5,'minutes').format(dateFormat)
  }
  //allows override setting using shortcode paramenter
  if(parseInt(minimumDayRentals) !== 0){
    tomorrowDate = dayjs(newDate, dateFormat).add(minimumDayRentals, 'day').format(dateFormat)
  }
  jQuery("#hq-times-pick-up-date").val(newDate)
  jQuery("#hq-times-return-date").val(tomorrowDate)
}
function getField(selector){
  return jQuery(selector).val()
}
