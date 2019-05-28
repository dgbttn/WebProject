<template>
	<div class="container">
		<div class="holder">

			<h1>Đăng nhập</h1>

			<form class="form-horizontal" @submit.prevent="log" v-on:keyup.enter="log">
				<div class="form-group">
					<label class="control-label" for="username">Tên đăng nhập:</label>
					<div v-bind:class="{ 'has-error' : errors.has('username') }">
						<input name="username" v-model="username"  data-vv-delay="100" v-validate="'required|min:6'" class="form-control" type="text">
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

	</div>
</template>

<script>
export default {
	name: 'Login',
	data() {
		return {
			username: '',
			password: '',
			rule: 'teacher'
		}
	},

	methods: {
		// login
		log() {
			// this.$validator.validateAll().then((result) => {
			// 	if (!result) {
			// 		alert('Đăng nhập thất bại.');
			// 		return;
			// 	}
			// 	else {
			// 		console.log(this.nickname + ' ' + this.password + ' ' + this.rule);
			// 	}
			// });

			this.$router.push('/home');
		},

		guest_log() {
			this.$router.push('/guest-home');
		}
	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";
	@import ""https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"";

	div {display: block;}

	h1 {
		font-size: 30px;
		margin: 10px;
		text-align: center;
	}

	label {
		position: relative;
		min-height: 1px;
		padding-right: 15px;
		display: inline-block;
		max-width: 100%;
		font-weight: 700;
	}

	.button {
		color: #fff;
		background-color: #4eb14e;
		border-color: #46a046;

		display: inline-block;
		padding: 6px 12px;
		margin: 10px 0px;
		font-size: 14px;
		font-weight: 450;
		line-height: 1.42857143;
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;
		touch-action: manipulation;
		cursor: pointer;
		border: 2px solid transparent;
		border-radius: 4px;
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
		margin-right: -15px;
		margin-left: -15px;
		margin-bottom: 15px;
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
		background-color: #fff;
		font-family: 'Montserrat', sans-serif;
		display: grid;
		grid-template-rows: auto;
		padding-top: 50px;
		justify-items: center;
	}

	.holder {width: 300px;}

	* {
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 14px;
		color: #333333;
		background-color: #fff;
		box-sizing: border-box;
	}
</style>
