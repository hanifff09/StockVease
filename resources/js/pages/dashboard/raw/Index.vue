<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import { useRouter } from 'vue-router'
import { useAuthStore } from "@/stores/auth";
import axios from "@/libs/axios";
import Swal from 'sweetalert2';

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
          h('span', { class: 'badge badge-light-warning' }, cell.getValue())
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
                class: "btn btn-sm btn-icon btn-success",
                onClick: () => handleConfirmation(cell.getValue()),
            },
            h("i", { class: "la la-check fs-2" })
        ),
        h(
            "button",
            {
                class: "btn btn-sm btn-icon btn-danger",
                onClick: () => handleConfirm(cell.getValue()),

            },
            h("i", { class: "la la-times fs-2" })
        ),
    ]),
});

const columns = [...baseColumns, actionColumn];

const router = useRouter()

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
});

async function handleConfirmation(uuid) {
    try {
        const result = await Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda ingin menkonfirmasi data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Benar',
            cancelButtonText: 'Batal',
            confirmButtonColor: 'success',
            cancelButtonColor: '#d33'
        });

        if (result.isConfirmed) {
            await update(uuid);
            await Swal.fire(
                'Berhasil!',
                'Data telah dikonfirmasi.',
                'success'
            );
            router.push('/dashboard/confirm');
        }
    } catch (error) {
        Swal.fire(
            'Error!',
            'Terjadi kesalahan saat mengonfirmasi data.',
            'error'
        );
        console.error(error);
    }
}

 async function handleConfirm(uuid) {
    try {
        const result = await Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda ingin menolak data ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Benar',
            cancelButtonText: 'Batal',
            confirmButtonColor: 'success',
            cancelButtonColor: '#d33'
        });

        if (result.isConfirmed) {
            // First update the status
            await update5(uuid);
            
            // Then send the rejection email
            try {
                await axios.get(`databaru/send-reject-email/${uuid}`);
                await Swal.fire(
                    'Berhasil!',
                    'Data berhasil ditolak dan email notifikasi telah dikirim.',
                    'success'
                );
            } catch (emailError) {
                console.error('Error sending email:', emailError);
                await Swal.fire(
                    'Perhatian!',
                    'Data berhasil ditolak tetapi gagal mengirim email notifikasi.',
                    'warning'
                );
            }
            
            router.push('/dashboard/cancel');
        }
    } catch (error) {
        Swal.fire(
            'Error!',
            'Terjadi kesalahan saat mengonfirmasi data.',
            'error'
        );
        console.error(error);
    }
}

async function update(uuid) {
    try {
        await axios.get(`databaru/abc/${uuid}`);
    } catch (err) {
        console.error(err);
        throw err;
    }
}

async function update5(uuid) {
    try {
        await axios.get(`databaru/jkl/${uuid}`);
    } catch (err) {
        console.error(err);
        throw err;
    }
}
</script>

<template>
    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Data Peminjaman Baru</h2>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-promo"
                url="/databaru/raw"
                :columns="columns"
                :payload="{uuid: $route.params.uuid}"
            ></paginate>
        </div>
    </div>
</template>