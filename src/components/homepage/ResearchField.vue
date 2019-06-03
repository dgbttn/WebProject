<template>
	<div class="content-bound">
		<span class="note">*Quyền quản trị viên</span>
		<br>
		<span class="note">Click đúp để sửa tên lĩnh vực</span>
		<br>

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
		// lấy tất cả các lĩnh vực nghiên cứu vào chuyển thành kiểu dữ liệu cây
		this.$http.get('http://localhost/uFaculty/Research/ResearchControl/getAll')
				.then(function (data) {
					var rawData = data.body.data;
					var root = {id: 0, name: 'Lĩnh vực nghiên cứu', children: []}
					for (var idx in rawData) {
						if (rawData[idx].parent_id == 0) {
							root.children.push(this.recursive(rawData[idx],rawData));
						}
					}
					this.treeData = root;
				})
	},

	methods: {
		// hàm đệ quy tạo dữ liệu cây
		recursive(rawNode, rawData) {
			var node = {id: rawNode.research_id, name: decodeURIComponent(escape(rawNode.name)), children: []}
			for (var idx in rawData) {
				if (rawData[idx].parent_id == rawNode.research_id) {
					node.children.push(this.recursive(rawData[idx], rawData))
				}
			}
			return node;
		},
		// hàm khởi tạo lại cây
		init() {
			this.$http.get('http://localhost/uFaculty/Research/ResearchControl/getAll')
					.then(function (data) {
						var rawData = data.body.data;
						var root = {id: 0, name: 'Lĩnh vực nghiên cứu', children: []}
						for (var idx in rawData) {
							if (rawData[idx].parent_id == 0) {
								root.children.push(this.recursive(rawData[idx],rawData));
							}
						}
						this.treeData = root;
					})
		},

		// make a tree node become a folder that contains many nodes
		makeFolder(item) {
			Vue.set(item, 'children', []);
		},
		// thêm lĩnh vực mới vào cây
		addItem(data) {
			//generate id
			data.item.children.push({id: data.id, name: 'New item'});
		},

		// find parents node of the id node
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

		// xóa lĩnh vực trong cây và các lĩnh vực con của nó
		removeSubTree(node) {
			for (var i in node.children)
				this.removeSubTree(node.children[i]);
			var url = 'http://localhost/uFaculty/Research/ResearchControl/delete';
			this.$http.post(url, {
				id: node.id
			}).then(function ($data) {

			})

			// call remove node to server

			node.children = [];
		},

		// remove a node
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