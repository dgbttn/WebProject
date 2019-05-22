import Vue from 'vue'
import App from './App.vue'
import VeeValidate from 'vee-validate'
import { Validator } from 'vee-validate'
import router from './router'
import VueResource from 'vue-resource'

Vue.use(VeeValidate);
Vue.use(VueResource);
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
