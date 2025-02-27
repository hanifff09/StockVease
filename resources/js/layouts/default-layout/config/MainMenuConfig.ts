import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "Dashboard",
                name: "dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },

    // WEBSITE
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
           
        
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    {
                        sectionTitle: "User",
                        route: "/users",
                        name: "master-user",
                        sub: [
                            {
                                heading: "Role",
                                name: "master-role",
                                route: "/dashboard/master/users/roles",
                            },
                            {
                                heading: "User",
                                name: "master-user",
                                route: "/dashboard/master/users",
                            },
                        ],
                    },
                    {
                        heading: "Kategori",
                        route: "/dashboard/kategori",
                        name: "kategori",
                    },
                    {
                        heading: "Kondisi",
                        route: "/dashboard/kondisi",
                        name: "kondisi",
                    },
                ],
            },
            {
                heading: "Setting",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
            {
                heading: "Item",
                route: "/dashboard/item",
                name: "item",
                keenthemesIcon: "basket",
            },
            {
                heading: "Data Peminjam",
                route: "/dashboard/peminjaman",
                name: "peminjaman",
                keenthemesIcon: "user",
            },
            {
                heading: "Data Baru",   
                route: "/dashboard/raw",
                name: "raw",
                keenthemesIcon: "sort",
            },
            {
                heading: "Konfirmasi",
                route: "/dashboard/confirm",
                name: "confirm",
                keenthemesIcon: "verify",
            },
            {
                heading: "Sedang Dipinjam",
                route: "/dashboard/loan",
                name: "loan",
                keenthemesIcon: "scooter",
            },
            {
                heading: "Belum Dikembalikan",
                route: "/dashboard/late",
                name: "late",
                keenthemesIcon: "time",
            },
            {
                heading: "Peminjaman Selesai",
                route: "/dashboard/done",
                name: "done",
                keenthemesIcon: "archive-tick",
            },
            {
                heading: "Peminjaman Ditolak",
                route: "/dashboard/cancel",
                name: "cancel",
                keenthemesIcon: "cross-square",
            },
        ],
    },
];

export default MainMenuConfig;
