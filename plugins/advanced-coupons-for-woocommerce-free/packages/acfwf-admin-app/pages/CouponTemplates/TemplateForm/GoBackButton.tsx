// #region [Imports] ===================================================================================================

// Libraries
import { Button } from 'antd';
import { useHistory } from 'react-router-dom';
import { SizeType } from 'antd/lib/config-provider/SizeContext';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  text: string;
  size: SizeType;
  onClick?: () => void;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const GoBackButton = (props: IProps) => {
  const { text, size, onClick } = props;
  const history = useHistory();

  const handleClick = () => {
    if (typeof onClick === 'function') onClick();

    history.goBack();
  };

  return (
    <Button onClick={handleClick} size={size}>
      {text}
    </Button>
  );
};

export default GoBackButton;

// #endregion [Component]
