const routes = [
  {
    path: "/automations",
    name: "automationsHolder",
    component: async () => import("./views/AutomationsView.vue"),
    children: [
      {
        path: "",
        component: async () => import("./views/AutomationsPage.vue"),
        name: "automations",
      },
      {
        path: "recipes",
        component: async () => import("./views/RecipesPage.vue"),
        name: "recipes",
      },
      {
        path: "/automations/:id",
        name: "automation",
        component: async () => import("./views/AutomationEdit.vue"),
      },
      ...(window.ShopMagic.modules.includes("shopmagic-manual-actions")
        ? [
            {
              path: "/automations/:id/manual/run",
              name: "manual-run",
              component: async () => import("./views/ManualPreview.vue"),
              props: true
            },
          ]
        : []),
    ],
  },
];

export default routes;
