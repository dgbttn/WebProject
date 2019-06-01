<template>
    <app-root name="app-root">
        <div class="header">
            <nav class="navbar">
                <div class="account">
                    <a class="nav-link account-content dropdown-account">
                        <img class="avatar" v-bind:src="avatar_src">

                        <span class="account-name">{{ username }}</span>

                        <i class="fa fa-chevron-down"></i>
                    </a>
                    <span class="bonus change-password" v-on:click="changePassword">
						<i class="fa fa-key"></i>&nbsp;
						Đổi mật khẩu
					</span><br>
                    <span class="bonus log-out" v-on:click="Logout">
						<i class="fa fa-sign-out"></i>&nbsp;
						Đăng xuất
					</span>
                </div>
            </nav>
            <img src="../image/Untitled-5.png" class="logo">
        </div>

        <div class="title">
            <h1>{{title}}</h1>
        </div>

        <div class="container">
            <div v-if="true" class="info-container">
                <div class="info">
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
                </div>

                <img class="officer-image" v-bind:src="avatar_src">
                <br>
                <input class="file-browser" type="file" @change="selectedAvatar">
            </div>

            <br>

            <div class="interested-field">
				<span class="big-field-title">
					Lĩnh vực quan tâm
				</span>
                <span class="sec-field-title">
					Tất cả lĩnh vực
				</span>
                <div v-if="fieldEditing" class="confirm-domain">
                    <i class="fa fa-check confirm-btn ok-btn" v-on:click="editFieldAccept">
                        <span class="tooltip-text">Xác nhận</span>
                    </i>
                    <i class="fa fa-times confirm-btn no-btn" v-on:click="editFieldCancel">
                        <span class="tooltip-text">Hủy</span>
                    </i>
                </div>
                <br>

                <div class="name-field">
                    <ul class="field-list">
                        <li v-for="field in officerInfo.interestedFields">
                            {{field.name}}
                        </li>
                    </ul>
                </div>

                <div class="show-field">
                    <div class="content-show">
                        <CheckTreeItem class="select-tree" :item="treeData"
                                       @select="selectField">
                        </CheckTreeItem>
                    </div>
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
                               v-on:click= "selectTopic(i)" @dblclick="selectTopicToEdit(i)">
							<span v-if="!topicEditing||selectedTopic!=i">
								{{topic.name}}
							</span>
                            <input v-else type="text" v-model="topicEditedValue.name" v-focus
                                   @keyup.enter = "editTopic"
                                   @keyup.esc = "topicEditCancel">
                        </li>
                    </ul>
                </div>

                <div class="show-field" @dblclick=" selectTopicToEdit(selectedTopic);">
                    <div class="content-show" v-if="selectedTopic>=0">
                        <span v-if="!topicEditing" >{{officerInfo.researchTopics[selectedTopic].description}}</span>
                        <textarea v-else rows="17" cols="80" v-model="topicEditedValue.description"></textarea>
                    </div>
                </div>
            </div>

        </div>
    </app-root>
</template>

<script>
    import CheckTreeItem from './classes/CheckTreeItem.vue'

    export default {
        name: 'OfficerHome',
        components: {
            CheckTreeItem,
        },
        data() {
            return {
                title: 'THÔNG TIN GIẢNG VIÊN',
                username: 'Giảng viên',
                avatar_src: require('../image/officer_avatar.jpg'),
                officerInfo: {},
                editing: '',
                editedValue: '',
                selectedTopic: -1,
                topicEditing: false,
                topicEditedValue: {},
                topicAdding: false,
                treeData: Object,
                fieldEditing: false,
                auth_id: 0
            }
        },
        created() {
            this.treeData = {id: 0, isSelected: false, name: 'Lĩnh vực nghiên cứu', children: []};

            // check token có hợp lệ hay không
            var token = this.$cookie.get('user');
            if (token == null) {
                this.$router.push('/login');
                return;
            }
            else {
                var decoded_token = this.$jwtDec.decode(token);
                var role = decoded_token.role;
                if (role == null) {
                    this.$router.push('/login');
                    return;
                }
            }
            // lấy username và staff_id từ token
            this.username = decoded_token.username;
            var staff_id = this.$jwtDec.decode(token).staff_id;
            this.auth_id = staff_id;

            // lấy avatar từ API
            this.$http.post('http://localhost/uFaculty/staff/StaffController/getAvatar',{
                staff_id: decoded_token.staff_id
            }).then(function (data) {
                this.avatar_src = 'data:image/jpeg;base64,'+data.body.data;
            })

            // lấy thông tin staff từ staff_id
            this.$http.post('http://localhost/uFaculty/staff/StaffController/getStaffByID',{
                staff_id: staff_id
            })
                .then(function (data) {
                    this.officerInfo = {
                        name : decodeURIComponent(escape(data.body.data[0].full_name)),
                        number : data.body.data[0].staff_number,
                        position : decodeURIComponent(escape(data.body.data[0].staff_type)),
                        unit: decodeURIComponent(escape(data.body.data[0].name)),
                        degree : decodeURIComponent(escape(data.body.data[0].academic_title)),
                        phone: data.body.data[0].phone_number,
                        VNUmail : data.body.data[0].vnu_email,
                        otherMail: data.body.data[0].other_email,
                        website: data.body.data[0].website,
                        address: decodeURIComponent(escape(data.body.data[0].address)),
                        staff_id: staff_id,
                        researchTopics: [],
                        interestedFields: []
                    };
                    // lấy những chủ đề nghiên cứu của staff
                    this.$http.post('http://localhost/uFaculty/topic/TopicControl/getTopicByStaffID',{
                        staff_id: staff_id
                    })
                        .then(function (data_topic) {
                            for (var i in data_topic.body.data) {
                                this.officerInfo.researchTopics.push({
                                    topic_id: data_topic.body.data[i].topic_id,
                                    name: decodeURIComponent(escape(data_topic.body.data[i].topic_name)),
                                    description: decodeURIComponent(escape(data_topic.body.data[i].description))
                                })
                            }
                        })
                    // lấy những lĩnh vực quan tâm của staff
                    this.$http.post('http://localhost/uFaculty/staff/StaffController/getInterestField',{
                        staff_id: staff_id
                    })
                        .then(function (data_research) {
                            this.officerInfo.interestedFields = [];
                            for (var i in data_research.body.data) {
                                this.officerInfo.interestedFields.push({
                                    id: data_research.body.data[i].research_id,
                                    name: decodeURIComponent(escape(data_research.body.data[i].name)),
                                })
                            }
                            // lấy tất cả lĩnh vực nghiên cứu và chuyển dữ liệu thành định dạng cây
                            this.$http.get('http://localhost/uFaculty/Research/ResearchControl/getAll')
                                .then(function (data_tree) {
                                    var rawData = data_tree.body.data;
                                    var root = {id: 0, isSelected: false, name: 'Lĩnh vực nghiên cứu', children: []}
                                    for (var idx in rawData) {
                                        if (rawData[idx].parent_id == 0) {
                                            root.children.push(this.recursive(rawData[idx],rawData));
                                        }
                                    }
                                    this.treeData = root;
                                    this.setupFieldTree(this.treeData, false);
                                })
                        })
                })
        },
        computed:{
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
            // hàm lặp tạo dữ liệu cây của lĩnh vực nghiên cứu
            recursive(rawNode, rawData) {
                var node = {id: rawNode.research_id, isSelected: false, name: decodeURIComponent(escape(rawNode.name)), children: []}
                for (var idx in rawData) {
                    if (rawData[idx].parent_id == rawNode.research_id) {
                        node.children.push(this.recursive(rawData[idx], rawData))
                    }
                }
                return node;
            },
            initRandomList() {
            },
            setupFieldTree(item, selected) {
                selected = selected || this.officerInfo.interestedFields.map(f => f.id).includes(item.id);
                item.isSelected = selected;
                for (var i in item.children)
                    this.setupFieldTree(item.children[i], selected);
            },
            updateFieldTree(item, selected) {
                item.isSelected = selected;
                for (var i in item.children)
                    this.updateFieldTree(item.children[i], selected);
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
            // sửa các giá trị thông tin của staff
            editValue() {
                var chosenField = this.infoFields[this.editing+1];
                var url = null;
                var input = {staff_id: this.officerInfo.staff_id};
                switch (chosenField) {
                    case "phone":
                        url = "http://localhost/uFaculty/staff/StaffController/staffEditPhone";
                        input.phone_number = this.editedValue;
                        break;
                    case "otherMail":
                        url = "http://localhost/uFaculty/staff/StaffController/staffEditEmail";
                        input.other_mail = this.editedValue;
                        break;
                    case "website":
                        url = "http://localhost/uFaculty/staff/StaffController/staffEditWebsite";
                        input.website = this.editedValue;
                        break;
                    case "address":
                        url = "http://localhost/uFaculty/staff/StaffController/staffEditAddress";
                        input.address = this.editedValue;
                        break;
                    default:
                        break;
                }

                this.$http.post(url,input)
                    .then(function (data) {
                        this.officerInfo[this.infoFields[this.editing+1]] = this.editedValue;
                        this.editCancel();
                    })
            },
            editCancel() {
                this.editing = '';
            },

            unselectField(item) {
                this.updateFieldTree(item, false);
                let parents = this.findParents(this.treeData, item.id);
                if (parents) parents[0].isSelected = false;
            },
            selectField(item) {
                if (!this.fieldEditing) this.fieldEditing=true;
                // item.i
                if (!item.isSelected) {
                    this.unselectField(item);
                    return;
                }
                item.isSelected = true;
                this.updateFieldTree(item, true);
                let parentsSelected = true;
                let parents = this.findParents(this.treeData, item.id);
                if (!parents) return;
                for (let i in parents[0].children)
                    if (!parents[0].children[i].isSelected) {
                        parentsSelected = false;
                        break;
                    }
                parents[0].isSelected = parentsSelected;
            },
            // update các lĩnh vực quan tâm của staff
            updateInterestedFields(item) {
                if (item.isSelected){
                    this.$http.post('http://localhost/uFaculty/staff/StaffController/addInterestField',{
                        staff_id: this.auth_id,
                        research_id: item.id
                    }).then(function (data) {
                        if (data.body.status == 1) {
                            console.log("add successfully item with id = "+ item.id);
                        }
                    })
                    this.officerInfo.interestedFields.push({id: item.id, name: item.name});
                    return;
                }
                for (let i in item.children)
                    this.updateInterestedFields(item.children[i]);
            },
            editFieldAccept() {
                // xóa tất cả các ánh xạ đến lĩnh vực quan tâm của staff và insert các ánh xạ mới
                this.$http.post('http://localhost/uFaculty/staff/StaffController/deleteInterestField',{
                    staff_id: this.auth_id
                }).then(function (data) {
                    if (data.body.status == 1 || data.body.status == 0) {
                        this.officerInfo.interestedFields = [];
                        this.updateInterestedFields(this.treeData);
                        this.fieldEditing = false;
                    }
                })
            },
            editFieldCancel() {
                this.fieldEditing = false;
                this.setupFieldTree(this.treeData, false);
            },

            selectTopicToEdit(i) {
                if (this.topicEditing) return;
                this.selectTopic(i);
                this.topicEditing = true;
                this.topicEditedValue = {
                    name: this.officerInfo.researchTopics[i].name,
                    description: this.officerInfo.researchTopics[i].description
                };
            },

            selectTopic(i) {
                if (this.topicEditing) return;
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
                // update các chủ đề nghiên cứu hiện nay của staff
                var url = 'http://localhost/uFaculty/topic/TopicControl/update';
                this.$http.post(url,{
                    topic_id: this.officerInfo.researchTopics[this.selectedTopic].topic_id,
                    topic_name: this.topicEditedValue.name,
                    description: this.topicEditedValue.description
                })
                    .then(function (data) {
                        this.officerInfo.researchTopics[this.selectedTopic] = {
                            topic_id: this.officerInfo.researchTopics[this.selectedTopic].topic_id,
                            name: this.topicEditedValue.name,
                            description: this.topicEditedValue.description
                        };
                        this.topicEditCancel();
                    })
            },
            topicEditCancel() {
                this.topicEditing = false;
            },
            // xóa các chủ đề nghiên cứu
            removeTopic(index) {
                if (confirm("Bạn chắc chắn muốn xóa Chủ đề này chứ?")) {
                    var url = 'http://localhost/uFaculty/topic/TopicControl/delete';
                    this.$http.post(url,{
                        topic_id: this.officerInfo.researchTopics[index].topic_id
                    })
                        .then(function (data) {
                            this.officerInfo.researchTopics.splice(index,1);
                            this.selectTopic(-1);
                        })
                }
            },
            // thêm chủ đề nghiên cứu mới
            addTopic() {
                var new_name = 'New Topic';
                var new_description = 'Hello World';
                var url = 'http://localhost/uFaculty/topic/TopicControl/create';
                this.$http.post(url,{
                    staff_id: this.auth_id,
                    topic_name: new_name,
                    description: new_description
                })
                    .then(function (data) {
                        this.officerInfo.researchTopics.push({topic_id: data.body.data[0].topic_id, name: new_name, description: new_description});
                        this.selectTopic(this.officerInfo.researchTopics.length - 1);
                    })
            },
            // thay đổi mật khẩu
            changePassword() {
                this.$router.push('/change-password');
            },
            // đăng xuất
            Logout() {
                this.$router.push('/Login');
                this.$cookie.delete('user');
            },
            // chọn và update avatar
            selectedAvatar(element) {
                var big_this = this;
                var file = element.target.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    var data_image = reader.result;
                    big_this.avatar_src = data_image;
                    var url = 'http://localhost/uFaculty/staff/staffController/updateAvatar';
                    big_this.$http.post(url,{
                        staff_id: big_this.auth_id,
                        avatar : data_image
                    }).then(function (data) {

                    });
                }
                reader.readAsDataURL(file);
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
        background-color: #0F2557;
        top: 0;
        left: 0;
        position: fixed;
        color: #e2eff1;
        z-index: 1;
    }

    .logo{
        height: 30px;
        margin-left: 30px;
        display: inline-block;
    }
    .navbar {
        border: 1px solid #e7e7e7;
        margin-bottom: 10px;
    }

    /* buttons on navbar*/
    .nav-link {
        display: block;
        padding: .5rem 1rem;
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
        background-color: #0F2557;
        position: relative;
        height: 48px;
    }

    .account:hover {background-color: #4080bf;}

    .account:hover .bonus {visibility: visible;}

    .bonus {
        visibility: hidden;
        z-index: 1;
        width: 100%;
        text-align: center;
        background-color: #4080bf;
        position: absolute;
        padding: 10px 0px 10px 0px;
        cursor: pointer;
        line-height: 1.5;
        font-size: 14px;
    }

    .bonus:hover {background-color: #336699;}

    .change-password {top: 100%;}

    .log-out {top: 186%;}

    .bonus span {
        width: 100%;
        position: absolute;
    }

    .navbar div {transition: 0.2s;}

    .avatar {
        vertical-align: middle;
        border-style: none;
        margin: 0px 5px;
    }

    a {
        color: #e2eff1;
        text-decoration: none;
    }

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

    .container {margin: 25px 40px 0px 90px;}

    .info-container {
        margin: 20px 60px 20px 60px;
        left: 0;
    }

    .info {
        width: 55%;
        display: inline-block;
    }

    .officer-image {
        display: inline-block;
        max-height: 260px;
        /* float: right; */
        border: 1px solid gray;
        margin: 0% 5%;
        max-width: 30%;
    }

    .file-browser {
        float: right;
        max-width: 28.5%;
        margin-right: 11.5%;
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
    .add-btn:hover, .edit-btn:hover, .ok-btn:hover, .no-btn:hover, .del-btn:hover, .topic-edit-btn:hover {color: #ffcd1f;}
    i:hover .tooltip-text {visibility: visible;}

    .add-btn {
        padding: 0px 10px;
        margin-left: -39px;
        color: #1FA67A;
    }

    .bold {font-weight: bold; color: #ffffff;}

    .research-topic {
        padding: 20px 40px 20px 40px;
    }

    .interested-field {
        padding: 20px 40px 20px 40px;
    }

    .big-field-title {
        font-size: 17px;
        font-weight: bold;
        background-color: #17b978;
        border-top: 3px solid #26b926;
        padding: 7px 15px;
        display: inline-block;
        width: 274px;
        vertical-align: middle;
        text-align: center;
        color: #f6f6f6;
    }

    .sec-field-title {
        color: #f6f6f6;
        font-size: 17px;
        font-weight: bold;
        padding: 7px 15px;
        display: inline-block;
        width: 60%;
        overflow: hidden;
        vertical-align: middle;
        text-align: center;
        background-color: #17b978;
        border-top: 3px solid #26b926;
    }

    .name-field {
        transition: 0.2s;
        border: 2px solid;
        border-color: #17b978 #17b978 #17b978 #17b978;
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
        overflow: auto;
        background-color: #f8f8f8;
        border: 2px solid;
        border-color: #17b978 #17b978 #17b978 #17b978;
        padding: 0px 13px;
    }

    .content-show {
        display: block;
        padding: 15px 10px 10px 10px;
        font-size: 14px;
    }

    .content-show span {
        white-space: pre-wrap;
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
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