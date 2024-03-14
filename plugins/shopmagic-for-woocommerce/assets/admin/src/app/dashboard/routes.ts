export default [
  {
    name: "dashboard",
    path: "/",
    component: async () => import("./views/MainDashboard.vue"),
  },
];
