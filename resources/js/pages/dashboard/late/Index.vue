<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import { useRouter } from 'vue-router'
import { useAuthStore } from "@/stores/auth";

const column = createColumnHelper<Item>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);

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
        cell: cell => h('div', [
          h('span', { class: 'badge badge-light-danger' }, cell.getValue())
        ])
    }),
    column.accessor("tanggal_peminjaman", {
        header: "tanggal peminjaman",
    }),
    column.accessor("tanggal_pengembalian", {
        header: "tanggal pengembalian",
    }),
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

const columns = user.id === 1 ? [...baseColumns, actionColumn] : baseColumns;

const router = useRouter()

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});
</script>

<template>
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Jatuh Tempo</h2>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-promo"
                url="/databaru/late"
                :columns="columns"
                :payload="{uuid: $route.params.uuid}"
            ></paginate>
        </div>
    </div>
</template>
