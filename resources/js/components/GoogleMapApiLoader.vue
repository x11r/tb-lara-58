<template>
    <div>

        <button v-on:click="showMap()">連続地図表示</button>

        <div>3333333333</div>
        <div class="map" ref="googleMap" v-for="(n, index) in news" >
            <div />
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
let googleApiKey = '';

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
        const targetElement = this.$el;

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
                let map = this.google.maps;
                this.news = data;

                for (let i = 0 ; i < data.length ; i++) {
                    // console.log(data[i]);
                    // 緯度経度をセットする
                    position = {
                        center: {
                            lat: data[i].latitude,
                            lng: data[i].longitude
                        },
                        zoom: 17
                    }
                    console.log(this.$el);
                    // new this.google.maps.Map(this.$ref.googleMap[i], position);

                }
                console.log(this.$refs);
                console.log(position);

                // new this.google.maps.Map(this.$refs.googleMap, position);
                // new this.google.maps.Map(this.$refs.googleMap22, position);
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.map {
    width: 30vw;
    height: 30vh;
    border: 1px solid #f00;
}
</style>
