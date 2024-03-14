<!-- note -->
<div class="wcpt-editor-row-option">
  <label class="wcpt-element-note">
    <p>
      This element allows you to enter supported 3rd party plugin integration shortcodes into your table. Please see list of special integration shortcodes below.
    </p>
    <p>
      Apart from the shortcodes listed below, not <em>every</em> shortcode can correctly work inside a WCPT table due to compatibility limitations. <a href="https://www.notion.so/List-of-3rd-party-plugins-compatible-with-WCPT-PRO-b6138e2590684cf49a198beb292aa3c1" target="_blank">See list</a> of known compatible plugins. If a shortcode you require is incompatible, please <a href="https://wcproducttable.com/support" target="_blank">contact support</a>. 
    </p>    
  </label>
</div>

<!-- shortcode -->
<div class="wcpt-editor-row-option">
  <label>Shortcode</label>
  <textarea wcpt-model-key="shortcode"></textarea>
  <label>
    <?php wcpt_general_placeholders__print_placeholders('shortcode'); ?>      
  </label>

  <span class="wcpt-built-in-shortcode-list-heading">
    Available shortcodes:
  </span>

  <ul class="wcpt-built-in-shortcode-list">
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_quick_view]</strong>
      <p>
        Create a quick view button in each product row. Requires either of these two plugins to be installed and activated on your site: <a href="https://woocommerce.com/products/woocommerce-quick-view/?aff=16345" target="_blank">Official WC Quick View</a>, <a href="https://wordpress.org/plugins/woo-quick-view/" target="_blank">Woo Quick View</a> or <a href="https://barn2.co.uk/wordpress-plugins/woocommerce-quick-view-pro/" target="_blank">Barn2 Quick View PRO</a>.
      </p>
      <p>
        If you are using either of these three compatible Quick View plugins on your you can also make the product table's Title or Product Image elements trigger the quick view as well. To do this, please modify your product table shortcode like so: [product_table id="123" quick_view_trigger="title, product image"]        
      </p>
      <p>
        For customizing the styling, label and other properties of the button please check your quick view plugin's documentation.
      </p>      
    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_player]</strong>
      <p>      
        Creates a compact audio player button to play / pause.
      </p>
      <p>        
        The 'src' shortcode attribute needs to contain the name of the custom field where you have entered the audio source url for each product.
      </p>
      <p>
        If you are not familiar with custom fields, then please check the <a href="https://www.notion.so/FAQs-f624e13d0d274a08ba176a98d6d79e1f" target="_blank">plugin FAQs</a> > Why are custom fields important? Where can I find the custom field on my site? How do I use them?
      </p>
      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">src="custom field name"</strong> <em>*This field is required*</em><br>
          Name of the custom field where you have stored the plain text url of the audio file.
        </li> 
        <li>
          <strong class="wcpt-auto-select-on-click">disable_loop="true"</strong><br>
          By default the track will loop unless this is used.
        </li> 
      </ul>

    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_video]</strong>      
      Wrapper for the wordpress native video player. It adds the facility to get the video url from a custom field or custom attribute of the product.

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">src="custom field name"</strong> <em>*This field is required*</em><br>
          Name of the custom field where you have stored the plain text url of the video file.
        </li> 
        <li>
          <strong class="wcpt-auto-select-on-click">width="300"</strong> <br>
          Width of the video iFrame
        </li> 
        <li>
          <strong class="wcpt-auto-select-on-click">height="170"</strong> <br>
          Height of the video iFrame
        </li>         
      </ul>      

      Steps to use this facility:
      <ol>
        <li>First, upload the video to your media library.</li>

        <li>Then store the uploaded video's url in a custom field in your product or a custom attribute.</li>

        <li>Enter [wcpt_video src="..."] in the empty shortcode input field above and replace src="..." with the custom field / custom attribute name. For example, if you have stored the video url in a custom field or custom attribute called 'Preview video' in each of your products, then you will enter src="Preview video" in the shortcode.</li>
      </ol>

    </li>
    <!-- <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_remove]</strong>
      Creates an 'X' remove button that can remove the row item from cart. The X button only appears after the item has been placed in cart by customer.
    </li>     -->
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_in_cart]</strong>
      Prints the quantity of the current product in cart.

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">template="({n})"</strong> <br>
          Prints the number in a template. Use {n} as placeholder for the sequence number.
        </li> 
      </ul>

    </li>

    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode" style="margin-top: 30px;">[wcpt_sequence]</strong>
      Prints a sequence number for each product. Begins from 1 and increments by 1 for each successive product in the results. 

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">template="#{n}"</strong> <br>
          Prints the sequence number in a template. Use {n} as placeholder for sequence number.
        </li> 
        <li>
          <strong class="wcpt-auto-select-on-click">min_digits="2"</strong> <br>
          If the product's sequence number has fewer digits than what you specific here then 0s will be prepended to the number. For example if min_digit="2" and sequence number is 6, it will be printed as 06.
        </li> 
      </ul>

    </li>

    <li>
        <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_modify_number]</strong>

        Useful for converting numbers like 1.2 to 1.200 (fixed decimal places) or 3 to 003 (minimum characters).
        <ul class="wcpt-built-in-shortcode__attributes">
          <li>
            <strong class="wcpt-auto-select-on-click">attribute="attribute name"</strong> <br>
            Enter the attribute name if the source of the number is an attribute. 
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">custom_field="custom field name"</strong> <br>
            Enter the custom field name if the source of the number is a custom field. 
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">decimal_places="2"</strong> <br>
            The total fixed decimal places the number needs to have.  
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">minimum_characters="6"</strong> <br>
            The minimum number of characters the number needs to have (including decimal if it is present). 
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">empty=""</strong> <br>
            This will be printed if there is no value.  
          </li>          
        </ul>
    </li>

    <!-- <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_total]</strong>
      Prints price * quantity box value. This facility only works with default woocomerce pricing rules. It cannot follow rules set by 3rd party pricing plugins such as bulk discounts or dynamic pricing.

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">include_total_in_cart="true"</strong> <br>
          Adds the product total already in the cart.
        </li> 
      </ul>

    </li> -->
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_variation_count]</strong>
      Prints the count of child variations if the current products type is a variable.

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">template="({n})"</strong>  <br>
          Prints the number in a template. Use {n} as placeholder for sequence number.
        </li> 
      </ul>

    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_translate default="text" en_US="US text" fr_FR="FR text"]</strong>
      <p>
        Use this for adding translations to any text that you enter in the table. Enter the default text in default="..." and then add a shortcode attribute with another language locale and add translation for that language (You can look up wordpress locale codes <a href="https://wcproducttable.com/wordpress-locale-codes" target="_blank">here</a>). If none of the  locale matches the visitor's locale code then the 'default' text will be used. This works outside the Shortcode element as well. 
      </p>
      <p>
        For example, the above shortcode will output the word 'text' by default. But if the site visitor's location is detected to be in USA the output will be 'US text' and if they are in France it will be 'FR text'. You can add as many translations to the shortcode as you need.
      </p>
    </li>
    <!-- <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_gallery image_width="40" max_images="3" see_more_label=" +{n} more" include_featured="true"]</strong>
      Prints a strip of gallery image thumbnails for the product that open in a lightbox when clicked. 
    </li> -->
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_wcfm_store]</strong>
      Prints the product's store name if you are using the WCFM plugin.

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">link="true"</strong>  <br>
          Turns the name into a link to the store.
        </li> 
        <li>
          <strong class="wcpt-auto-select-on-click">open_new_page="true"</strong>  <br>
          Opens the link on a new page.
        </li> 
      </ul>

    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_waveplayer]</strong>
      <p>
      This shortcode is a wrapper for the waveplayer plugin shortcode. You can use all the permitted shortcode attributes for <a href="https://www.waveplayer.info/shortcode/" target="_blank">[waveplayer]</a> here as well.
      </p>
      <p>
      This shortcode will auto-fill the waveplayer's urls="..." attribute based on the current product.
      </p>
      <p>
      Note: The skin "thumb_n_wave" will not display in the product table, so please ensure you use another skin instead.
      </p>

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">width="300"</strong> <br>
          This is one way to enter width. The waveplayer will have a fixed width across screen sizes. 
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">width="0 - 349: 200 | 350 - 599 : 300 | 600 - 1199 : 500 | 1200+ : 550"</strong> <br>
          This alternate method allows you to set resposive width rules based on browser screen width.  The format is min - max : val | ... where min is the minimum screen width for the rule, max is the maximum screen width and the val is the width of the waveplayer when screen width is between min and max. Use | to separate multiple rules.
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">file_name="60 bpm preview | 120 bpm preview"</strong> <br>
          Use this if you want to show audio preview for the specific file name(s) only in the waveplayer. You can use one or more preview file names, separated by "|" 
        </li>        
        
        <li>
          <strong class="wcpt-auto-select-on-click">active_row_background_color="#aaa"</strong> <br>
          Highlight the product row with a unqiue background color when its waveplayer is playing.
        </li>        

        <li>
          <strong class="wcpt-auto-select-on-click">active_row_outline_color="#4198de"</strong> <br>
          Specify a color for the outline around the highlighted row where waveplayer is playing.
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">active_row_outline_width="1"</strong> <br>
          Specify a width for the outline around the highlighted row where waveplayer is playing. Default is 1 px.
        </li>        

      </ul>
    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_sonaar]</strong>
      This integration is for the Sonaar theme footer audio player, not the Sonaar audio player plugin.<br>
      If you are using a Sonaar theme WCPT will automatically detect the playlist attached to the Footer player of the product.
    </li>
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_yith_quote]</strong>
      Use for adding a 'Request a Quote' button. This shortcode needs the premium <a href="https://yithemes.com/themes/plugins/yith-woocommerce-request-a-quote/?refer_id=1085714" target="_blank">Yith Request a Quote</a> plugin to be installed on your site. <br>

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click">add_to_quote="Add to quote"</strong> 
          <p>
            To add translations for this label, enter a separate shortcode attribute in the format add_to_quote_*language code*="*translation in that language*".
          </p>
          <p>          
            For example: product_added_fr_FR="Produit ajouté" will show the French code if fr_FR (French) locale is detected.
          </p>
          <p>          
            You can see the list of wordpress locale codes like fr_FR (France), en_US (USA), etc <a href="https://wcproducttable.com/wordpress-locale-codes" target="_blank">here</a>. 
          </p>
        </li> 

        <li>
          <strong class="wcpt-auto-select-on-click">product_added="Product added"</strong> <br>
          See above translation note.
        </li>   

        <li>
          <strong class="wcpt-auto-select-on-click">style="Button"</strong> <br>
          Style for the button. Can be: icon, link or button.
        </li>           

        <li>
          <strong class="wcpt-auto-select-on-click">default_image="url"</strong> <br>
          You can use this if you need to replace the 'adding' icon that appears in the Button with a custom image.
        </li>     

        <li>
          <strong class="wcpt-auto-select-on-click">default_image_width="15"</strong> <br>
          Set a width for your custom icon image in the Button.
        </li>       

        <li>
          <strong class="wcpt-auto-select-on-click">adding_image="url"</strong> <br>
          You can use this if you need to replace the 'adding' icon that appears in the Button with a custom image.
        </li>     

        <li>
          <strong class="wcpt-auto-select-on-click">adding_image_width="15"</strong> <br>
          Set a width for your custom 'adding' icon image in the Button.
        </li>         

        <li>
          <strong class="wcpt-auto-select-on-click">added_image="url"</strong> <br>
          You can use this if you need to replace the 'successfully added' icon that appears in the Button with a custom image.
        </li>     

        <li>
          <strong class="wcpt-auto-select-on-click">added_image_width="15"</strong> <br>
          Set a width for your custom 'successfully added' icon image in the Button.
        </li>           

      </ul>

    </li>  

    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_wholesale]</strong>
      This integration shortcode works with the plugin <a href="https://wordpress.org/plugins/woocommerce-wholesale-prices/" target="_blank">Wholesale Prices for WooCommerce by Wholesale Suite</a>. Without the wholesale plugin installed on your site, this shortcode will not output anything. This shortcode is capable of printing multiple wholesale properties in your table as explained below.<br>

      <ul class="wcpt-built-in-shortcode__attributes">
        <li>
          <strong class="wcpt-auto-select-on-click" style="margin-bottom: .5em">output="wholesale_price"</strong> 
          <p>
          Permitted values for the 'output' option:
          </p>
          <ul style="padding-left: 2em;">
            <li> <strong>wholesale_price</strong> Prints the wholesale price </li>
            <li> <strong>original_price</strong> Prints the original price </li>
            <li> <strong>discount_table</strong> Prints the quantity discount table </li>
            <li> <strong>wholesale_label</strong> Indicate whether a product is on wholesale or not. Along with this you can also use: on_wholesale_label="On wholesale!" not_on_wholesale_label="Not on wholesale!" to change the labels</li>
          </ul>
        </li> 
      </ul>

    </li>     
      
    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_upcoming_message] [wcpt_upcoming_date]</strong>
      Can be used with the <a href="https://wordpress.org/plugins/woocommerce-upcoming-product/" target="_blank">woocommerce upcoming products</a> plugin to show the message or date from your settings in that plugin. 
    </li>

    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_stock_valuation]</strong>
      This will print the total stock quantity for product * price of product, giving a valuaton of the entire product stock in the shop.
    </li>

    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_wishlist]</strong>
      Prints a button to add product to wishlist. This requires the free version of the <a href="https://wordpress.org/plugins/ti-woocommerce-wishlist/"  target="_blank">TI WooCommerce Wishlist</a> plugin installed and configured on your site.<br> 
      Note: Curently this integration <em>only works with the free version</em> of TI Wishlist. The paid version breaks this integrations.<br> 
      Shortcode attributes (click to select): <br>

      <ul class="wcpt-built-in-shortcode__attributes">

        <li>
          <strong class="wcpt-auto-select-on-click">variable_permitted="true"</strong> <br>
          By default TI WooCommerce Wishlis will not allow variable products to be added to wishlist. It only permits the variations to be added. Using this option you can override this behaviour and add the entire variable product to wishlist if you need.
        </li> 

        <li>
          <strong class="wcpt-auto-select-on-click">custom_url="..."</strong> <br>
          Visitor can be redirected to this custom url instead of the default wishlist page when they click on the 'View Wishlist' notification that appears when product is added to wishlist in the table. 
        </li>         

        <li>
          <strong class="wcpt-auto-select-on-click">icon="heart"</strong> <br>
          2 possible values: heart, playlist
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">add_title="Add to wishlist"</strong> <br>
          This is the HTML title attribute that appears when user hovers mouse on the add to wishlist icon 
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">remove_title="Remove from wishlist"</strong> <br>
          This is the HTML title attribute that appears on mouse hover when item is already in wishlist
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">icon_font_size="20"</strong> <br>
          Enter px value of icon font size
        </li>  

        <li>
          <strong class="wcpt-auto-select-on-click">icon_color="#999"</strong> <br>
          Icon stroke color
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">icon_fill="#fff"</strong> <br>
          Icon background color
        </li>  

        <li>
          <strong class="wcpt-auto-select-on-click">active_icon_color="#000"</strong> <br>
          Icon stroke color when item is in wishlist
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">active_icon_fill="#000"</strong> <br>
          Icon background color when item is in wishlist
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">item_added_label="{n} added to wishlist"</strong> <br>
          This is the text for notification that appears when item is added to wishlit. {n} is placeholder for product name.<br>
          To add translations for this message, enter a separate shortcode attribute in the format item_added_label_*language code*="*translation in that language*". <br>
          For example: item_added_label_fr_FR="{n} Ajouté à wishlit" <br>
          You can see the list of wordpress locale codes like fr_FR (France), en_US (USA), etc <a href="https://wcproducttable.com/wordpress-locale-codes" target="_blank">here</a>. 
        </li> 

        <li>
          <strong class="wcpt-auto-select-on-click">view_wishlist_label="View wishlist"</strong> <br>
          In the same text notification, controls the labels for the wishlist link.<br>
          Use same translations method here as noted above.
        </li>

        <li>
          <strong class="wcpt-auto-select-on-click">duration="4"</strong> <br>
          The number of seconds for which the 'Added to wishlist' notification will stay visible before disappearing. 
        </li>

      </ul>

    </li>



    <li>
        <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_name_your_price field="input"]</strong>
        You need to install the official woocommerce <a href="https://woocommerce.com/products/name-your-price/?aff=16345" target="_blank">Name Your Price plugin</a> on your site to use this integration.<br> 
        Shortcode attributes (click to select):<br>
        <ul class="wcpt-built-in-shortcode__attributes">
          <li>
            <strong class="wcpt-auto-select-on-click">field="input"</strong> <br>
            Permitted values: input, minimum, maximum, suggested. <br>For example [wcpt_name_your_price field="input"] will print the input field, while [wcpt_name_your_price field="minimum"] will print out the minimum permitted price you have set for the product. 
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">initial_value="suggested"</strong> <br>
            You can use this to pre-enter a value into the input field. Permitted values: minimum, maximum, suggested. <br>For example [wcpt_name_your_price field="input" initial_value="suggested"] will print the input field with suggested price in it. 
          </li>
          <li>
            <strong class="wcpt-auto-select-on-click">template="min: {n}"</strong>  <br>
            You can use this option if you want to create output template for any of the price fields (minimum, maximum, suggested).<br> For example [wcpt_name_your_price field="minimum" template="min. required: {n}"] will print "min. required: $5" in the table (if $5 is minimum permitted price in your 'name your price' plugin settings).
          </li>
        </ul>
        Please note, you can use this shortcode at multiple places in your table to print out different 'name your price' elements (input, minimum price, maximum price, suggested price) at different locations in your table. Just change the field option to show the field you want. 
    </li>

    <li>
      <strong class="wcpt-auto-select-on-click wcpt-built-in-shortcode">[wcpt_wcmp_preview]</strong>
      If you are using Music player for WooCommerce plugin you can use this shortcode to print the first demo audio file entered in the product's settings. For this shortcode to work, you need to enable the options 'Include music player' and 'Include in: all pages (with single or multiple-entries)' in the product's settings > Music player for WooCommerce. 
    </li>    


  </ul>

</div>

<!-- style -->
<?php include( 'style/common.php' ); ?>

<!-- condition -->
<?php include( 'condition/outer.php' ); ?>
