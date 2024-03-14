dayjs.extend(window.dayjs_plugin_customParseFormat)
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
    var newDate = dayjs( jQuery("#hq-times-pick-up-date").val(), dateFormatMoment ).add(minimumDayRentals, 'day')
    returnDate.setDate(newDate.toDate())
  })
  jQuery("#hq-times-pick-up-time").on("change",function(){
    jQuery("#hq-times-return-time").val(jQuery("#hq-times-pick-up-time").val())
  })
  jQuery('#hq-quote-form').on('submit',function(e){
    e.preventDefault()
    grecaptcha.ready(function() {
      grecaptcha.execute(captchaKey, {action: 'submit'}).then(function(token) {
        if(token){
          jQuery('.hq-places-submit-button').html('SENDING')
          jQuery('.hq-places-submit-button').attr('disabled', 'disabled')
          var form = getDataFromForm(token)
          axios({
            url : hqSite + 'wp-json/hqrentals/quote-form',
            method: 'post',
            data: form
          }).then( function (response) {
            if(response.data.data.success){
              resolveSuccess()
            }else{
              resolveFailure(response.data.data.message)
            }
          }).catch( function (error) {
            console.log(error)
            resolveFailure()
          })
        }else{
          alert('There was an issue with your request, please try again.')
        }
      })
    })

  })
  jQuery('#pick_up_location').on('change', function(){
    jQuery('#return_location').val(jQuery('#pick_up_location').val())
  })
})
function resolveSuccess(){
  jQuery('#hq-quote-button-wrapper').empty()
  jQuery('#hq-quote-button-wrapper').append(
    "<p class='alert success'>QUOTE SENT</p><p class='quote-message'>Do you need another quote? Please refresh this page.</p>"
  )
}
function resolveFailure(){
  jQuery('.hq-places-submit-button').attr('disabled', 'disabled')
  jQuery('#hq-quote-button-wrapper').empty()
  jQuery('#hq-quote-button-wrapper').append(
    "<p class='alert danger'>THERE WAS AN ISSUE PROCESSING YOUR REQUEST. PLEASE TRY AGAIN LATER.</p>"
  )
}
function getDataFromForm(token){
  return {
    customer_name: jQuery('#hq-name').val(),
    customer_email: jQuery('#hq-email').val(),
    pick_up_date: jQuery('#hq-times-pick-up-date').val(),
    return_date: jQuery('#hq-times-return-date').val(),
    vehicle_class_id: jQuery('#vehicle_class_id').val(),
    brand_id : jQuery('#brand_id').val(),
    return_location : jQuery('#return_location').val(),
    pick_up_location : jQuery('#pick_up_location').val(),
    captcha_token : token
  }
}
function setDefaults(dateFormat,minimumDayRentals){
  var newDate = dayjs().add(15, 'minutes').add(2,'hours').format(dateFormat)
  var tomorrowDate = dayjs().add(15,'minutes').add(minimumDayRentals, 'days').add(15, 'minutes').add(2,'hours').format(dateFormat)
  if(hqRentalsTenantDatetimeFormat && hqCarRentalSettingDefaultReturnTime){
    newDate = newDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultPickupTime.setting
    tomorrowDate = tomorrowDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultReturnTime.setting
  }
  var overrideWithNowPickup = hqCarRentalSettingSetDefaultPickupTimeToCurrentTime.setting === '1'
  var overrideWithNowReturn = hqCarRentalSettingSetDefaultReturnTimeToCurrentTime.setting === '1'
  if(overrideWithNowPickup){
    newDate = dayjs().add(1, 'days').format(dateFormat)
  }
  if(overrideWithNowReturn){
    tomorrowDate = dayjs().add(1, 'days').add(minimumDayRentals, 'days').format(dateFormat)
  }
  jQuery("#hq-times-pick-up-date").val(newDate)
  jQuery("#hq-times-return-date").val(tomorrowDate)
}
