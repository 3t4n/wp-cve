import React from 'react';
import { Link } from 'react-router-dom';
import { GrayPlusIcon } from '../icons';
import { getStrings } from './../Helpers';

function AddNewTable() {
	return (
		<Link
			to="/tables/create"
			className="add-new-table btn add-new-table-btn"
		>
			{GrayPlusIcon}
			{getStrings('add-new-table')}
		</Link>
	);
}

export default AddNewTable;
