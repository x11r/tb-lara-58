
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

var $ = require('jquery');
require('jquery-ui');

window.Vue = require('vue');

// import * as VueGoogleMaps from 'vue2-google-maps'

// Vue.use(VueGoogleMaps, {
//     load: {
//         key: process.env.MIX_GOOGLE_API_KEY,
//         libraries: 'places',
//         region: 'JP',
//         language: 'ja'
//     }
// })


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


let googleApiKey = process.env.MIX_GOOGLE_API_KEY;

Vue.component('rakuten-api-hotel', require('./components/RakutenHotelComponent').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data : function() {
        return {
            message: "default"
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

// jQuery
$(function() {

    // 初期状態
    $('#rakutenArea .middle-class').next().hide();

    // クリックしたら開く
    $('#rakutenArea .middle-class').on('click', function() {
        $('#rakutenArea .middle-class').next().hide();
        $(this).next().slideToggle();
    });
})
