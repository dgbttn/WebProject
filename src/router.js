import Vue from 'vue'
import Router from 'vue-router'

import Comp from './components/Comp.vue'
import About from './components/About.vue'
import Login from './components/Login.vue'
import HomePage from './components/HomePage.vue'

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [
		{
			path: '/comp',
			name: 'Comp',
			component: Comp
		},
		{
			path: '/about',
			name: 'About',
			component: About
		},
	]
})
