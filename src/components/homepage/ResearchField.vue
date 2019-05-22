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
				@add-item="addItem"
				@remove-item="removeItem">
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

	created() {
		this.$http.get('http://localhost/uFaculty/Research/ResearchControl/getAll')
				.then(function (data) {
					var rawData = data.body.data;
					var root = {id: 0, name: 'Root', children: []}
					for (var idx in rawData) {
						if (rawData[idx].parent_id == 0) {
							root.children.push(this.recursive(rawData[idx],rawData));
						}
					}
					this.treeData = root;
				})
	},

	methods: {
		recursive(rawNode, rawData) {
			var node = {id: rawNode.research_id, name: rawNode.name, children: []}
			for (var idx in rawData) {
				if (rawData[idx].parent_id == rawNode.research_id) {
					node.children.push(this.recursive(rawData[idx], rawData))
				}
			}
			return node;
		},

		initRandomTree() {
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
			}
		},

		makeFolder(item) {
			Vue.set(item, 'children', []);
		},

		addItem(item) {
			//generate id

			item.children.push({id: '', name: 'New item'});
		},

		findParents(node, id) {
			if (!(node.children && node.children.length)) return null;
			let res = null;
			for (var i in node.children) {
				if (node.children[i].id == id) return [node, i];
				res = this.findParents(node.children[i], id);
				if (res!=null) return res;
			}
			return res;
		},

		removeSubTree(node) {
			for (var i in node.children)
				this.removeSubTree(node.children[i]);

			// call remove node to server

			node.children = [];
		},

		removeItem(id) {
			let parents = this.findParents(this.treeData, id);
			let upperNode = parents[0];
			let i = parents[1];
			this.removeSubTree(upperNode.children[i]);
			upperNode.children.splice(i,1);
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
