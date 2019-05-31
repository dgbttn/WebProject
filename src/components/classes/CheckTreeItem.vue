<template>
	<li>
		<div class="item-bound" :id="item.id">
			<div class="item-btn" @click="toggle">
				<i v-if="!isOpen&&isFolder" class="fa fa-chevron-right show-btn"></i>
				<i v-if="isOpen&&isFolder" class="fa fa-chevron-down show-btn"></i>
			</div>

			<div class="selection" v-on:click="selfSelect">
				<i v-if="!item.isSelected" class="fa fa-square-o"></i>
				<i v-else class="fa fa-check-square"></i>
			</div>

			<div class="item-name">
				<label >{{ item.name }}</label>
			</div>


		</div>

		<ul class="children-list" v-show="isOpen" v-if="isFolder">
			<CheckTreeItem class="item" v-for="(child, index) in item.children" :item="child"
				@select = "$emit('select', $event)">
			</CheckTreeItem>
		</ul>
	</li>

</template>

<script>

export default {
	name: 'CheckTreeItem',
	props: {
		item: Object
	},

	data() {
		return {
			isOpen: true,
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

		selfSelect() {
			this.item.isSelected = !this.item.isSelected;
			this.$emit('select', this.item);
		}
	}
}
</script>

<style scoped>
	@import "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";

	ul {
		list-style-type: none;
		margin-block-start: 0px;
		margin-block-end: 0px;
		line-height: 1.7;
		padding-left: 30px;
	}

	li {list-style-type: none;}

	.item-btn {
		display: inline-block;
		cursor: pointer;
		width: 20px;
		height: 20px;
		margin-right: 5px;
		vertical-align: middle;
	}

	label {color: #4d0066}
	.item-bound:hover label{font-weight: bold;}

	.show-btn {
		width: 100%;
		height: 100%;
		/* margin-bottom: 2px; */
		font-size: 14px;
		margin-top: 3px;
		-webkit-text-stroke: 1px #f8f8f8;
	}

	.item-name {
		display: inline-block;
		margin: 0px 10px 0px 5px;
		/* width: 70%; */
		vertical-align: middle;
		white-space: nowrap;
	}

	.selection {
		display: inline-block;
		margin: 0px 5px 0px 5px;
		/* float: right; */
		vertical-align: middle;
	}

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

	.fa-square-o {color: #006633;}
	.fa-check-square {color: #00b300;}
</style>
