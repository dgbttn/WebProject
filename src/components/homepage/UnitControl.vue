<template >
	<div class="content-bound">

		<span class="note">*Quyền quản trị viên</span>
		<br>

		<button type="button" v-on:click="initRandomList">Tạo mặc định</button>
		<button type="button" class="add-btn" v-on:click="adding=true" @keyup.esc="addCancel">Thêm mới</button>

		<div class="table-bound">
			<table>
				<!-- Columns' width -->
				<col width="43px">  <!-- STT -->
				<col width="320px"> <!-- Tên đơn vị -->
				<col width="120px"> <!-- Loại đơn vị -->
				<col width="120px"> <!-- Địa chỉ -->
				<col width="107px"> <!-- Điện thoại -->
				<col width="140px"> <!-- Website -->

				<!-- Adding Form -->
				<tr v-if="adding" class="adding-form" @keyup.esc="addCancel" @keyup.enter="addUnit">
					<td></td>
					<td v-for="(content, key,) in newUnit">
						<input type="text" v-model="newUnit[key]">
					</td>

					<td class="confirm-domain">
						<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="addUnit"></i>
						<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="addCancel"></i>
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
				<tr v-for="(unit, i) in list">
					<td class="stt">{{i+1}}</td>
					<td v-for="(content, key, j) in unit" @dblclick="editing=(i+'_'+j); editedValue=content; editKey=key">
						<label v-if="editing != (i+'_'+j)">{{ content }}</label>
						<input class="edit-input" type="text" v-else v-model="editedValue" v-focus
						@keyup.esc="editCancel"
						@keyup.enter="valueEditing(i,key)">
					</td>
					<td class="confirm-domain">
						<i v-if="editing.startsWith(i+'_')" class="fa fa-check confirm-btn ok-btn" v-on:click="valueEditing(i,)"></i>
						<i v-if="editing.startsWith(i+'_')" class="fa fa-times confirm-btn no-btn" v-on:click="editCancel()"></i>
						<i v-if="!editing.startsWith(i+'_')" class="fa fa-trash confirm-btn del-btn" v-on:click="removeUnit(i)"></i>
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
			list: [],
			editing: '',
			editedValue: null,
			editKey: null,
			adding: false,
			newUnit: {name: '',type: '',address: '',phone: '',website: ''},
		}
	},

	methods: {
		initRandomList() {
			var units = [];
			units.push(new Unit("Bộ môn Các Hệ thống Thông tin", "Bộ môn", "", "", ""));
			units.push(new Unit("Bộ môn Công nghệ Phần mềm", "Bộ môn", "", "", ""));
			units.push(new Unit("Bộ môn Khoa học Máy tính", "Bộ môn", "", "", ""));
			units.push(new Unit("Bộ môn Khoa học và Ký thuật Tính toán", "Bộ môn", "", "", ""));
			units.push(new Unit("Bộ môn Mạng và Truyền thông Máy tính", "Bộ môn", "406-E3", "", ""));

			for (var i in units)
			this.list.push({
				name: units[i].name,
				type: units[i].type,
				address: units[i].address,
				phone: units[i].phone,
				website: units[i].website
			});
		},

		valueEditing(i, j) {
			j = (j|| this.editKey);
			this.editing = '';
			this.list[i][j] = this.editedValue;
		},

		editCancel() {
			this.editing = '';
		},

		addUnit() {
			var len = 0;
			for (var i in this.newUnit) len+= this.newUnit[i].length;
			if (len==0) {
				alert('Đơn vị mới chưa có thông tin nào.');
				return;
			}
			this.list.push(this.newUnit);
			this.addCancel();
		},

		addCancel() {
			this.adding = false;
			this.newUnit = {name: '',type: '',address: '',phone: '',website: ''};
		},

		removeUnit(i) {
			if(confirm("Bạn chắc chắn muốn xóa Đơn vị này chứ?")){
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

	input {
		width: 100%;
		height: 28px;
		box-sizing: border-box;
		display: block;
		padding: 6px 10px;
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
		padding: 6px 8px;
	}

	.table-bound tr td:nth-child(4) {text-align: center;}

	.table-bound tr:nth-child(even){background-color: #f2f2f2;}

	.table-bound tr:hover {background-color: #ddd;}

	.table-bound tr td:nth-child(7){
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
		width: 100px;
		padding: 8px;
	}

	.confirm-btn {
		padding: 0px 5px;
		vertical-align: middle;
		cursor: pointer;
		font-size: 22px;
	}

	.ok-btn 	  {color: #00e600;}
	/* .ok-btn:hover {color: #00cc00;} */
	.no-btn		  {color: #e60000;}
	/* .no-btn:hover {color: #cc0000;} */
	.del-btn      {color: #455358;}
	.del-btn:hover, .no-btn:hover, .ok-btn:hover {color: #ffcd1f;}

	* {
		transition: 0.2s;
	}
</style>
