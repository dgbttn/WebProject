<template>
    <div class="container">
        <div class="holder">

            <h1>Thay Đổi Mật Khẩu</h1>

            <form class="form-horizontal" @submit.prevent="changePass" v-on:keyup.enter="changePass">

                <div class="form-group">
                    <label class="control-label">Mật khẩu cũ:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="old_password" v-model="old_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('old_password')" class="text-danger">{{ errors.first('old_password') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Mật khẩu mới:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="new_password" v-model="new_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('new_password')" class="text-danger">{{ errors.first('new_password') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Nhập lại mật khẩu mới:</label>
                    <div v-bind:class="{ 'has-error' : errors.has('password') }">
                        <input name="re_new_password" v-model="re_new_password" v-validate="'required|min:6'" class="form-control" type="password">
                        <span v-show="errors.has('re_new_password')" class="text-danger">{{ errors.first('re_new_password') }}</span>
                    </div>
                </div>

            </form>

            <center><a class="button login-btn" v-on:click="changePass">Xác nhận</a></center>
        </div>
        <div class="msg-success" id="success-block" style="display: none">
            <b>Thành công!</b> Thay đổi mật khẩu thành công.
        </div>
        <div class="msg-error" id="error-block" style="display: none">
            <b>Lỗi!</b> Mật khẩu cũ không chính xác.
        </div>
        <div class="msg-error-pass" id="error-pass-block" style="display: none">
            <b>Lỗi!</b> Nhập lại mật khẩu mới chưa trùng với mật khẩu mới.
        </div>
        <div class="msg-warning" id="warning-block" style="display: none">
            <b>Cảnh báo!</b> Mật khẩu mới trùng với mật khẩu cũ.
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
        created() {
            let token = this.$cookie.get('user');
            if (token == null) {
                this.$router.push('/login');
            }
            else {
                var decoded_token = this.$jwtDec.decode(token);
                this.username = decoded_token.username;
            }
        },
        methods: {
            changePass(){
                var msg_success = document.getElementById("success-block");
                var msg_error = document.getElementById("error-block");
                var msg_error_pass = document.getElementById("error-pass-block");
                var msg_warning = document.getElementById("warning-block");
                if(this.new_password == this.old_password){
                    msg_warning.style.display = "";
                    setTimeout(function(){ msg_warning.style.display = "none" }, 3000);
                } else {
                    if(this.re_new_password == this.new_password){
                        console.log(this.username);
                        var url = 'http://localhost/uFaculty/account/AccountController/changePassword';
                        this.$http.post(url,{
                            username: this.username,
                            old_password: this.old_password,
                            new_password: this.new_password
                        }).then(function (data) {
                            //console.log(data.body);
                            let status = data.body.status;
                            if (status == 1) {
                                msg_success.style.display = "";
                                setTimeout(function(){ msg_success.style.display = "none" }, 3000);
                            }
                            if (status == 0) {
                                msg_warning.style.display = "";
                                setTimeout(function(){ msg_warning.style.display = "none" }, 3000);
                            }
                            if (status == -1) {
                                msg_error.style.display = "";
                                setTimeout(function(){ msg_error.style.display = "none" }, 3000);
                            }
                        })
                    }else {
                        msg_error_pass.style.display = "";
                        setTimeout(function(){ msg_error_pass.style.display = "none" }, 3000);
                    }
                }
            }
        }
    }

</script>

<style scoped>

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

    .text-danger {
        color: #a94442;
        display: block;
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .msg-success{
        margin: 20px 0;
        padding: 20px 20px 20px 20px;
        min-height: 22px;
        display: inline-block;
        border: 1px solid #cce6bb;
        background:#def6cf;
        color: #265708 !important;
    }
    .msg-success b {
        background:#def6cf;
        color: #265708 !important;
    }
    .msg-error{
        margin: 20px 0;
        padding: 20px 20px 20px 20px;
        min-height: 22px;
        display: inline-block;
        border: 1px solid #f5d1d1;
        background: #fdd;
        color: #800 !important;
    }
    .msg-error b {
        background: #fdd;
        color: #800 !important;
    }
    .msg-error-pass{
        margin: 20px 0;
        padding: 20px 20px 20px 20px;
        min-height: 22px;
        display: inline-block;
        border: 1px solid #f5d1d1;
        background: #fdd;
        color: #800 !important;
    }
    .msg-error-pass b {
        background: #fdd;
        color: #800 !important;
    }
    .msg-warning{
        margin: 20px 0;
        padding: 20px 20px 20px 20px;
        min-height: 22px;
        display: inline-block;
        border: 1px solid #dace89;
        background:#ffb;
        color: #000 !important;
    }
    .msg-warning b {
        background:#ffb;
        color: #000 !important;
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
        background-color: #fcfff6;
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