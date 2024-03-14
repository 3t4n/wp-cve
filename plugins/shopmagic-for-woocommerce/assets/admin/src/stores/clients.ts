import { defineStore } from "pinia";
import { get, query } from "@/_utils";
import type { Query } from "@/_utils";
import type { CustomerId } from "@/types";

export type Client = {
  id: CustomerId;
  email: string;
};

export const useClientsStore = defineStore("clients", {
  state: () => ({
    clients: [] as Client[],
  }),
  getters: {
    getClient: (state) => (clientId: CustomerId) =>
      state.clients.find((client) => client.id == clientId),
    getClientByEmail: (state) => (query: string) =>
      state.clients.filter((client) => client.email.includes(query)),
    getClients:
      (state) =>
      (queryArgs: Query = {}) =>
        query(state.clients, queryArgs),
    getItems:
      (state) =>
      (queryArgs: Query = {}) =>
        query(state.clients, queryArgs),
  },
  actions: {
    fetchItems: async function () {
      this.clients = await get<Client[]>("/clients");
      return this.clients;
    },
  },
});
