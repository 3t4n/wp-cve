function validate_digits_frontend_captcha_booster(event)
{
   if (event.which != 8 && event.which != 0 && event.which != 13 && (event.which < 48 || event.which > 57))
   {
      event.preventDefault();
   }
}
