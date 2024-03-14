import {__, _x, _n, _nx} from '@wordpress/i18n';
import Card from 'react-bootstrap/Card';
import {useState, useEffect} from '@wordpress/element';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Container from 'react-bootstrap/Container';
import Modal from 'react-bootstrap/Modal';
import {
	VideoLength,
	VideoSize,
	VideoViews,
	VideoDate,
} from './VideoAttributes';
import Form from 'react-bootstrap/Form';
import InputGroup from 'react-bootstrap/InputGroup';
import Button from 'react-bootstrap/Button';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import DeleteModal from './DeleteModal';
import Spinner from 'react-bootstrap/Spinner';

export default function VideoModal({
	                                   video,
	                                   setVideos,
	                                   selectVideo,
	                                   children,
                                   }) {
	const [show, setShow] = useState(false);
	const [title, setTitle] = useState(video.title);
	const [autoPlay, setAutoPlay] = useState(false);
	const [loop, setLoop] = useState(false);
	const [muted, setMuted] = useState(false);
	const [preload, setPreload] = useState(true);
	const [embedParams, setEmbedParams] = useState('');
	const [uploading, setUploading] = useState(false);
	const [loading, setLoading] = useState(false);
	const [iframe, setIframe] = useState(null);

	useEffect(() => {
		let params = [];
		if (autoPlay) {
			params.push('autoplay="true"');
		}
		if (loop) {
			params.push('loop="true"');
		}
		if (muted) {
			params.push('muted="true"');
		}
		if (preload) {
			params.push('preload="true"');
		}
		setEmbedParams(params.join(' '));
	}, [autoPlay, loop, muted, preload]);

	useEffect(() => {
		const component = (
			<iframe
				src={`https://iframe.mediadelivery.net/embed/${
					video.videoLibraryId
				}/${video.guid}?autoplay=false&v=${Math.random()}`}
				loading="lazy"
				allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
				allowFullScreen={true}
			></iframe>
		);
		setIframe(component);
	}, [video]);

	const getThumbnail = (file) => {
		return IUP_VIDEO.cdnUrl + '/' + video.guid + '/' + file;
	};

	const handleShow = () => {
		setShow(true);
	};
	const handleClose = () => {
		setShow(false);
	};

	function updateVideo() {
		setLoading(true);
		const formData = new FormData();
		formData.append('title', title);
		formData.append('video_id', video.guid);
		formData.append('nonce', IUP_VIDEO.nonce);

		const options = {
			method: 'POST',
			headers: {
				Accept: 'application/json',
			},
			body: formData,
		};

		fetch(`${ajaxurl}?action=infinite-uploads-video-update`, options)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					setVideos((videos) =>
						videos.map((v) =>
							v.guid === video.guid ? {...v, title} : v
						)
					);
				} else {
					console.error(data.data);
				}
				setLoading(false);
			})
			.catch((error) => {
				console.log('Error:', error);
				setLoading(false);
			});
	}

	function setThumbnail(thumbnailFileName) {
		setLoading(true);
		const formData = new FormData();
		formData.append('thumbnail', thumbnailFileName);
		formData.append('video_id', video.guid);
		formData.append('nonce', IUP_VIDEO.nonce);

		const options = {
			method: 'POST',
			headers: {
				Accept: 'application/json',
			},
			body: formData,
		};

		fetch(`${ajaxurl}?action=infinite-uploads-video-update`, options)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					setVideos((videos) =>
						videos.map((v) =>
							v.guid === video.guid
								? {...v, thumbnailFileName}
								: v
						)
					);
				} else {
					console.error(data.data);
				}
				setLoading(false);
			})
			.catch((error) => {
				console.log('Error:', error);
				setLoading(false);
			});
	}

	function uploadThumbnail(file) {
		setUploading(true);
		const formData = new FormData();
		formData.append('thumbnailFile', file);
		formData.append('video_id', video.guid);
		formData.append('nonce', IUP_VIDEO.nonce);

		const options = {
			method: 'POST',
			headers: {
				Accept: 'application/json',
			},
			body: formData,
		};

		fetch(`${ajaxurl}?action=infinite-uploads-video-update`, options)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					getVideo(); // refresh video data
					setUploading(false);
				} else {
					console.error(data.data);
					setUploading(false);
				}
			})
			.catch((error) => {
				console.log('Error:', error);
				setUploading(false);
			});
	}

	function getVideo() {
		const options = {
			method: 'GET',
			headers: {
				Accept: 'application/json',
				AccessKey: IUP_VIDEO.apiKey,
			},
		};

		fetch(
			`https://video.bunnycdn.com/library/${IUP_VIDEO.libraryId}/videos/${video.guid}`,
			options
		)
			.then((response) => response.json())
			.then((data) => {
				//replace video in videos array
				setVideos((videos) =>
					videos.map((v) =>
						v.guid === video.guid ? {...v, ...data} : v
					)
				);
			})
			.catch((error) => {
				console.error(error);
			});
	}

	let thumbnails = [];
	for (let i = 1; i <= 5; i++) {
		thumbnails.push(
			<Col key={i} className="mb-2">
				<Card
					className="bg-dark text-white h-100 p-0"
					role="button"
					disabled={loading || uploading}
					onClick={() => setThumbnail('thumbnail_' + i + '.jpg')}
				>
					<div className="ratio ratio-16x9 overflow-hidden bg-black rounded">
						<div
							className="iup-video-thumb rounded border-0"
							style={{
								backgroundImage: `url("${getThumbnail(
									'thumbnail_' + i + '.jpg'
								)}")`,
							}}
						></div>
					</div>
					<div className="card-img-overlay rounded border-0">
						<div className="card-title align-middle text-center text-white">
							{__('Set', 'infinite-uploads')}
						</div>
					</div>
				</Card>
			</Col>
		);
	}
	thumbnails.push(
		<Col key="fileupload" className="mb-2">
			<Card
				className="h-100 p-0 border-4 border-secondary"
				style={{borderStyle: 'dashed'}}
				disabled={loading || uploading}
				role="button"
				onClick={() =>
					document.getElementById('upload-thumbnail').click()
				}
			>
				<div className="ratio ratio-16x9 overflow-hidden bg-light border-0 rounded">
					<div>
						{uploading ? (
							<div className="h-100 w-100 d-flex align-items-center justify-content-center">
								<Spinner
									animation="border"
									role="status"
									className="text-muted"
								/>
							</div>
						) : (
							<span className="dashicons dashicons-upload h-100 w-100 d-flex align-items-center justify-content-center text-muted h3"></span>
						)}
					</div>
				</div>
				<Form.Control
					type="file"
					id="upload-thumbnail"
					className="d-none"
					accept="image/png, image/jpeg"
					disabled={loading || uploading}
					onChange={() =>
						uploadThumbnail(
							document.getElementById('upload-thumbnail')
								.files[0]
						)
					}
				/>
			</Card>
		</Col>
	);

	return (
		<>
			<a
				className="m-3 w-100 p-0 text-decoration-none"
				role="button"
				aria-label={__('Open video modal', 'infinite-uploads')}
				onClick={() => {
					if (selectVideo) {
						selectVideo(video);
					} else {
						handleShow();
					}
				}}
			>
				{children}
			</a>

			<Modal
				show={show}
				onHide={handleClose}
				size="xl"
				aria-labelledby="contained-modal-title-vcenter"
				centered
			>
				<Modal.Header closeButton>
					<Modal.Title id="contained-modal-title-vcenter">
						{__('Edit Video:', 'infinite-uploads')}{' '}
						{video.title}
					</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Container fluid className="pb-3">
						<Row
							className="justify-content-center mb-4 mt-3"
							xs={1}
							lg={2}
						>
							<Col>
								<Row className="mb-2">
									<Col>
										<div className="ratio ratio-16x9">
											{iframe}
										</div>
									</Col>
								</Row>
								<Row className="justify-content-between text-muted text-center">
									<Col>
										<VideoDate video={video}/>
									</Col>
									<Col>
										<VideoLength video={video}/>
									</Col>
									<Col>
										<VideoViews video={video}/>
									</Col>
									<Col>
										<VideoSize video={video}/>
									</Col>
								</Row>
							</Col>
							<Col>
								<Row className="mb-4">
									<Col>
										<label htmlFor="video-title">
											{__(
												'Video Title',
												'infinite-uploads'
											)}
										</label>
										<InputGroup>
											<Form.Control
												id="video-title"
												placeholder={__(
													'Title',
													'infinite-uploads'
												)}
												aria-label={__(
													'Title',
													'infinite-uploads'
												)}
												value={title}
												onChange={(e) =>
													setTitle(e.target.value)
												}
												disabled={
													loading || uploading
												}
											/>
											<Button
												variant="primary"
												className="text-white"
												disabled={
													loading || uploading
												}
												onClick={updateVideo}
											>
												{__(
													'Update',
													'infinite-uploads'
												)}
											</Button>
										</InputGroup>
									</Col>
								</Row>

								<Row className="mb-4">
									<Col className="col-4">
										<h6>
											{__(
												'Current Thumbnail',
												'infinite-uploads'
											)}
										</h6>
										<Card className="bg-dark text-white w-100 p-0 mb-2">
											<div className="ratio ratio-16x9 overflow-hidden bg-black rounded border-0">
												<div
													className="iup-video-thumb rounded border-0"
													style={{
														backgroundImage: `url("${getThumbnail(
															video.thumbnailFileName
														)}")`,
													}}
												></div>
											</div>
										</Card>
									</Col>
									<Col className="col-8">
										<p>
											{__(
												'Choose a new thumbnail to be displayed in the video player:',
												'infinite-uploads'
											)}
										</p>
										<Row className="justify-content-start d-flex row-cols-2 row-cols-md-3">
											{thumbnails}
										</Row>
									</Col>
								</Row>

								<Row className="justify-content-end mb-3">
									<Col className="justify-content-end d-flex">
										<DeleteModal
											video={video}
											setVideos={setVideos}
										/>
									</Col>
								</Row>
							</Col>
						</Row>

						<Tabs defaultActiveKey="shortcode" className="mb-4">
							<Tab
								eventKey="shortcode"
								title={
									<div className="d-inline-flex align-start">
										<span className="dashicons dashicons-shortcode me-1"></span>
										{__(
											'Embed Code',
											'infinite-uploads'
										)}
									</div>
								}
							>
								<Row className="justify-content-center mt-2">
									<Col>
										<Row>
											<Col>
												<p>
													{__(
														'Copy and paste this code into your post, page, or widget to embed the video. If using Gutenberg editor use our block.',
														'infinite-uploads'
													)}
												</p>
											</Col>
										</Row>
										<Row className="mb-1">
											<Col>
												<Form>
													<Form.Check
														inline
														label={__(
															'Autoplay',
															'infinite-uploads'
														)}
														type="checkbox"
														checked={autoPlay}
														onChange={(e) =>
															setAutoPlay(
																e.target.checked
															)
														}
													/>
													<Form.Check
														inline
														label={__(
															'Loop',
															'infinite-uploads'
														)}
														type="checkbox"
														checked={loop}
														onChange={(e) =>
															setLoop(
																e.target.checked
															)
														}
													/>
													<Form.Check
														inline
														label={__(
															'Muted',
															'infinite-uploads'
														)}
														type="checkbox"
														checked={muted}
														onChange={(e) =>
															setMuted(
																e.target.checked
															)
														}
													/>
													<Form.Check
														inline
														label={__(
															'Preload',
															'infinite-uploads'
														)}
														type="checkbox"
														checked={preload}
														onChange={(e) =>
															setPreload(
																e.target.checked
															)
														}
													/>
												</Form>
											</Col>
										</Row>
										<Row>
											<Col>
												<Form.Control
													type="text"
													aria-label="Embed Code"
													readOnly
													value={`[infinite-uploads-vid id="${video.guid}" ${embedParams}]`}
													onClick={(e) => {
														e.target.select();
														document.execCommand(
															'copy'
														);
													}}
												/>
											</Col>
										</Row>
									</Col>
								</Row>
							</Tab>

							<Tab
								eventKey="stats"
								disabled
								title={
									<>
										<span className="dashicons dashicons-chart-area me-1"></span>
										{__('Stats', 'infinite-uploads')}
									</>
								}
							>
								<Row className="justify-content-center">
									<Col>
										<Row>
											<Col>
												<h5>
													{__(
														'Statistics',
														'infinite-uploads'
													)}
												</h5>
												<p>
													{__(
														'View the statistics for this video.',
														'infinite-uploads'
													)}
												</p>
											</Col>
										</Row>
										<Row>
											<Col>Chart here</Col>
										</Row>
									</Col>
								</Row>
							</Tab>

							<Tab
								eventKey="captions"
								disabled
								title={
									<>
										<span className="dashicons dashicons-format-status me-1"></span>
										{__('Captions', 'infinite-uploads')}
									</>
								}
							></Tab>

							<Tab
								eventKey="chapters"
								disabled
								title={
									<>
										<span className="dashicons dashicons-text me-1"></span>
										{__('Chapters', 'infinite-uploads')}
									</>
								}
							></Tab>
						</Tabs>
					</Container>
				</Modal.Body>
			</Modal>
		</>
	);
}
