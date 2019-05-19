<template>
	<div class="content-bound">
		<span class="note">*Quyền quản trị viên</span>
		<br>
		<span class="note">Double click de make folder cho node</span>
		<br>

		<button type="button" v-on:click="initRandomTree">Tạo mặc định</button>

		<!-- the demo root element -->
		<ul id="demo">
			<TreeItem class="item" :item="treeData"
				@make-folder="makeFolder"
				@add-item="addItem">
			</TreeItem>
		</ul>

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
			Vue.set(item, 'children', [])
			this.addItem(item);
		},
		addItem(item) {
			item.children.push({name: 'new stuff'});
		}
	}
}
</script>

<style lang="css" scoped>
</style>
