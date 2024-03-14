export default [
  {
    name: "welcome",
    path: "/welcome",
    component: async () => import("./views/WelcomePage.vue"),
  },
];
