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

const user = ref<Peminjaman>({} as Peminjaman);
const formRef = ref()
const fileTypes = ref(["image/jpeg", "image/png", "image/jpg"]);
const image = ref<any>([]);

function getEdit() {
    block(document.getElementById("form-user"));
    ApiService.get("/peminjaman/edit", props.selected)
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
    formData.append("email", user.value.email);
    formData.append("nip", user.value.nip);
    formData.append("alasan_pinjam", user.value.alasan_pinjam);
    formData.append("item", user.value.item);
    formData.append("tanggal_peminjaman", user.value.tanggal_peminjaman);
    formData.append("tanggal_pengembalian", user.value.tanggal_pengembalian);


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
            ? `/peminjaman/update-admin/${props.selected}`
            : "/peminjaman/store-admin",
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
            <h2 class="mb-0">{{ selected ? "Edit" : "Tambah" }} Data Peminjaman </h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Batal
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="abc" autocomplete="off" placeholder="Masukan Nama" v-model="user.nama"/>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="nama" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Email
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="email" autocomplete="off" placeholder="Masukan Email" v-model="user.email"/>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="email" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nomor Induk Pegawai (NIP)
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="number" name="nip" autocomplete="off" placeholder="Masukan NIP" v-model="user.nip"/>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="nip" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Alasan Pinjam
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="alasan_pinjam" autocomplete="off" placeholder="Masukan Alasan" v-model="user.alasan_pinjam" as="textarea"  />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="alasan_pinjam" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Item
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="nama" autocomplete="off" placeholder="Nama Item" v-model="user.item"  as="textarea" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="nama" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">
                            Tanggal Pinjam
                        </label>
                        <Field v-model="user.tanggal_peminjaman" class="form-control form-control-lg form-control-solid mt-3" name="tanggal_peminjaman" autocomplete="off"> 
                        <date-picker v-model="user.tanggal_peminjaman" placeholder="Masukan Tanggal Pinjam" :config="{ minDate: 'today' }"/>
                    </Field>
                    <div class="fv-plugins-message-container">
                        <div class="fv-help-block">
                            <ErrorMessage name="booking_date" />
                        </div>
                    </div>  
                </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">
                            Tanggal Pengembalian
                        </label>
                        <Field v-model="user.tanggal_pengembalian" class="form-control form-control-lg form-control-solid mt-3" name="tanggal_pengembalian" autocomplete="off" :disabled="isReturnDateDisabled"> 
                        <date-picker v-model="user.tanggal_pengembalian" placeholder="Masukan Tanggal Pengembalian" :config="{ minDate: computedMinDate }" :disabled="isReturnDateDisabled"/>
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="booking_date" />
                            </div>
                        </div>
                    </div>
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