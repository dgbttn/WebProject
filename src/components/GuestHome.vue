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
			<img src="../image/Untitled-5.png" class="title-page">
		</div>

		<div class="title">
			<h1>{{title}}</h1>
			<span class="note">*Chọn đơn vị hoặc lĩnh vực, tiếp đó chọn giảng viên để xem chi tiết.</span>
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
				username: 'Sinh viên',
				searchRule: 0, // =0 for unit, =1 for field
				selectedUnit: -1,
				selectedField: null,
				selectedOfficer: -1,
				officerInfo: {},
				unitList: [],
				treeData: {name: 'sss', children: []},
				officerList: [],
				comparedList: [],
				useMode: 0, //0 for search officers, 1 for show selected officer's info
				avatar_src: require('../image/officer_avatar.jpg'),
				selectedTopic: -1
			}
		},

		created() {
			this.$http.get('http://localhost/uFaculty/Faculty/FacultyControl/getAll')
					.then(function (data) {
						this.unitList = [];
						for(var i in data.body.data) {
							this.unitList.push({
								id: data.body.data[i].faculty_id,
								name: decodeURIComponent(escape(data.body.data[i].name))
							});
						}
					});


			this.$http.get('http://localhost/uFaculty/Staff/StaffController/getAll')
					.then(function (data) {
						this.officerList = [];
						for(var i in data.body.data) {
							this.officerList.push({
								id : data.body.data[i].staff_id,
								number: data.body.data[i].staff_number,
								name : decodeURIComponent(escape(data.body.data[i].full_name)),
								position : decodeURIComponent(escape(data.body.data[i].staff_type)),
								VNUmail : decodeURIComponent(escape(data.body.data[i].vnu_email)),
								phone : data.body.data[i].phone_number,
								otherMail : data.body.data[i].other_email,
								address : data.body.data[i].address,
								website : data.body.data[i].website,
								degree : decodeURIComponent(escape(data.body.data[i].academic_title)),
								unit_id : data.body.data[i].faculty_id,
								account_id : data.body.data[i].account_id,
							});
						}
					});
			this.$http.get('http://localhost/uFaculty/Staff/StaffController/getAllResearchStaff')
					.then(function (data) {
						for(var i in data.body.data) {
							this.comparedList.push({
								research_id: data.body.data[i].research_id,
								staff_id: data.body.data[i].staff_id
							});
						}
					});

			this.$http.get('http://localhost/uFaculty/Research/ResearchControl/getAll')
					.then(function (data) {
						var rawData = data.body.data;
						var root = {id: 0, isSelected: false, name: 'Lĩnh vực nghiên cứu', children: []}
						for (var idx in rawData) {
							if (rawData[idx].parent_id == 0) {
								root.children.push(this.recursive(rawData[idx],rawData));
							}
						}
						this.treeData = root;
					});
		},

		computed: {

			// title of the current page
			title() {
				return this.titles[this.useMode];
			},

			// label of the search's result table
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

			// array of officers to import to table
			showOfficerList() {
				var uList = [];
				//unit
				if (this.searchRule==0) {
					if (this.selectedUnit<0) return uList;
					for (let i in this.officerList)
						if (this.officerList[i].unit_id == this.unitList[this.selectedUnit].id) {
							var chosenOne = this.officerList[i];
							chosenOne.unit = this.unitList[this.selectedUnit].name;
							uList.push(chosenOne);
						}
					return uList;
				} else {
					// field
					if (!this.selectedField) return uList;
					for (let i in this.officerList) {
						let check = false;
						for (let j in this.comparedList) {
							if (this.comparedList[j].staff_id == this.officerList[i].id && this.comparedList[j].research_id == this.selectedField.id) {
								check = true;
								break;
							}
						}
						if (check) {
							var chosenOneField = this.officerList[i];
							chosenOneField.unit = this.unitList.find(u => u.id == chosenOneField.unit_id).name;
							uList.push(chosenOneField);
						}
					}
					return uList;
				}
			},

			// get officer's info as a array
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

			// fields names of officer info
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

			// titles of officer info table
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

			// array of icon
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
			// build the object of fields base on data
			recursive(rawNode, rawData) {
				var node = {id: rawNode.research_id, isSelected: false, name: decodeURIComponent(escape(rawNode.name)), children: []}
				for (var idx in rawData) {
					if (rawData[idx].parent_id == rawNode.research_id) {
						node.children.push(this.recursive(rawData[idx], rawData))
					}
				}
				return node;
			},

			// build the check tree base on field array
			setupFieldTree(item, selected) {
				selected = selected || this.officerInfo.interestedFields.map(f => f.id).includes(item.id);
				item.isSelected = selected;
				for (var i in item.children)
					this.setupFieldTree(item.children[i], selected);
			},

			// switch search mode
			// 0 - search by unit
			// 1 - search by field
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

			// select a unit to show officer
			selectUnit(i) {
				this.selectedUnit = i;
			},

			// select a field to show officer
			selectField(item) {
				if (this.selectedField) {
					let oldSelected = document.getElementById(this.selectedField.id);
					oldSelected.className = oldSelected.className.replace(" selected", "");
				}
				document.getElementById(item.id).className += " selected";
				this.selectedField = {id: item.id, name: item.name};
			},

			// select a officer to show his/her details
			selectOfficer(id) {
				this.selectedOfficer = id;
				for (let i in this.officerList) {
					if (this.officerList[i].id == id) {
						this.officerInfo = this.officerList[i];
						break;
					}
				}
				// lấy avatar từ API
				this.$http.post('http://localhost/uFaculty/staff/StaffController/getAvatar',{
					staff_id: id
				}).then(function (data) {
					this.avatar_src = 'data:image/jpeg;base64,'+data.body.data;
				})
				this.$http.post('http://localhost/uFaculty/Staff/StaffController/getInterestField',{
					staff_id: this.officerInfo.id
				}).then(function (data) {
					this.officerInfo.interestedFields = [];
					for (let i in data.body.data) {
						this.officerInfo.interestedFields.push({id: data.body.data[i].research_id, name: data.body.data[i].name});
					}
					// console.log(this.officerInfo.interestedFields);
					this.setupFieldTree(this.treeData, false);

					this.$http.post('http://localhost/uFaculty/topic/TopicControl/getTopicByStaffID',{
						staff_id: this.officerInfo.id
					})
							.then(function (data) {
								this.officerInfo.researchTopics = [];
								for (let i in data.body.data) {
									this.officerInfo.researchTopics.push({
										topic_id: data.body.data[i].topic_id,
										name: decodeURIComponent(escape(data.body.data[i].topic_name)),
										description: decodeURIComponent(escape(data.body.data[i].description))
									})
								}
								this.useMode = 1;
							})
				})
			},

			// select a topic to show it
			selectTopic(i) {
				if (this.selectedTopic>-1)
					document.getElementsByClassName('topic-name')[this.selectedTopic].className =
							document.getElementsByClassName('topic-name')[this.selectedTopic].className.replace(" active", "");
				this.selectedTopic = i;
				if (i<0) return;
				document.getElementsByClassName('topic-name')[i].className += " active";
			},

			// from show officer's details back to main page
			back() {
				this.useMode = 0;
			}
		}
	}
</script>

<style scoped>
	.header {
		width: 100%;
		height: 50px;
		background-color: #0F2557;
		top: 0;
		left: 0;
		position: fixed;
		color: #e2eff1;
		z-index: 1;
	}
	.header img{
		margin-left: 40px;
	}
	.title-page{
		height: 30px;
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

	.content-bound .tab-item:hover {background-color: #6bb7cc;}

	.content-bound .tabs-navigation span.active {
		font-weight: bold;
		background-color: #58b368;
		color: #ffffff;
		border-top: 3px solid #0dbd1f;
	}

	.search-container {
		transition: 0.2s;
		border: 2px solid;
		border-color: #d9e9f2 transparent #d9e9f2 #d9e9f2;
		max-height: 380px;
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
		background-color: #17b978;
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
		margin: 25px 50px 0px 100px;
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
		background-color: #17b978;
		border-top: 3px solid #26b926;
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
		background-color: #17b978;
		border-top: 3px solid #26b926;
	}

	.name-field {
		transition: 0.2s;
		border: 2px solid;
		border-color: #17b978 #17b978 #17b978 #17b978;
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
		border-color: #17b978 #17b978 #17b978 #17b978;
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

	.officer-info ::-webkit-scrollbar-thumb:hover {background: #4c9ede;}

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
