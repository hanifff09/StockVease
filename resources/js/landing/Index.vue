<script setup lang="ts">
import { useRoute, useRouter } from "vue-router";
import KTHeader from "@/layouts/default-layout/components/header/NavbarLanding.vue";
import KTFooter from "@/layouts/default-layout/components/header/Footer.vue";
import { ref, onMounted, computed } from "vue";
import axios from "@/libs/axios";
import Splide from "@splidejs/splide";
import "@splidejs/splide/dist/css/splide.min.css";
import { get } from "jquery";

const route = useRoute();
const router = useRouter();
const categories = ref([]);
const items = ref([]);
const selectedCategoryId = ref(null);

const getCategory = async () => {
  try {
    const response = await axios.get("/category/get");
    categories.value = response.data.data;
  } catch (error) {
    console.error("Error fetching concerts:", error);
  }
};

const getItem = async () => {
  try {
    const response = await axios.get("/item/get");
    items.value = response.data.data;
  } catch (error) {
    console.error("Error fetching concerts:", error);
  }
};

// Computed property to filter items based on selected category
const filteredItems = computed(() => {
  if (!selectedCategoryId.value) {
    return items.value;
  }
  return items.value.filter(item => item.category_id === selectedCategoryId.value);
});

// Function to handle category selection
const selectCategory = (categoryId) => {
  if (selectedCategoryId.value === categoryId) {
    selectedCategoryId.value = null; // Deselect if clicking the same category
  } else {
    selectedCategoryId.value = categoryId;
  }
};

onMounted(() => {
  getCategory();
  getItem();
});
</script>

<template>
  <nav>
    <KTHeader />
  </nav>

  <div class="row mt-15 justify-content-center">
    <div class="text-center mb-10">
      <h1>KATEGORI</h1>
    </div>
    <div v-if="categories.length === 0" class="text-center">
      <p>Tidak Ada Kategori.</p>
    </div>
    <div v-for="category in categories" :key="category.id" class="col-lg-3 col-md-4 col-sm-6 mb-4"
      @click="selectCategory(category.id)">
      <div class="card concert-card border border-black" :class="{ 'active': selectedCategoryId === category.id }">
        <div class="card-img-wrapper">
          <img :src="`/storage/${category.image}`" class="card-img-top" alt="Item Image">
        </div>
        <div class="card-body bg-black rounded">
          <h5 class="card-title text-center text-white">{{ category.nama }}</h5>
        </div>
      </div>
  </div>
  </div>

  <div class="row mt-10 justify-content-center">
    <div class="text-center mb-10">
      <h1>ITEM</h1>
    </div>
    <div v-if="filteredItems.length === 0" class="text-center">
      <p>Tidak Ada Item.</p>
    </div>
    <router-link v-for="item in filteredItems" :key="item.id" :to="'/detail/' + item.uuid"
      class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card concert-card">
        <div class="card-img-wrapper">
          <img :src="`/storage/${item.image}`" class="card-img-top" alt="Item Image">
        </div>
        <div class="card-body">
          <h3 class="card-title">{{ item.nama }}</h3>
          <p class="card-text text-end text-black-50">Tersisa {{ item.stok }}</p>
        </div>
      </div>
    </router-link>
  </div>

  <nav>
    <KTFooter />
  </nav>
</template>

<style scoped>
.concert-card.active {
    border: 4px solid #ff9913 !important;
    transform: scale(1.02);
    transition: all 0.2s ease;
}

.concert-card {
  cursor: pointer;
  transition: all 0.2s ease;
}

.concert-card:hover {
  transform: scale(1.02);
}
</style>