<div class="lpagery-sidebar" id="lpagery-sidebar">
    <?php 
?>
    <a target="_blank" href="https://www.youtube.com/watch?v=yvfAxmKavzk" class="sidebar-block" id="lpagery_docs-link">
        <h6><i class="fa-solid fa-file-circle-question"></i> Want to know how it works?</h6>
        <p>Get started by watching our step-by-step tutorial on how to use LPagery.</p>
    </a>
    <a target="_blank" href="https://wordpress.org/support/plugin/lpagery/reviews/#new-post" class="sidebar-block"
       id="lpagery_review-link">
        <h6><i class="fa-solid fa-star"></i> Enjoying LPagery?</h6>
        <p>If you like using LPagery, please take 2 minutes and give us a review.</p>
    </a>
	<?php 

if ( lpagery_fs()->is_free_plan() ) {
    ?>
        <a target="_blank"  rel="noopener" href="<?php 
    echo  ( get_option( "lpagery.upgrade.url" ) != null ? get_option( "lpagery.upgrade.url" ) : "https://lpagery.io/pricing/?utm_source=free_version&utm_medium=banner&utm_campaign=upgrade" ) ;
    ?>" class="sidebar-block"
           id="lpagery_pro-link">
            <h6><i class="fa-solid fa-unlock"></i> Want all features?</h6>
            <p>Upgrage to Pro and unlock tons of useful features and the option to automatically create pages for
                cities in
                your area.</p> <span class="lpagery-button">Upgrade to Pro!</span>
        </a>
	<?php 
}

?>

</div>

