<template>
	<app-root name="app-root">
		<!-- <progress value="0" max="100">0%</progress> -->

		<div class="header">
			<nav class="navbar">
				<div class="task-icon" v-on:click = "toggle">
					<i class="fa fa-bars"></i>
				</div>

				<div class="account">
					<a href="" class="nav-link account-content dropdown-account">
						<img src="../image/avatar.jpg" alt="user_avatar" class="avatar">

						<span class="account-name">{{ username }}</span>

						<i class="fa fa-chevron-down"></i>
					</a>
					<span class="bonus change-password" v-on:click="changePassword">
						<i class="fa fa-key"></i>&nbsp;
						Đổi mật khẩu
					</span><br>
					<span class="bonus log-out" v-on:click="Logout">
						<i class="fa fa-sign-out"></i>&nbsp;
						Đăng xuất
					</span>
				</div>
			</nav>
		</div>

		<div class="content-bound">
			<div class="tool-bar">
				<div class="tool-control unit-control-btn active" v-on:click="openTool('unit-control', 0)">
					<i class="fa fa-th tool-icon"></i>
					<span v-if="!minimize" class="tool-name">QUẢN LÍ ĐƠN VỊ</span>
				</div>
				<div class="tool-control officer-account-btn" v-on:click="openTool('officer-account', 1)">
					<i class="fa fa-user tool-icon"></i>
					<span v-if="!minimize" class="tool-name">TÀI KHOẢN GIẢNG VIÊN</span>
				</div>
				<div class="tool-control research-field-btn" v-on:click="openTool('research-field', 2)">
					<i class="fa fa-tasks tool-icon"></i>
					<span v-if="!minimize" class="tool-name">LĨNH VỰC NGHIÊN CỨU</span>
				</div>
			</div>

			<div class="container" id="root">
				<div v-show="useMode==0" class="unit-control content" id="unit-control" >
					<UnitControl ref="unit"/>
				</div>
				<div v-show="useMode==1" class="officer-account content" id="officer-account" >
					<OfficerAccount ref="account"/>
				</div>
				<div v-show="useMode==2" class="research-field content" id="research-field" >
					<ResearchField ref="field"/>
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
			minimize: false,
			useMode: 0, 	/* 	0 for unit-control,
								1 for officer-account,
								2 for research-field */

		}
	},

	created() {
		// var token = this.$cookie.get('user');
		// if (token == null) {
		// 	this.$router.push('/login');
		// }
		// 	else {
		// 	var decoded_token = this.$jwtDec.decode(token);
		// 	var role = decoded_token.role;
		// 	if (role == null) {
		// 		this.$router.push('/login');
		// 	}
		// 	else {
		// 		if (role == 'lecture') {
		// 			this.$router.push('/staff-home');
		// 		}
		// 	}
		// }
	},

	methods: {
		// show the selected tool
		openTool(toolName, index) {
			this.useMode = index;
			var i, tabs, contents;

			tabs = document.getElementsByClassName('tool-control');
			for (i=0; i<tabs.length; i++)
			tabs[i].className = tabs[i].className.replace(" active", "");

			document.getElementsByClassName(toolName+'-btn')[0].className += " active";

			switch (this.useMode) {
				// Unit Control
				case 0:
					this.$refs.unit.initRandomList();
					break;

				// Officer Account
				case 1:
					this.$refs.account.initRandomList();
					break;

				// Research Field
				case 2:
					this.$refs.field.initRandomTree();
					break;
			}

		},

		toggle() {
			if (!this.minimize) {
				this.minimize = !this.minimize;
				document.getElementsByClassName('tool-bar')[0].style.width = "65px";
				document.getElementsByClassName('container')[0].style.marginLeft = "120px";
				return;
			}
			this.minimize = !this.minimize;
			document.getElementsByClassName('container')[0].style.marginLeft = "22%";
			document.getElementsByClassName('tool-bar')[0].style.width = "20%";
		},

		changePassword() {
			console.log('change password');
		},

		Logout() {
			console.log('log out');
		}
	}
}
</script>

<style scoped>

	.header {
		width: 100%;
		height: 50px;
		background-color: #555273;
		top: 0;
		left: 0;
		position: fixed;
		color: #e2eff1;
		z-index: 1;
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
		background-color: #555273;
		position: relative;
		height: 48px;
	}

	.account:hover {background-color: #4080bf;}

	.account:hover .bonus {visibility: visible;}

	.bonus {
		visibility: hidden;
		z-index: 1;
		width: 100%;
		text-align: center;
		background-color: #4080bf;
		position: absolute;
		padding: 10px 0px 10px 0px;
		cursor: pointer;
		font-size: 14px;
	}

	.bonus:hover {background-color: #336699;}

	.change-password {top: 100%;}

	.log-out {top: 186%;}

	.bonus span {
		width: 100%;
		position: absolute;
	}

	.navbar div, .content-bound {transition: 0.2s;}

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

	.task-icon:hover {
		background-color: #ffcd1f;
		color: #455358;
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
		border-left: 3px solid #ffcd1f;
	}

	.container {
		margin-top: 70px;
		margin-left: 22%;
		transition: 0.2s;
	}

	/* .content {display: none;} */

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
