<?php defined( 'ABSPATH' ) || exit;?>

<div class="emagic">
    <div class="ep-exts-bundle-banner ep-box-wrap ep-text-center ep-mt-5">
        <a href="https://theeventprime.com/all-extensions/" target="_blank" class="ep-inline-block">
            <img class="ep-extension-bundle" alt="<?php echo esc_attr( 'EventPrime Extension Bundle');?>" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/ep-extension-banner.png'); ?>" >
        </a>
    </div>

    <div class="ep-extensions-filters ep-box-wrap ep-mt-5">
        <div class="ep-box-row ep-mb-3">    
            <div class="ep-box-col-12 ">
                <div class="ep-ext-list-title ep-fw-bold ep-fs-5 ep-d-flex ep-align-items-center ep-content-center ">
                    <span class="ep-px-2"><?php echo esc_html('Extensions'); ?></span>
                </div>
            </div>
        </div>
        
        <div class="ep-box-row">
            <div class="ep-box-col-12">
                <div class="ep-extension-filters-wrap ep-d-flex ep-content-left ep-align-items-center ep-mb-3">
                    <span class="ep-filter-lable ep-fw-bold ep-mr-2">Filter</span>
                    <ul id="ep-ext-controls" class="ep-d-flex ep-align-items-center ep-m-0 ep-p-0">
                        <li class="ep-m-0 ep-mr-1"><a href="#" id="all-extensions" class="ep-extension-list-active">All</a></li>
                        <li class="ep-m-0 ep-mr-1"><a href="#" id="paid-extensions">Paid</a></li>
                        <li class="ep-m-0 ep-mr-1"><a href="#" id="free-extensions">Free</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="ep-extensions-box-wrap ep-box-wrap">
        <div class="ep-box-row ep-g-4">
            <?php $ext_list = ep_list_all_exts();
            foreach ( $ext_list as $ext ) {
                $ext_details = em_get_more_extension_data($ext);?>
                <div class="ep-box-col-3 ep-box-col-md-4 ep-box-col-sm-6  ep-ext-card <?php echo ( isset( $ext_details['is_free'] ) && $ext_details['is_free'] == 0 ) ? 'paid-extensions' : 'free-extensions';?>">
                    <div class="ep-card ep-text-small ep-box-h-100">
                        <div class="ep-card-body">
                            <div class="ep-box-row ep-box-h-100">
                                <div class="ep-box-col-3 ep-position-relative">
                                    <?php if( isset( $ext_details['is_free'] ) && $ext_details['is_free'] == 1){?>
                                    <div class="ep-text-small ep-position-absolute">
                                        <div class="ep-free-tag ep-overflow-hidden ep-text-small ep-text-white ep-bg-success ep-rounded ep-px-1 ep-py-1 ep-position-relative ep-border ep-border-white">
                                        <span class="material-icons ep-fs-6 ep-align-middle">new_releases</span>
                                        <span class="ep-fw-bold">Free</span>
                                        <div class="ep-free-spark ep-bg-white ep-position-absolute ep-border ep-border-white ep-border-3">wqdwqd</div>
                                        </div>
                                    </div><?php }?>
                                    <div class="ep-ext-box-icon ep-sm-text-center ep-xsm-text-center  ep-mb-2"><?php
                                        if( ! empty( $ext_details['image'] ) ) {?>
                                            <img class="ep-ext-icon ep-img-fluid" alt="<?php echo esc_attr( $ext );?>" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/'.$ext_details['image'] ); ?>" ><?php
                                        }?>
                                    </div>
                                </div> 
                                
                                <div class="ep-box-col-9">
                                    <div class="ep-card-title ep-fs-6 ep-md-text-start ep-sm-text-center ep-xsm-text-center ep-fw-bold ep-mb-1"> <?php echo esc_html( $ext );?></div>
                                    <div class="ep-ext-box-description">
                                        <p class="ep-col-desc"><?php 
                                        if( ! empty( $ext_details['desc'] ) ) {
                                            echo esc_html( $ext_details['desc'] );
                                        }?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ep-card-footer ep-d-flex ep-justify-content-between ep-py-2 ep-bg-white">
                            <?php 
                            if( $ext_details['button'] == 'Activate' ){?>
                                <span class="ep-text-danger ep-ext-not-installed">Not Activated</span><?php
                            }
                            else if( $ext_details['is_activate'] ) {?>
                                <span class="ep-text-muted ep-ext-installed"> Installed</span><?php
                            } else{?>
                                <span class="ep-text-muted ep-ext-not-installed">Not Installed</span><?php
                            }
                            
                            if( ! empty( $ext_details['button'] ) ) {?>
                                <a href="<?php echo esc_url( $ext_details['url'] );?>" class="" target="_blank"><?php echo $ext_details['button'];?></a><?php
                            }?>
                        </div>
                    </div>
                </div><?php
            }?>
        </div>
    </div>
    
    <div class="ep-exts-bundle-banner ep-box-wrap ep-text-center ep-mt-5">
        <a href="https://theeventprime.com/all-extensions/" target="_blank" class="ep-inline-block">
            <img  class="ep-extension-bundle" alt="<?php echo esc_attr( 'EventPrime Extension Bundle');?>" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/ep-extension-banner.png'); ?>" >
        </a>
    </div>
    
</div>

<style>
    /*--Extension Page CSS--*/

    .emagic span.ep-filter-lable {
        color: #283237;
        font-size: 14px;
        font-weight: 500;
        margin-right: 5px;
    }

    .emagic ul#ep-ext-controls li {

    }    

    .emagic ul#ep-ext-controls li a {
        border: none;
        outline: none;
        padding: 6px 16px;
        background-color: #DCDCDC;
        cursor: pointer;
        border-radius: 4px;
        color: #6B7262;
        box-shadow: none;
        transition: all ease 0.5s;
    }
    
.emagic ul#ep-ext-controls li a.ep-extension-list-active,
.emagic ul#ep-ext-controls li a:hover{
    background-color: #2371b1;
    color: #ffffff;
}

    .emagic #ep-ext-controls li a {
        text-decoration: none;
    }

    .ep-free-spark {
        animation: freespark 2s linear forwards normal infinite;
        height: 30px;
        transform: rotate(45deg);
        opacity: 0.5;
        filter: blur(8px);
        bottom: 0;
    }

    .ep-free-tag {
        left: -10px;
        top: -10px;
        min-width: 50px;
    }

    .ep-free-tag .material-icons {
        opacity: 0.5;
    } 
    
    img.ep-extension-bundle{
        width: 100%;
        max-width: 100%;
    }
    
    .ep-ext-box-icon img.ep-ext-icon{
        width: 100px;
        min-width: 50px;
    }

    @keyframes freespark {
        from {transform: translateX(-200px) rotate(45deg);}
        to {transform: translateX(200px) rotate(45deg);}
    }

    /*--Extension Page CSS End--*/
    
</style>