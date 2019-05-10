<template>
	<div class="container">
		<!-- <progress value="0" max="100">0%</progress> -->


		<div class="header">
			<nav class="navbar">
				<div class="content-nav">
					<div class="account">
						<a href="#" class="nav-link account-content dropdown-account">
							<img src="../image/avatar.jpg" alt="user_avatar" class="avatar">

							<span class="account-name">Tung</span>

							<i class="fa fa-minus-circle"></i>
						</a>

					</div>
				</div>
			</nav>
		</div>
	</div>
</template>

<script>
export default {
	name: 'HomePage',
	data() {
		return {

		}
	},

	methods: {

	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";
	@import ""https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"";
	@import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";

	.header {
		width: 100%;
		height: 50px;
		overflow: hidden;
		background-color: #555273;
		position: fixed;
		top: 0;
		left: 0;
		color: #e2eff1;
	}

	.navbar {
	    border: 1px solid #e7e7e7;
	    margin-bottom: 10px;
	}

	.nav-link {
		display: block;
	    padding: .5rem 1rem;
		color: inherit;
	}

	.avatar {
		width: 32px;
		height: 32px;
		border-radius: 50%!important;
	}

	.account-content {
		display: inline-block;
	}

	.account-name {
		padding: .40rem!important;
		margin: 0px 5px;
	}

	.account {
		float: right;
		margin-top: 1px;
	}

	.account:hover {
		background-color: #65799b;
	}

	img {
	    vertical-align: middle;
	    border-style: none;
		margin: 0px 5px;
	}

	a {
		text-decoration: none;
	}

	a:hover{
		text-decoration: underline;
	}

	body {
		background-color: #fff;
	}

	*{
		font-family: 'Montserrat', sans-serif;
		box-sizing: border-box;
		font-size: 1rem;
	    font-weight: 400;
	    line-height: 1.5;
	    text-align: left;
	}


</style>
