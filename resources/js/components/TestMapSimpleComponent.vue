<template>
    <div>
        <button v-on:click="showMaps()">地図一つだけ表示</button>
        <div id="map" class="map" ref="googleMap" />
    </div>
</template>

<script>
import GoogleMapsApiLoader from 'google-maps-api-loader';
let googleApiKey = '';
export default {
    name: "Map",
    data() {
        return {
            google: null,
            mapConfig: {
                center: {
                    lat: 35.68944,
                    lng: 139.69167
                },
                zoom: 17
            },
            news: [],
            message: 'テストの文字列'
        }
    },
    async mounted() {
        this.google = await GoogleMapsApiLoader({
            apiKey: googleApiKey
        });
        this.showMaps();
    },
    methods: {
        showMaps() {
            console.log('showMaps()');
            new this.google.maps.Map(document.getElementById('map'), this.mapConfig);
        }
    }
}
</script>

<style scoped>
.map {
    width: 30vw;
    height: 30vh;
    border: 1px solid #00f;
}
</style>
