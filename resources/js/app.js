/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: Axios } = require('axios');
const { default: Echo } = require('laravel-echo');

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('categoria-component', require('./components/Categoria.vue').default);
Vue.component('articulo-component', require('./components/Articulo.vue').default);
Vue.component('cliente-component', require('./components/Cliente.vue').default);
Vue.component('proveedor-component', require('./components/Proveedor.vue').default);
Vue.component('rol-component', require('./components/Rol.vue').default);
Vue.component('user-component', require('./components/User.vue').default);
Vue.component('ingreso-component', require('./components/Ingreso.vue').default);
Vue.component('venta-component', require('./components/Venta.vue').default);
Vue.component('dashboard-component', require('./components/Dashboard.vue').default);
Vue.component('consulta-ingreso-component', require('./components/ConsultaIngreso.vue').default);
Vue.component('consulta-venta-component', require('./components/ConsultaVenta.vue').default);
Vue.component('notification-component', require('./components/Notification.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        menu: 0,
        notifications: []
    },
    created() {
        var me = this;
        axios.post('notification/get').then(function(response) {
                console.log(response.data);
                var res = response.data;
                me.notifications = res;
            })
            .catch(function(error) {
                console.log(error);
            });
        var userId = $('meta[name="userId"]').attr('content');
        window.Echo.private('App.User.' + userId).notification((notification) => {
            console.log(notification);
            me.notifications.unshift(notification);
        });
    }
});