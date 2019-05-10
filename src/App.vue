<template>
	<div id="app">
		<!-- <nav>
			<router-link to="/">Home</router-link>
			<router-link to="/about">About</router-link>
		</nav>

		<router-view/> -->
		<Comp/>
	</div>
</template>

<script>
import Comp from './components/Comp.vue'
import Login from './components/Login.vue'
import HomePage from './components/HomePage.vue'

export default {
	name: 'app',
	components: {
		Comp,
		Login,
		HomePage
	}
}
</script>

<style scoped>
	@import url('https://fonts.googleapis.com/css?family=Montserrat:400,700');

	body {
		background-color: #fff;
		font-family: 'Montserrat', sans-serif;
	}

</style>
