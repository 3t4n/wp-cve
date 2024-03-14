
StageShowLib_addWindowsLoadHandler(stageshow_OnStripeCheckoutLoad); 

var stageshow_StripeHandler;

function stageshow_OnStripeCheckoutLoad()
{
	stageshow_StripeHandler = StripeCheckout.configure(
		{
			key: stripeKey,
			image: stripeLogoURL,
			locale: "auto",
			zipCode: stripeGetAddress,
			billingAddress: stripeGetAddress,
			token: function(token, args) 
			{
			    // Add return values from Stripe to HTML form
			    StageShowLib_AddHiddenValue("stripeToken", token.id);
			    StageShowLib_AddHiddenValue("stripeTokenType", token.type);
			    StageShowLib_AddHiddenValue("stripeEmail", token.email);
			    
			    if (stripeGetAddress)
			    {
			    	StageShowLib_AddHiddenValue("stripeBillingName", args.billing_name);
			    	StageShowLib_AddHiddenValue("stripeBillingAddressLine1", args.billing_address_line1);
			    	StageShowLib_AddHiddenValue("stripeBillingAddressCity", args.billing_address_city);
			    	StageShowLib_AddHiddenValue("stripeBillingAddressCounty", args.billing_address_state);
			    	StageShowLib_AddHiddenValue("stripeBillingAddressZip", args.billing_address_zip);
			    	StageShowLib_AddHiddenValue("stripeBillingAddressCountry", args.billing_address_country);		    	
				}
									
			    // Submit the form
			    document.getElementById("trolley").submit(); 
			},
			closed: function() 
			{
				stageshow_OnClickStripeClosed();
  			}
  		}
	);	
	
	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() 
		{			
			stageshow_OnClickStripeClosed();
		}
	);
}

function stageshow_OnClickStripeClosed()
{
	stageshow_StripeHandler.close();

	var pluginId = stageshowlib_cssDomain;
	StageShowLib_SetBusy(false, pluginId + "-trolley-ui", pluginId + "-trolley-button");
}

function stageshow_OnClickStripeCheckout(obj, unused)
{
	stageshow_OnClickCheckout(obj);
	
	var trolleyTotalObj = document.getElementById("stageshow-trolley-totalval");
	var trolleyTotal = 0;
	if (trolleyTotalObj != null)
	{
		trolleyTotal = StageShowLib_ParseCurrency(trolleyTotalObj.innerHTML);
		trolleyTotal *= 100;
	}

	// Open Checkout with further options:
	stageshow_StripeHandler.open(
		{
			name: stripePopupName,
			description: stripeSaleDescription,
			currency: stripeCurrency,
			amount: trolleyTotal
		}
	);
	return false;
}
