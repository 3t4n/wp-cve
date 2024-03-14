<script id="tmpl-aarambha-ds--demo__information" type="text/template">
    <div class="install--demo step--info">
        <div class="install-demo--body">

            <div class="install-demo--body__content d-flex justify-between flex-row">

                <div class="install-demo--body__screenshot" style="<# if(data.image) { #> background-image: url( {{ data.image }} ); <# } #>" >

                    <div class="aarambha-ds--item--name">
                        <h3>{{ data.name }} </h3>
                    </div>
                    
                </div>

                <div class="install-demo--body__steps">

                    <div class="install-demo--body__progress">

                        <div class="swal2-icon swal2-info swal2-icon-show" style="display: flex;">
                            <div class="swal2-icon-content">!</div>
                        </div>

                        <span>
                            <?php echo wp_kses_post( 'We suggest you to import demo on <strong>clean installation</strong> of WordPress.' ); ?></em>
                        </span>

                    </div>

                    <div class="install-demo--body__footer">
                        <a 
                            href="{{ data.preview }}" 
                            class="button-outline button-medium button-rounded"
                            target="_blank">
                            Preview
                        </a> 
                        <a 
                            href="#" 
                            class="button-main button-large button-rounded aarambha-ds--action__list" 
                            data-action="list-plugins" 
                            data-nonce="<?php echo esc_attr( wp_create_nonce('list-plugins') ); ?>"
                            data-target="install-demo--body__progress"
                            data-slug="{{ data.slug }}">
                            Continue
                        </a>    
                    </div>
                    
                </div>

                
            </div>

        </div>
    </div>
</script>