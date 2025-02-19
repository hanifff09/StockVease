<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
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
        header: "Nama",
    }),
    column.accessor("category.nama", {
        header: "Category",
    }),
    column.accessor("kondisi.kondisi", {
        header: "Kondisi",
    }),
    column.accessor("deskripsi", {
        header: "Deskripsi",
    }),
    column.accessor("stok", {
        header: "Stok",
    }),
    column.accessor("image", {
        header: "Image",
        cell: cell => h('img', { src: `/storage/${cell.getValue()}`, width: 150 }),
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
    <Form v-if="openForm && user.id === 1" :selected="selected" @close="openForm = false" @refresh="refresh" />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">List Item</h2>
            <button v-if="!openForm && user.id === 1" type="button" class="btn btn-sm btn-primary ms-10"
                @click="openForm = true">
                Add
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate ref="paginateRef" id="table-promo" url="/item" :columns="columns"
                :payload="{ uuid: $route.params.uuid }"></paginate>
        </div>
    </div>
</template>