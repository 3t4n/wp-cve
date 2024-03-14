// REFERSION TRACKING: BEGIN
if (localStorage.getItem("rfsn_cti_check") === null || localStorage.getItem("rfsn_cti_check") !== rfsn_vars.cti) {

	_refersion(function(){ _rfsn._addCart(rfsn_vars.cti); });

	localStorage.setItem("rfsn_cti_check", rfsn_vars.cti);

}
// REFERSION TRACKING: END
