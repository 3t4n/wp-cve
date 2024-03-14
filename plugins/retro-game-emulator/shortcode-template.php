<canvas id="retro-game-emulator-canvas" width="256" height="240" style="width: 100%;display:none;margin-bottom:10px"></canvas>
<select id="retro-game-emulator-game-select" class="form-control">
    <option>- Select Rom -</option>
    <?php foreach ($romsArray as $rom) : ?>
        <option value="<?php echo $rom['url']; ?>"><?php echo $rom['name']; ?></option>
    <?php endforeach; ?>
</select>
<h3><?php echo __("Controls"); ?></h3>
<table>
    <tr>
        <th><?php echo __("Button"); ?></th>
        <th><?php echo __("Player 1"); ?></th>
        <th><?php echo __("Player 2"); ?></th>
    </tr>
    <tr>
        <td><?php echo __("Left"); ?></td>
        <td><?php echo __("Left"); ?></td>
        <td><?php echo __("Num-4"); ?></td>
    <tr>
        <td><?php echo __("Right"); ?></td>
        <td><?php echo __("Right"); ?></td>
        <td><?php echo __("Num-6"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("Up"); ?></td>
        <td><?php echo __("Up"); ?></td>
        <td><?php echo __("Num-8"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("Down"); ?></td>
        <td><?php echo __("Down"); ?></td>
        <td><?php echo __("Num-2"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("A"); ?></td>
        <td><?php echo __("A"); ?></td>
        <td><?php echo __("Num-7"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("B"); ?></td>
        <td><?php echo __("S"); ?></td>
        <td><?php echo __("Num-9"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("Start"); ?></td>
        <td><?php echo __("Enter"); ?></td>
        <td><?php echo __("Num-1"); ?></td>
    </tr>
    <tr>
        <td><?php echo __("Select"); ?></td>
        <td><?php echo __("Tab"); ?></td>
        <td><?php echo __("Num-3"); ?></td>
    </tr>
</table>