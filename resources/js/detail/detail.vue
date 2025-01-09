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
        <KTHeader />
    </nav>

    <h1 class="text-xl font-bold mt-10 text-center">Detail</h1>

    <!-- <div class="card shadow-md rounded px-20 py-20 mx-20 my-20">
        <div class="d-sm-block px-20 pb-20">    
            <img :src="`/storage/${items.image}`" class="card-img-top rounded border border-dark-subtle " alt="Item Image">
            <h3 class="mt-10">{{ items.nama }}</h3>
            <span>{{ items.deskripsi }}</span>
            <span>{{ items.kondisi }}</span>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                <router-link :to="'/form'"  class="btn btn-primary" >Pinjam</router-link>
            </div>
    </div> -->

    <div class="card px-15 pt-15 pb-5 m-10">
        <div class="row">
            <div class="col-12 col-md-7">
                <img :src="`/storage/${items.image}`" class="img-fluid w-100 rounded border border-dark-subtle" alt="Item Image">
            </div>
            <div class="col-12 col-md-5">
                <h1 class="mb-5">{{ items.nama }}</h1>
                <p class="mb-5">{{ items.deskripsi }}</p>
                <span>{{ items.kondisi }}</span>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <router-link :to="'/form/' + items.uuid"  class="btn btn-primary" >Pinjam</router-link>
            </div>
        </div>
    </div>


    <nav>
        <KTFooter />
    </nav>
    
</template>