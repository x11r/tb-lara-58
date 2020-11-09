
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.MIX_GOOGLE_API_KEY,
        libraries: 'places',
        region: 'JP',
        language: 'ja'
    }
})


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


let googleApiKey = process.env.MIX_GOOGLE_API_KEY;

Vue.component('test-map-multiple', require('./components/TestMapMultipleComponent.vue').default);
Vue.component('test-map-simple', require('./components/TestMapSimpleComponent.vue').default);
Vue.component('google-map-api-loader', require('./components/GoogleMapApiLoader.vue').default);
Vue.component('rakuten-api-hotel', require('./components/RakutenHotelComponent').default)
// Vue.component('google-map-loader', require('./components/GoogleMapLoader.vue').default);
// Vue.component('google-zip', require('./components/GoogleZip.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data : function() {
        return {
            message: "default",
            news: [
                {
                    'title': 't1',
                    'body': 'body1',
                    'latitude': '129',
                    'longitude': '34'
                },
                {
                    'title': 't2',
                    'body': 'body2',
                    'latitude': '129',
                    'longitude': '34'
                }
            ]
        }
    },
    created: function () {
    },
    mounted: function () {
    },
    methods: {
        getNews : function() {
            this.message = "update";

            let googleapikey = process.env.MIX_GOOGLE_API_KEY;

            let url = '/api/v1/news/all';
            axios.get(url)
                .then(response  => {
                    this.news = response.data;
                    // this.message = 'reponse ok';
                    // console.log(response.data);
                    // console.log(this.news);
                })
                .catch(function(error) {
                    // console.log(error);
                })
                .finally(function() {
                    // console.log('finally');
                })
            ;
        }
    }
});
