<?php
wp_redirect("https://www.if-so.com/add-ons-and-integrations/?utm_source=Plugin&utm_medium=pluginMenu&utm_campaign=addons"); //until we make a fitting page here
?>
<style>
    .ifso-addon {
        position: relative;
        background-color: #fff;
        padding: 2em 2em 4em 2em;
        margin: 0;
        transition: 0.2s;
    }

    .addons-wrap .ifso-addon {
        float: left;
        max-width: 360px;
        margin: 1em
    }

    @supports(grid-area:auto) {
        .addons-wrap {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1em;
            margin-top: 24px
        }

        .addons-wrap .ifso-addon {
            float: none;
            margin: 0;
            max-width: unset
        }
    }

    .ifso-addon:hover {
        border: 1px solid #bbb;
        transform: scale(1.04);
        z-index: 5;
    }

    .ifso-addon a {
        display: contents;
    }

    .ifso-addon .title {
        margin: 0 0 1em
    }

    .ifso-addon .thumb {
        display: block;
        width: 100%;
        margin: 0 auto;
    }

    .ifso-addon .button {
        position: absolute;
        bottom: 14px;
        left: 14px;
    }

    @media screen and (max-width: 600px) {
        .addons-wrap {
            grid-template-columns: auto;
        }
    }
</style>

<?php
$addons_json_url = EDD_IFSO_STORE_URL . "/api/if-so-extensions-list.json";
$response = wp_remote_get($addons_json_url, array('timeout' => 10));
if (!empty($response['body']) && json_decode($response['body'])) {
    $addons_list = json_decode($response['body']);
    ?>
    <div class="addons-wrap">
        <?php
        foreach ($addons_list as $addon) {
            $img_url = !empty($addon->img_url) ? $addon->img_url : 'https://easydigitaldownloads.com/wp-content/uploads/edd/2019/03/stripe-product-image-540x270.png';      //Create a proper default image here
            echo "<div class='ifso-addon'><a target='_blank' href='{$addon->url}'><h3 class='title'>{$addon->title}</h3><img class='thumb' src='{$img_url}'><p class='description'>{$addon->description}</p><button class='button button-primary'>Learn More</button></a></div>" . PHP_EOL;
        }
        ?>
    </div>
    <?php
} else {
    echo 'There has been a communication failure';
}