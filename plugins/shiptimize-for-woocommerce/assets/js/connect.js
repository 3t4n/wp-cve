function ShopPlugin() {}

/** 
 * load the idx step in the install presentation 
 */
ShopPlugin.prototype.installloadstep = function (idx) {
  //skip this I already have an account 
  if (idx == 4) {
    jQuery.post(ajaxurl, {
      'action': 'shiptimizeconnectuser',
    }, function (resp) {
      console.log(resp); 
      window.location.href = jQuery("#urlto").val();
    });

    return;
  }

  var steps = document.getElementsByClassName("step");
  for (var x = 0; x < steps.length; ++x) {
    if (x == idx) {
      steps[x].classList.add("selected");
    } else {
      steps[x].classList.remove("selected");
    }
  }
};

/** 
 * @param string request_reference - the name of the plugin 
 */
ShopPlugin.prototype.requestaccount = function (request_reference) {
  $(".quote")
    .submit();

  if ($("form.quote")
    .validate()
    .errorList.length > 0) {
    return;
  }

  $("#formErrors")
    .html("Sending your data...");

  var data = {
    "companyName": $("input[name='companyName']")
      .val()
      .trim(),
    "name": $("input[name='name']")
      .val()
      .trim(),
    "contriesship": this.getIso2FromInput($("input[name='contriesship']"), ','),
    "email": $("input[name='email']")
      .val()
      .trim(),
    "originCountry": this.getIso2FromInput($("input[name='originCountry']"), ',')[0],
    "phone": $("input[name='phone']")
      .val(),
    "shipments": $("input[name='shipments']")
      .val()
      .trim(),
    "urlto": $("input[name='urlto']")
      .val(),
    "marketplace": $("input[name='marketplace']")
      .val(),
    "store": document.location.host
  };

  $.post(requestaccounturl, data, function (resp) {
      console.log("SERVER SENT BACK ", resp);
      if (resp.ErrorList) {
        var errors = '';
        for (var x = 0; x < resp.ErrorList.length; ++x) {
          errors += resp.ErrorList[x].Info + '<br/>';
        }
        $("#formErrors")
          .html(errors);
      } else if (resp.error) { //invalid config in the api 
        $("#formErrors")
          .html(resp.Error);
      } else {
        $("#formErrors")
          .html("");
        shopplugin.installloadstep(3)
      }
    }, 'json')
    .fail(function (resp) {
      if (resp.status == 200) {
        shopplugin.installloadstep(3);
      }
      $("#formErrors")
        .html(resp.responseText);
    });

  console.log(data);
};


ShopPlugin.prototype.getIso2FromInput = function (input, separator) {
  var value = input.val()
    .toLowerCase();
  if (!separator) {
    separator = value.indexOf(",") > -1 ? "," : " ";
  }

  var values = value.split(separator);
  var selectedCountries = [];

  for (var j = 0; j < values.length; ++j) {
    var country = values[j].trim();
    if (!country) {
      continue;
    }

    var found = false;
    for (var x = 0; x < countries.length && !found; ++x) {
      var c = countries[x];

      if (c.iso2 == country || c.iso3 == country || country == c.en || country == c.nl) {
        selectedCountries.push(c.iso2);
        found = true;
      }
    }

    if (!found) {
      console.log("not found " + country);
    }

  }

  return selectedCountries;
}

ShopPlugin.prototype.inputLabel = function (input) {
  var value = input.val();
  var placeholder = input.attr("placeholder");
  var label = input.siblings("label");

  if (value) {
    label.html(placeholder);
  } else {
    label.html("");
  }
};


ShopPlugin.prototype.removeAllAutocomplete = function () {
  $(".autocomplete")
    .remove();
}

ShopPlugin.prototype.addAutocomplete = function (elem) {
  var autoComplete = $("<div class='autocomplete'></div>");
  autoComplete.insertAfter(elem);
  autoComplete.hide();
}

/** 
 * the user is typing sugest stuff for the last country in list
 * \@param bool multiple - does this. field support more than one value?
 */
ShopPlugin.prototype.autoCompleteRefresh = function (elem, multiple) {
  var options = [];
  var value = $(elem)
    .val()
    .trim();

  // if there is a space but no. comma replace the space with a comma 
  values = value.split(",");
  var curr = values[values.length - 1].trim();
  var creg = new RegExp("^" + curr, "i");

  for (var x = 0; x < countries.length; ++x) {
    var c = countries[x];

    if (c.en.match(creg) || c.nl.match(creg)) {
      options.push(c.en);
      console.log(c.nl, c.en);
    }
  }

  var ecomplete = $(".autocomplete");
  console.log("value", value, "options", options);

  if (options.length) {
    ecomplete.show();
  } else {
    ecomplete.hide();
  }


  ecomplete.html("");
  for (var x = 0; x < options.length; ++x) {
    ecomplete.append("<div data='" + options[x] + "' onclick=\"shopplugin.autocompleteAppend('" + options[x] + "', $(this).parent(), " + (multiple ? 1 : 0) + ")\">" + options[x] + "</div>");
  }
}

/** 
 * Replace the last string by the given value
 */
ShopPlugin.prototype.autocompleteAppend = function (value, elem, multiple) {
  var einput = elem.siblings("input");
  var values = einput.val()
    .split(",");
  values[values.length - 1] = value;

  if (multiple) {
    //check if this country was already added 
    var found = false;
    for (var x = 0; x < values.length; ++x) {
      if (found && (values[x] == value)) {
        values.splice(x, 1);
        --x;
      }

      if (values[x] == value) {
        found = true;
      }
    }
    einput.val(values.join(",") + ",");
  } else {
    einput.val(value);
  }

  this.removeAllAutocomplete();
  einput.focus();
}
