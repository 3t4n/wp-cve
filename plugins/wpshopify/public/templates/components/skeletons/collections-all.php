<style>

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

    .wps-products-wrapper {
        display: flex;
        padding: 2em 0;
        width: 100%;
        max-width: 1100px;
    }

    .shopwp-skeleton-row {
        display: flex;
        justify-content: space-between;
    }

    .shopwp-col {
        flex: 1;
    }

    .shopwp-skeleton-product {
        width: 32.3%;
        flex: none;
    }

    .shopwp-skeleton-gallery {
        width: 50%;
    }

    .shopwp-skeleton-product-image {
        width: 90%;
        height: 350px;
    }

    .shopwp-skeleton-product-title {
        width: 100%;
        height: 20px;
    }

    .shopwp-skeleton-product-price {
        width: 40%;
        height: 20px;
    }

    .shopwp-skeleton-product-description {
        width: 100%;
        height: 70px;
    }

    .shopwp-skeleton-product-single {
        max-width: 1100px;
        margin: 20px auto 0px auto;
    }

    @media (max-width: 600px) {
        .shopwp-skeleton-row {
            flex-direction: column;
        }

        .shopwp-skeleton-product,
        .shopwp-skeleton-gallery,
        .shopwp-skeleton-component {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }
    }
    
</style>

<section class="shopwp-skeleton shopwp-skeleton-product-single">

    <div class="shopwp-skeleton-row">

        <div class="shopwp-col shopwp-skeleton-product">
            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-description"></div>
        </div>

        <div class="shopwp-col shopwp-skeleton-product">
            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-description"></div>
        </div>

        <div class="shopwp-col shopwp-skeleton-product">
            <div class="shopwp-skeleton-component shopwp-skeleton-product-title"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-price"></div>
            <div class="shopwp-skeleton-component shopwp-skeleton-product-description"></div>
        </div>

    </div>

</section>