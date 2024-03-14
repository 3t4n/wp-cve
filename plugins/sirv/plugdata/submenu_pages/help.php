<script>
  jQuery(document).ready(function(){
    setTimeout(function() {
      jQuery('.sirv-help-menu').scrollSpy();
    }, 300);
  });
</script>

<style>
  .sirv-help-wrapper img.card{
    padding: 0;
    border: none;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>

<!-- <div class="header" style="margin-top: 25px;">
  <h1>Help</h1>
</div> -->
<div class="sirv-help-wrapper">
  <div class="sirv-help-menu">
    <ul class="sirv-nav">
      <li class="active"><a href="#sirv-help-about">About Sirv</a></li>
      <li><a href="#sirv-help-sync">Sync your Media Library</a></li>
      <li><a href="#sirv-help-upload-images">Upload images</a></li>
      <li><a href="#sirv-help-static">Embed static images</a></li>
      <li><a href="#sirv-help-responsive">Embed responsive images</a></li>
      <li><a href="#sirv-help-gallery">Embed image galleries</a></li>
      <li><a href="#sirv-help-zoom-image">Embed zoom images</a></li>
      <li><a href="#sirv-help-zoom-galleries">Embed zoom galleries</a></li>
      <li><a href="#sirv-help-spin">Embed 360 spins</a></li>
      <li><a href="#sirv-help-zoom-and-spin">Embed spins & zooms</a></li>
      <li><a href="#sirv-help-serve-other-files">Serve other files</a></li>
      <li><a href="#sirv-help-learn-more">Learn more about Sirv</a></li>
    </ul>
  </div>
  <div class="sirv-help-data">
    <a class="sirv-anchor-help" id="sirv-help-about"></a>
    <h2>About Sirv</h2>

    <p>Sirv is an image hosting, processing and optimisation service which intelligently serves the most optimal image to each user.</p>

    <p>Best-practice in every way:
      <ul style="list-style: disc; margin-left: 25px;">
        <li>Responsive image resizing.</li>
        <li>Outstanding image optimisation.</li>
        <li>Optimal image format (including WebP).</li>
        <li>Lazy loading.</li>
        <li>CDN delivery from servers all around the world.</li>
        <li>HTTP/2 and TLS1.3 for fast, secure delivery.</li>
      </ul>
    </p>

    <a class="sirv-anchor-help" id="sirv-help-sync"></a>
    <h2>Sync your Media Library</h2>

    <p>Sirv can serve your WordPress media library faster than your own server can.</p>

    <p>First, it synchronizes your media library to your Sirv account. Then it serves your images from Sirv instead of your server.</p>

    <p>Images are perfectly sized and incredibly well optimized. They are delivered to each visitor by the closest server on Sirv's fast global CDN.</p>

    <p>1. Enable the Sirv CDN:</p>

    <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/simply-enable-the-Sirv-CDN.png?profile=wp-plugin-help" class="card" alt=""></p>

    <p>2. Sirv will fetch the images from your server the first time they are requested.</p>

    <p>Images will automatically stay in sync whenever you upload new images.</p>

    <p>3. Go to the Synchronization tab to check the status. To trigger a full sync of all images, click the <strong>Sync Images</strong> button:</p>

    <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/check-the-status-of-synchronization.png?profile=wp-plugin-help" class="card" alt=""></p>

    <h2>
      <a class="sirv-anchor-help" id="sirv-help-upload-images"></a>
      Upload images
    </h2>

    <p>You can either upload images to the WordPress media library (and Sirv will sync them if you enable the CDN) or you can upload images directly to your Sirv media library.</p>

    <p>Upload directly to Sirv in various ways:</p>

    <ol>
      <li>Browser</li>
      <li>FTP</li>
      <li>S3</li>
    </ol>

    <p>To upload through your browser, either drag and drop images into your <a href="admin.php?page=<?php echo SIRV_PLUGIN_RELATIVE_SUBDIR_PATH; ?>media_library.php" target="_blank">Sirv Media Library</a>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/upload-through-your-browser.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>You can also upload images after clicking <strong>Add Sirv Media</strong> from any page/post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-Sirv-Media-from-any-page.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Upload by FTP by copying <a href="https://my.sirv.com/#/account/settings" target="_blank">your Sirv FTP settings</a>. Either <a href="https://sirv.com/help/resources/upload-images-with-filezilla/" target="_blank">configure FileZilla</a> or another FTP program.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-static"></a>
        Embed static images
      </h2>

      <p>Static images are fixed in width or height. Whatever width or height you choose, Sirv will generate a new perfectly sized image on-the-fly. You can also add options to crop, change the canvas, add watermarks, add text, rotate, adjust colours, vignette and borders. See examples of all the <a href="https://sirv.com/help/resources/dynamic-imaging/%5Ddynamic" target="_blank">dynamic</a> imaging options[/a].</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the image(s) you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/static-image-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/static-image-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. You will see your image(s) embedded in your page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/static-image-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the image(s) in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/static-image-save-and-enjoy.png?profile=wp-plugin-help" class="card" alt=""></p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-responsive"></a>
        Embed responsive images
      </h2>

      <p>Responsive images are perfectly resized to fit the screen. During page load, Sirv detects the users' device, browser and screen size and generates an ideal image. This prevents images being larger than necessary and speeds up page loading.</p>

      <p>Images can also be lazy loaded, if they come into view. This can also reduce the total size of your page significantly and speed up loading.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the image(s) you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/responsive-images-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/responsive-images-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. You will see your image(s) embedded in your page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/responsive-images-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the image(s) in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/responsive-images-save-and-enjoy.png?profile=wp-plugin-help" class="card" alt=""></p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-gallery"></a>
        Embed image galleries
      </h2>

      <p>Image galleries are a great way to display lots of images. Galleries have one large image and lots of small images. Click the small images to swap the large image.</p>

      <p>Images are dynamically generated on-the-fly, to perfectly fit the users screen - not too big, not too small. Served over Sirv's CDN, its a fast and easy way to serve beautiful photo galleries.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the image(s) you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-gallery-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-gallery-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. You will see your images embedded in your page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-gallery-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the images in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-gallery-save-and-enjoy.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Get some inspiration! Check out <a href="https://sirv.com/demos/" target="_blank">11 examples</a> of zoom, responsive and 360 spin images using Sirv.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-zoom-image"></a>
        Embed zoom images
      </h2>

      <p>Sirv Zoom quickly zooms deep inside large images. The bigger your image, the better. They always load fast - even huge images - because of the way Sirv generates hundreds of tiny square images. Just like Google Maps, you can zoom and pan any image, effortlessly.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the image you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-zoom-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-zoom-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. Your image will show as a gallery. You can edit it with the settings icon and delete it with the X icon.</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-zoom-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the image in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/image-zoom-save-and-enjoy.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Get some inspiration! Check out <a href="https://sirv.com/demos/" target="_blank">11 examples</a> of zoom, responsive and 360 spin images using Sirv.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-zoom-galleries"></a>
        Embed zoom galleries
      </h2>

      <p>Sirv Zoom images can be embedded as a gallery. Perfectly for displaying lots of high resolution images, it has a stunning full-screen option that enlarges your image to the entire screen. Click between thumbnails to swap the images.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the images you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/zoom-gallery-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/zoom-gallery-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. Your images will show as a gallery, with arrows to navigate between thumbnails. You can edit it with the settings icon and delete it with the X icon.</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/zoom-gallery-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the images in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/zoom-gallery-save-and-enjoy.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Get some inspiration! Check out <a href="https://sirv.com/demos/" target="_blank">11 examples</a> of zoom, responsive and 360 spin images using Sirv.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-spin"></a>
        Embed 360 spins
      </h2>

      <p>Sirv Spin is the ultimate way to embed 360 spinning images in your site. Images automatically scale to fit the page or can be embedded at a fixed size. They load fast and can contain watermarks, text overlays and image effects.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the spin you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/360-spin-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/360-spin-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. Your spin will show as a gallery. You can edit it with the settings icon and delete it with the X icon.</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/360-spin-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>5. Save your page and enjoy the spin in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/360-spin-save-and-enjoy.gif?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Get some inspiration! Check out <a href="https://sirv.com/demos/" target="_blank">11 examples</a> of zoom, responsive and 360 spin images using Sirv.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-zoom-and-spin"></a>
        Embed spins &amp; zooms
      </h2>

      <p>Show a mixture of zoomable images and 360 spin images. Sirv can create a gallery of images and display them as a main image with thumbnails to switch image. A great way to showcase products with 360 spins and highly detailed zooms.</p>

      <p>1. Click the <strong>Add Sirv Media</strong> button on a page or post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/add-sirv-media.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>2. Click the spins and images you wish to embed:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/spin-and-zoom-click-the-image.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>3. Choose your options and click Insert into page:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/spin-and-zoom-choose-your-options.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>4. Your spins and zooms will show as a gallery. You can edit it with the settings icon and delete it with the X icon.</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/spin-and-zoom-see-your-images.png?profile=wp-plugin-help" class="card" alt=""></p>

      <p>.5. Save your page and enjoy the spin/zoom gallery in your post:</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/spin-and-zoom-save-and-enjoy.gif?profile=wp-plugin-help" class="card" alt=""></p>

      <p>Get some inspiration! Check out <a href="https://sirv.com/demos/" target="_blank">11 examples</a> of zoom, responsive and 360 spin images using Sirv.</p>


      <h2>
        <a class="sirv-anchor-help" id="sirv-help-serve-other-files"></a>
        Serve other files
      </h2>

      <p>Rapidly serve your other files from Sirv too. It is designed to quickly deliver any static files over its global CDN using HTTPS to all your users around the world.</p>

      <p>All kinds of file can be served:</p>

      <ul>
        <li>CSS</li>
        <li>JS</li>
        <li>SVG</li>
        <li>ICO</li>
        <li>PDF</li>
        <li>CSV</li>
        <li>XML</li>
        <li>Fonts</li>
        <li>Documents</li>
        <li>Spreadsheets</li>
        <li>Presentations</li>
      </ul>

      <p>Upload your images to your Sirv account, then copy the CDN link and use it in your page.</p>

      <p><img src="https://sirv-cdn.sirv.com/website/screenshots/wordpress/serve-other-files.png?profile=wp-plugin-help" class="card" alt=""></p>

      <h2>
        <a class="sirv-anchor-help" id="sirv-help-learn-more"></a>
        Learn more about Sirv
      </h2>

      <p>Use your Sirv account for your other websites too (not just WordPress). Files hosted on Sirv can be served to any website including Magento, Drupal, Squarespace, Joomla, PrestaShop and custom built sites.</p>

      <p>Search our <a href="https://sirv.com/help" target="_blank">Help Center</a>, for tutorials that get the best out of Sirv.</p>

      <p>Popular articles:</p>

      <ul>
        <li><a href="https://sirv.com/help/resources/dynamic-imaging" target="_blank">Dynamic imaging guide</a> - for resizing, watermarking, optimizing and all other dynamic options.</li>
        <li><a href="https://sirv.com/help/resources/responsive-imaging/" target="_blank">Responsive imaging guide</a> - for serving images to perfectly fit each users screen.</li>
        <li><a href="https://sirv.com/help/resources/sirv-zoom/" target="_blank">Zoom guide</a> - for customizing your deep image zooms.</li>
        <li><a href="https://sirv.com/help/resources/sirv-spin/" target="_blank">360 guide</a> - for customizing your 360 spins.</li>
      </ul>
  </div>
</div>
