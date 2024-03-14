<?php

use WordPress\Plugin\Encyclopedia\{
    MockingBird
};

?>
<p>
    <?php MockingBird::printProNotice('count_limit') ?>
</p>

<p>
    <a href="<?php MockingBird::printProNotice('upgrade_url') ?>" target="_blank" class="button-primary"><?php MockingBird::printProNotice('upgrade') ?></a>
</p>