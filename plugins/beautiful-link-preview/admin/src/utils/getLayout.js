import classNames from 'classnames';
import { decode } from 'he';
import { reduce } from 'lodash';
import striptags from 'striptags';

const getWrappedAnchor = ({ rel, target, url, content }) => {
    return (
        <a
            rel={rel}
            target={target}
            href={url}
        >{content}</a>
    );
};

const trimText = (text, maxLength) => {
    maxLength = parseInt(maxLength);
    text = striptags(text);

    return text.length < maxLength ?
        text :
        `${ text.substr(0,
                text.substr(0, maxLength).lastIndexOf(' ')
            ) } ...`;
};

const getLayout = (attributes) => {
    const globalOrAttribute = {
        ...zwtWPLinkPreviewerGlobals,
        ...reduce(attributes, ((acc, value, key) => {
            if (value) {
                acc[key] = value;
            }
            return acc;
        }), {})
    };
    const {
        description,
        hasImgCompact,
        hashMd5,
        hasImgFull,
        imgCompactSizeWidth,
        imgFullSizeWidth,
        imgURLStub,
        layout = 'full',
        max_desc_chars,
        max_title_chars,
        no_desc,
        no_img,
        no_title,
        rel,
        restNamespace,
        styleMaxWidth,
        target,
        title,
        urlHost,
        url,
    } = globalOrAttribute;

    const hasImg = layout === 'full' ? hasImgFull : hasImgCompact;

    const maxWidthStyle = {
        [styleMaxWidth]: `${ (layout === 'full') ? imgFullSizeWidth : imgCompactSizeWidth }px`,
    };

    const imgTag = (
        <img className={classNames('zwt-wp-link-prev-img', {
            empty: !hasImg
        })} src={!hasImg ? null : `${imgURLStub}/${restNamespace}/img/${hashMd5}/${layout}`} />
    );

    const imgContent = layout === 'full' ? imgTag : (
        <div className='zwt-wp-link-prev-img-container' style={ maxWidthStyle }>
            {imgTag}
        </div>
    );

    return (
        <div className={classNames('zwt-wp-link-prev', {
            full: layout === 'full',
            compact: layout === 'compact'
        })} style={ maxWidthStyle }>
            {!no_img && getWrappedAnchor({ rel, target, url, content: imgContent})}
            <div className="zwt-wp-link-prev-texts">
                <div>
                    {!no_title && (<h4 className="zwt-wp-link-prev-title">{getWrappedAnchor({ rel, target: target, url: url, content: decode(trimText(title, max_title_chars)) })}</h4>)}
                    {getWrappedAnchor({ rel, target, url, content: urlHost })}
                    {!no_desc && (<p className="zwt-wp-link-prev-desc">{decode(trimText(description, max_desc_chars))}</p>)}
                </div>
            </div>
        </div>
    );
};

export default getLayout;