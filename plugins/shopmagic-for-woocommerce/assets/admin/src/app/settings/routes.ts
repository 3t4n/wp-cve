export default [
  {
    path: "/settings/:page?",
    name: "settings",
    component: async () => import("./views/SettingsPage.vue"),
  },
];
