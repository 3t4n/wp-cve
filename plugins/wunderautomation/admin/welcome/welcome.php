<?php
$puffs = [
    "getStarted" => (object)[
        "header"       => "Get started right away",
        "desc1"        => "Find everything related to WunderAutomation under the 'Automation' menu on your dashboard.",
        "desc2"        => "",
        "image"        => WUNDERAUTO_URLBASE . 'admin/assets/images/wamenu.png',
        "link"         => admin_url('edit.php?post_type=automation-workflow'),
        "linkText"     => "Create your first workflow",
        "linkExternal" => false,
    ],
    "wizard"     => (object)[
        "header" => "Run the wizard",
        "desc1"  => "Step through this friendly wizard to get some basic information about how to use WunderAutomation",
        "desc2"  => "",
        "image"  => WUNDERAUTO_URLBASE . 'admin/assets/images/bulb_transparent.png',
    ],
    "docs"       => (object)[
        "header"       => "Read the docs",
        "desc1"        => "Visit our documentation pages to get help with building and fine tuning your workflows",
        "desc2"        => "",
        "image"        => WUNDERAUTO_URLBASE . 'admin/assets/images/documentation.png',
        "link"         => 'https://www.wundermatics.com/docs-category/wunderautomation/',
        "linkText"     => "Documentation",
        "linkExternal" => true,
    ],
    "videos"     => (object)[
        "header"       => "Videos",
        "desc1"        => "We're made some videos to help you get to know WunderAutomation better. Go watch theme here",
        "desc2"        => "",
        "image"        => WUNDERAUTO_URLBASE . 'admin/assets/images/ytlogo.png',
        "link"         => 'https://www.youtube.com/channel/UCuO3_ZsoLJ6S9Z7EgtRXg7Q',
        "linkText"     => "Visit our channel",
        "linkExternal" => true,
    ],
];
?>

<div class="wrap">
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-auto-rows: auto; grid-gap: 1rem;">
        <?php foreach ($puffs as $id => $puff) :?>
            <?php
            $link = '';
            if (isset($puff->link)) {
                $link = $puff->link;
                if (strpos($link, 'https://www.wundermatics.com') !== false) {
                    $link .= '?utm_source=dashboard&utm_medium=welcomecard&utm_campaign=installed_users';
                }
            }
            $target = "_self";
            if (isset($puff->linkExternal) && $puff->linkExternal) {
                $target = "_blank";
            }
            ?>
            <div class="card">
                <div style="text-align: center;">
                    <h2><?php esc_html_e($puff->header); ?></h2>
                    <p>
                        <?php esc_html_e($puff->desc1)?><br>
                    </p>
                    <?php if (strlen($link) > 0) :?>
                        <a href="<?php esc_attr_e($link)?>" target="<?php esc_attr_e($target)?>">
                    <?php endif ?>
                        <img height="250px" src="<?php esc_attr_e($puff->image)?>" />
                    <?php if (strlen($link) > 0) :?>
                        </a>
                    <?php endif ?>
                </div>

                <div style="text-align: center;">
                    <p>
                        <?php esc_html_e($puff->desc2); ?><br>
                    </p>

                    <?php if (strlen($link) > 0) :?>
                        <a class="button button-primary"
                           target="<?php esc_attr_e($target);?>"
                           href="<?php esc_attr_e($link);?>">
                            <?php esc_html_e($puff->linkText);?>
                        </a>
                    <?php endif?>
                    <?php if ($id === 'wizard') :?>
                        <?php include_once __DIR__ . '/wizard.php'; ?>
                    <?php endif ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>

