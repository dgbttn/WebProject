<template>
	<app-root name="app-root">
		<div class="header">
			<nav class="navbar">
				<div class="backward-btn">
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

		<div class="content-bound">
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
					<div class="search-content unit-search" id="unit-search">
						<ul class="unit-list">
							<li v-for="(unit, i) in unitList"
								v-on:click="selectUnit(i)"
								v-bind:class="{active: selectedUnit==i}">
								{{unit}}
							</li>
						</ul>
					</div>
					<div class="search-content field-search" id="field-search">

					</div>
				</div>
			</div>

			<div class="show-field">
				<label>{{ showLabel }}</label> <br>
				<table>
					<!-- Columns' width -->
					<col width="43px">  <!-- STT -->
					<col width="120px"> <!-- Học vị -->
					<col width="320px"> <!-- Tên giảng viên -->
					<col width="120px"> <!-- Đơn vị -->

					<tr>
						<th class="stt">STT</th>
						<th>Học vị</th>
						<th>Tên giảng viên</th>
						<th>Đơn vị</th>
					</tr>

					
				</table>
			</div>
		</div>

	</app-root>
</template>

<script>

export default {
	name: 'GuestHome',
	data() {
		return {
			title:'Tìm kiếm giảng viên',
			username: 'Sinh viên',
			searchRule: 0, // =0 for unit, =1 for field
			selectedUnit: -1,
			selectedField: -1,
			selectedFieldName: '',
			unitList: [],
			fieldList: [],
		}
	},

	computed: {
		showLabel() {
			// unit
			if (this.searchRule==0) {
				if (this.selectedUnit<0) return '';
				return 'Danh sách Giảng viên thuộc ' + this.unitList[this.selectedUnit];
			}
			// field
			else {
				if (this.selectedField<0) return '';
				return 'Danh sách Giảng viên quan tâm Lĩnh vực ' + this.selectedFieldName;
			}
			return '';
		}
	},

	methods: {
		initRandomList() {
			this.unitList.push('Bộ môn các hệ thống thông tin');
			this.unitList.push('Bộ môn Công nghệ phần mềm');
			this.unitList.push('Bộ môn Khoa học máy tính');
		},

		openSearchItem(index) {
			let Ubtn = document.getElementsByClassName('unit-search-btn')[0];
			let Fbtn = document.getElementsByClassName('field-search-btn')[0];
			let Usf = document.getElementsByClassName('unit-search')[0];
			let Fsf = document.getElementsByClassName('field-search')[0];

			Fsf.style.display = "none";
			Fbtn.className = Fbtn.className.replace(" active", "");
			Usf.style.display = "none";
			Ubtn.className = Ubtn.className.replace(" active", "");

			this.searchRule = index;

			// select unit
			if (index==0) {
				Usf.style.display = "block";
				Ubtn.className += " active";
			}

			// select field
			if (index==1) {
				Fsf.style.display = "block";
				Fbtn.className += " active";
			}
		},

		selectUnit(i) {
			this.selectedUnit = i;
			console.log(this.selectedUnit);
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
		width: 180px;
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
		margin-top: 60px;
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
		margin-top: 10px;
		margin-left: 10px;
		width: 35%;
		float: left;
		display: block;
	}

	.tab-item {
		font-size: 16px;
		background-color: #f1efda;
		padding: 10px 15px;
		cursor: pointer;
		display: inline-block;
	}

	.tab-item:hover {background-color: #e3dfb5;}

	.tabs-navigation span.active {
		font-weight: bold;
		background-color: #d9e9f2;
		color: #3E5252;
		border-top: 3px solid #3EB3F6;
	}

	.search-container {
		transition: 0.2;
		/* border: 2px solid #f1efda; */
		border: none;
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

	.unit-list {
		list-style-type: none;
		font-size: 14.5px;
		padding-left: 30px;
		padding-top: 5px;
		padding-bottom: 5px;
		margin: 0px 0px;
	}

	.unit-list li {
		margin: 1.5px 0px;
		cursor: pointer;
		color: #40bf9f;
	}

	.unit-list li:hover {color: #2d8386;}

	.unit-list li.active {
		font-weight: bold;
	}

	.show-field {
		display: inline-block;
		margin-left: 40px;
		margin-top: 10px;
		text-align: left;
	}

	h1 {
		font-size: 20px;
		text-align: center;
	}

	img {
		vertical-align: middle;
		border-style: none;
		margin: 0px 5px;
	}

	a {text-decoration: none;}

	*{line-height: 1.5;}
</style>
