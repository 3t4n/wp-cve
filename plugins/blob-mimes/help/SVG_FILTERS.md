If your site allows `svg` uploads, the following additional filters might be of use to you.

(If your site doesn't allow `svg` uploads, you can ignore them; they won't apply.)


&nbsp;
### lotf\_svg\_allowed\_attributes

Override the list of attributes (`width`, `height`, `id`, etc.) allowed to exist within an `svg`.

#### Parameters

| Type | Description |
| ---- | ---- |
| _array_ | An array of allowed attributes. |

#### Returns

This should always return an array of allowed attributes.


&nbsp;
### lotf\_svg\_allowed\_domains

Override the list of external hosts an `svg` is allowed to reference.

By default, the following domains are allowed:
 * (your site)
 * creativecommons.org
 * inkscape.org
 * sodipodi.sourceforge.net
 * w3.org

#### Parameters

| Type | Description |
| ---- | ---- |
| _array_ | An array of allowed domains. |

#### Returns

This should always return an array of allowed domains.


&nbsp;
### lotf\_svg\_allowed\_protocols

Override the list of protocols IRI attributes can link to.

By default, only `http` and `https` links are allowed.

#### Parameters

| Type | Description |
| ---- | ---- |
| _array_ | An array of allowed protocols. |

#### Returns

This should always return an array of allowed protocols.


&nbsp;
### lotf\_svg\_allowed\_tags

Override the list of tags (`circle`, `defs`, `g`, `path`, etc.) allowed to exist within an `svg`.

#### Parameters

| Type | Description |
| ---- | ---- |
| _array_ | An array of allowed tags. |

#### Returns

This should always return an array of allowed tags.
