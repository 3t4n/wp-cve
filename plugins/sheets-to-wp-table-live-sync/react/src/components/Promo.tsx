import React from 'react';
import { Link } from 'react-router-dom';
import Card from '../core/Card';
import Column from '../core/Column';
import Container from '../core/Container';
import Row from '../core/Row';
import { getStrings } from '../Helpers';
import { DesktopComputers } from '../icons';

function Promo() {
	return (
		<div className="swptls-promo">
			<Card>
				<Row middleSm>
					<Column sm="8">
						<div className="promo-content">
							<h4>{getStrings('lts-data')}</h4>
							<p>{getStrings('create-beautifully-designed-tables')}

							</p>

							<div className="button">
								<Link to="/tables/create">
									{getStrings('create-new-table')}
								</Link>
							</div>
						</div>
					</Column>
					<Column sm="4">
						<div className="promo-img">{DesktopComputers}</div>
					</Column>
				</Row>
			</Card>
		</div>
	);
}

export default Promo;
