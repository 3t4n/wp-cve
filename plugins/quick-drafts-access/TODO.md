# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Cache user draft count; clear count when a post transitions to/from draft
* Add unit test coverage: `quick_drafts_access()`, `'c2c_quick_drafts_access_show_all_drafts_menu_link'`, `'c2c_quick_drafts_access_show_my_drafts_menu_link'`, `'c2c_quick_drafts_access_show_if_empty'`
* For draft author filter dropdown, perhaps omit draft authors whose drafts are not editable by the current user
* Add a screen options setting to (globally) disable draft links for the given post type. By default, they aren't disabled, which represents plugin's default behavior. This could (should?) be a per-user setting instead, which is really what screen options are otherwise for.
* When a quick edit or bulk edit happens, potentially refresh the drafts count(s).
  * May require an authenticated API endpoint.

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/quick-drafts-access/) or on [GitHub](https://github.com/coffee2code/quick-drafts-access/) as an issue or PR).