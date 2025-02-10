import {
    createRouter,
    createWebHistory,
    type RouteRecordRaw,
} from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useConfigStore } from "@/stores/config";
import NProgress from "nprogress";
import "nprogress/nprogress.css";

declare module "vue-router" {
    interface RouteMeta {
        pageTitle?: string;
        permission?: string;
    }
}

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        name: "landing",
        component: () => import("@/landing/Index.vue"),
        meta: {
            pageTitle:"Landing Page",
        },
    },
    {
        path: "/form/:uuid",
        name: "form",
        component: () => import("@/form/form.vue"),
        meta: {
            pageTitle:"Form",
        },
},
    {
        path: "/detail/:uuid",
        name: "detail",
        component: () => import("@/detail/detail.vue"),
        meta: {
            pageTitle:"Detail",
        },
    },
    {
        path: "/verif/:uuid",
        name: "verif",
        component: () => import("@/verif/verif.vue"),
        meta: {
            pageTitle:"verif",
        },
    },
    {
        path: "/dashboard",
        redirect: "/dashboard",
        component: () => import("@/layouts/default-layout/DefaultLayout.vue"),
        meta: {
            middleware: "auth",
        },
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/pages/dashboard/Index.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboard"],
                },
            },
            {
                path: "/dashboard/kategori",
                name: "kategori",
                component: () => import("@/pages/dashboard/kategori/Index.vue"),
                meta: {
                    pageTitle: "kategori",
                    breadcrumbs: ["kategori"],
                },
            },
            {
                path: "/dashboard/kondisi",
                name: "kondisi",
                component: () => import("@/pages/dashboard/kondisi/Index.vue"),
                meta: {
                    pageTitle: "kondisi",
                    breadcrumbs: ["kondisi"],
                },
            },
            {
                path: "/dashboard/item",
                name: "item",
                component: () => import("@/pages/dashboard/item/Index.vue"),
                meta: {
                    pageTitle: "item",
                    breadcrumbs: ["item"],
                },
            },
            {
                path: "/dashboard/peminjaman",
                name: "peminjaman",
                component: () => import("@/pages/dashboard/peminjaman/Index.vue"),
                meta: {
                    pageTitle: "peminjaman",
                    breadcrumbs: ["peminjaman"],
                },
            },
            {
                path: "/dashboard/profile",
                name: "dashboard.profile",
                component: () => import("@/pages/dashboard/profile/Index.vue"),
                meta: {
                    pageTitle: "Profile",
                    breadcrumbs: ["Profile"],
                },
            },
            {
                path: "/dashboard/setting",
                name: "dashboard.setting",
                component: () => import("@/pages/dashboard/setting/Index.vue"),
                meta: {
                    pageTitle: "Website Setting",
                    breadcrumbs: ["Website", "Setting"],
                },
            },
            {
                path: "/dashboard/raw",
                name: "raw",
                component: () => import("@/pages/dashboard/raw/Index.vue"),
                meta: {
                    pageTitle: "raw",
                    breadcrumbs: ["raw"],
                },
            },{
                path: "/dashboard/confirm",
                name: "confirm",
                component: () => import("@/pages/dashboard/confirm/Index.vue"),
                meta: {
                    pageTitle: "confirm",
                    breadcrumbs: ["confirm"],
                },
            },{
                path: "/dashboard/loan",
                name: "loan",
                component: () => import("@/pages/dashboard/loan/Index.vue"),
                meta: {
                    pageTitle: "loan",
                    breadcrumbs: ["loan"],
                },
            },{
                path: "/dashboard/late",
                name: "late",
                component: () => import("@/pages/dashboard/late/Index.vue"),
                meta: {
                    pageTitle: "late",
                    breadcrumbs: ["late"],
                },
            },{
                path: "/dashboard/done",
                name: "done",
                component: () => import("@/pages/dashboard/done/Index.vue"),
                meta: {
                    pageTitle: "done",
                    breadcrumbs: ["done"],
                },
            },{
                path: "/dashboard/cancel",
                name: "cancel",
                component: () => import("@/pages/dashboard/cancel/Index.vue"),
                meta: {
                    pageTitle: "cancel",
                    breadcrumbs: ["cancel"],
                },
            },

            // MASTER
            {
                path: "/dashboard/master/users/roles",
                name: "dashboard.master.users.roles",
                component: () =>
                    import("@/pages/dashboard/master/users/roles/Index.vue"),
                meta: {
                    pageTitle: "User Roles",
                    breadcrumbs: ["Master", "Users", "Roles"],
                },
            },
            {
                path: "/dashboard/master/users",
                name: "dashboard.master.users",
                component: () =>
                    import("@/pages/dashboard/master/users/Index.vue"),
                meta: {
                    pageTitle: "Users",
                    breadcrumbs: ["Master", "Users"],
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "sign-in",
                component: () => import("@/pages/auth/sign-in/Index.vue"),
                meta: {
                    pageTitle: "Sign In",
                    middleware: "guest",
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/reset-pass",
                name: "reset-pass",
                component: () => import("@/pages/auth/sign-in/tabs/ResetPass.vue"),
                meta: {
                    pageTitle: "Sign In",
                    middleware: "guest",
                },
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/SystemLayout.vue"),
        children: [
            {
                // the 404 route, when none of the above matches
                path: "/404",
                    me: "404",
                component: () => import("@/pages/errors/Error404.vue"),
                meta: {
                    pageTitle: "Error 404",
                },
            },
            {
                path: "/500",
                name: "500",
                component: () => import("@/pages/errors/Error500.vue"),
                meta: {
                    pageTitle: "Error 500",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/404",
    },
    
    {
        path: "/spv/landing",
        name: "spv.landing",
        component: () => import ("@/spvpage/landingspv.vue")
    },
    
    {
        path: "/staff/landing",
        name: "staff.landing",
        component: () => import ("@/staffpage/landing.vue")
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to) {
        // If the route has a hash, scroll to the section with the specified ID; otherwise, scroll to the top of the page.
        if (to.hash) {
            return {
                el: to.hash,
                top: 80,
                behavior: "smooth",
            };
        } else {
            return {
                top: 0,
                left: 0,
                behavior: "smooth",
            };
        }
    },
});

router.beforeEach(async (to, from, next) => {
    if (to.name) {
        // Start the route progress bar.
        NProgress.start();
    }

    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // current page view title
    if (to.meta.pageTitle) {
        document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME
            }`;
    } else {
        document.title = import.meta.env.VITE_APP_NAME as string;
    }

    // reset config to initial state
    configStore.resetLayoutConfig();

    // verify auth token before each page change
    if (!authStore.isAuthenticated) await authStore.verifyAuth();

    // before page access check if page requires authentication
    if (to.meta.middleware == "auth") {
        if (authStore.isAuthenticated) {
            if (
                to.meta.permission &&
                !authStore.user.permission.includes(to.meta.permission)
            ) {
                next({ name: "404" });
            } else if (to.path == '/') {
                if (authStore.user.role?.id == 1) {
                    // Admin role
                    next({ name: "dashboard" });
                } else if (authStore.user.role?.id == 2) {
                    // Supervisor role
                    next({ name: "spv.landing" });
                } else if (authStore.user.role?.id == 3) {
                    // Staff role
                    next({ name: "staff.landing" });
                } else {
                    next({ name: "landing.page" });
                }
            } else if (to.meta.checkDetail == false) {
                next();
            }
            next();
        } else {
            next({ name: "sign-in" });
        }
    } else if (to.meta.middleware == "guest" && authStore.isAuthenticated) {
        if (authStore.user.role?.id == 1) {
            // Admin role
            next({ name: "dashboard" });
        } else if (authStore.user.role?.id == 2) {
            // Supervisor role
            next({ name: "spv.landing" });
        } else if (authStore.user.role?.id == 3) {
            // Staff role
            next({ name: "staff.landing" });
        } else {
            next({ name: "landing.page" });
        }
    } else {
        next();
    }
});

router.afterEach(() => {
    // Complete the animation of the route progress bar.
    NProgress.done();
});

export default router;
