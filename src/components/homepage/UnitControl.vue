<template >
	<div class="content-bound">

		<span class="note">*Quyền {{rule}}</span>
		<br>

		<button type="button" class="add-btn" v-on:click="adding=true; searching=false" @keyup.esc="addCancel">Thêm mới</button>
		<button type="button" class="search-btn" v-on:click="searching=true; adding=false;" @keyup.esc="searchCancel">Tìm kiếm</button>

		<div class="table-bound">
			<table>
				<!-- Columns' width -->
				<col width="43px">  <!-- STT -->
				<col width="320px"> <!-- Tên đơn vị -->
				<col width="150px"> <!-- Loại đơn vị -->
				<col width="120px"> <!-- Địa chỉ -->
				<col width="120px"> <!-- Điện thoại -->
				<col width="200px"> <!-- Website -->

				<!-- Adding Form -->
				<tr v-if="adding||searching" class="extend-form" @keyup.esc="formCancel" @keyup.enter="formAccept">
					<td class="icon-domain">
						<i class="fa fa-plus-square tool-icon" v-if="adding"></i>
						<i class="fa fa-search tool-icon" v-if="searching"></i>
					</td>
					<!-- <td v-for="(content, key, j) in newUnit" v-if="j>0">
						<input type="text" v-model="newUnit[key]">
					</td> -->

					<td>
						<input type="text" v-model="newUnit.name">
					</td>

					<td>
						<select v-model="newUnit.type">
							<option value=""></option>
							<option value="Bộ môn">Bộ môn</option>
							<option value="Phòng thí nghiệm">Phòng thí nghiệm</option>
						</select>
					</td>

					<td v-for="(content, key, j) in newUnit" v-if="j>2">
						<input type="text" v-model="newUnit[key]">
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
					<th>Tên đơn vị</th>
					<th>Loại đơn vị</th>
					<th>Địa chỉ</th>
					<th>Điện thoại</th>
					<th>Website</th>
				</tr>
				<tr v-for="(unit, i) in list" @dblclick="selectToEdit(i)">
					<td class="stt">{{i+1}}</td>

					<td v-for="(content, key, j) in unit" v-if="j>0">
						<label v-if="editing!=i">{{ content }}</label>

						<input v-else-if="j!=2" class="edit-input" type="text" v-model="editedUnit[key]"
							   @keyup.esc="editCancel"
							   @keyup.enter="valueEditing(i)">

						<select v-else class="edit-input" type="text" v-model="editedUnit.type"
								@keyup.esc="editCancel"
								@keyup.enter="valueEditing(i,key)">
							<option value="Bộ môn">Bộ môn</option>
							<option value="Phòng thí nghiệm">Phòng thí nghiệm</option>
						</select>
					</td>
					<td class="confirm-domain">
						<i v-if="editing==i" class="fa fa-check confirm-btn ok-btn" v-on:click="valueEditing(i,)">
							<span class="tooltip-text">Xác nhận</span>
						</i>
						<i v-if="editing==i" class="fa fa-times confirm-btn no-btn" v-on:click="editCancel()">
							<span class="tooltip-text">Hủy</span>
						</i>
						<i v-if="editing!=i" class="fa fa-pencil confirm-btn edit-btn" v-on:click="selectToEdit(i,-1)">
							<span class="tooltip-text">Sửa</span>
						</i>
						<i v-if="editing!=i" class="fa fa-trash confirm-btn del-btn" v-on:click="removeUnit(i)">
							<span class="tooltip-text">Xóa</span>
						</i>
					</td>
				</tr>
			</table>

		</div>
	</div>
</template>

<script>
import {Unit} from '../classes/Unit.js'

export default {
	name: 'UnitControl',
	data() {
		return {
			rule: 'quản trị viên',
			list: [],
			list_clone: [],
			editing: -1,
			editedUnit: null,
			editKey: null,
			adding: false,
			searching: false,
			searched: false,
			newUnit: {id:'',name: '',type: '',address: '',phone: '',website: ''},
		}
	},

	// Get data from server to this.list
	created() {
		this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAllUetOwner')
				.then(function (data) {
					this.list = [];
					for(var idx in data.body.data) {
						var name= decodeURIComponent(escape(data.body.data[idx].name));
						var address= decodeURIComponent(escape(data.body.data[idx].address));
						var type= decodeURIComponent(escape(data.body.data[idx].type));
						var id= data.body.data[idx].faculty_id;
						var phone= data.body.data[idx].phone_number;
						var website = data.body.data[idx].website;
						this.list.push(new Unit(id,name,type,address,phone,website));
					}
				})
	},

	methods: {
		// add some random units to the list
		init() {
			this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAllUetOwner')
					.then(function (data) {
						this.list = [];
						for(var idx in data.body.data) {
							var name= decodeURIComponent(escape(data.body.data[idx].name));
							var address= decodeURIComponent(escape(data.body.data[idx].address));
							var type= decodeURIComponent(escape(data.body.data[idx].type));
							var id= data.body.data[idx].faculty_id;
							var phone= data.body.data[idx].phone_number;
							var website = data.body.data[idx].website;
							this.list.push(new Unit(id,name,type,address,phone,website));
						}
					})
		},

		selectToEdit(i) {
			this.editing = i;
			this.editedUnit = {
				id: this.list[i].id,
				name: this.list[i].name,
				type: this.list[i].type,
				address: this.list[i].address,
				phone: this.list[i].phone,
				website: this.list[i].website
			};
		},

		// edit the selected value after acceptance
		valueEditing(i) {
			this.list[i] = {
				id: this.editedUnit.id,
				name: this.editedUnit.name,
				type: this.editedUnit.type,
				address: this.editedUnit.address,
				phone: this.editedUnit.phone,
				website: this.editedUnit.website
			}
			this.editedUnit = null;
			this.editCancel();
			// server
			var url = 'http://localhost/uFaculty/Faculty/FacultyControl/update';
			this.$http.post(url,{
				id: this.list[i].id,
				name: this.list[i].name,
				type: this.list[i].type,
				address: this.list[i].address,
				phone: this.list[i].phone,
				website: this.list[i].website
			})
		},

		// cancel editing value
		editCancel() {
			this.editing = -1;
		},

		formAccept() {
			if (this.adding) {
				this.addUnit();
				return;
			}
			else this.searchUnit();
		},

		formCancel() {
			if (this.adding) this.addCancel()
			else this.searchCancel();

		},

		// add new unit to the list
		addUnit() {
			var len = 0;
			for (var i in this.newUnit) len+= this.newUnit[i].trim().length;
			if (len==0) {
				alert('Đơn vị mới chưa có thông tin nào.');
				return;
			}
			if (!this.newUnit.name) {
				alert('Đơn vị mới chưa có tên.');
				return;
			}

			var url = 'http://localhost/uFaculty/Faculty/FacultyControl/create';
			this.$http.post(url,{
				name: this.newUnit.name,
				type: this.newUnit.type,
				address: this.newUnit.address,
				phone: this.newUnit.phone,
				website: this.newUnit.website
			}).then(function (data) {
				this.newUnit.id = data.body.data[0].faculty_id;
				this.list.push(this.newUnit);
				this.addCancel();
			})
		},

		// cancel adding new unit
		addCancel() {
			this.adding = false;
			this.newUnit = {id:'',name: '',type: '',address: '',phone: '',website: ''};
		},

		searchUnit() {
			if (this.searched)
				this.list = Array.from(Object.create(this.list_clone));
			else
				this.list_clone = Array.from(Object.create(this.list));

			this.searched = true;
			for (var i=0; i<this.list.length; i++) {
				var unit = this.list[i];
				var match = true;
				for (var j in unit)
					if (!unit[j].toLowerCase().includes(this.newUnit[j].toLowerCase())) {
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
			this.newUnit = {id:'', name: '',type: '',address: '',phone: '',website: ''};
		},

		// delete an unit
		removeUnit(i) {
			if(confirm("Bạn chắc chắn muốn xóa Đơn vị này chứ?")){
				var url = 'http://localhost/uFaculty/Faculty/FacultyControl/delete';
				this.$http.post(url, {
					id: this.list[i].id
				}).then(function ($data) {
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
	input:focus {
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
		padding: 6px 10px;
	}
	.table-bound tr td:nth-child(4) {text-align: center;}
	.table-bound tr:nth-child(even){background-color: #f2f2f2;}
	.table-bound tr:hover {background-color: #ddd;}
	.table-bound tr td:nth-child(7){
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
	.del-btn:hover, .no-btn:hover, .ok-btn:hover, .edit-btn:hover  {color: #ffcd1f;}
	tr:hover td .del-btn , tr:hover td .edit-btn {visibility: visible;}
	* {
		transition: 0.2s;
	}
</style>