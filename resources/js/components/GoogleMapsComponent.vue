<template>
    <div class="app">
        <GmapMap
            :center="{lat: 10, lng: 10}"
            :zoom="7"
            map-type-id="terrain"
            style="width: 300px; height: 300px"
            ref="map"
        >
        </GmapMap>
        <GmapMaker
            :key="index"
            v-for="(m , index) in news"
            >
            <div>
                position : {{ m.position }}
            </div>
        </GmapMaker>
        <div>{{ news }}</div>
    </div>
</template>

<script>
// import * as VueGoogleMaps from 'vue2-google-maps'
import GoogleMapsApiLoader from 'google-maps-api-loader';

let googleApiKey = process.env.MIX_GOOGLE_API_KEY;

export default {
    name: "GoogleMapsComponent",
    data() {
        return {
            google: null,
            mapConfig: {
                center: {
                    lat: 35.6894,
                    lng: 139.59197
                },
                zoom: 17
            },
            news: []
        }
    },
    async mounted() {
        this.google = await GoogleMapsApiLoader({
            apiKey: googleApiKey
        });
        this.getNews();
        this.initializeMap();
    },
    methods: {
        getNews() {

            let url = '/api/v1/news/all';
            axios.get(url)
            .then(response => {

                let data = [];
                for ( let i = 0; i < response.data.length; i++) {
                    data[i] = {
                        title: response.data[i].title,
                        body: response.data[i].body,
                        position: {
                            lat: response.data[i].latitude,
                            lng: response.data[i].longitude
                        }
                    }
                }
                this.news = data;
            })
            .catch(function(error) {

            })
            .finally(function() {

            });

            // console.log('getNews()');
            // console.log(this.news);
        },
        initializeMap() {
            new this.google.maps.Map(this.$refs.googleMap, this.mapConfig);
        }
    }
}
</script>

<style lang="scss" scoped>
.map {
    width: 50vw;
    height: 50vh;
}
</style>
