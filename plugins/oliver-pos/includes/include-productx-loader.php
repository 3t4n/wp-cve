<style>
    .loading{
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        display: block;
        opacity: 1;
        background-color: #fff;
        text-align: center;
        padding-top: 100px;
        z-index: 9;
    }
    .loader-fixed {
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
        top: 0px;
        z-index: 1050;
        background-color: #ffffff;
        color: #fff;
        align-items: center;
        justify-content: center;
        display: flex;
        text-align: center;
    }
    .loader-centered {
        min-width: 200px;
        background: #ffffff;
        color: #46A9D4;
        padding: 5px 0px;
        border-radius: 8px;
        font-weight: 500;
        font-family: "Poppins";
    }
    #wpadminbar{
        display: none;
    }
</style>
<?php
$loader_img= plugin_dir_url( dirname( __FILE__ ) ).'public/resource/img/loader.gif';
?>
<div id="loading">
    <div class="loader-fixed">
        <div class="loader-centered text-primary">
            <img src="<?php echo $loader_img;?>" alt="Loading">
            <div>
                <span class="">Loading</span>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</div>