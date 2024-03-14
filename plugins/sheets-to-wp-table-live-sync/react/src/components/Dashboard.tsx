import React, { useState, useEffect } from 'react';
import TablesList from './TablesList';
import Title from '../core/Title';
import { Cloud, WhitePlusIcon, searchIcon } from '../icons';
import { Link } from 'react-router-dom';

import { getNonce, getTables, convertToSlug, getStrings } from './../Helpers';

//styles
import '../styles/_dashboard.scss';
import Card from '../core/Card';

function Dashboard() {
	const [loader, setLoader] = useState<boolean>(false);
	const [tables, setTables] = useState(getTables());
	const [copiedTables, setCopiedTables] = useState(getTables());
	const [searchKey, setSearchKey] = useState<string>('');
	const [tableCount, setTableCount] = useState(0);

	// console.log(copiedTables)

	useEffect(() => {
		setLoader(true);

		wp.ajax.send('swptls_get_tables', {
			data: {
				nonce: getNonce(),
			},
			success(response) {
				setTables(response.tables);
				setCopiedTables(response.tables);
				setTableCount(response.tables_count);
				setLoader(false);
			},
			error(error) {
				console.error(error);
			},
		});
	}, []);

	useEffect(() => {
		if (searchKey !== '') {
			const filtered = tables.filter(({ table_name }: any) =>
				table_name
					.toLowerCase()
					.includes(searchKey.toString().toLowerCase())
			);

			setCopiedTables(filtered);
		} else {
			setCopiedTables(tables);
		}
	}, [searchKey]);

	// Reseting Table 
	useEffect(() => {
		const currentHash = window.location.hash;
		if (!currentHash.startsWith('#/tables/edit/')) {
			localStorage.setItem('active_tab', 'data_source');
		}
	}, [window.location.hash]);

	return (
		<>
			{tables.length < 1 ? (
				<>
					<div className="no-tables-created-intro text-center">
						<div className="no-tables-intro-img">{Cloud}</div>
						<h2>{getStrings('no-tables-have-been-created-yet')}</h2>
						<p>{getStrings('tables-will-be-appeared-here-once-you-create-them')}</p>
						<Link className='btn btn-lg' to="/tables/create">{getStrings('create-new-table')}</Link>
						<p className="help">
							{getStrings('need-help')} <a href="https://youtu.be/hKYqE4e_ipY?list=PLd6WEu38CQSyY-1rzShSfsHn4ZVmiGNLP" target="_blank">{getStrings('watch-now')}</a>
						</p>
					</div>
				</>
			) : (
				<>
					<div className="create-table-intro">
						<Title tagName="h2">
							{getStrings('db-title')}
						</Title>
						<Link
							className="create-table btn btn-md"
							to="/tables/create"
						>
							{getStrings('new-tables')} {WhitePlusIcon}
						</Link>
					</div>

					<div className="table-header">
						<Title tagName="h4">
							<strong>{tableCount}</strong>&nbsp;{getStrings('tables-created')}
						</Title>
						<div className="table-search-box">
							<input
								type="text"
								placeholder={getStrings('search-tb')}
								onChange={(e) =>
									setSearchKey(e.target.value.trim())
								}
							/>
							<div className="icon">{searchIcon}</div>
						</div>
					</div>

					{loader ? (
						<Card>
							<h1>{getStrings('loading')}</h1>
						</Card>
					) : (
						<TablesList
							// tables={tables}
							tables={copiedTables}
							copiedTables={copiedTables}
							setCopiedTables={setCopiedTables}
							setTables={setTables}
							setTableCount={setTableCount}
							setLoader={setLoader}
						/>
					)}
				</>
			)}
		</>
	);
}

export default Dashboard;
