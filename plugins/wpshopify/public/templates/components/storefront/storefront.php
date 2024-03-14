<style>

    .shopwp-skeleton-storefront {
        width: 100%;
        max-width: 1400px;
        margin-top: 40px;
    }

    .shopwp-skeleton-component {
        margin-bottom: 13px;
        border-radius: 15px;
        background: #eee;
        animation: shimmer 0.4s ease-out 0s alternate infinite none running;
    }

    .shopwp-skeleton-row {
        display: flex;
    }

    .shopwp-skeleton-row-center {
        justify-content: center;
    }

    .shopwp-skeleton-storefront-payload {
        width: 100%;
        justify-content: space-between;
    }

    .shopwp-skeleton-storefront-options {
        width: 300px;
        height: 400px;
        display: flex;
        flex-direction: column;
        margin-right: 25px;
    }      

    .shopwp-skeleton-storefront-option {
        width: 100%;
        height: 20px;
        margin-bottom: 10px;
    }

    .shopwp-skeleton-storefront-sort-group {
        width: 98.5%;
        display: flex;
        justify-content: flex-end;
    }

    .shopwp-skeleton-storefront-sort {
        width: 100px;
        height: 20px;
        margin-left: 20px;
    }

    .shopwp-skeleton-pagination {
        width: 200px;
        height: 42px;
    }


    .shopwp-skeleton-storefront-payload {
        display: flex;
    }

    .shopwp-col {
        flex: 1;
    }

    .shopwp-skeleton-product {
        width: 33.3%;
    }

    .shopwp-skeleton-product-image {
        width: 94%;
        height: 150px;
    }

    .shopwp-skeleton-product-title {
        width: 200px;
        height: 20px;
    }

    .shopwp-skeleton-product-price {
        width: 160px;
        height: 20px;
    }      

    .shopwp-skeleton-product-buy-button {
        width: 300px;
    }

    .shopwp-skeleton-product-buy-button-value {
        width: 30%;
        max-width: 30%;
        flex: 0 0 30%;
        height: 20px;
    }

    .shopwp-skeleton-product-buy-button-cta {
        width: 300px;
        height: 40px;
    }






    @keyframes shimmer {
        0% {
            opacity: 0.6;
        }

        100% {
            opacity: 1;
        }
    }

    .shopwp-row {
        display: flex;
        align-items: flex-start;
    }

    .show-row-flex-end {
        justify-content: flex-end;
    }

    .shopwp-col {
        display: flex;
        flex-direction: column;
    }

    #shopwp-storefront {
        max-width: 1500px;
        width: 100%;
        padding-top: 0;
        margin: 20px auto 0 auto;
    }

    #shopwp-storefront-options {
        width: 300px;
        position: sticky;
        top: 25px;
        margin-right: 30px;
    }

    #shopwp-storefront-payload,
    #shopwp-storefront-selections {
        flex: 1;
    }

    #shopwp-storefront-selections {
        display: flex;
        max-width: 50%;
        flex-wrap: wrap;
    }

    #shopwp-selectors {
        display: flex;
        min-height: 52px;
        width: 100%;
        padding: 0;
        margin: 0 0 -10px 0;
        justify-content: space-between;
    }

    #shopwp-storefront-controls {
        width: 400px;
    }

    #shopwp-storefront-payload {
        margin-top: 20px;
        
    }

    #shopwp-storefront-content {
        flex: 1;
        position: relative;
    }

    #shopwp-storefront-dropzone-page-size {
        margin-left: 30px;
    }

    #shopwp-storefront-page-size {
        margin-left: 15px;
    }

    /*
    
    Needed to fix position stick on some themes

    */
    #site-content {
        overflow: visible;
    }

    @media (max-width: 1500px) {
        #shopwp-selectors {
            flex-direction: column;
            justify-content: flex-start;
        }

        #shopwp-selectors .show-row-flex-end {
            justify-content: flex-start;
            margin-bottom: 20px
        }
        
    }

    @media (max-width: 1300px) {
        #shopwp-storefront-payload .wps-items-list {
            grid-template-columns: repeat( 2, 1fr );
        }
    }

    @media (max-width: 1100px) {
        #shopwp-storefront .shopwp-row {
            flex-direction: column;
        }

        #shopwp-storefront .wps-storefront-sidebar {
            margin-bottom: 20px;
        }

        #shopwp-storefront-options {
            width: 100%;
            position: relative;
            top: 0;
        }

        #shopwp-storefront-page-size {
            margin-left: 0;
        }

        #shopwp-storefront-page-size,
        #shopwp-storefront-sort {
            margin-bottom: 15px;
        }

        #shopwp-storefront .swp-select label {
            min-width: 85px;
            text-align: left;
        }
    }

</style>

<section id="shopwp-storefront">

   <div class="shopwp-row">
        <div id="shopwp-storefront-options"></div>

        <div id="shopwp-storefront-content" class="shopwp-col">

            <div id="shopwp-selectors">
                <div id="shopwp-storefront-selections"></div>
                <div class="shopwp-storefront-component-filters">
                    <div class="shopwp-row show-row-flex-end">
                        <div id="shopwp-storefront-sort"></div>
                        <div id="shopwp-storefront-page-size"></div>
                    </div>
                </div>
            </div>

            <div id="shopwp-storefront-payload">
                
                <div class="shopwp-skeleton">
                    <div class="shopwp-skeleton-storefront-payload">
                        <div class="shopwp-skeleton-product">
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-image"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
                        </div>
                        
                        <div class="shopwp-skeleton-product">
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-image"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
                        </div>

                        <div class="shopwp-skeleton-product">
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-image"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
                            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
                        </div>

                    </div>
                </div>
            
            </div>
        </div>
   </div>

   <div class="shopwp-row">
      <div id="shopwp-storefront-pagination"></div>
   </div>

</section>