// @flow
import React from 'react';
import { stringify } from 'query-string';
import throttle from 'lodash.throttle';
import {
  activitiesDefaultAttributes, cityDefaultAttributes, gygData, spacers,
} from '../config';

const iframeMsgListener = (target) => {
  const el = target;
  if (!el) {
    return;
  }
  window.addEventListener(
    'message',
    (msg) => {
      if (
        msg.origin !== 'https://widget.getyourguide.com'
        || parseInt(el.style.height, 10) === msg.data.height
        || msg.data.url !== el.firstChild.src
      ) {
        return;
      }
      el.style.height = `${msg.data.height + 1}px`;
    },
    false,
  );
};

const postMessage = (target) => {
  if (!target) {
    return;
  }
  target.firstChild.contentWindow.postMessage('update height', 'https://widget.getyourguide.com');
};

const handleIframeResize = (target) => {
  window.addEventListener('resize', throttle(postMessage.bind(null, target), 1000));
};

const iframeHandlers = (target) => {
  if (!target) {
    return;
  }
  iframeMsgListener(target);
  postMessage(target);
  handleIframeResize(target);
};

const qs = ({ widgetType = 'activities', queries = {} }) => {
  const { partnerID: partner_id } = gygData; // eslint-disable-line camelcase
  const allQueries = (() => {
    switch (widgetType) {
    case 'city':
      return { ...cityDefaultAttributes, ...queries, partner_id };
    default:
      return { ...activitiesDefaultAttributes, ...queries, partner_id };
    }
  })();
  return stringify(
    Object.keys(allQueries).reduce(
      (acc, key) => (allQueries[key] && allQueries[key].length > 0 ? { [key]: allQueries[key], ...acc } : acc),
      {},
    ),
  );
};
const title = widgetType => `GetYourGuide ${widgetType} widget`;
const url = 'https://widget.getyourguide.com/default/';

type IframeProps = {
  queries?: Object,
  widgetType?: string,
  wpWidgetType?: string,
};
class Iframe extends React.Component<IframeProps> {
  container = React.createRef();

  static defaultProps = {
    queries: {},
    widgetType: 'activities',
    wpWidgetType: 'wp_activities',
  };

  componentDidMount() {
    const { current } = this.container;
    iframeHandlers(current);
  }

  render() {
    const { container } = this;
    const { widgetType = 'activities', wpWidgetType = 'wp_activities', queries } = this.props;
    return (
      <div ref={container}>
        <iframe
          src={`${url}${widgetType}.frame?widget=${wpWidgetType}&${qs({
            widgetType,
            queries,
          })}`}
          style={{ height: '100%', marginBottom: spacers.standard }}
          title={title(widgetType)}
        />
      </div>
    );
  }
}

const wpIframe = ({ widgetType, wpWidgetType, queries }) => {
  const { createElement } = window.wp.element;
  return createElement(
    'div',
    {
      class: 'gyg-iframe-container',
      style: {
        marginBottom: spacers.standard,
      },
    },
    createElement('iframe', {
      style: {
        border: '0',
        height: '100%',
        width: '100%',
      },
      title: title(widgetType),
      src: `${url}${widgetType}.frame?widget=${wpWidgetType}&${qs({
        widgetType,
        queries,
      })}`,
    }),
  );
};

export { Iframe, wpIframe, iframeHandlers };
