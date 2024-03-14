export type CartProduct = {
  id: number;
  name: string;
  quantity: number;
  image: string;
};

type Price = {
  price: number;
  currency: string;
};

export type Cart = {
  id: number;
  status: "completed" | "failed" | "unknown" | "pending";
  object: "cart";
  updated: string;
  products: CartProduct[];
  value: Price;
};
