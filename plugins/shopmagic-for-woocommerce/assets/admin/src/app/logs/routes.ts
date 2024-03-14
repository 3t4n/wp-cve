export default [
  {
    path: "/logs",
    component: async () => import("./views/OutcomesView.vue"),
    children: [
      {
        path: "outcomes",
        component: async () => import("./views/OutcomesPage.vue"),
        name: "outcomes",
      },
      {
        path: "queue",
        component: async () => import("./views/QueuePage.vue"),
        name: "queue",
      },
      {
        path: "tracker",
        component: async () => import("./views/TrackerPage.vue"),
        name: "tracker",
      },
    ],
  },
];
