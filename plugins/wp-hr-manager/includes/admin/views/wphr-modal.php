<div id="wphr-modal">
    <div class="wphr-modal">

        <span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'wphr' ); ?></span>
        <a href="#" class="close">× <span class="screen-reader-text"><?php _e( 'Close modal window', 'wphr' ); ?></span></a>

        <form action="" class="wphr-modal-form" method="post">
            <header class="modal-header">
                <h2>&nbsp;</h2>
            </header>

            <div class="content-container modal-footer">
                <div class="content"><?php _e( 'Loading', 'wphr' ); ?></div>
            </div>

            <footer>
                <ul>
                    <li>
                        <div class="wphr-loader wphr-hide"></div>
                    </li>
                    <li>
                        <span class="activate">
                            <button type="submit" class="button-primary"></button>
                        </span>
                    </li>
                </ul>
            </footer>
        </form>
    </div>
    <div class="wphr-modal-backdrop"></div>
</div>
