<template>
	<div class="app-root">
		<div class="container">
			<div class="header">
				<center><img class="logo" v-bind:src="logo"><br></center>
			</div>

			<div class="holder">
				<center><img class="title" v-bind:src="title"></center>
				<form class="form-horizontal" @submit.prevent="log" v-on:keyup.enter="log">
					<div class="form-group">
						<label class="control-label" for="username">Tên đăng nhập:</label>
						<div v-bind:class="{ 'has-error' : errors.has('username') }">
							<input name="username" v-model="username"  data-vv-delay="100" v-validate="'required'" class="form-control" type="text">
							<span v-show="errors.has('username')" class="text-danger">{{ errors.first('username') }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label" for="password">Mật khẩu:</label>
						<div v-bind:class="{ 'has-error' : errors.has('password') }">
							<input name="password" v-model="password" v-validate="'required|min:6'" class="form-control" type="password">
							<span v-show="errors.has('password')" class="text-danger">{{ errors.first('password') }}</span>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label" for="rule">Vai trò:</label>
						<div class="">
							<select class="form-control" name="rule" v-model="rule">
								<option value="teacher">Giảng viên</option>
								<option value="admin">Quản trị viên</option>
							</select>
						</div>
					</div>
				</form>

				<center><a class="button login-btn" v-on:click="log">Đăng nhập</a></center>

				<center><a class="student-login-btn" v-on:click="guest_log">Tham gia với tư cách sinh viên</a></center>

			</div>
			<div class="message" id="message" style="display: none">
				<b>Lỗi!</b> {{mess}}
			</div>
		</div>

	</div>
</template>

<script>
export default {
	name: 'Login',
	data() {
		return {
			username: '',
			password: '',
			mess: '',
			rule: 'teacher',
			logo: require('../image/logo-outline.png'),
			title: require('../image/title.png')
		}
	},

	methods: {
		// login
		log() {
			// kiểm tra độ dài username và password nhập vào
		    this.$validator.validateAll().then((result) => {
				if (!result) {
					this.mess = 'Đăng nhập thất bại';
					document.getElementById('message').style.display = '';
					setTimeout(function () {
						document.getElementById('message').style.display = 'none';
					}, 3000);
					return;
				}
				else {
					// xác thực username và password
					var url = 'http://localhost/uFaculty/Account/AccountController/validate'
					this.$http.post(url,{
						username: this.username,
						password: this.password,
						role: this.rule
					}).then(function (data) {
						var status = data.body.status;
						if (status == 0) {
							var reason = data.body.reason;
							this.mess = reason;
							document.getElementById('message').style.display = '';
							setTimeout(function () {
								document.getElementById('message').style.display = 'none';
							}, 3000);
							//alert(reason);
						}
							else {
							// lấy json web token từ server và lưu vào cookies
							var token = data.body.data;
							var decoded_token = this.$jwtDec.decode(token);
							var expired_time = decoded_token.expired;
							var role = decoded_token.role;
							// điều hướng tùy theo quyền và mục đích của user
							if (role == 'admin') {
								if (this.rule == 'admin') {
									this.$cookie.set('user',token, expired_time);
									this.$router.push('/home')
								}
									else {
									this.$cookie.set('user',token, expired_time);
									this.$router.push('/staff-home')
								}
							}
								else {
								if (this.rule == 'admin') {
									this.mess = 'Không đúng quyền';
									document.getElementById('message').style.display = '';
									setTimeout(function () {
										document.getElementById('message').style.display = 'none';
									}, 3000);
									//alert('Không đúng quyền')
								}
								else {
									this.$cookie.set('user',token, expired_time);
									this.$router.push('/staff-home')
								}
							}
						}
					})

				}
			});
			//this.$router.push('/home');


		},

		guest_log() {
			// đăng nhập khách
			this.$router.push('/guest-home');
		}
	}
}
</script>

<style scoped>
	div {display: block;}

	label {
		position: relative;
		min-height: 1px;
		padding-right: 15px;
		display: inline-block;
		max-width: 100%;
		font-weight: 700;
	}

	.title {
		width: 270px;
		margin: 0px 0px 15px 0px;
	}

	.logo {
		width: 230px;
	}

	.login-btn {
		color: #fff;
		background-color: #4eb14e;
		border-color: #46a046;

		display: inline-block;
		padding: 6px 12px;
		margin: 20px 0px;
		font-size: 14px;
		font-weight: 450;
		line-height: 1.42857143;
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;
		touch-action: manipulation;
		cursor: pointer;
		border: 2px solid transparent;
		border-radius: 30px;

		width: 200px;
	}

	.button:hover {	background-color: #46a046;}

	.button:active {background-color: #3e8e3e;}

	.student-login-btn {
		display: inline-block;
		margin: 5px 0px;
		color: #3366ff;
		text-decoration: underline;
		font-weight: bold;
		cursor: pointer;
	}

	.student-login-btn:hover {color: #0040ff;}

	.text-danger {
		color: #a94442;
		display: block;
		margin-top: 5px;
		margin-bottom: 10px;
	}

	.form-horizontal .control-label {
		padding-top: 7px;
		margin-bottom: 5px;
		text-align: right;
	}

	.form-group {
		margin-bottom: 5px;
	}

	.form-control:focus {
		border-color: #66afe9;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
	}

	.form-control {
		display: block;
		width: 100%;
		height: 34px;
		padding: 6px 12px;
		font-size: 14px;
		font-weight: 500;
		line-height: 1.42857143;
		color: #555;
		background-color: #fff;
		border: 1px solid #ccc;
		border-radius: 4px;
	}

	.has-error .form-control {
		border-color: #a94442;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	}

	input {
		text-rendering: auto;
		margin: 0em;
		font: 400 13.3333px Arial;
	}

	option {
		padding: 3px 0px;
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
	}

	.container {
		background-color: transparent;
		font-family: 'Montserrat', sans-serif;
		display: grid;
		/* padding-top: 10px; */
		min-width: 330px;
		max-width: 30%;
		margin-left: 35%;
	}

	.header {
		z-index: 1;
		width: 390px;
	}

	.holder {
		width: 330px;
		background-color: #fff;
		padding-left: 30px;
		padding-right: 30px;
		padding-top: 60px;
		margin-top: -93px;
		padding-bottom: 20px;
		border-radius: 15px;
		border: 1px solid gray;
	}

	.holder * {
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 14px;
		box-sizing: border-box;
	}
	.message{
		margin: 30px 0;
		padding: 10px 10px 10px 10px;
		min-height: 22px;
		display: inline-block;
		border: 1px solid #f5d1d1;
		background: #fdd;
		color: #800 !important;
		width: 370px;
	}
	.message b {
		background: #fdd;
		color: #800 !important;
	}

	.app-root {
		background-color: #55a1e1;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		position: fixed;
	}

</style>
