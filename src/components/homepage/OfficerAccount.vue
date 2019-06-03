<template>
	<div class="content-bound">
		<span class="note">*Quyền {{rule}}</span>
		<br>

		<button type="button" class="add-btn" v-on:click="adding=true; searching=false" @keyup.esc="addCancel">Thêm mới</button>
		<button type="button" class="search-btn" v-on:click="searching=true; adding=false;" @keyup.esc="searchCancel">Tìm kiếm</button>
		<button type="button" class="file-btn" v-on:click="fileAdding=!fileAdding">Thêm tệp</button>
		<input v-if="fileAdding" type="file" class="file-browser"
			   accept=".xlsx"
			   ref="loadedFile"
			   v-on:change="loadFile(getDataFromJSON)">
		<td v-if="fileAdding" class="file-confirm-domain">
			<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="fileAddAccept">
				<span class="tooltip-text">Xác nhận</span>
			</i>
			<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="fileAddCancel">
				<span class="tooltip-text">Hủy</span>
			</i>
		</td>
		<br>

		<div class="table-bound">
			<table>
				<!-- Columns' width -->
				<col width="40px">  <!-- STT -->
				<col width="80px">  <!-- Mã cán bộ -->
				<col width="180px"> <!-- Họ và tên -->
				<col width="100px"> <!-- Tài khoản -->
				<col width="180px"> <!-- VNU email -->
				<col width="110px"> <!-- Chức vụ -->
				<col width="100px"> <!-- Học vị -->
				<col width="250px"> <!-- Đơn vị công tác -->

				<!-- Adding Form -->
				<tr v-if="adding" class="extend-form" @keyup.esc="addCancel" @keyup.enter="addOfficer">
					<td class="icon-domain">
						<i class="fa fa-plus-square tool-icon" v-if="adding"></i>
					</td>
					<td v-for="(content, key, j) in newOfficer" v-if="j<6">
						<input type="text" v-model="newOfficer[key]">
					</td>

					<td>
						<select v-model="newOfficer.faculty_name">
							<option v-for="val in unitList" :value="val">{{val}}</option>
						</select>
					</td>

					<td class="confirm-domain">
						<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="addOfficer">
							<span class="tooltip-text">Xác nhận</span>
						</i>
						<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="addCancel">
							<span class="tooltip-text">Hủy</span>
						</i>
					</td>
				</tr>


				<!-- Searching Form -->
				<tr v-if="searching" class="extend-form" @keyup.esc="searchCancel" @keyup.enter="searchOfficer">
					<td class="icon-domain">
						<i class="fa fa-search tool-icon" v-if="searching"></i>
					</td>
					<td v-for="(content, key, j) in newOfficer" v-if="j<4">
						<input type="text" v-model="newOfficer[key]">
					</td>

					<td>
						<select v-model="newOfficer.staff_type">
							<option v-for="pos in existedPositionList" :value="pos">{{pos}}</option>
						</select>
					</td>

					<td>
						<select v-model="newOfficer.academic_title">
							<option v-for="deg in existedDegreeList" :value="deg">{{deg}}</option>
						</select>
					</td>

					<td>
						<select v-model="newOfficer.faculty_name">
							<option v-for="unit in existedUnitList" :value="unit">{{unit}}</option>
						</select>
					</td>

					<td class="confirm-domain">
						<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="searchOfficer">
							<span class="tooltip-text">Xác nhận</span>
						</i>
						<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="searchCancel">
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

					<td v-for="(content, key, j) in officer" v-if="j<7" >
						<label v-if="editing!=i||j==2">{{ content }}</label>
						<input class="edit-input" type="text" v-else-if="j<6" v-model="editedOfficer[key]"
							   @keyup.esc="editCancel"
							   @keyup.enter="valueEditing(i)">
						<select v-model="editedOfficer.faculty_name" v-else>
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
	import XLSX from 'xlsx'

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
				fileAdding: false,
				newOfficer: {staff_number:'', full_name:'', account:'', vnu_email:'', staff_type:'', academic_title:'', faculty_name:'', account_id:'', staff_id:''},
				unitList: [],
				newOfficers: [],
			}
		},

		computed: {
			// ánh xạ drop down list của thuộc tính position
			existedPositionList() {
				var tmp_list = this.list_clone.map(off => off.staff_type);
				if (!tmp_list.hasOwnProperty('')) {
					tmp_list.unshift('');
				}
				return [...new Set(tmp_list)];
			},
			// ánh xạ drop down list của thuộc tính degree
			existedDegreeList() {
				var tmp_list = this.list_clone.map(off => off.academic_title);
				if (!tmp_list.hasOwnProperty('')) {
					tmp_list.unshift('');
				}
				return [...new Set(tmp_list)];
			},
			// ánh xạ drop down list của thuộc tính unit
			existedUnitList() {
				var tmp_list = this.list_clone.map(off => off.faculty_name);
				if (!tmp_list.hasOwnProperty('')) {
					tmp_list.unshift('');
				}
				return [...new Set(tmp_list)];
			}
		},
		// Get data from server to this.list
		created() {
			// lấy thông tin của tất cả các staff
			this.$http.get('http://localhost/uFaculty/staff/StaffController/adminListStaff')
					.then(function (data) {
						this.list = [];
						for(var i in data.body.data) {
							this.list.push({
								staff_number : data.body.data[i].staff_number,
								full_name : decodeURIComponent(escape(data.body.data[i].full_name)),
								account : data.body.data[i].username,
								vnu_email : data.body.data[i].vnu_email,
								staff_type : decodeURIComponent(escape(data.body.data[i].staff_type)),
								academic_title : decodeURIComponent(escape(data.body.data[i].academic_title)),
								faculty_name : decodeURIComponent(escape(data.body.data[i].name)),
								account_id: data.body.data[i].account_id,
								staff_id: data.body.data[i].staff_id,
							});
						}
						this.list_clone = Array.from(Object.create(this.list));
					})
			// lấy thông tin của tất cả các faculties
			this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAll')
					.then(function (data) {
						this.unitList = [];
						for(var i in data.body.data) {
							this.unitList.push(decodeURIComponent(escape(data.body.data[i].name)))
						}
					})
		},
		methods: {
			// add some random accounts to the list
			init() {
				// lấy lại thông tin của tất cả các staff và faculty khi có faculty mới được thêm vào
				this.$http.get('http://localhost/uFaculty/staff/StaffController/adminListStaff')
						.then(function (data) {
							this.list = [];
							for(var i in data.body.data) {
								this.list.push({
									staff_number : data.body.data[i].staff_number,
									full_name : decodeURIComponent(escape(data.body.data[i].full_name)),
									account : data.body.data[i].username,
									vnu_email : data.body.data[i].vnu_email,
									staff_type : decodeURIComponent(escape(data.body.data[i].staff_type)),
									academic_title : decodeURIComponent(escape(data.body.data[i].academic_title)),
									faculty_name : decodeURIComponent(escape(data.body.data[i].name)),
									account_id: data.body.data[i].account_id,
									staff_id: data.body.data[i].staff_id,
								});
							}
							this.list_clone = Array.from(Object.create(this.list));
						})
				this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAll')
						.then(function (data) {
							this.unitList = [];
							for(var i in data.body.data) {
								this.unitList.push(decodeURIComponent(escape(data.body.data[i].name)))
							}
						})
			},

			// select officer to edit informations
			selectToEdit(i) {
				this.editing = i;
				this.editedOfficer = {
					staff_id: this.list[i].staff_id,
					staff_number: this.list[i].staff_number,
					full_name: this.list[i].full_name,
					account: this.list[i].account,
					vnu_email: this.list[i].vnu_email,
					staff_type: this.list[i].staff_type,
					academic_title: this.list[i].academic_title,
					faculty_name: this.list[i].faculty_name,
				};
			},
			// edit the selected value after acceptance
			valueEditing(i) {

				this.list[i] = {
					staff_number: this.editedOfficer.staff_number,
					full_name: this.editedOfficer.full_name,
					account: this.list[i].account,
					vnu_email: this.editedOfficer.vnu_email,
					staff_type: this.editedOfficer.staff_type,
					academic_title: this.editedOfficer.academic_title,
					faculty_name: this.editedOfficer.faculty_name,
					account_id: this.list[i].account_id,
					staff_id: this.editedOfficer.staff_id,
				}
				// sửa thông tin của staff
				var url_findFacID = 'http://localhost/uFaculty/faculty/facultyControl/getFacultyId';
				var url = 'http://localhost/uFaculty/staff/StaffController/adminEditStaff';
				this.$http.post(url_findFacID,{
					faculty_name: this.editedOfficer.faculty_name
				}).then(function (data) {
					this.$http.post(url,{
						staff_id: this.editedOfficer.staff_id,
						full_name: this.editedOfficer.full_name,
						vnu_email: this.editedOfficer.vnu_email,
						staff_type: this.editedOfficer.staff_type,
						academic_title: this.editedOfficer.academic_title,
						faculty_id: data.body.data[0].faculty_id
					}).then(function (data) {
						this.editedOfficer = null;
						this.editCancel();
					})
				})

			},
			// cancel editing value
			editCancel() {
				this.editing = -1;
			},

			// accept adding/searching form
			formAccept() {
				if (this.adding) {
					this.addOfficer();
					return;
				}
				else this.searchOfficer();
			},

			// ignore adding/searching form
			formCancel() {
				if (this.adding) this.addCancel()
				else this.searchCancel();
			},
			// add a new account to the list
			addOfficer() {
				if (!(this.newOfficer.full_name&&this.newOfficer.vnu_email&&this.newOfficer.account)) {
					alert('Cán bộ mới phải có đủ thông tin về Mã cán bộ, Họ và tên, Tài khoản.');
					return;
				}
				// tạo staff mới, tạo 1 tài khoản mới ánh xạ đến staff vừa tạo và tạo ánh xạ đến faculty
				var url_findFacID = 'http://localhost/uFaculty/faculty/facultyControl/getFacultyId';
				var url_createAcc = 'http://localhost/uFaculty/account/accountController/register';
				var url = 'http://localhost/uFaculty/staff/StaffController/adminCreateStaff';

				this.$http.post(url_findFacID,{
					faculty_name: this.newOfficer.faculty_name

				}).then(function (data_findFacID) {
					this.$http.post(url_createAcc, {
						username: this.newOfficer.account,
						password: '123456789',
						role: 'lecture'

					}).then(function (data_createAcc) {
						if (data_createAcc.body.status == 0) {
							alert("Username đã tồn tại");
							return;
						}
						this.$http.post(url,{
							staff_number: this.newOfficer.staff_number,
							full_name: this.newOfficer.full_name,
							vnu_email: this.newOfficer.vnu_email,
							staff_type: this.newOfficer.staff_type,
							academic_title: this.newOfficer.academic_title,
							faculty_id: data_findFacID.body.data[0].faculty_id,
							account_id: data_createAcc.body.data[0].id

						}).then(function (data) {
							this.newOfficer.account_id = data_createAcc.body.data[0].id;
							this.newOfficer.staff_id = data.body.data[0].staff_id;
							this.list.push(this.newOfficer);
							this.addCancel();
						})
					})

				})
			},
			// cancel adding new account
			addCancel() {
				this.adding = false;
				this.newOfficer = {staff_number:'', full_name:'', account:'', vnu_email:'', staff_type:'', academic_title:'', faculty_name:'', account_id:'', staff_id:''};
			},
			// đọc dữ liệu từ file excel
			getDataFromJSON(json) {
				this.newOfficers = [];
				for (var i in json)
					if (i>0 && json[i] && json[i][0])
						this.newOfficers.push({
							account: json[i][1],
							password: json[i][2],
							name: json[i][3],
							VNUemail: json[i][4]
						});
			},
			// kiểm tra định dạng của file excel nhập vào
			validateData(data) {
				for (var i in data)
					for (var j in data[i])
						if (!data[i][j]) return false;
				return true;
			},

			// confirm add information from loaded file
			fileAddAccept() {
				// kiểm tra định dạng của file excel nhập vào
				if (!this.newOfficers.length || !this.validateData(this.newOfficers)) {
					alert('Tệp đính kèm không khả dụng');
					return;
				}
				// thêm nhân viên mới cùng tài khoản từ dữ liệu file excel
				var url_findFacID = 'http://localhost/uFaculty/faculty/facultyControl/getFacultyId';
				this.$http.post(url_findFacID,{
					faculty_name: this.unitList[0]

				}).then(function (data_findFacID) {
					for (let i in this.newOfficers) {
						var url_createAcc = 'http://localhost/uFaculty/staff/staffController/addExcel';
						this.$http.post(url_createAcc, {
							username: this.newOfficers[i].account,
							password: this.newOfficers[i].password,
							role: 'lecture',
							full_name: this.newOfficers[i].name,
							vnu_email: this.newOfficers[i].VNUemail,
							faculty_id: data_findFacID.body.data[0].faculty_id
						}).then(function (data_createAcc) {
							this.list.push({
								staff_number: '',
								full_name: decodeURIComponent(data_createAcc.body.data.full_name),
								account: data_createAcc.body.data.username,
								vnu_email: data_createAcc.body.data.vnu_email,
								staff_type: '',
								academic_title: '',
								faculty_name: this.unitList[0],
								account_id: data_createAcc.body.data.id,
								staff_id: data_createAcc.body.data.staff_id,
							});
						})
					}
					this.newOfficers = [];
					this.fileAddCancel();
				})
				// push this.newOfficers to server
			},

			// adding file cancel
			fileAddCancel() {
				this.fileAdding = false;
			},

			// tìm kiếm staff
			searchOfficer() {
				if (this.searched)
					this.list = Array.from(Object.create(this.list_clone));
				else
					this.list_clone = Array.from(Object.create(this.list));
				this.searched = true;
				for (var i=0; i<this.list.length; i++) {
					var officer = this.list[i];
					var match = true;
					for (var j in officer) {
						if ((officer[j] == null && this.newOfficer[j] != '') || (officer[j] != null && !officer[j].toLowerCase().includes(this.newOfficer[j].toLowerCase()))) {
							match = false;
							break;
						}
					}
					if (!match) {
						this.list.splice(i,1);
						i--;
					}
				}
			},

			// searching cancel
			searchCancel() {
				if (this.searched) this.list = Array.from(Object.create(this.list_clone));
				this.searching = false;
				this.searched = false;
				this.newOfficer = {staff_number:'', full_name:'', account:'', vnu_email:'', staff_type:'', academic_title:'', faculty_name:'', account_id:'', staff_id:''};
			},
			// load file excel
			loadFile(callback) {
				var f = this.$refs.loadedFile.files[0];
				var reader = new FileReader();
				reader.onload = function(e) {
					var data = e.target.result;
					data = new Uint8Array(data);
					var workbook = XLSX.read(data, {type: "array"});
					var firstSheetName = workbook.SheetNames[0];
					var worksheet = workbook.Sheets[firstSheetName];
					var json = XLSX.utils.sheet_to_json(worksheet, {header: 1});
					callback(json);
				}
				reader.readAsArrayBuffer(f);
			},
			// delete an account
			removeAccount(i) {
				if(confirm("Bạn chắc chắn muốn xóa Tài khoản Cán bộ này chứ?")){
					var url = 'http://localhost/uFaculty/account/AccountController/deleteUser';
					this.$http.post(url,{
						account_id: this.list[i].account_id
					}).then(function (data) {
						this.list.splice(i,1);
					})
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
	.note {
		font-size: 11px;
		font-style: italic;
		color: red;
	}
	button {
		color: #ffffff;
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
		background-color: #17b978;
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
		color: #ffffff;
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