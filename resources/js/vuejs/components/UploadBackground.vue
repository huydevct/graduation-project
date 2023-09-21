<template>
    <div class="col-md-8">
        <div class="form-group form-emoji-file">
            <div class="upload">
                <label class="btn btn-primary" for="image_upload">
                    <i class="fas fa-upload"></i>
                    Upload Background
                </label>
            </div>
            <div :class="{'list-image':true, 'is-invalid': Object.hasOwn(this.errors, 'path')}">
                <div v-for="(item,index) in images" :class="{
                    'img-item': true,
                    selected: item.file_name === img_select
                }" :key="index"
                >
                    <img :src="item.path.small" alt="Image uploaded">
                    <div class="delete" @click.stop.prevent="deleteBackground(this,index)">
                        x
                    </div>
                </div>
            </div>
            <span v-if="Object.hasOwn(this.errors, 'path')" class="invalid-feedback">
                {{ this.errors.path[0] }}
            </span>
        </div>
        <input type="text" style="display: none" name="path" :value="img_select">
        <input id="image_upload" accept="image/*" @change="uploadImages" name="image_upload" type="file"
               style="display: none">
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "UploadABackground",
    data() {
        return {
            img_select: window.pathImage || '',
            images: [],
            errors: {},
        }
    },
    created() {
        if (window.$VueData.images.length !== 0) {
            this.images.push(window.$VueData.images)
        }
        this.errors = window.$VueData.errors
    },
    methods: {
        deleteBackground(el, index) {
            this.images.splice(index, 1)
        },
        async uploadImages(images) {
            if (images.length === 0) return;
            images = images.target.files;
            const formData = new FormData();
            formData.append("file", images[0]);

            console.log(window.$VueData.csrf_token)

            await axios.post("/admin/storage/images", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    // 'AuthorizationApi': window.$VueData.access_token
                },
            }).then(res => {
                console.log("Upload file Success:", res.data);
                alert("Tải ảnh lên thành công")
                this.images = [res.data.data];
                this.img_select = this.images[0].file_name
            }).catch(e => {
                console.error("Upload file error:", e);
                alert("Tải ảnh lên lỗi!")
            });
        },
    }
}
</script>

<style scoped lang="scss">
.form-emoji-file {
    .upload {

    }

    .list-image {
        margin-top: 20px;
        display: flex;
        gap: 20px;

        .img-item {
            cursor: pointer;
            border-radius: 5px;
            position: relative;


            img {
                width: 70px;
                height: auto;
            }


            &.selected {
                border: 2px solid blue;
            }

            .delete {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                position: absolute;
                bottom: 0;
                right: 0;
                background: red;
                color: white;
                font-size: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    }
}
</style>
