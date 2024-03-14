<div class="wrap">
    <h1>Hyperpay Logs history</h1>
    <div id="templateside">
        <h2 id="plugin-files-label">Logs files</h2>
        <ul role="tree" aria-labelledby="plugin-files-label">
            <li role="treeitem" tabindex="-1" aria-expanded="true" aria-level="1" aria-posinset="1" aria-setsize="1">
                <ul role="group">
                    <?php foreach ($files as $file) : ?>
                        <li role="none">
                            <a role="treeitem" href="<?php echo "$_SERVER[REQUEST_URI]&file=$file"; ?>">
                                <?php if ($current_file == $file) : ?>
                                    <span class="notice notice-info"><?php echo $file; ?></span>
                                <?php else : echo $file; ?>
                                <?php endif ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
        </ul>
    </div>

    <form name="template" id="template" action="plugin-editor.php" method="post">
        <div>
            <label for="hyperpay_logs_file" id="theme-plugin-editor-label"><?php _e('Selected file content:'); ?></label>
            <textarea cols="70" rows="25" id="hyperpay_logs_file" aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo isset($content) ? $content : ''; ?></textarea>
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function($) {
        wp.codeEditor.initialize($("#hyperpay_logs_file"), {
            "codemirror": {
                "indentUnit": 4,
                "indentWithTabs": true,
                "inputStyle": "contenteditable",
                "lineNumbers": true,
                "lineWrapping": true,
                "styleActiveLine": true,
                "continueComments": true,
                "extraKeys": {
                    "Ctrl-Space": "autocomplete",
                    "Ctrl-/": "toggleComment",
                    "Cmd-/": "toggleComment",
                    "Alt-F": "findPersistent",
                    "Ctrl-F": "findPersistent",
                    "Cmd-F": "findPersistent"
                },
                "direction": "ltr",
                "gutters": [],
                "mode": "log"
            },
            "csslint": {
                "errors": true,
                "box-model": true,
                "display-property-grouping": true,
                "duplicate-properties": true,
                "known-properties": true,
                "outline-none": true
            },
            "jshint": {
                "boss": true,
                "curly": true,
                "eqeqeq": true,
                "eqnull": true,
                "es3": true,
                "expr": true,
                "immed": true,
                "noarg": true,
                "nonbsp": true,
                "onevar": true,
                "quotmark": "single",
                "trailing": true,
                "undef": true,
                "unused": true,
                "browser": true,
                "globals": {
                    "_": false,
                    "Backbone": false,
                    "jQuery": false,
                    "JSON": false,
                    "wp": false
                }
            },
            "htmlhint": {
                "tagname-lowercase": true,
                "attr-lowercase": true,
                "attr-value-double-quotes": false,
                "doctype-first": false,
                "tag-pair": true,
                "spec-char-escape": true,
                "id-unique": true,
                "src-not-empty": true,
                "attr-no-duplication": true,
                "alt-require": true,
                "space-tab-mixed-disabled": "tab",
                "attr-unsafe-chars": true
            }
        });
    });
</script>