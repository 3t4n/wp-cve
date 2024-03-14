<?php if(array_key_exists('site', $_page)) { echo '<p>' . __('You are connected to this EasyMe account', 'easyme') . ': <strong>' . $_page['site'] . '</strong></p>'; } ?>

<?php

echo '<ul style="list-style-type: circle; margin-left: 25px">';

if($_page['isPro']) {
    echo '<li>' . $_page['i18n']['IS_PRO'] . '</li>';
} else {
    echo '<li>' . $_page['i18n']['NOT_PRO'] . '</li>';
}

echo '<li>' . __('Number of protected pages/posts', 'easyme') . ': ' . $_page['protected_pages'] . '</li>';
echo '<li>' . __('Last checkin with EasyMe', 'easyme') . ': ' . ($_page['access_token_acquired'] ? wp_date('Y-m-d H:i', $_page['access_token_acquired']) : __('Never', 'easyme')) . '</li>';

echo '</ul>';
?>

