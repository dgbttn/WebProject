<template>
	<div id="app">
		<router-view/>
	</div>
</template>

<script>

import Comp from './components/Comp.vue'
import HomePage from './components/HomePage.vue'
import Login from './components/Login.vue'

export default {
	name: 'app',
	components: {
	}
}
</script>

<style>
	@import url('https://fonts.googleapis.com/css?family=Montserrat:400,700');

	body {
		background-color: #fff;
		font-family: hurme_no2-webfont,-apple-system,BlinkMacSystemFont,sans-serif;
	}

</style>
