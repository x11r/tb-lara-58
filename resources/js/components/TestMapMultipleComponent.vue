<template>
    <div>
        <button v-on:click="getNews()">複数取得</button>
        <button v-on:click="showMaps()">地図複数表示</button>
        <button v-on:click="getNewsAndShowMaps()">取得して表示</button>
        <div v-for="(n, index) in news">
            <div class="row">
                <div class="col-md-2"> {{ n.title }}</div>
                <div class="col-md-2">lng:{{ n.mapConfig.center.lng }}</div>
                <div class="col-md-2">lat:{{ n.mapConfig.center.lat }} </div>
            </div>
            <div class="row">
                <div class="col-md-6">{{ n.body }}</div>
                <div class="col-md-6 map map-multi test-map" ref="googleMap" />
            </div>
        </div>
        <div><pre>{{ news }}</pre></div>
        <div>message : {{ message }}</div>
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
                center: {lat: 35.68944, lng: 139.69167},
                zoom: 17
            },
            news: [],
            message: 'テストの文字列'
        }
    },
    async mounted() {
        //
        this.google = await GoogleMapsApiLoader({
            apiKey: googleApiKey
        });
        this.getNewsAndShowMaps();
    },
    methods: {
        // 2つ同時に実行する
        getNewsAndShowMaps() {
            // this.getNews();
            // setTimeout(function() {
            //     this.getNews();
            // }, 1000);
            // this.showMaps();
        },
        getNews() {
            console.log('getNews()');
            let url = '/api/v1/news/all';
            axios.get(url)
            .then(response => {
                let data = [];
                for (let i in response.data) {
                    data[i] = {
                        title: response.data[i].title,
                        body: response.data[i].body,
                        mapConfig: {
                            center: {
                                lat: response.data[i].latitude,
                                lng: response.data[i].longitude
                            },
                            zoom: 17
                        }
                    }
                }
                this.news = data;
                console.log(this.news);
                this.showMaps();
            }).catch(err => {
                console.log(err);
            });
        },
        // 地図表示
        showMaps() {
            // console.log('showMaps()');
            // this.getNews();
            // console.log('==============');
            // console.log(this.news);

            let elements = document.getElementsByClassName('test-map');
            // console.log(elements);

            for (let i in this.news) {
                console.log(i);
                console.log(this.news[i]);
            }
            new this.google.maps.Map(elements[i], this.news[i]);
        }
    }
}
</script>
<style scoped>
.map-multi {
    width: 10vw;
    height: 10vh;
    border: 1px solid #00f;
}
</style>
