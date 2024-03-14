String.prototype.formatUnicorn = String.prototype.formatUnicorn ||
  function () {
    "use strict";
    var str = this.toString();
    if (arguments.length) {
      var t = typeof arguments[0];
      var key;
      var args = ("string" === t || "number" === t) ?
        Array.prototype.slice.call(arguments)
        : arguments[0];

      for (key in args) {
        str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
      }
    }

    return str;
  };

var get_comps = function() {
  return fetch(compApiSettings.root + 'cluevo/v1/competence/competences', {
    credentials: 'include',
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      'X-WP-Nonce': compApiSettings.nonce
    }
  })
    .then(function (response) {
      return response.json();
    })
    .then(function(data) {
      return data;
    })
    .catch(function(error) {
      console.error(error);
    });
}

var get_areas = function() {
  return fetch(compApiSettings.root + 'cluevo/v1/competence/areas', {
    credentials: 'include',
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      'X-WP-Nonce': compApiSettings.nonce
    }
  })
    .then(function (response) {
      return response.json();
    })
    .then(function(data) {
      return data;
    })
    .catch(function(error) {
      console.error(error);
    });
}

var get_modules = function() {
  return fetch(compApiSettings.root + 'cluevo/v1/modules', {
    credentials: 'include',
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      'X-WP-Nonce': compApiSettings.nonce
    }
  })
    .then(function (response) {
      return response.json();
    })
    .then(function(data) {
      return data;
    })
    .catch(function(error) {
      console.error(error);
    });
}

