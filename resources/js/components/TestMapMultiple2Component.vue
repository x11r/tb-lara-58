
<GoogleMapLoader
    :mapConfig = "mapConfig"
    googleApiKey = '';
>
    <template slot-scope="{ google, map }">
        <GoogleMapMarker
            v-for="(n, index) in news"
            :key=index
            :marker="n"
            :google="google"
            :map="map"
        />
        www
    </template>
</GoogleMapLoader>

<script>
import { mapSetting } from '@/constants/mapSettings'
export default {
    name: "TestMapMultiple2Component.vue",
    components: {
        GoogleMapLoader,
        GoogleMapMarker
    },
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
            this.getNews();
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
        showMaps() {

            let elements = document.getElementsByClassName('test-map');
            // console.log(elements);

            for (let i in this.news) {
                console.log(i);
                new this.google.maps.Map(elements[i], this.news[i]);
            }
        }
    }
}
</script>

<style scoped>

</style>
