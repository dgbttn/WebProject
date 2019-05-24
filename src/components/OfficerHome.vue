<template>
	<app-root name="app-root">
		<div class="header">
			<nav class="navbar">
				<div v-show="useMode" class="backward-btn" v-on:click="changeUseMode(0)">
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

			<button type="button" v-on:click="initRandomList">Tạo mặc định</button>
		</div>

		<div class="container">
			<div v-if="true" class="info-container">
				<div class="name">
					<i class="fa fa-user"></i>
					<label >{{infoList[4]}}. {{infoList[0]}}</label>
				</div>

				<div class="details">
					<div class="detail-fields" v-for = "(field, i) in infoTitleList">
						<div class="detail-label">
							<i class="info-icon" :class="infoIconList[i]"></i>
							<label :class="{bold: editing==i&&editable[i]}">{{infoTitleList[i]}}:&nbsp</label>
						</div>

						<div class="detail-value">
							<label v-if="editing!=i||!editable[i]">{{infoList[i+1]}}</label>
							<input v-else class="edit-input" type="text"
									v-focus v-model="editedValue"
									@keyup.esc="editCancel"
									@keyup.enter="editValue">
							</input>
						</div>

						<div class="confirm-domain">
							<i v-if="editing==i&&editable[i]" class="fa fa-check confirm-btn ok-btn" v-on:click="editValue">
								<span class="tooltip-text">Xác nhận</span>
							</i>
							<i v-if="editing==i&&editable[i]" class="fa fa-times confirm-btn no-btn" v-on:click="editCancel">
								<span class="tooltip-text">Hủy</span>
							</i>
							<i v-if="editable[i]&&editing!=i" class="fa fa-pencil-square confirm-btn edit-btn"
								v-on:click="editing=i; editedValue=infoList[i+1]">
								<span class="tooltip-text">Chỉnh sửa</span>
							</i>
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
							{{field}}
						</li>
					</ul>
				</div>

				<div class="show-field">

				</div>
			</div>

			<div class="research-topic">
				<i class="fa fa-plus-square confirm-btn add-btn" v-on:click="addTopic">
					<span class="tooltip-text">Thêm</span>
				</i>
				<span class="big-field-title">
					Chủ đề nghiên cứu
				</span>
				<span class="sec-field-title">
					Mô tả
				</span>

				<div v-if="selectedTopic>-1" class="confirm-domain">
					<i v-if="topicEditing" class="fa fa-check confirm-btn ok-btn" v-on:click="editTopic">
						<span class="tooltip-text">Xác nhận</span>
					</i>
					<i v-if="topicEditing" class="fa fa-times confirm-btn no-btn" v-on:click="topicEditCancel">
						<span class="tooltip-text">Hủy</span>
					</i>
					<i v-if="!topicEditing" class="fa fa-pencil confirm-btn topic-edit-btn"
						v-on:click="topicEditing = true;
									topicEditedValue = {
										name: officerInfo.researchTopics[selectedTopic].name,
										description: officerInfo.researchTopics[selectedTopic].description
									};">
						<span class="tooltip-text">Sửa</span>
					</i>
					<i v-if="!topicEditing" class="fa fa-trash confirm-btn del-btn"
						v-on:click="removeTopic(selectedTopic)">
						<span class="tooltip-text">Xóa</span>
					</i>
				</div>

				<br>
				<div class="name-field">
					<ul class="topic-list">
						<li	v-for="(topic,i) in officerInfo.researchTopics" class="topic-name"
							v-on:click= "selectTopic(i)">
							<span v-if="!topicEditing||selectedTopic!=i"
									@dblclick="topicEditing = true;
										selectTopic(i);
										topicEditedValue = {
											name: officerInfo.researchTopics[i].name,
											description: officerInfo.researchTopics[i].description
										};">
								{{topic.name}}
							</span>
							<input v-else type="text" v-model="topicEditedValue.name" v-focus
									@keyup.enter = "editTopic"
									@keyup.esc = "topicEditCancel">
						</li>
					</ul>
				</div>

				<div class="show-field">
					<div class="content-show" v-if="selectedTopic>=0">
						<span v-if="!topicEditing" @dblclick="topicEditing = true;
							topicEditedValue = {
								name: officerInfo.researchTopics[selectedTopic].name,
								description: officerInfo.researchTopics[selectedTopic].description
							};">{{officerInfo.researchTopics[selectedTopic].description}}</span>
						<textarea v-else rows="17" cols="80" v-model="topicEditedValue.description"></textarea>
					</div>
				</div>
			</div>

		</div>
	</app-root>
</template>

<script>
export default {
	name: 'OfficerHome',
	data() {
		return {
			titles: ['THÔNG TIN GIẢNG VIÊN', 'chỉnh sửa chủ đề nghiên cứu', 'chỉnh sửa lĩnh vực quan tâm'],
			username: 'Giảng viên',
			avatar_src: require('../image/officer_avatar.jpg'),
			officerInfo: {},
			editing: '',
			editedValue: '',
			selectedTopic: -1,
			topicEditing: false,
			topicEditedValue: {},
			topicAdding: false,

		}
	},

	created() {

	},

	computed:{
		title() {
			return this.titles[this.useMode];
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

		editable() {
			return [false, false, false, false, true, false, true, true, true];
		},


	},

	methods: {
		initRandomList() {
			// this.officerInfo = [
			// 	'Lê Đình Thanh', 						//name:
			// 	'12334', 								//id:
			// 	'Giảng viên', 							//position:
			// 	'Phòng Thí nghiệm An toàn Thông tin', 	//unit:
			// 	'TS', 									//degree:
			// 	'0987654321', 							//phone:
			// 	'thanhld@vnu.edu.vn', 					//VNUmail:
			// 	'thanhld.vnuh@gmail.com', 				//otherMail:
			// 	'https://uet.vnu.edu.vn/~thanhld', 		//website:
			// 	'Phòng 413 - E3' 						//address:
			// ];

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

		editValue() {
			this.officerInfo[this.infoFields[this.editing+1]] = this.editedValue;
			this.editCancel();
		},

		editCancel() {
			this.editing = '';
		},

		changeUseMode(mode) {
			this.useMode = mode;
		},

		selectTopic(i) {
			if (this.selectedTopic>-1)
				document.getElementsByClassName('topic-name')[this.selectedTopic].className =
				document.getElementsByClassName('topic-name')[this.selectedTopic].className.replace(" active", "");
			this.selectedTopic = i;
			if (i<0) return;
			document.getElementsByClassName('topic-name')[i].className += " active";
		},

		editTopic() {
			if (!this.topicEditedValue.name) {
				alert('Chủ đề này chưa có tên.');
				return;
			}
			this.officerInfo.researchTopics[this.selectedTopic] = {
				name: this.topicEditedValue.name,
				description: this.topicEditedValue.description
			};
			this.topicEditCancel();
		},

		topicEditCancel() {
			if (this.topicAdding) {
				this.officerInfo.researchTopics.splice(this.selectedTopic,1);
				this.selectTopic(-1);
				this.topicAdding = false;
			}
			this.topicEditing = false;
		},

		removeTopic(index) {
			if (confirm("Bạn chắc chắn muốn xóa Chủ đề này chứ?")) {
				this.officerInfo.researchTopics.splice(index,1);
				this.selectTopic(-1);
			}
		},

		addTopic() {
			this.officerInfo.researchTopics.push({name: '', description: ''});
			this.topicEditing = true;
			this.topicAdding = true;
			this.topicEditedValue = {name: '', description: ''};
			this.selectTopic(this.officerInfo.researchTopics.length - 1);
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

	.avatar {
		vertical-align: middle;
		border-style: none;
		margin: 0px 5px;
	}

	a {text-decoration: none;}

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

	h1 {
		font-size: 22px;
		text-align: center;
		font-family: "brandon-grotesque", "Brandon Grotesque", "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-weight: 500;
		text-transform: uppercase;
	}

	.container {margin: 25px 50px 0px 50px;}

	.info-container {
		margin: 20px 60px 20px 60px;
		left: 0;
		width: 70%;
	}

	.name {
		font-size: 25px;
		font-weight: bold;
		color: #990000;
	}

	.name i, .name label {margin: 0px 5px;}

	.details {
		display: inline-block;
		padding: 5px 0px 5px 35px;
		color: #455358;
		font-size: 14px;
	}

	.detail-fields {margin-top: 3px;}

	.detail-fields:hover {font-weight: bold;}

	.detail-fields div {display: inline-block;}

	.info-icon {
		padding: 0px 5px;
		vertical-align: middle;
		text-align: center;
		width: 25px;
		font-size: 18px;
		display: inline-block;
	}

	.detail-value {word-break: break-all;}

	.officer-image {
		display: inline-block;
		max-height: 260px;
		float: right;
		border: 1px solid gray;
	}

	input, textarea {
		display: inline-block;
		width: 90%;
		box-sizing: border-box;
		margin-left: 5px;
		padding: 6px 10px;
		font-size: 18px;
		line-height: 1.5;
		border: 1px solid #ccc;
		border-radius: 4px;
		text-rendering: auto;
		font: 400 13.3333px Arial;
	}

	input:focus, textarea:focus {
		border-color: #66afe9;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
	}

	.confirm-domain {
		height: 100%;
		width: 70px;
		display: inline-block;
		padding-left: 8px;
		vertical-align: middle;
	}

	.confirm-btn {
		padding: 0px 5px;
		vertical-align: middle;
		cursor: pointer;
		font-size: 22px;
		position: relative;
		display: inline-block;
	}

	.detail-fields:hover .edit-btn {visibility: visible;}

	.tooltip-text {
		word-wrap: normal;
		visibility: hidden;
		width: auto;
		background-color: #4257B2;
		color: #fff;
		text-align: center;
		padding: 2px 5px;
		position: absolute;
		z-index: 1;
		margin-left: -20px;
		width: 40px;
		top: 120%;
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

	.ok-btn   {color: #00e600;}
	.no-btn	  {color: #e60000;}
	.edit-btn {color: #0099cc; visibility: hidden;}
	.topic-edit-btn {color: #0099cc;}
	.del-btn  {color: #455358;}
	.edit-btn:hover, .ok-btn:hover, .no-btn:hover, .del-btn:hover, .topic-edit-btn:hover {color: #ffcd1f;}
	i:hover .tooltip-text {visibility: visible;}

	.add-btn {
		padding: 0px 10px;
		margin-left: -39px;
		color: #1FA67A;
	}

	.bold {font-weight: bold; color: black;}

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

	.show-field {
		display: inline-block;
		height: 300px;
		width: 60%;
		overflow-y: auto;
		background-color: #f8f8f8;
		border: 2px solid;
		border-color: #ffc8b3 #ffc8b3 #ffc8b3 #ffc8b3;
		padding: 0px 13px;
	}

	.content-show {
		display: block;
		padding: 15px 10px 10px 20px;
		font-size: 14px;
	}

	.content-show span {
		white-space: pre-wrap;
	}

	::-webkit-scrollbar {
		width: 8px;
		height: 12px;
	}

	::-webkit-scrollbar-track {
		border: 1px solid #3EB3F6;
		border-radius: 8px;
	}

	::-webkit-scrollbar-thumb {
		background: #85cff9;
		border-radius: 8px;
	}

	::-webkit-scrollbar-thumb:hover {background: #3EB3F6;}

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

	* {
		line-height: 1.3;
	}
</style>
