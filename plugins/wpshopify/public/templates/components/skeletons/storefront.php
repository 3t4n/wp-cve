<style>

    @keyframes shimmer {
        0% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

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
    
</style>

<section class="shopwp-skeleton shopwp-skeleton-storefront">

    <div class="shopwp-skeleton-row shopwp-skeleton-storefront-sort-group">
        <div class="shopwp-skeleton-component shopwp-skeleton-storefront-sort"></div>
        <div class="shopwp-skeleton-component shopwp-skeleton-storefront-sort"></div>
    </div>

   <div class="shopwp-skeleton-row">
        <div class="shopwp-skeleton-storefront-options">
            <div class="shopwp-skeleton-component shopwp-skeleton-storefront-option"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-storefront-option"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-storefront-option"></div>
        </div>

        <div class="shopwp-col">
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

   <div class="shopwp-skeleton-row shopwp-skeleton-row-center">
      <div class="shopwp-skeleton-component shopwp-skeleton-pagination"></div>
   </div>

</section>