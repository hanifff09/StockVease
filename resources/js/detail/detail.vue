<script setup lang="ts">
import KTHeader from "@/layouts/default-layout/components/header/NavbarLanding.vue";
import KTFooter from "@/layouts/default-layout/components/header/Footer.vue";
import axios from "@/libs/axios";
import { ref, onMounted, computed } from "vue";
import "@splidejs/splide/dist/css/splide.min.css";
import { useRoute, useRouter } from "vue-router";

const items = ref({});
const route = useRoute();
const router = useRouter();
const isButtonHidden = ref(false);

const getItem = async () => {
    try {
        const response = await axios.get(`/item/edit/${route.params.uuid}`);
        items.value = response.data.data;
    } catch (error) {
        console.error("Error fetching item:", error.response);
    }
};

const handlePinjamClick = () => {
    // Simpan data item ke Local Storage
    localStorage.setItem('selectedItem', JSON.stringify({
        uuid: items.value.uuid,
        nama: items.value.nama
    }));

    // Simpan status tombol tersembunyi
    localStorage.setItem('buttonHidden', 'true');
    isButtonHidden.value = true;

    // Navigasi ke halaman verifikasi
    router.push(`/verif/${items.value.uuid}`);
};

// Computed property to check if button should be shown
const showButton = computed(() => {
    return !isButtonHidden.value && items.value && items.value.stok > 0;
});

onMounted(() => {
    getItem();

    if (localStorage.getItem('buttonHidden') === 'true') {
        isButtonHidden.value = true;
    }
});
</script>

<template>
    <nav>
        <KTHeader />
    </nav>

    <h1 class="text-xl font-bold mt-10 text-center">Detail Barang</h1>

    <div class="card px-15 pt-15 pb-5 m-10 w-75 mx-auto">
        <div class="row">
            <div class="col-8 col-md-5">
                <img :src="`/storage/${items.image}`" class="img-fluid w-100 rounded border border-dark-subtle"
                    alt="Item Image">
            </div>
            <div class="col-8 col-md-7">
                <h1 class="mb-5">{{ items.nama }}</h1>
                <p class="mb-5">{{ items.deskripsi }}</p>
                <span>{{ items.kondisi }}</span>
                <div v-if="items.stok !== undefined" class="mt-3">
                    <span :class="items.stok > 0 ? 'text-success' : 'text-danger'">
                        {{ items.stok > 0 ? `Tersedia: ${items.stok}` : 'Stok Habis' }}
                    </span>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <button v-if="showButton" @click="handlePinjamClick" class="btn btn-primary">
                    Pinjam
                </button>
                <span v-else-if="items.stok <= 0" class="text-danger">Maaf, stok barang habis</span>
            </div>
        </div>
    </div>

    <nav>
        <KTFooter />
    </nav>
</template>