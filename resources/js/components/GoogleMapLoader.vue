<template>
    <div class="app">
        <div id="map">
            <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 100%">
                <GmapMaker v-for="(m, index) in news"
                           :position="m.position"
                           :title="m.title"
                           :key="index"
                >
                </GmapMaker>
            </GmapMap>
        </div>
    </div>
</template>

<script>
import GoogleMapApiLoader from 'google-maps-api-loader';

let googleApiKey =

export default {

    name: "GoogleMapLoader",
    props: {
        mapCofig: Object,
        apiKey: googleApiKey
    },
    data: function() {
        return {
            google: null,
            map: null,
            news: [
                {
                    title: 'sample',
                    position: {lng: 0, lat: 0}
                },
                {
                    title: 'sample',
                    position: {lng: 0, lat: 0}
                }
            ]
        }
    },
    async mounted() {
        const googleMapApi = await GoogleMapApiloader({
            apiKey: this.apiKey
        })
        // this.google = googleMapsAutoCompleteAPILoad()
        // this.getNews()
        // this.initializeMap()
    },
    methods: {
        initializeMap() {
            const mapContainer = this.$refs.googleMap
            this.map = new this.google.maps.Map()
            mapContainer, this.mapConfig
        },
        getNews() {
            let url = '/api/v1/news/all';
            axios.get(url)
            .then(response => {
                console.log(response.data);
                this.news = response.data;
            })
            .catch(function(error) {

            })
            .finally(function() {

            })
        }
    }
}
</script>

<style scoped>

</style>
