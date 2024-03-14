<div class="bootstrap-yop wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h1>
        <?php esc_html_e( 'Edit Ban', 'yop-poll' ); ?>
	</h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder addban">
			<!-- main content -->
			<div id="post-body-content ">
				<form>
					<input type="hidden" name="_token" id="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-edit-ban' ) ); ?>">
					<div class="yop-container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="yop-text">
                                        <label>
                                        <?php esc_html_e( 'Poll', 'yop-poll' ); ?>
                                        </label>
                                    </div>
                                    <select class="ban-poll admin-select" style="min-width:50%">
                                        <?php
                                        if ( 0 === intval( $ban->poll_id ) ) {
                                            ?>
                                            <option value="0" selected>
                                                <?php
                                                esc_html_e( 'All Polls', 'yop-poll' );
                                                ?>
                                            </option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="0">
                                                <?php
                                                esc_html_e( 'All Polls', 'yop-poll' );
                                                ?>
                                            </option>
                                            <?php
                                        }
                                        foreach ( $polls as $poll ) {
                                            if ( $ban->poll_id === $poll->id ) {
                                                ?>
                                                <option value="<?php echo esc_attr( $poll->id ); ?>" selected>
                                                    <?php echo esc_html( $poll->name ); ?>
                                                </option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value="<?php echo esc_attr( $poll->id ); ?>">
                                                    <?php echo esc_html( $poll->name ); ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="yop-container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="yop-text">
                                        <label>
                                            <?php esc_html_e( 'Ban By', 'yop-poll' ); ?>
                                        </label>
                                    </div>
                                    <?php
                                    switch ( $ban->b_by ) {
                                        case 'ip': {
                                            $ban_by_ip = 'selected';
                                            $ban_by_email = '';
                                            $ban_by_username = '';
                                            break;
                                        }
                                        case 'email': {
                                            $ban_by_ip = '';
                                            $ban_by_email = 'selected';
                                            $ban_by_username = '';
                                            break;
                                        }
                                        case 'username': {
                                            $ban_by_ip = '';
                                            $ban_by_email = '';
                                            $ban_by_username = 'selected';
                                            break;
                                        }
                                        default: {
                                            $ban_by_ip = 'selected';
                                            $ban_by_email = '';
                                            $ban_by_username = '';
                                            break;
                                        }
                                    }
                                    ?>
                                    <select class="ban-by admin-select" style="min-width:50%">
                                        <option value="ip" <?php echo esc_attr( $ban_by_ip ); ?>>
                                            <?php esc_html_e( 'IP', 'yop-poll' ); ?>
                                        </option>
                                        <option value="email" <?php echo esc_attr( $ban_by_email ); ?>>
                                            <?php esc_html_e( 'Email', 'yop-poll' ); ?>
                                        </option>
                                        <option value="username" <?php echo esc_attr( $ban_by_username ); ?>>
                                            <?php esc_html_e( 'Username', 'yop-poll' ); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="yop-container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="yop-text">
                                        <label>
                                            <?php esc_html_e( 'Value', 'yop-poll' ); ?>
                                        </label>
                                    </div>
                                    <input type="text" class="form-control ban-value"
                                        style="width:50%"
                                        value="<?php echo esc_attr( $ban->b_value ); ?>"
                                    >
                                </div>
                            </div>
                        </div>
					</div>
					<div class="yop-container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input
                                        name="updateban"
                                        class="button button-primary button-large center update-ban"
                                        value="<?php esc_html_e( 'Update', 'yop-poll' ); ?>"
                                        data-id="<?php echo esc_attr( $ban->id ); ?>"
                                        type="submit">
                                </div>
                            </div>
                        </div>
					</div>
				</form>
			</div> <!-- #post-body  -->
			<br class="clear">
		</div> <!-- #poststuff -->
	</div> <!-- .wrap -->
</div>
