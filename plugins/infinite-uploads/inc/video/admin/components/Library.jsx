import {useEffect, useState} from '@wordpress/element';
import {__, _x, _n, _nx} from '@wordpress/i18n';
import VideoCard from './VideoCard';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Header from './Header';
import Paginator from './Paginator';
import Spinner from 'react-bootstrap/Spinner';

export default function Library({selectVideo}) {
	const [videos, setVideos] = useState([]);
	const [loading, setLoading] = useState(true);
	const [orderBy, setOrderBy] = useState('date');
	const [search, setSearch] = useState('');
	const [page, setPage] = useState(1);
	const [totalItems, setTotalItems] = useState(0);
	const [itemsPerPage, setItemsPerPage] = useState(40);
	const [refreshInterval, setRefreshInterval] = useState(60000);

	//get videos on render
	useEffect(() => {
		if (!loading) {
			setLoading(true);
			getVideos();
		}
	}, [orderBy, page]);

	useEffect(() => {
		if (search.length > 2 || search.length === 0) {
			setPage(1);
			setLoading(true);
			getVideos();
		}
	}, [search]);

	useEffect(() => {
		//check the videos array if any of the video objects are currently processing or transcoding
		const processing = videos.find((video) => (video.status === 2 || video.status === 3));
		if (processing) {
			setRefreshInterval(10000);
		} else {
			setRefreshInterval(60000);
		}
	}, [videos]);

	//fetch videos on a 30s interval
	useEffect(() => {
		const interval = setInterval(() => {
			getVideos();
		}, refreshInterval);
		return () => clearInterval(interval);
	}, [orderBy, page, search, refreshInterval]);

	function getVideos() {
		const options = {
			method: 'GET',
			headers: {
				Accept: 'application/json',
				AccessKey: IUP_VIDEO.apiKey,
			},
		};

		fetch(
			`https://video.bunnycdn.com/library/${IUP_VIDEO.libraryId}/videos?page=${page}&itemsPerPage=${itemsPerPage}&orderBy=${orderBy}&search=${search}`,
			options
		)
			.then((response) => response.json())
			.then((data) => {
				console.log('Videos:', data);
				setVideos(data.items);
				setTotalItems(data.totalItems);
				setItemsPerPage(data.itemsPerPage);
				setLoading(false);
			})
			.catch((error) => {
				console.error(error);
				setLoading(false);
			});
	}

	return (
		<>
			{!selectVideo && (
				<h1 className="text-muted mb-3">
					<img
						src={IUP_VIDEO.assetBase + '/img/iu-logo-gray.svg'}
						alt="Infinite Uploads Logo"
						height="32"
						width="32"
						className="me-2"
					/>
					{__('Cloud Video Library', 'infinite-uploads')}
				</h1>
			)}
			<Container fluid>
				<Header
					{...{
						orderBy,
						setOrderBy,
						search,
						setSearch,
						selectVideo,
						getVideos
					}}
				/>

				{!loading ? (
					<Container fluid>
						<Row
							xs={1}
							sm={1}
							md={2}
							lg={3}
							xl={4}
							xxl={5}
						>
							{videos.length > 0 ? (
								videos.map((video, index) => {
									return (
										<Col key={index + video.guid}>
											<VideoCard
												{...{
													video,
													setVideos,
													selectVideo,
												}}
											/>
										</Col>
									);
								})
							) : (
								<Container className="my-5 justify-content-center align-items-center">
									<p className="text-muted text-center h5">
										{__('No videos found.', 'infinite-uploads')}
									</p>
								</Container>
							)}
						</Row>
						<Paginator
							{...{page, setPage, totalItems, itemsPerPage}}
						/>
					</Container>
				) : (
					<Container className="d-flex justify-content-center align-middle my-5">
						<Spinner
							animation="grow"
							role="status"
							className="my-5"
						>
							<span className="visually-hidden">Loading...</span>
						</Spinner>
					</Container>
				)}
			</Container>
		</>
	);
}
