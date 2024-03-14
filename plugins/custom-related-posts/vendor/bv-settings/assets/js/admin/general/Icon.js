import React from 'react';
import SVG from 'react-inlinesvg';

import IconArrows from '../../../icons/arrows.svg';
import IconBook from '../../../icons/book.svg';
import IconBrush from '../../../icons/brush.svg';
import IconButtonClick from '../../../icons/button-click.svg';
import IconClock from '../../../icons/clock.svg';
import IconCode from '../../../icons/code.svg';
import IconCog from '../../../icons/cog.svg';
import IconDocApple from '../../../icons/doc-apple.svg';
import IconDoc from '../../../icons/doc.svg';
import IconEdit from '../../../icons/edit.svg';
import IconImport from '../../../icons/import.svg';
import IconKey from '../../../icons/key.svg';
import IconLetter from '../../../icons/letter.svg';
import IconLink from '../../../icons/link.svg';
import IconLock from '../../../icons/lock.svg';
import IconPrinter from '../../../icons/printer.svg';
import IconQuestion from '../../../icons/question.svg';
import IconSearch from '../../../icons/search.svg';
import IconShare from '../../../icons/share.svg';
import IconSliders from '../../../icons/sliders.svg';
import IconStar from '../../../icons/star.svg';
import IconSupport from '../../../icons/support.svg';
import IconText from '../../../icons/text.svg';
import IconUp from '../../../icons/up.svg';

const icons = {
    arrows: IconArrows,
    book: IconBook,
    brush: IconBrush,
    'button-click': IconButtonClick,
    clock: IconClock,
    code: IconCode,
    cog: IconCog,
    'doc-apple': IconDocApple,
    doc: IconDoc,
    edit: IconEdit,
    import: IconImport,
    key: IconKey,
    letter: IconLetter,
    link: IconLink,
    lock: IconLock,
    printer: IconPrinter,
    question: IconQuestion,
    search: IconSearch,
    share: IconShare,
    sliders: IconSliders,
    star: IconStar,
    support: IconSupport,
    text: IconText,
    up: IconUp,
};

const Icon = (props) => {
    let icon = icons.hasOwnProperty(props.type) ? icons[props.type] : false;

    if ( !icon ) {
        return <span className="bvs-settings-noicon">&nbsp;</span>;
    }

    return (
        <SVG
            src={icon}
            className='bvs-settings-icon'
        />
    );
}
export default Icon;