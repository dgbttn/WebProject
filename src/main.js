import Vue from 'vue'
import App from './App.vue'
import VeeValidate from 'vee-validate'
import { Validator } from 'vee-validate'
import router from './router'
import VueResource from 'vue-resource'
import VueCookie from 'vue-cookie'
import VueJwtDecode from 'vue-jwt-decode'
import XLSX from 'xlsx'

Vue.use(VeeValidate);
Vue.use(VueResource);
Vue.use(VueCookie);
Vue.use(VueJwtDecode);
Vue.use(XLSX);
// Vue.config.productionTip = false

const dict = {
	en: {
		messages: {
			required: () => 'Trường này không được để trống',
			min: (_, num) => 'Trường này phải có ít nhất ' + num + ' ký tự.',
		}
	}
}

Validator.localize(dict);
Validator.localize('en');

new Vue({
	router,
	render: h => h(App),
}).$mount('#app')
