<div
    id="loader"
    style="display: <?php echo (CONVEYTHIS_LOADER) ? 'block' : 'none'; ?>;width: 100%;height: 600px;background-color: rgba(255, 255, 255, 0.8);z-index: 1000;"
>

    <div
            style="	position: relative;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;"
    >

        <img
                style="position: absolute; width: 230px;"
                src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/loader-1.webp" alt="Loading" id="image1"
    >
        <img
                style="position: absolute; transform: translateX(45px);width: 100px;"
                src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/loader-2.webp" alt="Loading" id="image2"
    >
        <img
                style="position: absolute; transform: translateX(-90px);width: 80px;"
                src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/loader-3.webp" alt="Loading" id="image3"
    >

        <div
                class="box-loading"
                style="position: absolute;bottom: 100px;width: 100%;display: flex; flex-direction: column; justify-content: center;"
        >


            <div
                    class="loading"
                    style="justify-content: center;align-items: center;display: flex;"
            >
                <span
                        id="loadingDots"
                        style="font-size: 2em;height: 50px;line-height: 50px;text-align: center;"
                ></span>
            </div>

            <div style="width: 100%; text-align: center;">
                <p style="font-size: 15px; margin: 0; padding: 0;"><b>ConveyThis</b> - promote to the general public</p>
                <span style="font-size: 10px"><b>Plugin version:</b> <?php echo CONVEYTHIS_PLUGIN_VERSION ?></span>
            </div>


        </div>


    </div>

</div>