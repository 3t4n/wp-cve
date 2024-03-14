<div class="card shadow">
    <div class="card-body">
        <h5 class="card-title text-uppercase"><?php echo __( 'Placeholder', 'send-users-email' ); ?></h5>
        <p class="card-text"><?php echo __( 'You can use following placeholder to replace user detail on email.',
				'send-users-email' ); ?></p>
        <table class="table table-borderless">
            <tr>
                <td>
                    {{user_id}}<br>
					<?php echo __( 'Use this placeholder to display user ID.', 'send-users-email' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    {{username}}<br>
					<?php echo __( 'Use this placeholder to display username', 'send-users-email' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    {{user_display_name}}<br>
					<?php echo __( 'Use this placeholder to display user display name', 'send-users-email' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    {{user_first_name}}<br>
					<?php echo __( 'Use this placeholder to display user first name', 'send-users-email' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    {{user_last_name}}<br>
					<?php echo __( 'Use this placeholder to display user last name', 'send-users-email' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    {{user_email}}<br>
					<?php echo __( 'Use this placeholder to display user email', 'send-users-email' ); ?>
                </td>
            </tr>
        </table>

        <div class="sue-messages"></div>

    </div>
</div>