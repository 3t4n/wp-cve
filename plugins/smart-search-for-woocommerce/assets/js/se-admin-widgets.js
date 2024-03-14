SearchaniseAdmin = {};
SearchaniseAdmin.host = SeOptions.host;
SearchaniseAdmin.PrivateKey = SeOptions.parent_private_key;
SearchaniseAdmin.ReSyncLink = SeOptions.re_sync_link;
SearchaniseAdmin.LastRequest = SeOptions.last_request;
SearchaniseAdmin.LastResync = SeOptions.last_resync;
SearchaniseAdmin.ConnectLink = SeOptions.connect_link;
SearchaniseAdmin.Platform = SeOptions.platform;
SearchaniseAdmin.AddonStatus = SeOptions.status;
SearchaniseAdmin.AddonVersion = SeOptions.version;
SearchaniseAdmin.PlatformEdition = SeOptions.platform_edition;
SearchaniseAdmin.PlatformVersion = SeOptions.platform_version;
SearchaniseAdmin.ShowResultsControlPanel = true;
SearchaniseAdmin.Engines = [];

if (SeOptions.s_engines.length) {
	for (var i = 0; i < SeOptions.s_engines.length; i++) {
		var engine = SeOptions.s_engines[i];

		SearchaniseAdmin.Engines.push({
			PrivateKey: engine.private_key,
			LangCode: engine.lang_code,
			Name : engine.language_name,
			ExportStatus: engine.export_status,
			PriceFormat: {
				rate : 1.0,
				symbol: SeOptions.symbol,
				decimals: SeOptions.decimals,
				decimals_separator: SeOptions.decimals_separator,
				thousands_separator: SeOptions.thousands_separator,
				after: false
			}
		});
	}
}
