// #region [Imports] ===================================================================================================

// Libraries
import {
  ControlOutlined,
  SettingOutlined,
  TagsOutlined,
  ScheduleOutlined,
  UserOutlined,
  LinkOutlined,
  MedicineBoxOutlined,
  ApiOutlined,
  StarOutlined,
  RocketOutlined,
  DollarCircleOutlined,
  GiftOutlined,
  CreditCardOutlined,
  ThunderboltOutlined,
} from '@ant-design/icons';

// #endregion [Imports]

// #region [Interfaces] ================================================================================================

interface IProps {
  section: string;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const MenuIcon = (props: IProps) => {
  const { section } = props;

  switch (section) {
    case 'modules_section':
      return <ControlOutlined />;

    case 'general_section':
      return <SettingOutlined />;

    case 'checkout_section':
      return <CreditCardOutlined />;

    case 'bogo_deals_section':
      return <TagsOutlined />;

    case 'scheduler_section':
      return <ScheduleOutlined />;

    case 'role_restrictions_section':
      return <UserOutlined />;

    case 'url_coupons_section':
      return <LinkOutlined />;

    case 'store_credits_section':
      return <DollarCircleOutlined />;

    case 'advanced_section':
      return <ThunderboltOutlined />;

    case 'help_page':
      return <MedicineBoxOutlined />;

    case 'license_page':
      return <ApiOutlined />;

    case 'premium_upgrade':
      return <StarOutlined />;

    case 'loyalty_program_section':
    case 'loyalty_program':
      return <RocketOutlined />;

    case 'advanced_gift_cards':
      return <GiftOutlined />;
  }

  return null;
};

export default MenuIcon;

// #endregion [Component]
