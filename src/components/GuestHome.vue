<template>
	<app-root name="app-root">
		<div class="header">
			<nav class="navbar">
				<div v-show="useMode==1" class="backward-btn" v-on:click="back">
					<a class="nav-link">
						<i class="fa fa-chevron-left"></i>
						<span>Trở lại</span>
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
						<SelectTreeItem class="select-tree" :item="treeData"
							@select = "selectField">
						</SelectTreeItem>
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
			<div class="container">
				<div class="info-container">
					<div class="info">

						<div class="name">
							<i class="fa fa-user"></i>
							<label >{{infoList[4]}}. {{infoList[0]}}</label>
						</div>

						<div class="details">
							<div class="detail-fields" v-for = "(field, i) in infoTitleList">
								<div class="detail-label">
									<i class="info-icon" :class="infoIconList[i]"></i>
									<label >{{infoTitleList[i]}}:&nbsp</label>
								</div>

								<div class="detail-value">
									<label>{{infoList[i+1]}}</label>
								</div>
							</div>
						</div>
					</div>

					<img class="officer-image" v-bind:src="avatar_src">
				</div>

				<br>

				<div class="interested-field">
					<span class="big-field-title">
						Lĩnh vực quan tâm
					</span>
					<span class="sec-field-title">
						Tất cả lĩnh vực
					</span>
					<br>

					<div class="name-field">
						<ul class="field-list">
							<li v-for="field in officerInfo.interestedFields">
								{{field.name}}
							</li>
						</ul>
					</div>

					<div class="show-field">
						<div class="content-show">
							<CheckTreeItem class="check-tree" :item="treeData">
							</CheckTreeItem>
						</div>
					</div>
				</div>

				<div class="research-topic">
					<span class="big-field-title">
						Chủ đề nghiên cứu
					</span>
					<span class="sec-field-title">
						Mô tả
					</span>

					<br>
					<div class="name-field">
						<ul class="topic-list">
							<li	v-for="(topic,i) in officerInfo.researchTopics" class="topic-name"
								v-on:click= "selectTopic(i)">
								<span>{{topic.name}}</span>
							</li>
						</ul>
					</div>

					<div class="show-field">
						<div class="content-show" v-if="selectedTopic>=0">
							<span>{{officerInfo.researchTopics[selectedTopic].description}}</span>
						</div>
					</div>
				</div>

			</div>
		</div>

	</app-root>
</template>

<script>
import {OfficerAccount} from './classes/OfficerAccount.js'
import SelectTreeItem from './classes/SelectTreeItem.vue'
import CheckTreeItem from './classes/CheckTreeItem.vue'

export default {
	name: 'GuestHome',
	components: {
		SelectTreeItem,
		CheckTreeItem
	},

	data() {
		return {
			titles: ['Tìm kiếm giảng viên', 'Thông tin giảng viên'],
			searchRule: 0, // =0 for unit, =1 for field
			selectedUnit: -1,
			selectedField: null,
			selectedOfficer: -1,
			officerInfo: {},
			unitList: [],
			treeData: {name: 'sss', children: []},
			officerList: [],
			useMode: 0, //0 for search officers, 1 for show selected officer's info
			selectedTopic: -1,
			avatar_src: require('../image/officer_avatar.jpg'),
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

		this.unitList.push({id: 3, name:'Bộ môn Khoa học máy tính'});
		this.unitList.push({id: 4, name:'Bộ Công an'});
		this.unitList.push({id: 1, name:'Bộ môn các hệ thống thông tin'});
		this.unitList.push({id: 2, name:'Bộ môn Công nghệ phần mềm'});
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
				interestedFields: ['4','2']
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
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});
		this.officerInfo.researchTopics.push({name: 'An toàn thông tin', description: 'Môn An toàn thông tin'});
		this.officerInfo.researchTopics.push({name: 'Phát triển ứng dụng web', description: 'Môn Phát triển ứng dụng web'});
		this.officerInfo.researchTopics.push({name: 'Mạng cảm biến không dây', description: 'Môn Mạng cảm biến không dây'});

		this.officerInfo.interestedFields.push({id: '3', name: 'Network security'});
		// this.officerInfo.interestedFields.push({id: '6', name: 'Web application security'});
		this.officerInfo.interestedFields.push({id: '7', name: 'Web protocol security'});
		this.officerInfo.interestedFields.push({id: '8', name: 'Web application security'});

		this.treeData = {
			id: '1',
			name: 'My Tree',
			children: [
				{ id: '2', name: 'hello' },
				{ id: '3', name: 'wat' },
				{
					id: '4', name: 'child folder',
					children: [
						{
							id: '5', name: 'child folder',
							children: [
								{ id: '6', name: 'hello' },
								{ id: '7', name: 'wat' }
							]
						},
						{ id: '8', name: 'hello' },
						{ id: '9', name: 'wat' },
						{
							id: '10', name: 'child folder',
							children: [
								{ id: '11', name: 'hello' },
								{ id: '12', name: 'wat' }
							]
						}
					]
				}
			]
		};

		this.setupFieldTree(this.treeData, false);
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
				if (!this.selectedField) return '';
				return 'Danh sách Giảng viên quan tâm Lĩnh vực ' + this.selectedField.name;
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
				if (!this.selectedField) return uList;
				for (var i in this.officerList)
					if (this.officerList[i].interestedFields.includes(this.selectedField.id)) {
						var chosenOne = this.officerList[i];
						chosenOne.unit = this.unitList.find(u => u.id == chosenOne.unit_id).name;
						uList.push(chosenOne);
					}
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

		},

		setupFieldTree(item, selected) {
			selected = selected || this.officerInfo.interestedFields.map(f => f.id).includes(item.id);
			item.isSelected = selected;
			for (var i in item.children)
				this.setupFieldTree(item.children[i], selected);
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

		selectField(item) {
			if (this.selectedField) {
				let oldSelected = document.getElementById(this.selectedField.id);
				oldSelected.className = oldSelected.className.replace(" selected", "");
			}

			document.getElementById(item.id).className += " selected";
			this.selectedField = {id: item.id, name: item.name};
		},

		selectOfficer(id) {
			this.selectedOfficer = id;
			// this.avatar_src = ...
			// this.officerInfo = ....
			this.useMode = 1;
		},

		selectTopic(i) {
			if (this.selectedTopic>-1)
				document.getElementsByClassName('topic-name')[this.selectedTopic].className =
				document.getElementsByClassName('topic-name')[this.selectedTopic].className.replace(" active", "");
			this.selectedTopic = i;
			if (i<0) return;
			document.getElementsByClassName('topic-name')[i].className += " active";
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
		width: 150px;
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
		/* margin-top: 8px; */
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
		background-color: #FFCCCC;
		color: #3E5252;
		border-top: 3px solid #FF4D4D;
	}

	.search-container {
		transition: 0.2s;
		border: 2px solid;
		border-color: #d9e9f2 transparent #d9e9f2 #d9e9f2;
		height: 380px;
		overflow: auto;
		/* padding: 15px 5px 5px 15px; */
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
		/* padding: 5px 5px 5px 15px; */
		margin: 0px 0px;
	}

	.content-bound .unit-list li {
		margin: 1.5px 0px;
		cursor: pointer;
		color: #00cca3;
	}

	.content-bound .unit-list li:hover {color: #2d8386;}

	.content-bound .unit-list li.active {font-weight: bold;}

	.content-bound .show-field {
		margin-left: 43%;
		margin-top: 20px;
		text-align: left;
	}

	.select-tree {
		margin: 10px 5px 5px 15px;
	}

	table {
		border-spacing: 0px;
		margin-top: 20px;
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

	.container {
		margin: 25px 50px 0px 50px;
	}

	.info-container {
		margin: 20px 60px 20px 60px;
		left: 0;
	}

	.container .info {
		width: 55%;
		display: inline-block;
	}

	.container .officer-image {
		display: inline-block;
		max-height: 260px;
		float: right;
		border: 1px solid gray;
		margin-right: 10%;
		max-width: 30%;
		top: 0;
	}

	.container .name {
		font-size: 25px;
		font-weight: bold;
		color: #990000;
	}

	.container .name i, .name label {margin: 0px 5px;}

	.container .details {
		display: inline-block;
		padding: 5px 0px 5px 35px;
		color: #455358;
		font-size: 14px;
	}

	.container .detail-fields {margin-top: 3px;}

	.container .detail-fields div {display: inline-block;}

	.container .info-icon {
		padding: 0px 5px;
		vertical-align: middle;
		text-align: center;
		width: 25px;
		font-size: 18px;
		display: inline-block;
	}

	.detail-value {word-break: break-all;}

	.research-topic {
		padding: 20px 40px 20px 40px;
	}

	.interested-field {
		padding: 20px 40px 20px 40px;
	}

	.big-field-title {
		font-size: 17px;
		font-weight: bold;
		background-color: #ffcccc;
		border-top: 3px solid #ff4d4d;
		padding: 7px 15px;
		display: inline-block;
		width: 274px;
		vertical-align: middle;
		text-align: center;
	}

	.sec-field-title {
		font-size: 17px;
		font-weight: bold;
		padding: 7px 15px;
		display: inline-block;
		width: 60%;
		overflow: hidden;
		vertical-align: middle;
		text-align: center;
		border-top: 3px solid #ff8a5c;
		background-color: #ffc8b3;
	}

	.name-field {
		transition: 0.2s;
		border: 2px solid;
		border-color: #ffcccc #ffcccc #ffcccc #ffcccc;
		width: 300px;
		height: 300px;
		background-color: #f8f8f8;
		overflow-y: auto;
		display: inline-block;
	}

	.container .show-field {
		display: inline-block;
		height: 300px;
		width: 60%;
		overflow: auto;
		background-color: #f8f8f8;
		border: 2px solid;
		border-color: #ffc8b3 #ffc8b3 #ffc8b3 #ffc8b3;
		padding: 0px 13px;
	}

	.container .content-show {
		display: block;
		padding: 15px 10px 10px 10px;
		font-size: 14px;
	}

	.container .content-show span {
		white-space: pre-wrap;
	}

	.officer-info ::-webkit-scrollbar {
		width: 8px;
		height: 8px;
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

	ul {
		margin: 0px;
		padding: 0px;
		list-style-type: none;
	}

	ul li {
		padding: 13px 20px;
		background-color: #E0EDF4;
		cursor: pointer;
		font-size: 14px;
		color: #3E5252;
	}

	ul li:hover {
		background-color: #fff9b3;
	}

	.topic-list li.active {
		border-left: 3px solid #ffcd1f;
		background-color: #fff9b3;
		font-weight: bold;
		font-size: 15px;
	}

	*{line-height: 1.5;}
</style>
