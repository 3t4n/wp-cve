<?php
$groupId = isset($groupId) && !empty($groupId) ? $groupId :  uniqid('group_');
?>
<div class="hurryt-wc-condition-group " data-group-id="<?php echo $groupId ?>">
    <?php if(isset($conditions) && !empty($conditions)): foreach($conditions as $active):
        $selected = hurryt_wc_conditions()[$active['key']];
        ?>
    <?php include HURRYT_DIR . 'admin/templates/wc-condition.php'; ?>
<?php endforeach; else: 
     include HURRYT_DIR . 'admin/templates/wc-condition.php'; 
    endif;    
    ?>  
    <div class="hurryt-mt-4 hurryt-mb-2 hurryt-font-bold">or</div>
</div>
