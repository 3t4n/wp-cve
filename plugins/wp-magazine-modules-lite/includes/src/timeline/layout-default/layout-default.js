/**
 * Timeline block default - editor preview
 * 
 */
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { decodeEntities } = wp.htmlEntities;
const { withSelect } = wp.data

class TimelineDefault extends Component {
    constructor( props ) {
        super(...arguments)
    }

    render() {
        const { contentType, timelineRepeater, thumbOption, dateOption, titleOption, contentOption, permalinkTarget } = this.props.attributes
        const { posts } = this.props
        let timelineContent;
        const hasPosts = Array.isArray(posts) && posts.length;

        switch( contentType ) {
            case 'custom': timelineContent = timelineRepeater.map( ( timeline, index ) => {
                                return (
                                    <div class="cvmm-timeline-single-item">
                                        { thumbOption &&
                                            <div class="cvmm-timeline-count">
                                                { timeline.timeline_image &&
                                                    <figure class="cvmm-post-thumb">
                                                        <img src={ timeline.timeline_image } />
                                                    </figure>
                                                }
                                            </div>
                                        }
                                        <div class="cvmm-timeline-allcontent-wrap">
                                            { dateOption &&
                                                <div class="cvmm-timeline-date">
                                                    { moment( timeline.timeline_date ).local().format('MMMM DD, Y') }
                                                </div>
                                            }
                                            <div class="cvmm-timeline-content-wrap">
                                                { titleOption &&
                                                    <div class="cvmm-timeline-title">
                                                        { timeline.timeline_title }
                                                    </div>
                                                }
                                                { contentOption &&
                                                    <div class="cvmm-timeline-desc">
                                                        { timeline.timeline_desc }
                                                    </div>
                                                }
                                            </div>
                                        </div>
                                    </div>
                                )
                            })
                            break;
            default: if( !hasPosts ) {
                        timelineContent = <div class="cvmm-timeline-single-item">{ escapeHTML( __( 'No posts found', 'wp-magazine-modules-lite' ) ) }</div>
                    }
                    if( hasPosts ) {
                        timelineContent = posts.map( ( post, index ) => {
                            return (
                                <div class="cvmm-timeline-single-item">
                                    { thumbOption &&
                                        <div class="cvmm-timeline-count">
                                            <figure class="cvmm-post-thumb">
                                                <img src={ post.wpmagazine_modules_lite_featured_media_urls.thumbnail[0] } />
                                            </figure>
                                        </div>
                                    }
                                    <div class="cvmm-timeline-allcontent-wrap">
                                        { dateOption &&
                                            <div class="cvmm-timeline-date">
                                                { moment( post.date_gmt ).local().format('MMMM DD, Y') }
                                            </div>
                                        }
                                        <div class="cvmm-timeline-content-wrap">
                                            { titleOption &&
                                                <div class="cvmm-timeline-title">
                                                    <a href={ post.link } target={ `${permalinkTarget}` } >{ post.title.rendered }</a>
                                                </div>
                                            }
                                            { contentOption &&
                                                <div class="cvmm-timeline-desc" dangerouslySetInnerHTML={{ __html: post.excerpt.rendered.trim() }} />
                                            }
                                        </div>
                                    </div>
                                </div>
                            )
                        })
                    }
                    break;
        }

        return(
            <div class="cvmm-timeline-wrapper">
                { timelineContent }
            </div>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { postCount, postCategory } = props.attributes;
    const { getEntityRecords } = select( 'core' );

    const PostsQuery = {
        status: 'publish',
        per_page: postCount,
    };
    
    PostsQuery['categories'] = postCategory;
    
    return {
        posts: getEntityRecords( 'postType', 'post', PostsQuery )
    };
} )( TimelineDefault );