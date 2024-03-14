<script>
export default {
    name: "PargoStore",
    props: {
        point: {
            type: Object,
            default: {},
        }
    },
}
</script>

<template>
<div>
    <p class="pargo_style_title">{{  `Selected Pickup Point: ${point.storeName}` }}</p>
    <img class="pargo_style_image" :src="point.photo" v-if="point.photo" />
    <p class="pargo_style_desc">{{ point.addressSms }}</p>
</div>
</template>