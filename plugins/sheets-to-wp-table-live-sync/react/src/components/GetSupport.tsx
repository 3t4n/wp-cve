import { FC } from 'react';
import { createPortal } from 'react-dom';
import './../styles/_getSupport.scss';

type Props = {
    children: React.ReactNode;
    customClass?: string;
};

const GetSupport: FC<Props> = ({ children, customClass }): JSX.Element => {
    return createPortal(
        <>
            <div className="modal-overlay" />
            <div className="modal-content">
                <div
                    className={`modal-content-inner ${customClass ? customClass : ''
                        }`}
                >
                    <div className="modal-body">{children}</div>
                </div>
            </div>
        </>,
        document.getElementById('swptls-app-portal')
    );
}

export default GetSupport
