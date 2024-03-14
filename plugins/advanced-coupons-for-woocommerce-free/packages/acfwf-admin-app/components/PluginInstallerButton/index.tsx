// #region [Imports] ===================================================================================================

// Libraries
import { useState } from 'react';
import { Button, message } from 'antd';
import type { SizeType } from 'antd/lib/config-provider/SizeContext';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var ajaxurl: string;
declare var jQuery: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface IProps {
  pluginSlug: string;
  className?: string;
  type: 'text' | 'link' | 'ghost' | 'default' | 'primary' | 'dashed' | undefined;
  size: SizeType;
  text: string;
  nonce: string;
  successMessage: string;
  afterInstall?: () => void;
}
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const PluginInstallerButton = (props: IProps) => {
  const { pluginSlug, className, type, size, text, successMessage, nonce, afterInstall } = props;
  const [loading, setLoading] = useState(false);

  const handleInstallPlugin = () => {
    setLoading(true);

    jQuery
      .ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
          action: 'acfw_install_activate_plugin',
          plugin_slug: pluginSlug,
          nonce: nonce,
        },
      })
      .done((response: any) => {
        setLoading(false);
        if (response?.success && !response.success) {
          message.error(response.data);
        } else {
          if (typeof afterInstall === 'function') afterInstall();
          message.success(successMessage);
        }
      })
      .fail((error: any) => {
        message.error(error.responseJSON.data);
      });
  };

  return (
    <Button className={className} type={type} size={size} loading={loading} onClick={handleInstallPlugin}>
      {text}
    </Button>
  );
};

export default PluginInstallerButton;
// #endregion [Component]
