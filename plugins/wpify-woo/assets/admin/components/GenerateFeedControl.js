import React, { useEffect, useState } from 'react';
import { Line } from 'rc-progress';
import { Button, Spinner } from '@wordpress/components';
import classnames from 'classnames';

const GenerateFeedControl = (props) => {
	const { className, label, desc, button = label, primary } = props;
	const [processedCount, setProcessedCount] = useState(0);
	const [percent, setPercent] = useState(0);
	const [isProcessing, setIsProcessing] = useState(false);
	const [page, setPage] = useState(0);

	const resolveCall = (response) => response.ok ? response.json() : Promise.reject(response);

	const processCall = (json) => {
		if (json.status === 'done') {
			setPercent(100);
			setIsProcessing(false);
			setPage(0);
		} else if (json.status === 'pending') {
			const count = processedCount + json.processed_count;
			setProcessedCount(count);
			setPercent(count / json.total_count * 100);

			if (page !== json.next_page) {
				setPage(json.next_page);
			}
		}
	};

	const catchCall = (error) => {
		console.error(error);
		setIsProcessing(false);
	};

	const fetchApi = () => {
		if (page > 0 && isProcessing) {
			fetch(`${props.feed_chunk_url}?page=${page}`)
				.then(resolveCall)
				.then(processCall)
				.catch(catchCall);
		}
	};

	useEffect(() => {
		if (page > 0) {
			fetchApi(page);
		}
	}, [page]);

	const handleGenerate = () => {
		setProcessedCount(0);
		setPercent(0);
		setIsProcessing(true);
		setPage(1);
	};

	if (!props.feed_chunk_url) {
		return null;
	}

	return (
		<div>
			<React.Fragment>
				<div style={{ display: 'flex' }}>
					<Button
						className={classnames(className, 'button', {
							'button-primary': primary,
						})}
						onClick={handleGenerate}
						disabled={isProcessing}
					>
						{button}
					</Button>
					{isProcessing && (
						<span>
              <Spinner/> <strong>{Math.round((percent + Number.EPSILON) * 100) / 100}%</strong>
            </span>
					)}
				</div>
				{desc && (
					<p dangerouslySetInnerHTML={{ __html: desc }} style={{ margin: '10px 0' }}/>
				)}
			</React.Fragment>
			{isProcessing && (
				<Line percent={percent} strokeWidth="2" strokeColor="#15d6d6"/>
			)
			}
		</div>
	);
};

export default GenerateFeedControl;
