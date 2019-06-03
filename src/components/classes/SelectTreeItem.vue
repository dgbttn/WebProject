<template>
    <li>
        <div class="item-bound" :id="item.id">
            <div class="item-btn" @click="toggle">
                <i v-if="!isOpen&&isFolder" class="fa fa-angle-right show-btn"></i>
                <i v-if="isOpen&&isFolder" class="fa fa-angle-down show-btn"></i>
            </div>

            <div class="item-name"  v-on:click="selfSelect">
                <label >{{ item.name }}</label>
            </div>
        </div>

        <ul class="children-list" v-show="isOpen" v-if="isFolder">
            <SelectTreeItem class="item" v-for="(child, index) in item.children" :item="child"
                            @select="$emit('select', $event)">
            </SelectTreeItem>
        </ul>
    </li>
</template>

<script>
    export default {
        name: 'SelectTreeItem',
        props: {
            item: Object
        },
        data() {
            return {
                isOpen: false
            }
        },
        computed: {
            // the current node is a folder or not
            isFolder() {
                return this.item.children && this.item.children.length;
            }
        },
        methods: {
            // open/close the current node
            toggle() {
                if (this.isFolder)
                    this.isOpen = !this.isOpen;
            },
            // select the current node
            selfSelect() {
                this.$emit('select', this.item);
            }
        }
    }
</script>

<style scoped>
    ul {
        list-style-type: none;
        margin-block-start: 0px;
        margin-block-end: 0px;
        line-height: 1.7;
        padding-left: 1;
    }

    li {list-style-type: none;}

    .selected {	font-weight: bold;}

    .item-bound {cursor: pointer;}

    label {
        cursor: pointer;
        color: #4d0066;
    }
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