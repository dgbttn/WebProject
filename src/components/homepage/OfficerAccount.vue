<template>
	<div class="content-bound">
		<span class="note">*Quyền {{rule}}</span>
		<br>

		<button type="button" v-on:click="initRandomList">Tạo mặc định</button>
		<button type="button" class="add-btn" v-on:click="adding=true; searching=false" @keyup.esc="addCancel">Thêm mới</button>
		<button type="button" class="search-btn" v-on:click="searching=true; adding=false;" @keyup.esc="searchCancel">Tìm kiếm</button>

		<div class="table-bound">
			<table>
				<!-- Columns' width -->
				<col width="40px">  <!-- STT -->
				<col width="90px">  <!-- Mã cán bộ -->
				<col width="180px"> <!-- Họ và tên -->
				<col width="100px"> <!-- Tài khoản -->
				<col width="200px"> <!-- VNU email -->
				<col width="120px"> <!-- Chức vụ -->
				<col width="80px">  <!-- Học vị -->
				<col width="180px"> <!-- Đơn vị công tác -->

				<!-- Adding Form -->
				<tr v-if="adding||searching" class="extend-form" @keyup.esc="formCancel" @keyup.enter="formAccept">
					<td class="icon-domain">
						<i class="fa fa-plus-square tool-icon" v-if="adding"></i>
						<i class="fa fa-search tool-icon" v-if="searching"></i>
					</td>
					<td v-for="(content, key, j) in newOfficer" v-if="j<6">
						<input type="text" v-model="newOfficer[key]">
					</td>

					<td>
						<select v-model="newOfficer.unit">
							<option v-for="val in unitList" :value="val">{{val}}</option>
						</select>
					</td>

					<td class="confirm-domain">
						<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="formAccept">
							<span class="tooltip-text">Xác nhận</span>
						</i>
						<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="formCancel">
							<span class="tooltip-text">Hủy</span>
						</i>
					</td>
				</tr>

				<!-- Content of Table -->
				<tr>
					<th class="stt">STT</th>
					<th>Mã CB</th>
					<th>Họ và tên</th>
					<th>Tài khoản</th>
					<th>VNU email</th>
					<th>Chức vụ</th>
					<th>Học vị</th>
					<th>Đơn vị công tác</th>
				</tr>

				<tr v-for="(officer, i) in list" @dblclick="selectToEdit(i)">
					<td class="stt">{{ i+1 }}</td>

					<td v-for="(content, key, j) in officer" >
						<label v-if="editing!=i">{{ content }}</label>
						<input class="edit-input" type="text" v-else-if="j<6" v-model="editedOfficer[key]"
								@keyup.esc="editCancel"
								@keyup.enter="valueEditing(i)">
						<select v-model="editedOfficer.unit" v-else>
							<option v-for="val in unitList" :value="val">{{val}}</option>
						</select>
					</td>

					<td class="confirm-domain">
						<i v-if="editing==i" class="fa fa-check confirm-btn ok-btn" v-on:click="valueEditing(i)">
							<span class="tooltip-text">Xác nhận</span>
						</i>
						<i v-if="editing==i" class="fa fa-times confirm-btn no-btn" v-on:click="editCancel()">
							<span class="tooltip-text">Hủy</span>
						</i>
						<i v-if="editing!=i" class="fa fa-pencil confirm-btn edit-btn" v-on:click="selectToEdit(i,-1)">
							<span class="tooltip-text">Sửa</span>
						</i>
						<i v-if="editing!=i" class="fa fa-trash confirm-btn del-btn" v-on:click="removeAccount(i)">
							<span class="tooltip-text">Xóa</span>
						</i>
					</td>

				</tr>
			</table>
		</div>
	</div>
</template>

<script>
import {OfficerAccount} from '../classes/OfficerAccount.js'

export default {
	name: 'OfficerAccount',
	data() {
		return {
			rule: 'quản trị viên',
			list: [],
			list_clone: [],
			editing: -1,
			editedOfficer: null,
			adding: false,
			searching: false,
			searched: false,
			newOfficer: {id:'', name:'', account:'', mail:'', position:'', degree:'', unit:''},
			unitList: [],
		}
	},

	// Get data from server to this.list
	created() {
		// this.$http.get('http://localhost/uFaculty/staff/StaffController/adminListStaff')
		// 		.then(function (data) {
		// 			this.list = [];
		// 			for(var idx in data.body.data) {
		// 				this.list.push({
		// 					id : data.body.data[idx].staff_number,
		// 					name : decodeURIComponent(escape(data.body.data[idx].full_name)),
		// 					account : data.body.data[idx].username,
		// 					mail : data.body.data[idx].vnu_email,
		// 					position : decodeURIComponent(escape(data.body.data[idx].staff_type)),
		// 					degree : decodeURIComponent(escape(data.body.data[idx].academic_title)),
		// 					unit : decodeURIComponent(escape(data.body.data[idx].name)),
		// 					account_id: data.body.data[idx].account_id
		// 				});
		// 			}
		// 		})
	},

	methods: {
		// add some random accounts to the list
		initRandomList() {
			var officers = [];
			officers.push(new OfficerAccount("1231","Hồ Văn Canh","canhkas","jashdj@vnu.edu.vn","Giảng viên","Tiến sĩ","Bộ Công an"));
			officers.push(new OfficerAccount("3643","Lê Phê Đô","ádasd","ádasdsa@vnu.edu.vn","Giảng viên","Tiến sĩ","Bộ Công an"));
			officers.push(new OfficerAccount("345sd","Minh Châu","sdfv","ádwqr@vnu.edu.vn","Giảng viên","Tiến sĩ","Bộ Công an"));
			officers.push(new OfficerAccount("ád34","Hồ Văn Cường","ádadasđ","gdfdfgdf@vnu.edu.vn","Giảng viên","Tiến sĩ","Bộ Công an"));

			for (var i in officers)
				this.list.push({
					id : officers[i].id,
					name : officers[i].name,
					account : officers[i].account,
					mail : officers[i].mail,
					position : officers[i].position,
					degree : officers[i].degree,
					unit : officers[i].unit
				});

			this.unitList = [
				"Bộ môn Các Hệ thống Thông tin",
				"Bộ môn Công nghệ Phần mềm",
				"Bộ môn Khoa học Máy tính",
				"Bộ môn Khoa học và Ký thuật Tính toán",
				"Bộ môn Mạng và Truyền thông Máy tính"
			];

			// this.$http.get('http://localhost/uFaculty/staff/StaffController/adminListStaff')
			// 		.then(function (data) {
			// 			//console.log(data);
			// 			this.list = [];
			// 			for(var idx in data.body.data) {
			// 				var full_name= decodeURIComponent(escape(data.body.data[idx].full_name));
			// 				var staff_type= decodeURIComponent(escape(data.body.data[idx].staff_type));
			// 				var academic_title= decodeURIComponent(escape(data.body.data[idx].academic_title));
			// 				var staff_id= data.body.data[idx].staff_id;
			// 				var vnu_email= data.body.data[idx].vnu_email;
			// 				var unit = decodeURIComponent(escape(data.body.data[idx].name));
			// 				var account = data.body.data[idx].username;
			// 				this.list.push(new OfficerAccount(staff_id,full_name,account,vnu_email,staff_type,academic_title,unit));
			// 			}
			// 			//console.log(full_name);
			// 		});
		},

		selectToEdit(i) {
			this.editing = i;

			this.editedOfficer = {
				id: this.list[i].id,
				name: this.list[i].name,
				account: this.list[i].account,
				mail: this.list[i].mail,
				position: this.list[i].position,
				degree: this.list[i].degree,
				unit: this.list[i].unit
			};
		},

		// edit the selected value after acceptance
		valueEditing(i) {
			this.list[i] = {
				id: this.editedOfficer.id,
				name: this.editedOfficer.name,
				account: this.editedOfficer.account,
				mail: this.editedOfficer.mail,
				position: this.editedOfficer.position,
				degree: this.editedOfficer.degree,
				unit: this.editedOfficer.unit
			}
			this.editedOfficer = null;
			this.editCancel();
			// var url = 'http://localhost/uFaculty/staff/StaffController/adminEditStaff';
			// this.$http.post(url,{
			// 	staff_id: this.list[i].id,
			// 	full_name: this.list[i].name,
			// 	vnu_email: this.list[i].account,
			// 	staff_type: this.list[i].mail,
			// 	academic_title: this.list[i].degree
			// })
		},

		// cancel editing value
		editCancel() {
			this.editing = -1;
		},

		formAccept() {
			if (this.adding) {
				this.addOfficer();
				return;
			}
			else this.searchOfficer();
		},

		formCancel() {
			if (this.adding) this.addCancel()
			else this.searchCancel();

		},

		// add a new account to the list
		addOfficer() {
			if (!(this.newOfficer.name&&this.newOfficer.id&&this.newOfficer.account)) {
				alert('Cán bộ mới phải có đủ thông tin về Mã cán bộ, Họ và tên, Tài khoản.');
				return;
			}
			this.list.push(this.newOfficer);
			this.addCancel();
		},

		// cancel adding new account
		addCancel() {
			this.adding = false;
			this.newOfficer = {id:'', name:'', position:'', account:'', mail:'', degree:'', unit:''};
		},

		searchOfficer() {
			if (this.searched)
				this.list = Array.from(Object.create(this.list_clone));
			else
				this.list_clone = Array.from(Object.create(this.list));

			this.searched = true;
			for (var i=0; i<this.list.length; i++) {
				var officer = this.list[i];
				var match = true;
				for (var j in officer)
					if (!officer[j].toLowerCase().includes(this.newOfficer[j].toLowerCase())) {
						match = false;
						break;
					}
				if (!match) {
					this.list.splice(i,1);
					i--;
				}
			}
		},

		searchCancel() {
			if (this.searched) this.list = Array.from(Object.create(this.list_clone));
			this.searching = false;
			this.searched = false;
			this.newOfficer = {id:'', name:'', position:'', account:'', mail:'', degree:'', unit:''};
		},

		// delete an account
		removeAccount(i) {
			if(confirm("Bạn chắc chắn muốn xóa Tài khoản Cán bộ này chứ?")){
				this.list.splice(i,1);
			}

		}
	},

	directives: {
		focus: {
			inserted: function(el) {
				el.focus();
			}
		}
	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";
	@import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";

	.note {
		font-size: 11px;
		font-style: italic;
		color: red;
	}

	button {
		color: #fff;
	    background-color: #3366ff;
	    border-color: #1a53ff;

		display: inline-block;
	    padding: 6px 12px;
	    margin: 10px 10px;
	    font-size: 14px;
	    font-weight: bold;
	    line-height: 1.42857143;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: middle;
	    touch-action: manipulation;
	    cursor: pointer;
	    border: 2px solid transparent;
	    border-radius: 4px;
	}

	button:hover {
		background-color: #ffcd1f;
		color: #455358;
	}

	button:focus {
		outline: none;
		color: #fff;
		background-color: #3366ff;
	}

	table {border-spacing: 0px;}

	input, select {
		width: 100%;
		height: 30px;
		box-sizing: border-box;
		display: block;
		padding: 6px 6px;
		font-size: 14px;
		line-height: 1.5;
		border: 1px solid #ccc;
		border-radius: 4px;
		text-rendering: auto;
		margin: 0em;
		font: 400 13.3333px Arial;
	}

	input:focus, select:focus {
		border-color: #66afe9;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
	}

	.table-bound {
		font-family: "Helvetica", sans-serif;
		border-collapse: collapse;
		width: 100%;
	}

	.table-bound td, .table-bound th {
		border: 1px solid #ddd;
		padding: 6px 8px;
	}

	.table-bound tr td:nth-child(6), .table-bound tr td:nth-child(7) {text-align: center;}

	.table-bound tr:nth-child(even){background-color: #f2f2f2;}

	.table-bound tr:hover {background-color: #ddd;}

	.table-bound tr td:nth-child(9){
		background-color: #fff;
		border: none;
	}

	.table-bound tr:nth-child(1) td[class="icon-domain"] {
		background-color: #fff;
		border: none;
	}

	.table-bound th {
		padding: 9px 0px;
		text-align: center;
		background-color: #4CAF50;
		color: white;
		font-size: 14px;
	}

	.table-bound td {font-size: 13px;}

	.stt {text-align: center;}

	.confirm-domain {
		background-color: #fff;
		height: 100%;
		width: 70px;
		padding: 8px;
	}

	.icon-domain {
		background-color: #fff;
		height: 100%;
		padding: 8px;
		text-align: center;
	}

	.tool-icon {
		padding: 0px 2px;
		vertical-align: middle;
		font-size: 18px;
		color: #455358;
	}


	.confirm-btn {
		padding: 0px 5px;
		vertical-align: middle;
		cursor: pointer;
		font-size: 22px;
		position: relative;
	}

	.tooltip-text {
		word-wrap: normal;
		word-wrap: normal;
		visibility: hidden;
		width: auto;
		background-color: #4257B2;
		color: #fff;
		text-align: center;
		padding: 5px 5px;
		position: absolute;
		z-index: 1;
		margin-left: -50%;
		width: 100%;
		top: 170%;
		left: 35%;

		font-family: hurme_no2-webfont,-apple-system,BlinkMacSystemFont,sans-serif;
		font-size: 12px;
		font-weight: 600;
		transition: 0.2s;
	}

	.tooltip-text::after {
		content: "";
		position: absolute;
		bottom: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: transparent transparent #4257B2 transparent;
	}

	.confirm-btn:hover .tooltip-text {visibility: visible;}

	.ok-btn 	  {color: #00e600;}
	.no-btn		  {color: #e60000;}
	.del-btn      {color: #455358; visibility: hidden;}
	.edit-btn 	  {color: #3333cc; visibility: hidden;}
	.del-btn:hover, .no-btn:hover, .ok-btn:hover, .edit-btn:hover {color: #ffcd1f;}
	tr:hover td .del-btn, tr:hover td .edit-btn {visibility: visible;}

	* {
		transition: 0.2s;
	}
</style>
