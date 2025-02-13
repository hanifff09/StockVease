<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import { useRouter } from 'vue-router'
import { useAuthStore } from "@/stores/auth";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
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

const countdowns = ref<{ [key: string]: number }>({});
const cooldownTime = 30; // cooldown dalam detik

const startCountdown = (uuid: string) => {
    countdowns.value[uuid] = cooldownTime;
    const timer = setInterval(() => {
        if (countdowns.value[uuid] > 0) {
            countdowns.value[uuid]--;
        } else {
            clearInterval(timer);
            delete countdowns.value[uuid];
        }
    }, 1000);
};

async function handleConfirmation(uuid) {
    try {
        const result = await Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda sudah memastikan bahwa barang telah kembali ke Anda dengan benar?',
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
            router.push('/dashboard/done');
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
        await axios.get(`databaru/mno/${uuid}`);
    } catch (err) {
        console.error(err);
        throw err;
    }
}

const actionColumn = column.accessor("uuid", {
    id: "actions",
    header: "Action",
    cell: (cell) => {
        const uuid = cell.getValue();
        return h("div", { class: "d-flex gap-2" }, [
            h(
                "button",
                {
                    class: `btn btn-sm btn-icon ${countdowns.value[uuid] ? 'btn-secondary' : 'btn-danger'}`,
                    disabled: !!countdowns.value[uuid],
                    onClick: () => sendLateEmail(uuid),
                },
                countdowns.value[uuid]
                    ? h("span", { class: "fs-2" }, countdowns.value[uuid])
                    : h("i", { class: "la la-envelope fs-2" })
            ),
            h(
            "button",
            {
                class: "btn btn-sm btn-icon btn-success",
                onClick: () => handleConfirmation(cell.getValue()),
            },
            h("i", { class: "la la-check fs-2" })
        ),
        ]);
    }
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

const sendLateEmail = async (uuid: string) => {

    if (countdowns.value[uuid]) return; // Jika masih dalam cooldown, tidak melakukan apa-apa
    
    try {
        const response = await axios.get(`databaru/send-late-email/${uuid}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.status) {
            toast.success('Email peringatan berhasil dikirim', {
                position: 'top-right',
                autoClose: 3000,
            });
            // Mulai countdown setelah berhasil
            startCountdown(uuid);
        } else {
            toast.error(response.data.message || 'Gagal mengirim email', {
                position: 'top-right',
                autoClose: 3000,
            });
        }
    } catch (error) {
        const errorMessage = error.response 
            ? error.response.data.message || error.response.statusText
            : error.message;

        toast.error(`Gagal mengirim email: ${errorMessage}`, {
            position: 'top-right',
            autoClose: 3000,
        });
    }
};

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
