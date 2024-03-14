<?php
global $wp_roles;
$this->isConnected = $this->checkIsConnected();
?>

<div class="cloudFiltPlugin">
    <div class="cloudFiltPlugin__wrapper">
        <div class="cloudFiltPlugin__head">
            <h1 class="cloudFiltPlugin__head__logo">
                <a href="https://app.cloudfilt.com/" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__); ?>../img/cloudFiltLogo.png" class="logoCloudFilt"></a>
            </h1>
            <div class="cloudFiltPlugin__head__description">
                <p>
                    To use CloudFilt to prevent, block, and protect you against bots, you must provide the following information.
                    <br />
                    You will find the information needed to validate your site at <a href="https://app.cloudfilt.com/websites" target="_blank">https://app.cloudfilt.com/websites</a>. Go to Settings > Integration & Plugins.
                </p>
            </div>
        </div>

        <?php if(isset($this->isConnected)) { ?>
            <?php if($this->isConnected) { ?>
                <div class="cloudFiltPlugin__alert cloudFiltPlugin__alert--info">
                    CloudFilt is installed. To config and consult your dashboard go to <a href="https://app.cloudfilt.com/" target="_blank">https://app.cloudfilt.com/</a> and select your site.
                </div>
            <?php } else { ?>
                <div class="cloudFiltPlugin__alert cloudFiltPlugin__alert--warning">
                    Your site is no longer connected to CloudFilt. Update your keys.
                </div>
            <?php } ?>
        <?php } ?>

        <div class="cloudFiltPlugin__form">
            <?php if($this->showMessage === -1) { ?>
                <div class="cloudFiltPlugin__alert cloudFiltPlugin__alert--danger">
                    An error occurred.
                    <br /><br />
                    <?php if(!empty($this->error)) { ?>
                        <b>Details:</b>
                        <br /> -
                        <?php echo implode('<br /> - ', $this->error); ?>
                    <?php } ?>
                </div>
            <?php } else if($this->showMessage === 1) { ?>
                <div class="cloudFiltPlugin__alert cloudFiltPlugin__alert--success">
                    The keys have been successfully registred. You can now access security tracking on CloudFilt.
                </div>
            <?php } ?>

            <form method="POST">
                <?php
                    settings_fields('cloudfilt_codes_settings');
                    do_settings_sections('cloudfilt_codes_settings');
                ?>
                <?php foreach ($this->fieldsSettings as $fieldName => $fieldLabel) : ?>
                    <?php $value = esc_attr(get_option($this->fieldsPrefix . $fieldName)); ?>
                    <input
                            type="text"
                            placeholder="<?php echo $fieldLabel; ?>"
                            value="<?php echo $value ? $value : ''; ?>"
                            name="<?php echo $this->fieldsPrefix . $fieldName; ?>"
                            class="form-control"
                            required
                    />
                <?php endforeach; ?>

                <?php
                  $enabled = get_option( $this->fieldsPrefix.'restrict' );

                  $check = ($enabled == 'on') ? 'checked' : '';

                ?>

                <div style="margin-bottom: 25px; text-align:left;">
                  <label class="toggle-check">
                    Restrict checking by role
                    <input  style="display:none" type="checkbox" class="toggle-check-input" name="restrict" <?php echo $check; ?> />
                    <span class="toggle-check-text"></span>
                  </label>
                </div>
                <?php
                  $option = get_option($this->fieldsPrefix.'exclude_options');
                 ?>
                <div class="select-roles">
				  <span style="float:left">Do not check the following roles</span>
                  <select class="cloudfilt-exroles" multiple="multiple" name="exclude_options[roles][]">
                     <?php

                     $all_roles = $wp_roles->roles;
                     foreach ($all_roles as $role) { ?>
                     <?php if(!empty($option['roles'])){$selected = in_array( $role['name'], $option['roles'] ) ? ' selected="selected" ' : ''; }?>

                         <option value="<?php echo $role['name']; ?>" <?php echo $selected; ?> >
                            <?php echo $role['name']; ?>
                         </option>
                     <?php } //endforeach ?>
                  </select>
                </div>
                <input type="submit" value="Save changes" class="cloudFiltPlugin__form__button">
            </form>
            <script>

              jQuery(document).ready(function() {

                if(!jQuery('.toggle-check-input').is(':checked')){
                  jQuery('.select-roles').hide();
                }

                jQuery(".toggle-check-input").click(function(){

                  if(jQuery('.toggle-check-input').is(':checked')){
                    jQuery('.select-roles').show();
                  }else{
                    jQuery('.select-roles').hide();
                  }
                });

                jQuery('.cloudfilt-exroles').select2({width:'100%', height:'40px', placeholder: "Excluded Roles",});
              });
            </script>
        </div>
    </div>
</div>