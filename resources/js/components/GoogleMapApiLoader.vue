<template>
    <div>
        <button v-on:click="initializeMap()">一つだけのやつ</button>
        <div class="map" ref="googleMap1" />
        <button v-on:click="showMap()">連続地図表示</button>

        <div class="map" ref="googleMap" v-for="(n, index) in news" >
            <div class="row">
                <div class="col-md-4">
                    {{ n.title }}
                </div>
                <div class="col-md-4">
                    lat : {{ n.latitude }}
                    lng: {{ n.longitude }}
                </div>
            </div>
            <div>
                {{ n.body }}
            </div>

        </div>
    </div>
</template>

<script>
import GoogleMapsApiLoader from 'google-maps-api-loader';
let googleApiKey = 'AIzaSyBxzPg1_Z1QwADZ-ISU5iC7GvXiBZUpwQo';

export default {
    name: 'Map',
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
            news: []
        }
    },
    async mounted() {
        this.google = await GoogleMapsApiLoader({
            apiKey: googleApiKey
        });
        // this.initializeMap();
        // this.showMap();
    },
    methods: {
        initializeMap() {
            console.log(this.mapConfig);
            new this.google.maps.Map(this.$refs.googleMap1, this.mapConfig);
        },
        showMap() {
            let url = '/api/v1/news/all';
            axios.get(url)
            .then(response => {
                // 使いやすくするために一度代入する
                let data = response.data;
                // 緯度経度を決めるオブジェクトを初期化する
                let position;
                for (let i = 0 ; i < data.length ; i++) {
                    // 緯度経度をセットする
                    position = {
                        center: {
                            lat: data[i].latitude,
                            lng: data[i].longitude
                        },
                        zoom: 17
                    }
                    console.log(position);
                    new this.google.maps.Map(this.$refs.googleMap, position);
                }
                this.news = data;
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.map {
    width: 50vw;
    height: 20vh;
}
</style>
