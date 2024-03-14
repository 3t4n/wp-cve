// #region [Imports] ===================================================================================================

// Libraries
import { useState } from 'react';
import { Card, Button, Row, Col, Divider, Tag, message } from 'antd';
import { PlusOutlined } from '@ant-design/icons';

// Components
import IconText from './IconText';
import PluginInstallerButton from '../PluginInstallerButton';

// SCSS
import './index.scss';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var ajaxurl: string;
declare var jQuery: any;
declare var acfwAdminApp: any;

// #endregion [Variables]

// #region [Interfaces]=================================================================================================

interface ITriggerAction {
  type: string;
  desc: string;
  is_pro: boolean;
}

interface ISampleAutomation {
  title: string;
  requires: string[];
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const StoreCreditAutomations = () => {
  const {
    logo,
    main_content,
    action_text,
    action_url,
    is_plugin_active,
    nonce,
    labels,
    triggers_actions,
    sample_automations,
  } = acfwAdminApp.uncanny_automator;

  const [isPluginActive, setIsPluginActive] = useState(!!is_plugin_active);

  return (
    <div className="store-credit-automations">
      <div className="store-credit-automations__inner">
        <div className="store-credit-automations__header">
          <img src={logo} />
          <p dangerouslySetInnerHTML={{ __html: main_content }} />

          {isPluginActive ? (
            <Button className="main-cta-btn" type="primary" size="large" href={action_url} icon={<PlusOutlined />}>
              {labels.add_new_recipe}
            </Button>
          ) : (
            <PluginInstallerButton
              pluginSlug="uncanny-automator"
              className="main-cta-btn"
              type="primary"
              size="large"
              text={action_text}
              nonce={nonce}
              successMessage={labels.success_message}
              afterInstall={() => setIsPluginActive(true)}
            />
          )}
        </div>

        <div className="triggers-actions">
          <Row gutter={16}>
            {triggers_actions.map((ta: ITriggerAction, key: number) => (
              <Col key={key} span={12}>
                <Card>
                  <span className="trigger-action__type">
                    {ta.type} {ta.is_pro && <Tag color="#1393A6">{labels.pro}</Tag>}
                  </span>
                  <span className="trigger-action__desc">{ta.desc}</span>
                </Card>
              </Col>
            ))}
          </Row>
        </div>

        <Divider />

        <div className="sample-automations">
          <p>{labels.sample_automations}</p>
          <Row gutter={16}>
            {sample_automations.map((sa: ISampleAutomation, key: number) => (
              <Col key={key} span={8}>
                <Card bordered={false}>
                  <h3>{sa.title}</h3>
                  <p>{labels.requires}</p>
                  <ul>
                    {sa.requires.map((r: string, key: number) => (
                      <li key={key}>
                        <IconText slug={r} />
                      </li>
                    ))}
                  </ul>
                </Card>
              </Col>
            ))}
          </Row>
        </div>
      </div>
    </div>
  );
};

export default StoreCreditAutomations;

// #endregion [Component]
