import React, { useEffect, useState, useRef } from 'react';
import { Cross } from '../icons';
import Container from '../core/Container';
import Row from '../core/Row';
import Column from '../core/Column';
import Card from '../core/Card';
import CtaAdd from './CtaAdd';
import GetSupport from './GetSupport';
import SupportModel from './SupportModel';
import { book, videoPlay, support, KeyboardArrowDown, promoThumbnail } from './../icons';
import { isProActive, getNonce, getStrings } from '../Helpers';

//styles
import '../styles/_documentation.scss';

function Documentation() {
	const supportModalRef = useRef();
	const [supportModal, setSupportleModal] = useState(false);
	const [activeItems, setActiveItems] = useState([0]);

	const faqs = [
		{ question: getStrings('doc-1'), answer: getStrings('doc-1-ans') },
		{ question: getStrings('doc-2'), answer: getStrings('doc-2-ans') },
		{ question: getStrings('doc-3'), answer: getStrings('doc-3-ans') },
		{ question: getStrings('doc-4'), answer: getStrings('doc-4-ans') },
		{ question: getStrings('doc-5'), answer: getStrings('doc-5-ans') },
		{ question: getStrings('doc-6'), answer: getStrings('doc-6-ans') },
		{ question: getStrings('doc-7'), answer: getStrings('doc-7-ans') },
		// Add more FAQ items as needed
	];

	const handleToggle = (index) => {
		// console.log(index)
		if (activeItems.includes(index)) {
			setActiveItems(activeItems.filter((item) => item !== index));
		} else {
			setActiveItems([...activeItems, index]);
		}
	};

	function AccordionItem({ index, isActive, faq }) {
		return (
			<Card customClass={`accordion-body ${isActive ? 'active' : ''}`}>
				<div className="accordion-item">
					<div
						className={`accordion-header ${isActive ? 'active' : ''}`}
						onClick={() => handleToggle(index)}
					>
						<h5 className="accordion-title">{faq.question}</h5>
						<div className={`accordion-icon ${isActive ? 'active' : ''}`}>
							{KeyboardArrowDown}
						</div>
					</div>
					<div className="accordion-body">
						{isActive && (
							// <p className='accordion-content'>{faq.answer}</p>
							<div className='acc-content' style={{ lineHeight: '25px' }} dangerouslySetInnerHTML={{ __html: faq.answer }} />
						)}
					</div>
				</div>
			</Card>
		);
	}

	const handleCreateSupportPopup = (e) => {
		e.preventDefault();
		setSupportleModal(true);
	};
	const handleVisitSupportForum = (e) => {
		e.preventDefault();
		window.open('https://wordpress.org/support/plugin/sheets-to-wp-table-live-sync/', '_blank');
	};

	const handleClosePopup = (e) => {
		setSupportleModal(false);
	};

	/**
	 * Alert if clicked on outside of element
	 *
	 * @param  event
	 */
	function handleCancelOutside(event: MouseEvent) {
		if (
			supportModalRef.current &&
			!supportModalRef.current.contains(event.target)
		) {
			handleClosePopup();
		}
	}

	useEffect(() => {
		document.addEventListener('mousedown', handleCancelOutside);
		return () => {
			document.removeEventListener('mousedown', handleCancelOutside);
		};
	}, [handleCancelOutside]);


	return (
		<Container customClass="documentation-page-wrap">
			<Row customClass='documentation-flex-row'>
				<Column lg="3" sm="4">
					<Card customClass='documentation-card'>
						<a href="https://wppool.dev/docs-category/how-to-use-sheets-to-wp-table/" target="_blank" className="single-doc-item">
						</a>
						{book}
						<h4>{getStrings('documentation')}</h4>
					</Card>
				</Column>

				<Column lg="3" sm="4">
					<Card customClass='documentation-card'>
						<a href="https://www.youtube.com/watch?v=hKYqE4e_ipY&list=PLd6WEu38CQSyY-1rzShSfsHn4ZVmiGNLP" target="_blank" className="single-doc-item"></a>
						{videoPlay}
						<h4>{getStrings('vt')}</h4>
					</Card>
				</Column>

				<Column lg="3" sm="4">
					<a className="documentation-contact" href="#" onClick={(e) => isProActive() ? handleCreateSupportPopup(e) : handleVisitSupportForum(e)}>
						{support}
						<h4>{getStrings('need-help')}</h4>
						<p>{getStrings('prof-need-help')}</p>
					</a>
				</Column>

			</Row>



			<CtaAdd />

			{/* Frequently Asked Questions*/}
			<Row middleXs={true}>
				<Column xs="12" sm='6' customClass='documentation-page'>
					<h2 className='fag-header'>{getStrings('faq')}</h2>
					{faqs.map((faq, index) => (
						<AccordionItem
							key={index}
							index={index}
							isActive={activeItems.includes(index)}
							faq={faq}
						/>
					))}
				</Column>
			</Row>

			{!isProActive() && (<Card><Row middleXs={true}>

				<Column xs="12" sm='6'>
					<div className='get-pro-promo'>
						<h2>{getStrings('get-pro')}</h2>
						<p>{getStrings('get-plugin')}</p>
						<a href="https://go.wppool.dev/KfVZ" target="_blank" className="unlock-features button">{getStrings('unlock-all')}</a>
						<p className='documention-list'>{getStrings('link-supp')}</p>
						<p className='documention-list'>{getStrings('pre-built-sheet-style')}</p>
						<p className='documention-list'>{getStrings('hide-row-based-on')}</p>
						<p className='documention-list'>{getStrings('unlimited-fetch-from-gs')}</p>

					</div>
				</Column>
			</Row></Card>)}

			{supportModal && (
				<GetSupport>
					<div
						className="create-support-modal-wrap modal-content manage-support-modal-content"
						ref={supportModalRef}
					>
						<div
							className="cross_sign"
							onClick={(e) => handleClosePopup(e)}
						>
							{Cross}
						</div>
						<div className="create-table-modal">
							<SupportModel />
						</div>
					</div>
				</GetSupport>
			)}

		</Container>
	);
}

export default Documentation;
