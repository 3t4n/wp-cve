{
  "slug":       "autoupdates",
  "properties": {
    "name":                   "Automatic Updates",
    "show_feature_menu_item": false,
    "storage_key":            "autoupdates"
  },
  "sections":   [
    {
      "slug":   "section_non_ui",
      "hidden": true
    }
  ],
  "options":    [
    {
      "key":     "current_plugin_version",
      "section": "section_non_ui"
    },
    {
      "key":     "action_hook_priority",
      "section": "section_non_ui",
      "default": 1001
    },
    {
      "key":     "enable_autoupdates",
      "section": "section_non_ui",
      "default": "N"
    },
    {
      "key":     "autoupdate_plugin_self",
      "section": "section_non_ui",
      "default": "Y"
    },
    {
      "key":     "override_email_address",
      "section": "section_non_ui",
      "default": ""
    },
    {
      "key":     "auto_update_plugins",
      "section": "section_non_ui",
      "default": []
    },
    {
      "key":     "auto_update_themes",
      "section": "section_non_ui",
      "default": []
    }
  ]
}