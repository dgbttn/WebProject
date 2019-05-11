<template >
	<div class="content-bound">
		<button type="button" name="button" v-on:click="initRandomList">haaaaaaaaa</button>

		<!-- <ul>
			<li v-for="unit in list">
				{{ unit.name }}
			</li>
		</ul> -->

		</form>

		<div class="table-bound">
			<table>
				<tr>
					<th>STT</th>
					<th>Tên đơn vị</th>
					<th>Loại đơn vị</th>
					<th>Địa chỉ</th>
					<th>Điện thoại</th>
					<th> </th>
				</tr>
				<tr v-for="(unit, index) in list">
					<td>{{index}}</td>
					<td>{{ unit.name }}</td>
					<td>{{ unit.type }}</td>
					<td>{{ unit.address }}</td>
					<td>{{ unit.phone }}</td>
					<td>{{ unit.website }}</td>
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
			list: []
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


	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";
	@import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";

	.table-bound {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 80%;
	}

	.table-bound td, .table-bound th {
		border: 1px solid #ddd;
		padding: 8px;
	}

	.table-bound tr:nth-child(even){background-color: #f2f2f2;}

	.table-bound tr:hover {background-color: #ddd;}

	.table-bound th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
	}

	.table-bound td {
		font-size: 14px;
	}
</style>
