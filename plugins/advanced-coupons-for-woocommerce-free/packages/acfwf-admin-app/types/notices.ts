export interface ISingleNotice {
  slug: string;
  id: string;
  is_dismissable: boolean;
  type: string;
  nonce: string;
  content: string[];
  actions: INoticeAction[];
}

export interface INoticeAction {
  key: string;
  response: string;
  link: string;
  text: string;
  is_external?: boolean;
}
