type GuestPrefix = "g_";
type UserId = number;

export type CustomerId = `${GuestPrefix}${UserId}` | UserId;

export type InternalTableData = {
  [k: string]: unknown;
};

export type PostStatus = "publish" | "draft" | "trash";

export type HttpProblem = {
  title: string;
  code: number;
  detail?: string;
};
