import React, { useState, useEffect } from 'react';
import { SpinLoader } from './../icons';
import { getStrings, getNonce } from '../Helpers';
//styles
import '../styles/_recommendation.scss';

function Recommendation() {
	const [loader, setLoader] = useState<boolean>(true);
	const [pluginlist, setPluginlist] = useState<string>('');

	useEffect(() => {
		wp.ajax.send('gswpts_product_fetch', {
			data: {
				nonce: getNonce(),
			},
			success(response) {
				setPluginlist(response.plugin_cards_html);
				setLoader(false);
			},
			error(error) {
				console.error(error);
				setPluginlist(error);
				setLoader(false);
			},
		});
	}, []);

	return (
		<div>
			{loader ? (
				<div className='loader-container'>
					<div className='plugin-list-loader'>
						{SpinLoader}
					</div>
				</div>
			) : (
				<div>
					<h3>{getStrings('other-prodct')}</h3>
					<p>{getStrings('remarkable-product')}</p>
					<div className='plugin-list' dangerouslySetInnerHTML={{ __html: pluginlist }} />
				</div>
			)}
		</div>
	);
}

export default Recommendation;