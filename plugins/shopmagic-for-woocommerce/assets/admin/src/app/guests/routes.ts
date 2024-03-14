export default [
  {
    path: "/guests",
    name: "guests",
    component: async () => import("./views/GuestsPage.vue"),
  },
];
