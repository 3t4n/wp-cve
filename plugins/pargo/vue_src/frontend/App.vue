<script>
    import { createApp } from 'vue';
    import PargoMap from '../components/PargoMap.vue';
    import PargoModal from '../components/PargoModal.vue';
    import PargoStore from '../components/PargoStore.vue';

    export default {
        name: 'App',
        props: {
            type: "",
        },
        data() {
            return {
                renderMap: true,
                status: "",
                token: "",
                urlEndPoint: "",
                selectedPoint: {},
                isModalVisible: true,
            };
        },
        async mounted() {
            await fetch(`${OBJ.api_url}pargo/v1/get-pargo-settings`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': OBJ.nonce,
                },
                })
                .then(response => response.json())
                .then((response) => {
                    const {data} = response;
                    if (data.pargo_url_endpoint) {
                        this.urlEndPoint = data.pargo_url_endpoint;
                    } else if (data.pargo_url.length > 0 && data.pargo_url_endpoint.length === 0) {
                      if (data.pargo_url.match('staging')) {
                          this.urlEndPoint = 'staging';
                      }
                    } else {
                       this.urlEndPoint = 'production';
                    }
                        this.token = data.pargo_map_token;
                    });
        },
        methods: {
            async pointSelected(point) {
                if (point.address1) {
                    this.selectedPoint = point;
                    this.status = "Setting Pargo Pickup Point";
                    this.renderMap = false;

                    // AJAX call to update shipping address
                    const data = new FormData();
                    data.append('pargoshipping', JSON.stringify(this.selectedPoint));
                    data.append('action', 'set_pick_up_point');
                    await fetch(`${OBJ.ajax_url}`, {
                        method: "POST",
                        body: data,
                        headers: {
                            'X-WP-Nonce': OBJ.nonce,
                        },
                    })
                    .then(response => response.json())
                    .then(response => {
                        this.status = response.message;
                        if (response.code === 'error') {
                            this.selectedPoint = {};
                            this.status = response.message;
                        }
                    })
                    .finally(() => {
                        // Finally show the point on the cart / checkout
                        this.isModalVisible = false;
                        if (Object.keys(this.selectedPoint).length > 0) {
                            const StoreComponent = createApp(PargoStore, {point: this.selectedPoint});
                            const container = document.getElementById('pargo-after-cart');
                            container.innerHtml = '';
                            StoreComponent.mount(container);
                        }
                    });
                    
                }
            },
            closeModal() {
                this.isModalVisible = !this.isModalVisible;
            }
        },
        components: {
    PargoMap,
    PargoModal,
    PargoStore
}

    }
</script>

<style>
    div.pmap__renderMap {
        min-width: 1000px    ;
        height: 80vh;
    }
    div.pmap__inlineMap {
      width: 100%;
      height: 600px;
    }
</style>

<template>
    <div v-show="status" style="padding: 0.5rem;">{{ status }}</div>
    <PargoModal
        v-show="isModalVisible"
        @close="closeModal"
        v-if="type === 'modal'"
        >
        <template v-slot:header>
            <span class="pargo_style_title">{{ renderMap ? 'Select a Pickup Point' : `Selected Pickup Point: ${selectedPoint.storeName}`  }}</span>
        </template>

        <template v-slot:body>
            <div class="pmap__renderMap"  v-show="renderMap">
                <PargoMap :mapToken="this.token" :urlEndPoint="this.urlEndPoint" :selectedPargoPoint="this.pointSelected" v-if="this.token" />
            </div>
        </template>

        <template v-slot:footer>
            {{ null }}
        </template>
    </PargoModal>
    <div v-if="!type">
        <div class="pmap__inlineMap"  v-show="renderMap">
            <PargoMap :mapToken="this.token" :urlEndPoint="this.urlEndPoint" :selectedPargoPoint="this.pointSelected" v-if="this.token" />
        </div>
    </div>
</template>
