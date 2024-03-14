// Global variables.
declare var wc: any;

// Get the settings from the integration interface.
export const { wcSettings } = wc;

/**
 * Get the settings from the integration interface.
 * - We need to add `_data` suffix because : https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/checkout-block/integration-interface.md#getting-data-added-in-get_script_data
 * */
export const getSetting = (IntegrationName: string) => wcSettings.getSetting(`${IntegrationName}_data`);
