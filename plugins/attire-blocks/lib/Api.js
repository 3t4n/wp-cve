import Util from "./util";

class Api {
    constructor(attributes) {
        this.attributes = attributes;
    }

    getPosts() {
        let data = {
            postType: this.attributes.postType,
            postsPerRow: this.attributes.postsPerRow || this.attributes.postsPerSlide,
            rows: this.attributes.rows || this.attributes.numberSlide,
            sortBy: this.attributes.sortBy,
            posts: Util.getValuesFromMultiSelectObject(this.attributes.posts),
            excludePosts: Util.getValuesFromMultiSelectObject(this.attributes.excludePosts),
            authors: Util.getValuesFromMultiSelectObject(this.attributes.authors),
            excludeAuthors: Util.getValuesFromMultiSelectObject(this.attributes.excludeAuthors),
            tags: Util.getValuesFromMultiSelectObject(this.attributes.tags),
            excludeTags: Util.getValuesFromMultiSelectObject(this.attributes.excludeTags),
            categories: Util.getValuesFromMultiSelectObject(this.attributes.categories),
            excludeCategories: Util.getValuesFromMultiSelectObject(this.attributes.excludeCategories),
            thumbnailHeight: this.attributes.thumbnailHeight,
            thumbnailWidth: this.attributes.thumbnailWidth,
            excerptLength: this.attributes.excerptLength,
        }
        data = Util.serialize(data);
        return wp.apiFetch({
            method: 'GET',
            path: '/atbs/get_filtered_posts?' + data
        });

    }

    search(term, ofWhat) {
        let postsEp = '';
        const endpoints = {
            categories: this.attributes.postType === 'product' ? '/wc/v3/products/categories?search=' + term : '/wp/v2/categories?search=' + term,
            tags: '/wp/v2/tags?search=' + term,
            authors: '/wp/v2/users?search=' + term,
            posts: {
                post: '/wp/v2/posts?search=' + term,
                product: '/wp/v2/product?search=' + term,
                page: '/wp/v2/pages?search=' + term
            }
        }
        if (endpoints[ofWhat][this.attributes['postType']]) {
            postsEp = endpoints[ofWhat][this.attributes['postType']];
        } else {
            postsEp = `/atbs/search_custom_post?type=${this.attributes.postType}&term=${term}`
        }
        return wp.apiFetch({
            method: 'GET',
            path: ofWhat !== 'posts' ? endpoints[ofWhat] : postsEp
        })
    }
}

export default Api;