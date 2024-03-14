<body class="lpagery-body">


<div class="lpagery-container">
    <div class="Menu">
        <div class="lpagery-wrapper">
            <row>
                <div class="column">
                    <img src="<?php echo  plugin_dir_url(dirname( __FILE__ )) . '/../assets/img/LP_Logo_horizontal_2022_RZ.png'; ?>"
                         width="250px" height="auto" id="lpagery_logo">
                </div>
                <div class="column">
                    <nav class="tabs">
                        <div class="selector"></div>
                        <a href="#dashboard" class="active" id="lpagery_anchor_dashboard"><i class="fas fa-copy"> </i>Dashboard</a>
                        <a href="#settings" id="lpagery_anchor_settings"><i class="fas fa-wrench"></i>Settings</a>
                        <a href="#history" id="lpagery_anchor_history"><i class="fa-solid fa-list-check"></i>Manage</a>
                        <?php if ( lpagery_fs()->is_free_plan() ): ?>
                            <a href="#" id="lpagery_anchor_pro"><i class="fas fa-bolt" id="lpagery_anchor_pro"></i>Go Pro!</a>
						<?php endif; ?>
                    </nav>
                </div>
            </row>
        </div>
    </div>


    <main>
        <section id="lpagery_dashboard_container">

			<?php include_once( 'dashboard.php' ); ?>
        </section>
        <section id="lpagery_settings_container" >
			<?php include_once( 'settings.php' ); ?>
        </section>
        <section id="lpagery_history_container" >
		    <?php include_once( 'history.php' ); ?>
        </section>
        <section id="lpagery_pro_container" >
			<?php include_once( 'purchase_pro.php' ); ?>
        </section>
    </main>
	<?php include_once( 'sidebar.php' ); ?>
</div>

<?php include_once( 'confirm_modal.php' ); ?>
<?php include_once( 'update_modal.php' ); ?>
</body>
