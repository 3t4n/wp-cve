The following filters may be used to alter the general file upload verification results.


&nbsp;
### lotf\_get\_mime\_aliases

Override the list of MIME type aliases matching a given file extension.

#### Parameters

| Type | Description |
| ---- | ---- |
| _array_ or _bool_ | An array containing type aliases or `false` if none. |
| _string_ | The associated file extension. |

#### Returns

If the extension has type aliases, they should be returned as a simple indexed array. If there are none, `false` should be returned.



&nbsp;
### Having the Final Word

`Lord of the Files` hooks into the [wp\_check\_filetype\_and\_ext](https://developer.wordpress.org/reference/hooks/wp_check_filetype_and_ext/) filter with a priority of `10`.

If you would like to run additional code before LotF gets involved, you can hook into the same filter with a priority of less than `10`.

Likewise, if you would like to run additional code after LotF has done its thing, you can hook into `wp_check_filetype_and_ext` with a priority greater than `10`.

Note: `svg` sanitizing is achieved during a second pass at priority `15`. To intervene with that process, again, simply set your hooks less than or greater than `15`.
