<template>
	<div id="app">
		<HomePage/>
	</div>
</template>

<script>

import Comp from './components/Comp.vue'
import HomePage from './components/HomePage.vue'
import Login from './components/Login.vue'

export default {
	name: 'app',
	components: {
		Comp,
		HomePage,
		Login
	}
}
</script>

<style>
	@import url('https://fonts.googleapis.com/css?family=Montserrat:400,700');

	body {
		background-color: #fff;
		font-family: 'Montserrat', sans-serif;
	}

</style>
