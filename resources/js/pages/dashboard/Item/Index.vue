<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import { useRouter } from 'vue-router'

const column = createColumnHelper<Item>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);

const { delete: deleteUser } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const columns = [
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
        cell: cell=> h('img', {src: `/storage/${cell.getValue()}`, width: 150}),
    }),
    column.accessor("uuid", {
        header: "Action",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
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
    }),
];

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
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">List Item</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-10"
                v-if="!openForm"
                @click="openForm = true"
            >
                Add
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-promo"
                url="/item"
                :columns="columns"
                :payload="{uuid: $route.params.uuid}"
            ></paginate>
        </div>
    </div>
</template>
