/**
 * External dependencies
 */
import classnames from 'classnames';
import Colcade from 'colcade';

const {
	addAction,
} = wp.hooks;

/**
 * Prepare Masonry layout with Colcade script.
 */
function maybePrepareMasonryLayout(element) {
	element.className = classnames(
		element.className,
		'cnvs-block-posts-layout-masonry-colcade-ready'
	);

	element.colcadeObj = new Colcade(element, {
		columns: '.cnvs-block-post-grid-col',
		items: '.cnvs-block-post-grid-item'
	});
}

/**
 * Destroy Masonry layout with Colcade script.
 */
function maybeDestroyMasonryLayout(element) {
	if (element.colcadeObj) {
		element.colcadeObj.destroy();
		element.colcadeObj = null;
	}
}

function getMasonryBlockId(props) {
	if ('canvas/posts' !== props.block) {
		return false;
	}

	if ('masonry' !== props.attributes.layout) {
		return false;
	}

	const clientId = props.blockProps.clientId;

	if (!clientId) {
		return false;
	}

	return clientId;
}

addAction('canvas.components.serverSideRender.onChange', 'canvas/posts.masonry.init', function (props) {
	const clientId = getMasonryBlockId(props);

	if (!clientId) {
		return;
	}

	const element = document.querySelector(`[data-block="${clientId}"] .cnvs-block-posts-layout-masonry:not(.cnvs-block-posts-layout-masonry-colcade-ready)`);

	if (element) {
		maybePrepareMasonryLayout(element);
	}
});

addAction('canvas.components.serverSideRender.onBeforeChange', 'canvas-posts.masonry.destroy', function (props) {
	const clientId = getMasonryBlockId(props);

	if (!clientId) {
		return;
	}

	const element = document.querySelector(`[data-block="${clientId}"] .cnvs-block-posts-layout-masonry-colcade-ready`);

	if (element) {
		maybeDestroyMasonryLayout(element);
	}
});
