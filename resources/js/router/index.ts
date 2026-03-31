import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import CreateView from "../views/CreateView.vue";
import ReportView from "../views/ReportView.vue";
import NotFoundView from "../views/NotFoundView.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: HomeView },
        { path: "/create", component: CreateView },
        { path: "/report", component: ReportView },
        { path: "/:pathMatch(.*)*", component: NotFoundView },
    ],
});

export default router;
