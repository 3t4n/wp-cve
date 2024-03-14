export interface ICouponTemplatesStore {
  loading: boolean;
  templates: ICouponTemplate[];
  recent: ICouponTemplate[];
  review: ICouponTemplate[];
  edit: ICouponTemplate | null;
  categories: ICouponTemplateCategory[];
  formResponse: ICreateCouponFromTemplateResponse | null;
  premiumModal: boolean;
}

export interface ICouponTemplateListItem {
  id: number;
  title: string;
  description: string;
  license_type: 'free' | 'premium';
  image_bg_color: string;
  image_svg: string;
}

export interface ICouponTemplate extends ICouponTemplateListItem {
  fields: ICouponTemplateField[];
}

export interface ICouponTemplateCategory {
  name: string;
  slug: string;
  url: string;
  count: number;
}

export interface ICouponTemplateField {
  field: string;
  is_required: boolean;
  field_value: 'user_defined' | 'editable' | 'readonly';
  value: string;
  fixtures: IFieldFixtures;
  error?: string;
  pre_filled_value: string;
}

export interface IFieldFixtures {
  label: string;
  description: string;
  type: string;
  tooltip: string;
  placeholder?: string;
  min?: string;
  max?: string;
  step?: string;
  options?: Record<string, string>;
}

export interface IFieldComponentProps {
  defaultValue: any;
  editable: boolean;
  fixtures: IFieldFixtures;
  onChange: (value: any) => void;
}

export interface ICouponTemplateFormData {
  id: number;
  fields: ICouponTemplateFieldData[];
}

export interface ICouponTemplateFieldData {
  key: string;
  value: any;
  type: string;
}

export interface ICouponTemplateFieldResponseData {
  label: string;
  value: string;
}

export interface ICreateCouponFromTemplateResponse {
  status: string;
  message: string;
  fields: ICouponTemplateFieldResponseData[];
  coupon_id: number;
  coupon_edit_url: string;
}
