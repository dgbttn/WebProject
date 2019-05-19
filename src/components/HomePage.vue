<template>
	<app-root name="app-root">
		<!-- <progress value="0" max="100">0%</progress> -->

		<div class="header">
			<nav class="navbar">
				<div class="task-icon">
					<i class="fa fa-bars"></i>
				</div>

				<div class="account">
					<a href="#" class="nav-link account-content dropdown-account">
						<img src="../image/avatar.jpg" alt="user_avatar" class="avatar">

						<span class="account-name">{{ username }}</span>

						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</nav>
		</div>

		<div class="content-bound">
			<div class="tool-bar">
				<div class="tool-control unit-control-btn" v-on:click="openTool('unit-control')">
					<i class="fa fa-th tool-icon"></i>
					<span class="tool-name">QUẢN LÍ ĐƠN VỊ</span>
				</div>
				<div class="tool-control teacher-account-btn" v-on:click="openTool('teacher-account')">
					<i class="fa fa-user tool-icon"></i>
					<span class="tool-name">TÀI KHOẢN GIẢNG VIÊN</span>
				</div>
				<div class="tool-control research-field-btn" v-on:click="openTool('research-field')">
					<i class="fa fa-tasks tool-icon"></i>
					<span class="tool-name">LĨNH VỰC NGHIÊN CỨU</span>
				</div>
			</div>

			<div class="container">
				<div class="unit-control content" id="unit-control">
					<UnitControl />
				</div>
				<div class="teacher-account content" id="teacher-account">
					<OfficerAccount />
				</div>
				<div class="research-field content" id="research-field">
					<ResearchField />
				</div>
			</div>
		</div>

	</app-root>
</template>

<script>
import UnitControl from './homepage/UnitControl.vue'
import OfficerAccount from './homepage/OfficerAccount.vue'
import ResearchField from './homepage/ResearchField.vue'

export default {
	name: 'HomePage',
	components: {
		UnitControl,
		OfficerAccount,
		ResearchField
	},
	data() {
		return {
			username: 'Tung',

		}
	},

	methods: {
		// show the selected tool
		openTool(toolName) {
			var i, tabs, contents;

			contents = document.getElementsByClassName('content');
			for (i=0; i<contents.length; i++)
			contents[i].style.display = "none";

			tabs = document.getElementsByClassName('tool-control');
			for (i=0; i<tabs.length; i++)
			tabs[i].className = tabs[i].className.replace(" active", "");

			document.getElementById(toolName).style.display = "block";
			document.getElementsByClassName(toolName+'-btn')[0].className += " active";

		}
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
		background-color: #555273;
		top: 0;
		left: 0;
		position: fixed;
		color: #e2eff1;
	}

	.navbar {
		border: 1px solid #e7e7e7;
		margin-bottom: 10px;
	}

	/* buttons on navbar*/
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

	.account-name {
		padding: .40rem!important;
		margin: 0px 5px;
	}

	.account {
		float: right;
		margin-top: 1px;
		background-color: inherit;
	}

	.navbar div:hover, div:focus {
		background-color: #ffcd1f;
		color: #455358;
	}

	.navbar div {
		transition: 0.2s;
	}

	.task-icon {
		font-size: 22px;
		float: left;
		display: block;
		padding: 8px 15px;
		margin: 1px 5px;
		width: 25px;
		text-align: center;
		color: inherit;
		background-color: inherit;
		cursor: pointer;
	}

	.tool-bar {
		background-color: #36AE88;
		margin-top: 50px;
		width: 20%;
		height: 100%;
		top: 0;
		left: 0;
		position: fixed;
		box-shadow: 0 0.5rem 1.25rem 0 rgba(0,0,0,0.28)
	}

	.tool-control {
		display: block;
		background-color: inherit;
		color: #ffebd3;
		cursor: pointer;
		padding: 13px 10px;
		transition: 0.2s;
		font-size: 16px;
		font-weight: 500;
		font-family: hurme_no2-webfont,-apple-system,BlinkMacSystemFont,sans-serif;
	}

	.tool-control:hover {background-color: #1c9770;}

	.tool-icon {
		font-size: 22px;
		vertical-align: middle;
		height: 25px;
		width: 25px;
		margin: 0px 10px;
		text-align: center;
	}

	.tool-bar div.active {
		background-color: #b5525c ;
		color: #ffcd1f;
	}

	.container {
		margin-top: 70px;
		margin-left: 22%;
		transition: 0.2s;
	}

	.content {display: none;}

	img {
		vertical-align: middle;
		border-style: none;
		margin: 0px 5px;
	}

	a {text-decoration: none;}

	*{
		line-height: 1.5;
	}

</style>
