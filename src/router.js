import Vue from 'vue'
import Router from 'vue-router'

import HomePage from './components/HomePage.vue'
import Login from './components/Login.vue'
import GuestHome from './components/GuestHome.vue'
import OfficerHome from './components/OfficerHome.vue'
import ChangePassword from "./components/ChangePassword";

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
			path: '/staff-home',
			name: 'OfficerHome',
			component: OfficerHome
		},
		{
			path: '/change-password',
			name: 'ChangePassword',
			component: ChangePassword
		},
		{
			path: '*',
			redirect: '/login'
		},
	]
})