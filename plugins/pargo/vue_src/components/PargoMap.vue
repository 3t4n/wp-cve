<template>
<div  class="p-a-map-container">
    <div v-show="!loaded">Loading Pargo Map Locations...</div>
    <iframe
        id='thePargoPageFrameID'
        :src="src"
        width='100%'
        height='100%'
        allow="geolocation *"
        name='thePargoPageFrame'
        @load="load"
        v-show="loaded"
    ></iframe>
</div>
</template>
<script>
export default {
    name: 'PargoMap',
    props: {
        mapToken: {
            type: String,
            required: true
        },
        urlEndPoint: {
            type: String,
            default: 'production'
        },
        selectedPargoPoint: {
            type: Function,
            default: (point) => {console.log('selectedPoint', point)}
        }
    },
    mounted() {
        if (window.addEventListener) {
            window.addEventListener("message", this.selectPargoPoint, false);
        } else {
            window.attachEvent("onmessage", this.selectPargoPoint);
        }
    },
    data() {
        return {
            loaded: false,
            src: `https://map${this.urlEndPoint === 'staging' ? '.staging' : ''}.pargo.co.za/?token=${this.mapToken}`
        }
    },
    methods: {
		load(){
            this.loaded = true;
        },
        selectPargoPoint(event) {
            if (event.data) {
                if (event.data.pargoPointCode) {
                  this.selectedPargoPoint(event.data);
                }
            }
        }
    }
}
</script>
<style scoped>
    .p-a-map-container {
        width: 100%;
        height: 100%;
        border: thin solid;
        background-color: white;
        z-index: 10;
    }
    .p-a-map-container div {
        padding: 0.2rem;
    }
</style>
