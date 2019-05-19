<template>
	<div class="content-bound">
		<span class="note">*Quyền quản trị viên</span>
		<br>
		<span class="note">Double click de make folder cho node</span>
		<br>

		<button type="button" v-on:click="initRandomTree">Tạo mặc định</button>
		<button type="button" >Thêm lĩnh vực</button>

		<!-- the demo root element -->
		<div class="tree-bound">
			<TreeItem class="item" :item="treeData"
				@make-folder="makeFolder"
				@add-item="addItem">
			</TreeItem>
		</div>

	</div>
</template>

<script>
import TreeItem from '../classes/TreeItem.vue'
import Vue from 'vue'

export default {
	name: 'ResearchField',
	components: {
		TreeItem
	},
	data() {
		return {
			treeData: {name: 'sss', children: []},
		}
	},

	methods: {
		initRandomTree() {
			this.treeData = {
				name: 'My Tree',
				children: [
					{ name: 'hello' },
					{ name: 'wat' },
					{
						name: 'child folder',
						children: [
							{
								name: 'child folder',
								children: [
									{ name: 'hello' },
									{ name: 'wat' }
								]
							},
							{ name: 'hello' },
							{ name: 'wat' },
							{
								name: 'child folder',
								children: [
									{ name: 'hello' },
									{ name: 'wat' }
								]
							}
						]
					}
				]
			}
		},

		makeFolder(item) {
			Vue.set(item, 'children', []);
		},

		addItem(item) {
			item.children.push({name: 'new item'});
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

	.tree-bound {
		font-family: "Helvetica", sans-serif;
		border-collapse: collapse;
		width: 100%;
		font-size: 16px;
		margin: 10px 5px;
	}

</style>
