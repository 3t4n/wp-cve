<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
         aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
				<?php echo __( 'Success', 'send-users-email' ) ?>.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>

    <div id="warningToast" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive"
         aria-atomic="true" data-bs-delay=20000>
        <div class="d-flex">
            <div class="toast-body">
				<?php echo __( 'Warning', 'send-users-email' ) ?>.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>
</div>