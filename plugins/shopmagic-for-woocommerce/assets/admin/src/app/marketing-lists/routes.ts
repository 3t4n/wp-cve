export default [
  {
    path: "/marketing-lists",
    component: async () => import("./views/MarketingListsView.vue"),
    children: [
      {
        path: "",
        component: async () => import("./views/MarketingListsPage.vue"),
        name: "lists",
      },
      {
        path: "subscribers",
        component: async () => import("./views/SubscribersPage.vue"),
        name: "subscribers",
      },
      {
        path: "transfer",
        component: async () => import("./views/TransferPage.vue"),
        name: "transfer",
      },
      {
        path: "/marketing-lists/:id",
        name: "marketing-list",
        component: async () => import("./views/MarketingListEdit.vue"),
      },
    ],
  },
];
