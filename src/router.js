import Vue from 'vue'
import Router from 'vue-router'

import About from './components/About.vue'
import HomePage from './components/HomePage.vue'
import Login from './components/Login.vue'
import GuestHome from './components/GuestHome.vue'

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [
		{
			path: '/login',
			name: 'Login',
			component: Login
		},
		{
			path: '/home',
			name: 'HomePage',
			component: HomePage
		},
		{
			path: '/guest-home',
			name: 'GuestHome',
			component: GuestHome
		},
		{
			path: '*',
			redirect: '/login'
		},
	]
})
