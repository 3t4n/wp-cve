jQuery(document).ready(function () {
  const data = {
    disable_afk: php_vars.disable_afk === "1",
    disable_auto_sync: php_vars.disable_auto_sync === "1",
    blacklist: php_vars.blacklist === "1",
  };

  console.log(data);

  if (!data.disable_afk && !data.disable_auto_sync && !data.blacklist) {
    jQuery(
      "<div style='background-color: #00bf72; color: #fff; font-weight: bold; padding: 10px 10px 1px; margin-bottom: 5px;'>Note: If Focus keyword field is empty then Post title will be added as Focus keyword. You can add your own custom focus keyword and it will work fine or disbale auto focus keyword from sidebar or globally from <a href='admin.php?page=auto-focus-keyword-for-seo' target='_blank' style='color: #fff!important'>Settings page</a></p>"
    ).insertBefore("#wpseo-metabox-root label:first-child");
  }
});
