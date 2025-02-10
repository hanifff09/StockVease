<script setup lang="ts">
import { useRouter } from "vue-router";

const router = useRouter()

const butt = () => {
  router.push('/dashboard/peminjaman');
}

const but = () => {
  router.push('/dashboard/loan');
}

const bat = () => {
  router.push('/dashboard/late');
}
</script>

<template>
  <div class="card mb-10 pb-10">
    <div
      class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-primary">
      <h3 class="card-title align-items-start flex-column text-white pt-10">
        <span class="fw-bold fs-2x cursor">StockVease</span>
      </h3>
    </div>
    <div class="card-body mt-n20">
      <div class="mt-n20 position-relative">
        <div class="row g-3 g-lg-6">

          <div class="col-xl-4 col-sm-6">
            <div
              class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-success border-left-5 border-start d-block"  @click="but">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-code fs-2x text-success">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-success fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1 cursor">
                    Barang Dipinjam
                  </span>
                  <span class="text-gray-500 fw-semibold fs-6 cursor">Barang yang sedang dipinjam</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-sm-6">
            <div
              class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-warning border-left-5 border-start d-block"  @click="bat">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-gear fs-2x text-warning">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-warning fw-bolder cursor d-block fs-2qx lh-1 ls-n1 mb-1">
                    Jatuh Tempo
                  </span>
                  <span class="text-gray-500 fw-semibold cursor fs-6">Barang yang perlu dikembalikan</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-sm-6">
            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-info border-left-5 border-start d-block" @click="butt">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-send fs-2x text-info">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-info fw-bolder cursor d-block fs-2qx lh-1 ls-n1 mb-1">
                    Total Peminjaman
                  </span>
                  <span class="text-gray-500 fw-semibold cursor fs-6">Seluruh rekapan data peminjaman</span>
                </div>
                <!-- <i class="fas fa-angle-double-right text-info fs-1 pointer" @click="butt"></i> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
