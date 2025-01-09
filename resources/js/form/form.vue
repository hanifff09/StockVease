<script setup lang="ts">
import KTHeader from "@/layouts/default-layout/components/header/NavbarLanding.vue";
import KTFooter from "@/layouts/default-layout/components/header/Footer.vue";
import axios from "@/libs/axios";
import { ref, onMounted } from "vue";
import "@splidejs/splide/dist/css/splide.min.css";
import { useRoute, useRouter } from "vue-router";

const items = ref([]);
const route = useRoute()

const getItem = async () => {
    try {
        const response = await axios.get(`/item/edit/${route.params.uuid}`);
        items.value = response.data.data;
    } catch (error) {
        console.error("Error fetching concerts:", error.response);
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
        <form @submit.prevent="submitForm" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-1/3">
            <h1 class="text-xl font-bold my-10 text-center">Form Peminjaman</h1>
            
            <div class="row mt-20">
                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nama
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="name" autocomplete="off" placeholder="Masukan Nama" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Nomor Induk Pegawai (NIP)
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="name" autocomplete="off" placeholder="Masukan NIP" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Alasan Pinjam
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="name" autocomplete="off" placeholder="Masukan Alasan"  as="textarea"  />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required">
                            Item
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" type="text" name="name" autocomplete="off" placeholder="Nama Item" v-model="items.nama"  as="textarea" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mt-5">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6">
                            Tanggal Pinjam
                        </label>
                        <Field class="form-control form-control-lg form-control-solid mt-3" name="booking_date" autocomplete="off"> 
                        <date-picker placeholder="Masukan Tanggal Pinjam"/>
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
                        <Field class="form-control form-control-lg form-control-solid mt-3" name="booking_date" autocomplete="off"> 
                        <date-picker placeholder="Masukan Tanggal Pengembalian" />
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
                    <input type="submit" class="btn btn-primary h-75" value="Kirim" />
                </div>
        </form>
    </div>
  </template>
  
  <style scoped>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  }
  </style>
  