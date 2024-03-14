<style text="text/css">

    @keyframes shimmer {
        0% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

    .shopwp-skeleton-component {
        margin-bottom: 13px;
        border-radius: 15px;
        background: #eee;
        animation: shimmer 0.4s ease-out 0s alternate infinite none running;
    }

    .shopwp-skeleton-row {
        display: flex;
        justify-content: space-between;
    }

    .shopwp-skeleton-product-image {
        width: 300px;
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

<div class="shopwp-skeleton">
    <div class="shopwp-skeleton-product">
        <div class="shopwp-skeleton-component shopwp-skeleton-product-image"></div>
        <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
        <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
        <div class="shopwp-skeleton-product-buy-button">
        
        <div class="shopwp-skeleton-row">
            <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-value"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-value"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-value"></div>
        </div>
        
        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-cta"></div>

        </div>
    </div>
</div>