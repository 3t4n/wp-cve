<div class="box-congratulation key-block mt-5 d-flex justify-content-center flex-column gap-3 text-center" >
<!-- container p-4 main-block shadow-sm rounded d-flex justify-content-center flex-column gap-3 text-center	 -->

    <div class="confetti">
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
        <div class="confetti-piece"></div>
            
        <div>

            <div class="p-3">
                <a href="https://www.conveythis.com/" target="_blank">
                    <img src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/conveythis-logo-vertical-blue.png" alt="ConveyThis">
                </a>
            </div>

            <div class="text-body-tertiary">
                <h2>CONGRATULATION!</h2>
                <p>Your plugin has been activated!</p>
            </div>

            <div class="animation-image">
                
                <div class="main-image-front">
                    <img id="animatedImage" src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/header-design-2.webp" alt="Animated">
                </div>

                <div class="main-image-front">
                    <img id="animatedImageFront" src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/header-design-1.webp" alt="Animated">
                </div>
             
                <div class="main-image-front-back">
                    <img id="animatedImageFront" src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/header-design-3.webp" alt="Animated">
                </div>

            </div>

            <div class="btn-website-go">
                <a href="<?php echo home_url(); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary conveythis_new_user">Visit my translated website</a>
            </div>

            <div class="p-3 text-body-tertiary">
                <a href="#" class="conveythis_new_user">View more settings</a>
            </div>

        </div>

    </div>	

</div>

<script>
    let img = document.getElementById('animatedImage');
    let imgFront = document.getElementById('animatedImageFront');

    const shifts = [
        { x: -20, y: 0 },
        { x: 20, y: 0 },
        { x: 0, y: -20 },
        { x: 0, y: 20 },
        { x: -20, y: -20 },
        { x: 20, y: 20 }
    ];
    let currentIndex = 0;

    function jerkImage() {
        let shift = shifts[currentIndex];
        img.style.transform = `translate(${shift.x}px, ${shift.y}px)`;

        currentIndex = (currentIndex + 1) % shifts.length;
    }

    setInterval(jerkImage, 1000);

    function jerkImageFront() {
        let shift = shifts[currentIndex];
        imgFront.style.transform = `translate(${shift.x}px, ${shift.y}px)`;

        currentIndex = (currentIndex + 1) % shifts.length;
    }

    setInterval(jerkImageFront, 1000);
</script>




