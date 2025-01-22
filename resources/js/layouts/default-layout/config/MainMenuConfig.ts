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
                    {
                        heading: "Item",
                        route: "/dashboard/item",
                        name: "item",
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
                name: "peminjaman",
                keenthemesIcon: "sort",
            },
            {
                heading: "Konfirmasi",
                route: "/dashboard/confirm",
                name: "peminjaman",
                keenthemesIcon: "verify",
            },
            {
                heading: "Sedang Dipinjam",
                route: "/dashboard/loan",
                name: "peminjaman",
                keenthemesIcon: "loading",
            },
            {
                heading: "Belum Dikembalikan",
                route: "/dashboard/late",
                name: "peminjaman",
                keenthemesIcon: "compass",
            },
        ],
    },
];

export default MainMenuConfig;
