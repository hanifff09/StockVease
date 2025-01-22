<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import ApiService from "@/core/services/ApiService";
import { useRole } from "@/services/useRole";
import { useCategory } from "@/services/useCategory";
import { useKondisi } from "@/services/useKondisi";
import { id } from "element-plus/es/locale/index.mjs";


const props = defineProps({
    selected: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const user = ref<Item>({} as Item);
const formRef = ref()
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const image = ref<any>([]);


function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("/item/edit", props.selected)
        .then(({ data }) => {
            user.value = data.data;
            image.value = data.data.image ? ["/storage/" + data.data.image] : [];
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}



function submit() {
    const formData = new FormData();
    formData.append("nama", user.value.nama);
    formData.append("category_id", user.value.category_id);
    formData.append("kondisi_id", user.value.kondisi_id);
    formData.append("stok", user.value.stok);
    formData.append("deskripsi", user.value.deskripsi);
    formData.append("image", user.value.image);


    if (image.value.length) {
        formData.append("image", image?.value[0].file);
    }

    if (props.selected) {
        formData.append("_method", "PUT");
    }

    block(document.getElementById("form-user"));
    axios({
        method: "post",
        url: props.selected
            ? `/item/update/${props.selected}`
            : "/item/store",
        data: formData,
        headers: {
            "Content-Type": "multipart/form-data",
        },
    })
        .then(() => {
            emit("close");
            emit("refresh");
            toast.success("Data berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err: any) => {
            formRef.value.setErrors(err.response.data.errors);
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-user"));
        });
}

const Category = useCategory();
const category = computed(() => Category.data.value?.map(item => ({
    id: item.id,
    text: item.nama
})))

const Kondisi = useKondisi();
const kondisi = computed(() => Kondisi.data.value?.map(item => ({
    id: item.id,
    text: item.kondisi
})))


onMounted(async () => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);

const formattedDeskripsi = computed({
    get: () => user.value.deskripsi,
    set: (value) => {
        user.value.deskripsi = value?.replace(/\r\n/g, '\n');
    }
});
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-user" ref="formRef">

        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} KATEGORI </h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="nama"
                            autocomplete="off" v-model="user.nama" placeholder="Nama Item" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="nama" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Kategori</label>
                        <Field type="hidden" v-model="user.category_id" name="category_id" readonly />
                        <select2 placeholder="Pilih Kategori" class="form-select-solid" :options="category"
                            name="category_id" v-model="user.category_id">
                        </select2>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="category_id" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">Kondisi</label>
                        <Field type="hidden" v-model="user.kondisi_id" name="kondisi_id" readonly />
                        <select2 placeholder="Pilih Kondisi" class="form-select-solid" :options="kondisi"
                            name="kondisi_id" v-model="user.kondisi_id">
                        </select2>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="kondisi_id" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Stok
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="number" name="stok"
                            autocomplete="off" v-model="user.stok" placeholder="Stok Item" min="0" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="stok" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>

                <div class="col-md-12">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Deskripsi
                        </label>
                        <Field 
                        as="textarea" class="form-control form-control-lg form-control-solid" name="deskripsi"
                            autocomplete="off" v-model="formattedDeskripsi" placeholder="Deskripsi Item" rows="10"></Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="deskripsi" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>

                <div class="col-md-12">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6"> Foto Item </label>
                        <!--begin::Input-->
                        <file-upload :files="image" :accepted-file-types="fileTypes" required
                            v-on:updatefiles="(file) => (image = file)"></file-upload>
                        <!--end::Input-->
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="foto" />
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>

            </div>
        </div>
        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                Simpan
            </button>
        </div>
    </VForm>
</template>