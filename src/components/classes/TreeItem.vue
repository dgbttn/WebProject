<template>
	<li>
		<div class="item-bound">
			<div class="item-btn" @click="toggle">
				<i v-if="!isOpen&&isFolder" class="fa fa-caret-right show-btn"></i>
				<i v-if="isOpen&&isFolder" class="fa fa-caret-down show-btn"></i>
			</div>

			<div class="item-name" @dblclick="editing=true; editedValue=item.name">
				<label v-if="!editing">{{ item.name }}</label>
				<input v-else class="edit-input" type="text" style="font-size:15px;"
						v-focus v-model="editedValue"
						@keyup.esc="editCancel"
						@keyup.enter="editValue">
			</div>

			<div v-if="!editing" class="tool-btn">
				<i class="fa fa-pencil edit-btn" v-on:click="editing=true; editedValue=item.name">
					<span class="tooltip-text">Sửa</span>
				</i>
				<i class="fa fa-plus add-btn" v-on:click="addItem">
					<span class="tooltip-text">Thêm</span>
				</i>
				<i class="fa fa-trash del-btn" v-on:click="selfRemove">
					<span class="tooltip-text">Xóa</span>
				</i>
			</div>

			<div v-if="editing" class="confirm-domain">
				<i class="fa fa-check-circle confirm-btn ok-btn" v-on:click="editValue">
					<span class="tooltip-text">Xác nhận</span>
				</i>
				<i class="fa fa-times-circle confirm-btn no-btn" v-on:click="editCancel">
					<span class="tooltip-text">Hủy</span>
				</i>
			</div>
		</div>

		<ul class="children-list" v-show="isOpen" v-if="isFolder">
			<TreeItem class="item" v-for="(child, index) in item.children" :item="child"
						@make-folder="$emit('make-folder', $event)"
						@add-item="$emit('add-item', $event)"
						@remove-item="$emit('remove-item', $event)">
			</TreeItem>
		</ul>
	</li>
</template>

<script>
export default {
	name: 'TreeItem',
	props: {
		item: Object
	},

	data() {
		return {
			isOpen: false,
			editing: false,
			editedValue: null,
		}
	},

	computed: {
		isFolder() {
			return this.item.children && this.item.children.length;
		}
	},

	methods: {
		toggle() {
			if (this.isFolder)
				this.isOpen = !this.isOpen;
		},

		makeFolder() {
			if (!this.isFolder) {
				this.$emit('make-folder', this.item);
				this.isOpen = true;
			}
		},

		addItem() {
			// var url = 'http://localhost/uFaculty/Research/ResearchControl/create';
			// this.$http.post(url,{parent_id: this.item.id, name: 'New item'})
			// 		.then(function (data) {
						if (!this.isFolder) this.makeFolder();
						this.isOpen = true;
						this.$emit('add-item', {item: this.item, id: '10'});
			// 		})
		},

		editValue() {
			// var url = 'http://localhost/uFaculty/Research/ResearchControl/update';
			// this.$http.post(url,{id: this.item.id,name: this.editedValue})
			// 		.then(function (data) {
						this.item.name = this.editedValue;
						this.editCancel();
			// 		})
		},

		editCancel() {
			this.editing = false;
		},

		selfRemove() {
			if(confirm("Bạn chắc chắn muốn xóa Mục này và các mục con của nó chứ?")){
				this.$emit('remove-item', this.item.id);
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

	* {transition: 0.2;}

	ul {
		list-style-type: none;
		margin-block-start: 0px;
		margin-block-end: 0px;
		line-height: 1.7;
		padding-left: 1;
	}

	li {list-style-type: none;}

	input {
		width: 100%;
		height: 100%;
		box-sizing: border-box;
		display: block;
		padding: 6px 10px;
		font-size: 14px;
		line-height: 1.5;
		border: 1px solid #ccc;
		border-radius: 4px;
		text-rendering: auto;
		margin: 1px 0px;
		font: 400 13.3333px Arial;
	}

	input:focus {
		border-color: #66afe9;
		outline: 0;
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
	}

	.bold {font-weight: bold;}

	.item-btn {
		display: inline-block;
		cursor: pointer;
		width: 20px;
		height: 20px;
		margin-right: 5px;
	}

	.show-btn {
		width: 100%;
		height: 100%;
		margin-bottom: 2px;
	}

	.item-name {
		display: inline-block;
		margin: 0px 15px 0px 5px;
	}

	.tool-btn {
		visibility: hidden;
		display: inline-block;
		padding-left: 8px;
	}

	.item-bound:hover .tool-btn {visibility: visible;}

	.confirm-domain {
		display: inline-block;
		height: 100%;
		width: 100px;
		padding: 8px;
	}

	.tooltip-text {
		word-wrap: normal;
		visibility: hidden;
		width: auto;
		background-color: #4257B2;
		color: #fff;
		text-align: center;
		padding: 5px 5px;
		position: absolute;
		z-index: 1;
		margin-left: -20px;
		width: 40px;
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

	i:hover .tooltip-text {visibility: visible;}

	.ok-btn 	  {color: #00e600;}
	.no-btn		  {color: #e60000;}
	.del-btn      {color: #455358;}
	.edit-btn     {color: #3333cc;}
	.add-btn      {color: #00cc00;}
	.fa-caret-down{color: #cc0000;}
	i:hover {color: #ffcd1f;}

	i {
		font-size: 19px;
		padding: 0px 5px;
		cursor: pointer;
		vertical-align: middle;
		position: relative;
		text-align: center;
	}

</style>
