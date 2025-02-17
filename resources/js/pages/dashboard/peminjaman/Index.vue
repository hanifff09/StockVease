<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useRouter } from 'vue-router'
import { useAuthStore } from "@/stores/auth";
import axios from "@/libs/axios";
import { useTahunStore } from "@/stores/tahun";

const column = createColumnHelper<Item>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);
const { tahun } = useTahunStore()

const { user } = useAuthStore()
const { delete: deleteUser } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const baseColumns = [
    column.accessor("no", {
        header: "no",
    }),
    column.accessor("nama", {
        header: "nama",
    }),
    column.accessor("email", {
        header: "email",
    }),
    column.accessor("nip", {
        header: "nip",
    }),
    column.accessor("alasan_pinjam", {
        header: "alasan pinjam",
    }),
    column.accessor("item", {
        header: "item",
    }),
    column.accessor("text_status", {
        header: "status",
        cell: (cell) => {
            const status = cell.row.original.status;
            const value = cell.getValue();

            let badgeClass = '';

            switch (status) {
                case 0:
                    badgeClass = 'badge-light-warning';
                    break;
                case 1:
                    badgeClass = 'badge-light-info';
                    break;
                case 2:
                    badgeClass = 'badge-light-primary';
                    break;
                case 3:
                    badgeClass = 'badge-light-danger';
                    break;
                case 4:
                    badgeClass = 'badge-light-success';
                    break;
                case 5:
                    badgeClass = 'badge-light-danger';
                    break;
                default:
                    badgeClass = 'badge-light-secondary'; // Default case
            }

            return h('div', [
                h('span', {
                    class: `badge ${badgeClass}`
                }, value)
            ]);
        }
    }),
    column.accessor("tanggal_peminjaman", {
        header: "tanggal peminjaman",
    }),
    column.accessor("tanggal_pengembalian", {
        header: "tanggal pengembalian",
    }),
    // column.accessor("image", {
    //     header: "Image",
    //     cell: cell=> h('img', {src: `/storage/${cell.getValue()}`, width: 150}),
    // }),
];

const actionColumn = column.accessor("uuid", {
    id: "actions",
    header: "Action",
    cell: (cell) => h("div", { class: "d-flex gap-2" }, [
        h(
            "button",
            {
                class: "btn btn-sm btn-icon btn-info",
                onClick: () => {
                    selected.value = cell.getValue();
                    openForm.value = true;
                },
            },
            h("i", { class: "la la-pencil fs-2" })
        ),
        h(
            "button",
            {
                class: "btn btn-sm btn-icon btn-danger",
                onClick: () =>
                    deleteUser(`item/item/${cell.getValue()}`),
            },
            h("i", { class: "la la-trash fs-2" })
        ),
    ]),
});

const downloadPDF = async () => {
    try {
        const response = await window.axios.get(`/peminjaman/download-pdf`, {
            params: {
                bulan: bulan.value,
                tahun: tahun,
                search: paginateRef.value?.search || ''
            },
            responseType: 'blob'
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'rekap-peminjaman.pdf');
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error('Error downloading PDF:', error.response?.status, error.response?.data);
        alert('Error downloading PDF. Please try again later.');
        // Show error notification to user
    }
};

const columns = user.id === 1 ? [...baseColumns, actionColumn] : baseColumns;

const router = useRouter()

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});

const bulan = ref(new Date().getMonth() + 1)
const bulans = ref<any[]>([
    { id: 1, text: "Januari" },
    { id: 2, text: "Februari" },
    { id: 3, text: "Maret" },
    { id: 4, text: "April" },
    { id: 5, text: "Mei" },
    { id: 6, text: "Juni" },
    { id: 7, text: "Juli" },
    { id: 8, text: "Agustus" },
    { id: 9, text: "September" },
    { id: 10, text: "Oktober" },
    { id: 11, text: "November" },
    { id: 12, text: "Desember" },
])
</script>

<template>
    <Form v-if="openForm && user.id === 1" :selected="selected" @close="openForm = false" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">List Data Peminjaman</h2>
            <div class="d-flex">
                <select2 placeholder="Pilih Bulan" class="form-select-solid mw-200px mw-md-100" name="bulan"
                    :options="bulans" v-model="bulan">
                </select2>
                <button type="button" class="btn btn-sm btn-light-danger ms-2" @click="downloadPDF">
                    Download PDF
                    <i class="la la-file-pdf fs-2"></i>
                </button>
                <button v-if="!openForm && user.id === 1" type="button" class="btn btn-sm btn-primary ms-2  "
                    @click="openForm = true">
                    Add
                    <i class="la la-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-promo" url="/peminjaman" :columns="columns"
                :payload="{ uuid: $route.params.uuid, bulan: bulan }"></paginate>
        </div>
    </div>
</template>
