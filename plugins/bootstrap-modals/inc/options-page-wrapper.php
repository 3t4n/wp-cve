

<h2><?php _e( 'Bootstrap Modal', 'bootstrap-modal' ); ?></h2>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Bootstrap Modal Options', 'bootstrap-modal' ); ?></h1>
  <?php

   $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'plugin_options';

?>
<h2 class="nav-tab-wrapper">
<a href="?page=bootstrap-modal&tab=plugin_options" class="nav-tab <?php echo $active_tab == 'plugin_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Plugin Options', 'bootstrap-modal'); ?></a>
<a href="?page=bootstrap-modal&tab=markup_options" class="nav-tab <?php echo $active_tab == 'markup_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Markup Instructions', 'bootstrap-modal'); ?></a>
<a href="?page=bootstrap-modal&tab=shortcode_options" class="nav-tab <?php echo $active_tab == 'shortcode_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Shortcode', 'bootstrap-modal'); ?></a>

</h2>


	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="inside">
              <?php
              if( $active_tab == 'plugin_options' ) {
                echo'<form method="post" action="options.php">';
                settings_fields( 'ng_bm_settings_group' ); //group name
                do_settings_sections( 'bootstrap-modal' );
                submit_button('Update');
                echo'</form>';

              ?>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->
    <?php
    }
    elseif ( $active_tab == 'markup_options' ) {
      ?>
      <h3><span><?php esc_attr_e( 'Markup instructions on how to use Bootstrap Modal', 'bootstrap-modal' ); ?></span></h3>
        <p>There is no WP-Admin options setting to create the modal, mark up needs to be directly applied to post/page or widget area.<br>Here is a simple HTML Bootstrap Modal MarkUp</p>
        <a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal">Launch demo modal</a>
        <pre data-initialized="true" data-gclp-id="0" style="background:#eaeaea;"><code style="background:#eaeaea;">
&lt;!-- Button trigger modal --&gt;
&lt;a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal"&gt;Launch demo modal&lt;/a&gt;

&lt;!-- Modal --&gt;
&lt;div id="myModal1" class="modal fade" tabindex="-1" role="dialog"&gt;
    &lt;div class="modal-dialog" role="document"&gt;
        &lt;div class="modal-content"&gt;
            &lt;div class="modal-header"&gt;
                &lt;button class="close" type="button" data-dismiss="modal"&gt;×&lt;/button&gt;
                    &lt;h4 class="modal-title"&gt;My Title in a Modal Window&lt;/h4&gt;
            &lt;/div&gt;
            &lt;div class="modal-body"&gt;This is the body of a modal...&lt;/div&gt;
            &lt;div class="modal-footer"&gt;This is the footer of a modal...&lt;/div&gt;
        &lt;/div&gt;&lt;!-- /.modal-content --&gt;
    &lt;/div&gt;&lt;!-- /.modal-dialog --&gt;
&lt;/div&gt;&lt;!-- /.modal --&gt;
</code>
</pre>
<p>Here is one with a close button in the modal footer as well as still having the top right close.</p>
<a class="btn btn-primary btn-lg" href="#myModal2" data-toggle="modal">Launch demo modal</a>
<pre data-initialized="true" data-gclp-id="0" style="background:#eaeaea;"><code style="background:#eaeaea;">
&lt;!-- Button trigger modal --&gt;
&lt;a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal"&gt;Launch demo modal&lt;/a&gt;

&lt;!-- Modal --&gt;
&lt;div id="myModal1" class="modal fade" tabindex="-1"&gt;
  &lt;div class="modal-dialog"&gt;
    &lt;div class="modal-content"&gt;
      &lt;div class="modal-header"&gt;
          &lt;button class="close" type="button" data-dismiss="modal"&gt;×&lt;/button&gt;
              &lt;h4 class="modal-title"&gt;My Title in a Modal Window&lt;/h4&gt;
      &lt;/div&gt;
      &lt;div class="modal-body"&gt;This is the body of a modal...&lt;/div&gt;
      &lt;div class="modal-footer"&gt;
        &lt;button class="btn btn-default" type="button" data-dismiss="modal"&gt;Close&lt;/button&gt;
      &lt;/div&gt;
    &lt;/div&gt;&lt;!-- /.modal-content --&gt;
  &lt;/div&gt;&lt;!-- /.modal-dialog --&gt;
&lt;/div&gt;&lt;!-- /.modal --&gt;
</code>
</pre>
        </div>
        <!-- .inside -->

      </div>
      <!-- .postbox -->

    </div>
    <!-- .meta-box-sortables .ui-sortable -->

    </div>
    <!-- post-body-content -->
      <?php
    }
    else {
      ?>
      <h3><span><?php esc_attr_e( 'Shortcode Bootstrap Modal', 'bootstrap-modal' ); ?></span></h3>
			<p>Instead of using HTML markup you can use a shortcode - well 2 actually...</p>
        <p>There are 2 bits of shortcode available for the Bootstrap Modal: the modal itself and the trigger or link that executes it.</p>
				<ul>
					<li>[bs_trigger]...[/bs_trigger]</li>
					<li>[bs_modal]...[/bs_modal]</li>
				</ul>
				<h4> Available attributes for [bs_trigger]Link Text Goes Here[/bs_trigger] are:</h4>
				<ul>
					<li>'id' = <em>required</em> - the modal target</li>
					<li>'class' = <em>optional</em> - CSS class</li>
					<li>'arialabel' = <em>optional</em> - Accessibility label</li>
				</ul>
				<h4> Available attributes for [bs_modal]Modal Text Goes Here[/bs_modal] are:</h4>
				<ul>
					<li>'id' = <em>required</em> - the modal target</li>
					<li>'class' = <em>optional</em> - CSS class</li>
					<li>'arialabel' = <em>optional</em> - Accesscibility label</li>
					<li>'header' = <em>optional</em> - Modal Header text</li>
					<li>'footer' = <em>optional</em> - Modal Footer text</li>
				</ul>
				<p>The key thing is that the 'id' value must match in both.</p>
				<h4>Shortcode Modal Example</h4>
				<code>[bs_trigger id="modal1"]Launch The Modal[/bs_trigger]</code><br>
				<code>[bs_modal id="modal1" header="I am a header" footer="I am a footer"]I am a modal[/bs_modal]</code>

        </div>
        <!-- .inside -->

      </div>
      <!-- .postbox -->

    </div>
    <!-- .meta-box-sortables .ui-sortable -->

    </div>
    <!-- post-body-content -->
    <?php
    }
    ?>
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h2><span><?php esc_attr_e(
									'Further Ref', 'bootstrap-modal'
								); ?></span></h2>

						<div class="inside">
							<p>
							More documentation on the <a href="http://getbootstrap.com/javascript/#modals">Bootstrap website</a></p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->


  <!-- Modal -->
  <div id="myModal1" class="modal fade" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button class="close" type="button" data-dismiss="modal">×</button>
                      <h4 class="modal-title">My Title in a Modal Window</h4>
              </div>
              <div class="modal-body">This is the body of a modal...</div>
              <div class="modal-footer">This is the footer of a modal...</div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


<!-- Modal -->
<div id="myModal2" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                    <h4 class="modal-title">My Title in a Modal Window</h4>
            </div>
            <div class="modal-body">This is the body of a modal...</div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
              This is the footer of a modal...</div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div> <!-- .wrap -->
