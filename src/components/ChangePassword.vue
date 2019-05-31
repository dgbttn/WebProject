<template>
    <div class="container">
        <div class="holder">

            <h1>Thay Đổi Mật Khẩu</h1>

            <form class="form-horizontal" @submit.prevent="changePass" v-on:keyup.enter="changePass">

                <div class="form-group">
                    <label class="control-label" for="old_password">Mật khẩu cũ:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="old_password" v-model="old_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('password')" class="text-danger">{{ errors.first('old_password') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="new_password">Mật khẩu mới:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="new_password" v-model="new_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('password')" class="text-danger">{{ errors.first('new_password') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="re_new_password">Nhập lại mật khẩu mới:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="re_new_password" v-model="re_new_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('password')" class="text-danger">{{ errors.first('re_new_password') }}</span>
                    </div>
                </div>

            </form>

            <center><a class="button login-btn" v-on:click="changePass">Xác nhận</a></center>
        </div>
        <div>
            <label name="notification" id="notification"></label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChangePassword",
        data() {
            return {
                username: '',
                old_password: '',
                new_password: '',
                re_new_password: ''
            }
        },
        methods: {
            changePass(){
                // if(this.re_new_password == this.new_password){
                //     var url = 'http://localhost/uFaculty/account/AccountController/changePassword';
                //     this.$http.post(url,{
                //         username: this.username,
                //         old_password: this.old_password,
                //         new_password: this.new_password,
                //         re_new_password: this.re_new_password
                //     }).then(function (data) {
                //         console.log(data);
                //     })
                // } else {
				//
                // }

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
