window.onload = function ()
{
	const rfsn = {
		cart: rfsn_vars.cti,
		id: localStorage.getItem("rfsn_v4_id"),
		url: window.location.href,
		aid: localStorage.getItem("rfsn_v4_aid"),
		cs: localStorage.getItem("rfsn_v4_cs")
	};
	r.sendCheckoutEvent(rfsn.cart, rfsn.id, rfsn.url, rfsn.aid, rfsn.cs);

}
