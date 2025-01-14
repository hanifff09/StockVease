<script setup lang="ts">
import KTHeader from "@/layouts/default-layout/components/header/NavbarLanding.vue";
import KTFooter from "@/layouts/default-layout/components/header/Footer.vue";
import axios from "@/libs/axios";
import { ref, onMounted } from "vue";
import { Field, ErrorMessage, Form } from 'vee-validate';
import * as yup from 'yup';
import { useRoute, useRouter } from "vue-router";
// import DatePicker from 'vue-datepicker';

const router = useRouter();
const items = ref({});
const route = useRoute();

// Form validation schema
const schema = yup.object({
  nama: yup.string().required('Nama wajib diisi'),
  nip: yup.string().required('NIP wajib diisi'),
  alasan_pinjam: yup.string().required('Alasan pinjam wajib diisi'),
  item: yup.string().required('Item wajib diisi'),
  tanggal_peminjaman: yup.date().required('Tanggal peminjaman wajib diisi'),
  tanggal_pengembalian: yup.date()
    .required('Tanggal pengembalian wajib diisi')
    .min(yup.ref('tanggal_peminjaman'), 'Tanggal pengembalian harus setelah tanggal peminjaman')
});

const initialValues = ref({
  nama: '',
  nip: '',
  alasan_pinjam: '',
  item: items.value?.nama || '',
  tanggal_peminjaman: '',
  tanggal_pengembalian: ''
});

const getItem = async () => {
    try {
        const response = await axios.get(`/item/edit/${route.params.uuid}`);
        items.value = response.data.data;
        initialValues.value.item = items.value.nama;
    } catch (error) {
        console.error("Error fetching item:", error.response);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.post('/peminjaman/store', initialValues.value);
        if (response.data.status) {
            // Redirect atau tampilkan pesan sukses
            router.push('/peminjaman'); // Sesuaikan dengan route yang sesuai
        }
    } catch (error) {
        console.error("Error submitting form:", error.response);
    }
};

onMounted(() => {
    getItem();
});
</script>

<template>

    <nav>
        <KTHeader/>
    </nav>

    <div class="flex flex-col items-center justify-center h-screen">
        <VForm 
            :validation-schema="schema"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-1/3"    
        >
            <h1 class="text-xl font-bold my-10 text-center">Form Peminjaman</h1>

            <!-- <button class="btn btn-primary" @click="console.log(items)">CAKCIKCOK</button> -->
            
            <div class="row mt-20">
                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="abc" autocomplete="off" placeholder="Masukan Nama" v-model="initialValues.nama"/>
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
                            Nomor Induk Pegawai (NIP)
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="nip" autocomplete="off" placeholder="Masukan NIP" v-model="initialValues.nip"/>
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
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="alasan_pinjam" autocomplete="off" placeholder="Masukan Alasan" v-model="initialValues.alasan_pinjam" as="textarea"  />
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
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="nama" autocomplete="off" placeholder="Nama Item" v-model="initialValues.item"  as="textarea" />
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
                        <Field v-model="initialValues.tanggal_peminjaman" class="form-control form-control-lg form-control-solid mt-3" name="tanggal_peminjaman" autocomplete="off"> 
                        <date-picker v-model="initialValues.tanggal_peminjaman" placeholder="Masukan Tanggal Pinjam"/>
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
                        <Field v-model="initialValues.tanggal_pengembalian" class="form-control form-control-lg form-control-solid mt-3" name="tanggal_pengembalian" autocomplete="off"> 
                        <date-picker v-model="initialValues.tanggal_pengembalian" placeholder="Masukan Tanggal Pengembalian" />
                        </Field>
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="booking_date" />
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                    <button @click="submitForm" type="submit" class="btn btn-primary h-75">Kirim</button>
                </div>
        </VForm>
    </div>
  </template>
  
  <style scoped>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  }
  </style>
  