<div class="modal fade" id="wpraFeedbackForm" tabindex="-1" role="dialog" aria-labelledby="wpraFeedbackFormlLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-600" id="wpraFeedbackFormLabel" style="color: #303030;"><?php _e('Your feedback is important to us', 'wpreactions-lite'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 30px;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group text-center">
                        <p><?php _e('What do you think of this plugin?', 'wpreactions-lite'); ?></p>
                        <div class="feedback-ratings">
                            <div class="rating-item"
                                 data-label="Very Bad">
                            </div>
                            <div class="rating-item"
                                 data-label="Bad">
                            </div>
                            <div class="rating-item"
                                 data-label="Good">
                            </div>
                            <div class="rating-item"
                                 data-label="Very Good">
                            </div>
                            <div class="rating-item"
                                 data-label="Love">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="feedback-message" class="col-form-label"><?php _e('Your Feedback', 'wpreactions-lite'); ?></label>
                        <textarea class="form-control" id="feedback-message" style="height: 100px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="feedback-email" class="col-form-label"><?php _e('Email (optional)', 'wpreactions-lite'); ?></label>
                        <input type="text" class="form-control" id="feedback-email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-purple wpra-submit-feedback"><?php _e('Send feedback', 'wpreactions-lite'); ?></button>
            </div>
        </div>
    </div>
</div>