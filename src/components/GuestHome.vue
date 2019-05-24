<template>
	<app-root name="app-root">
		<div class="header">
			<nav class="navbar">
				<div v-show="useMode==1" class="backward-btn" v-on:click="back">
					<a class="nav-link">
						<i class="fa fa-chevron-left"></i>
						<span>Back</span>
					</a>
				</div>

				<div class="account">
					<a class="nav-link account-content dropdown-account">
						<img src="../image/avatar.jpg" alt="user_avatar" class="avatar">

						<span class="account-name">{{ username }}</span>

						<i class="fa fa-chevron-down"></i>
					</a>
				</div>
			</nav>
		</div>

		<div class="title">
			<h1>{{title}}</h1>
			<span class="note">*Chọn đơn vị hoặc lĩnh vực, tiếp đó chọn giảng viên để xem chi tiết.</span>

			<button type="button" v-on:click="initRandomList">Tạo mặc định</button>
		</div>

		<div v-show="useMode==0" class="content-bound">
			<div class="search-field">
				<div class="tabs-navigation">
					<span class="tab-item unit-search-btn active" v-on:click="openSearchItem(0)">
						Đơn vị công tác
					</span>
					<span class="tab-item field-search-btn" v-on:click="openSearchItem(1)">
						Lĩnh vực quan tâm
					</span>
				</div>

				<div class="search-container">
					<div v-show="searchRule==0" class="search-content unit-search">
						<ul class="unit-list">
							<li v-for="(unit, i) in unitList"
								v-on:click="selectUnit(i)"
								v-bind:class="{active: selectedUnit==i}">
								{{unit.name}}
							</li>
						</ul>
					</div>
					<div v-show="searchRule==1" class="search-content field-search">

					</div>
				</div>
			</div>

			<div class="show-field">
				<label>{{ showLabel }}</label> <br>

				<div class="table-bound">
					<table>
						<!-- Columns' width -->
						<col width="43px">  <!-- STT -->
						<col width="120px"> <!-- Học vị -->
						<col width="200px"> <!-- Tên giảng viên -->
						<col width="320px"> <!-- Đơn vị -->

						<tr>
							<th class="stt">STT</th>
							<th>Học vị</th>
							<th>Tên giảng viên</th>
							<th>Đơn vị</th>
						</tr>

						<tr v-for="(officer,i) in showOfficerList" v-on:click="selectOfficer(officer.id)">
							<td class="stt">{{i+1}}</td>
							<td>{{officer.degree}}</td>
							<td>{{officer.name}}</td>
							<td>{{officer.unit}}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div v-show="useMode==1" class="officer-info">
			<div class="info-container">
				<div class="name">
					<i class="fa fa-user"></i>
					<label >{{infoList[4]}}. {{infoList[0]}}</label>
				</div>

				<div class="details">
					<div class="detail-fields" v-for = "(field, i) in infoTitleList">
						<i class="info-icon" :class="infoIconList[i]"></i>
						<label >{{infoTitleList[i]}}:&nbsp{{infoList[i+1]}}</label>
					</div>
				</div>
			</div>

			<div class="research-topic">
				<span class="big-field-title">
					Chủ đề nghiên cứu
				</span>
				<div class="show-field">
					<ul class="topic-list">
						<li	v-for="topic in officerInfo.researchTopics">
							{{topic.name}}
						</li>
					</ul>
				</div>
			</div>

			<div class="interested-field">
				<span class="big-field-title">
					Lĩnh vực quan tâm
				</span>

				<div class="show-field">
					<ul class="field-list">
						<li v-for="field in officerInfo.interestedFields">
							{{field}}
						</li>
					</ul>
				</div>
			</div>
		</div>

	</app-root>
</template>

<script>
import {OfficerAccount} from './classes/OfficerAccount.js'

export default {
	name: 'GuestHome',
	data() {
		return {
			titles: ['Tìm kiếm giảng viên', 'Thông tin giảng viên'],
			username: 'Sinh viên',
			searchRule: 0, // =0 for unit, =1 for field
			selectedUnit: -1,
			selectedField: -1,
			selectedFieldName: '',
			selectedOfficer: -1,
			officerInfo: {},
			unitList: [],
			fieldList: [],
			officerList: [],
			useMode: 0, //0 for search officers, 1 for show selected officer's info

		}
	},

	created() {
		// this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAll')
		// 		.then(function (data) {
		// 			this.unitList = [];
		// 			for(var i in data.body.data) {
		// 				this.unitList.push({
		// 					id: data.body.data[i].faculty_id,
		// 					name: decodeURIComponent(escape(data.body.data[i].name))
		// 				});
		// 			}
		// 		})
		// this.$http.get('http://localhost/uFaculty/Staff/StaffController/getAll')
		// 		.then(function (data) {
		// 			this.officerList = [];
		// 			for(var i in data.body.data) {
		// 				this.officerList.push({
		// 					id : data.body.data[i].staff_id,
		// 					name : decodeURIComponent(escape(data.body.data[i].full_name)),
		// 					position : decodeURIComponent(escape(data.body.data[i].staff_type)),
		// 					mail : decodeURIComponent(escape(data.body.data[i].vnu_email)),
		// 					degree : decodeURIComponent(escape(data.body.data[i].academic_title)),
		// 					unit_id : data.body.data[i].faculty_id,
		// 					account_id : data.body.data[i].account_id
		// 				});
		// 			}
		// 		})
	},

	computed: {
		title() {
			return this.titles[this.useMode];
		},

		showLabel() {
			// unit
			if (this.searchRule==0) {
				if (this.selectedUnit<0) return '';
				return 'Danh sách Giảng viên thuộc ' + this.unitList[this.selectedUnit].name;
			}
			// field
			else {
				if (this.selectedField<0) return '';
				return 'Danh sách Giảng viên quan tâm Lĩnh vực ' + this.selectedFieldName;
			}
		},

		showOfficerList() {
			var uList = [];

			//unit
			if (this.searchRule==0) {
				if (this.selectedUnit<0) return uList;
				for (var i in this.officerList)
					if (this.officerList[i].unit_id == this.unitList[this.selectedUnit].id) {
						var chosenOne = this.officerList[i];
						chosenOne.unit = this.unitList[this.selectedUnit].name;
						uList.push(chosenOne);
					}
				return uList;
			}

			// field
			if (this.searchRule==1) {
				return uList;
			}
		},

		infoList() {
			return [
				this.officerInfo.name,
				this.officerInfo.number,
				this.officerInfo.position,
				this.officerInfo.unit,
				this.officerInfo.degree,
				this.officerInfo.phone,
				this.officerInfo.VNUmail,
				this.officerInfo.otherMail,
				this.officerInfo.website,
				this.officerInfo.address
			]
		},

		infoFields() {
			return [
				'name:',
				'number',
				'position',
				'unit',
				'degree',
				'phone',
				'VNUmail',
				'otherMail',
				'website',
				'address'
			]
		},

		infoTitleList() {
			return [
				'Mã cán bộ',
				'Chức vụ',
				'Đơn vị',
				'Học hàm, học vị',
				'Số điện thoại',
				'VNU email',
				'Email khác',
				'Website',
				'Địa chỉ'
			]
		},

		infoIconList() {
			return [
				"fa fa-id-card-o",
				"fa fa-briefcase",
				"fa fa-flag",
				"fa fa-graduation-cap",
				"fa fa-phone",
				"fa fa-envelope",
				"fa fa-envelope-o",
				"fa fa-paper-plane",
				"fa fa-map-marker"
			]
		},

	},

	methods: {
		initRandomList() {
			this.unitList.push({id: 1, name:'Bộ môn các hệ thống thông tin'});
			this.unitList.push({id: 2, name:'Bộ môn Công nghệ phần mềm'});
			this.unitList.push({id: 3, name:'Bộ môn Khoa học máy tính'});
			this.unitList.push({id: 4, name:'Bộ Công an'});

			let officers = [];
			officers.push(new OfficerAccount("1231","Hồ Văn Canh","canhkas","jashdj@vnu.edu.vn","Giảng viên","Tiến sĩ",4));
			officers.push(new OfficerAccount("3643","Lê Phê Đô","ádasd","ádasdsa@vnu.edu.vn","Giảng viên","Tiến sĩ",2));
			officers.push(new OfficerAccount("345sd","Minh Châu","sdfv","ádwqr@vnu.edu.vn","Giảng viên","Tiến sĩ",4));
			officers.push(new OfficerAccount("ád34","Hồ Văn Cường","ádadasđ","gdfdfgdf@vnu.edu.vn","Giảng viên","Tiến sĩ",4));

			for (var i in officers)
				this.officerList.push({
					number : officers[i].number,
					name : officers[i].name,
					position : officers[i].position,
					account : officers[i].account,
					mail : officers[i].mail,
					degree : officers[i].degree,
					unit_id : officers[i].unit,
				});

			this.officerInfo = {
				name:'Lê Đình Thanh',
				number:'12334',
				position:'Giảng viên',
				unit:'Phòng Thí nghiệm An toàn Thông tin',
				degree:'TS',
				phone:'0987654321',
				VNUmail:'thanhld@vnu.edu.vn',
				otherMail:'thanhld.vnuh@gmail.com',
				website:'https://uet.vnu.edu.vn/~thanhld',
				address:'Phòng 413 - E3',
				staff_id: '',
				researchTopics: [],
				interestedFields: []
			};

			this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
			this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
			this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});

			this.officerInfo.interestedFields.push('Network security');
			this.officerInfo.interestedFields.push('Web application security');
			this.officerInfo.interestedFields.push('Web protocol security');
			this.officerInfo.interestedFields.push('Web application security');
		},

		openSearchItem(index) {
			let Ubtn = document.getElementsByClassName('unit-search-btn')[0];
			let Fbtn = document.getElementsByClassName('field-search-btn')[0];

			Fbtn.className = Fbtn.className.replace(" active", "");
			Ubtn.className = Ubtn.className.replace(" active", "");

			this.searchRule = index;

			// select unit
			if (index==0) {
				Ubtn.className += " active";
			}

			// select field
			if (index==1) {
				Fbtn.className += " active";
			}
		},

		selectUnit(i) {
			this.selectedUnit = i;
		},

		selectOfficer(id) {
			this.selectedOfficer = id;
			// this.officerInfo = ....
			this.useMode = 1;
		},

		back() {
			this.useMode = 0;
		}
	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";

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
		text-align: center;
	}

	/* buttons on navbar*/
	.nav-link {
		display: block;
		padding: .5rem 1rem;
		color: inherit;
		cursor: pointer;
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

	.backward-btn {
		margin-top: 1px;
		float: left;
		width: 100px;
	}

	.backward-btn a i, .backward-btn a span {
		margin: 0px 5px;
		padding: 4px 0px;
	}

	.navbar div:hover, div:focus {
		background-color: #ffcd1f;
		color: #455358;
	}

	.navbar div {transition: 0.2s;}

	.title {
		margin-top: 65px;
		margin-left: 50px;
		margin-right: 50px;
	}

	.note {
		font-size: 12px;
		font-style: italic;
		color: red;
	}

	.content-bound {
		margin-top: 10px;
		margin-left: 50px;
		margin-right: 50px;
	}

	.search-field {
		margin-top: 24px;
		margin-left: 5%;
		width: 35%;
		left: 0;
		position: fixed;
	}

	.content-bound .tab-item {
		font-size: 16px;
		background-color: #f1efda;
		padding: 10px 15px;
		cursor: pointer;
		display: inline-block;
	}

	.content-bound .tab-item:hover {background-color: #e3dfb5;}

	.content-bound .tabs-navigation span.active {
		font-weight: bold;
		background-color: #d9e9f2;
		color: #3E5252;
		border-top: 3px solid #3EB3F6;
	}

	.search-container {
		transition: 0.2s;
		border: 2px solid;
		border-color: #d9e9f2 transparent #d9e9f2 #d9e9f2;
		padding: 5px;
		height: 380px;
		overflow: auto;
	}

	::-webkit-scrollbar {
		width: 8px;
		height: 12px;
	}

	::-webkit-scrollbar-track {
		border: 1px solid #ff8080;
		border-radius: 8px;
	}

	::-webkit-scrollbar-thumb {
		background: #ff8080;
		border-radius: 8px;
	}

	::-webkit-scrollbar-thumb:hover {
		background: #ff4d4d;
	}

	.content-bound .unit-list {
		list-style-type: none;
		font-size: 14.5px;
		padding-left: 30px;
		padding-top: 5px;
		padding-bottom: 5px;
		margin: 0px 0px;
	}

	.content-bound .unit-list li {
		margin: 1.5px 0px;
		cursor: pointer;
		color: #40bf9f;
	}

	.content-bound .unit-list li:hover {color: #2d8386;}

	.content-bound .unit-list li.active {font-weight: bold;}

	.content-bound .show-field {
		margin-left: 43%;
		margin-top: 10px;
		text-align: left;
	}

	table {
		border-spacing: 0px;
		margin-top: 8px;
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

	.table-bound tr:hover {
		background-color: #ddd;
		font-weight: bold;
	}
	.table-bound tr {cursor: pointer;}

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

	h1 {
		font-size: 22px;
		text-align: center;
		font-family: "brandon-grotesque", "Brandon Grotesque", "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-weight: 500;
		text-transform: uppercase;
	}

	img {
		vertical-align: middle;
		border-style: none;
		margin: 0px 5px;
	}

	a {text-decoration: none;}

	.officer-info {
		margin: 25px 50px 0px 50px;
	}

	.info-container {
		margin-left: 40px;
		left: 0;
		width: 35%;
		position: fixed;
	}

	.name {
		font-size: 25px;
		font-weight: bold;
		color: #990000;
	}

	.name i, .name label {margin: 0px 5px;}

	.details {
		display: block;
		padding: 5px 0px 5px 20px;
		color: #455358;
		font-size: 14px;
	}

	.detail-fields {margin-top: 3px;}

	.info-icon {
		padding: 0px 5px;
		vertical-align: middle;
		text-align: center;
		width: 25px;
		font-size: 18px;
		display: inline-block;
	}

	.research-topic {margin-left: 38%;}

	.interested-field {
		margin-left: 70%;
		margin-top: -42px;
	}

	.officer-info div span.big-field-title {
		font-size: 16px;
		font-weight: bold;
		background-color: #ffcccc;
		color: #3E5252;
		border-top: 3px solid #ff4d4d;
		padding: 7px 15px;
		display: inline-block;
		cursor: pointer;
	}

	.officer-info div span.big-field-title:hover {background-color: #ffb3b3;}

	.officer-info .show-field {
		transition: 0.2s;
		border: 2px solid;
		border-color: #ffcccc transparent transparent #ffcccc;
		padding: 5px;
		height: 55%;
		width: 25%;
		position: fixed;
		overflow: auto;
	}

	.officer-info ::-webkit-scrollbar {
		width: 8px;
		height: 12px;
	}

	.officer-info ::-webkit-scrollbar-track {
		border: 1px solid #3EB3F6;
		border-radius: 8px;
	}

	.officer-info ::-webkit-scrollbar-thumb {
		background: #85cff9;
		border-radius: 8px;
	}

	.officer-info ::-webkit-scrollbar-thumb:hover {background: #3EB3F6;}

	*{line-height: 1.5;}
</style>
