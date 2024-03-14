jQuery(document).ready(function ($) {
  $('input.mepr-paystack-testmode').each(function () {
    var integration = $(this).data('integration');

    if ($(this).is(':checked')) {
      $('#mepr-paystack-test-keys-' + integration).show();
    }
    else {
      $('#mepr-paystack-live-keys-' + integration).show();
    }
  });

  $('div#integration').on('change', 'input.mepr-paystack-testmode', function () {
    var integration = $(this).data('integration');
    if ($(this).is(':checked')) {
      $('#mepr-paystack-live-keys-' + integration).hide();
      $('#mepr-paystack-test-keys-' + integration).show();
    }
    else {
      $('#mepr-paystack-live-keys-' + integration).show();
      $('#mepr-paystack-test-keys-' + integration).hide();
    }
  });

  // respondToVisibility = function (element, callback) {
  //   var options = {
  //     root: document.documentElement
  //   }
  
  //   var observer = new IntersectionObserver((entries, observer) => {
  //     entries.forEach(entry => {
  //       callback(entry.intersectionRatio > 0);
  //     });
  //   }, options);
  
  //   observer.observe(element);
  // }

  // $('div#integration').on('change', 'select.mepr-gateways-dropdown', function () {
  //   var gateway = $(this).val();
  //   var integration = $(this).data('integration');
  //   var targetNode = document.getElementById('mepr-paystack-live-keys-' + integration);
  //   // var observer = new MutationObserver(function () {
  //   //   if (targetNode.style.display != 'none') {
  //   //     if (gateway === 'MeprPaystackGateway') {
  //   //       $('#mepr-paystack-live-keys-' + integration).show();
  //   //     }
  //   //   }
  //   // });
  //   // observer.observe(targetNode, { attributes: true, childList: true });

  //   respondToVisibility(targetNode, visible => {
  //     if (gateway === 'MeprPaystackGateway') {
  //       $('#mepr-paystack-live-keys-' + integration).show();
  //     }
  //   });
  // });

});

