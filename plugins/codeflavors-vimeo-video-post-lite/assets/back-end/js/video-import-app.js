'use strict';
var CvmVideos = CvmVideos || {};
(function ($, $$) {
    $$.AppRouter = Backbone.Router.extend({
        routes: {
            "": "home",
            "load/:feed/:query/:order/:album_user/:search_results": 'load'
        }
    });
    $(document).ready(function () {
        $$.videosCollection = new $$.VideosCollection;
        $$.appView = new $$.AppView;
        $$.appRouter = new $$.AppRouter;
        $$.appRouter.on('route:home', function () {
        });
        $$.appRouter.on('route:load', function (feed, query, order, album_user, search_results) {
            $('#cvm_feed').val(feed).trigger('change');
            $('#cvm_query').val(decodeURIComponent(query));
            $('#cvm_order').val(order);
            $('#cvm_album_user').val(album_user == '0' ? '' : album_user);
            $('#cvm_search_results').val(search_results == '0' ? '' : search_results);
            $$.appView.loadMore();
        });
        Backbone.history.start();
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.AdminAjaxSyncableMixin = {
        url: ajaxurl,
        action: 'cvm_get_videos',
        sync: function (method, object, options) {
            if (typeof options.data === 'undefined') {
                options.data = {};
            }
            options.data.nonce = $$.nonce;
            options.data.action_type = method;
            if (undefined === options.data.action && undefined !== this.action) {
                options.data.action = this.action;
            }
            if ('read' === method) {
                return Backbone.sync(method, object, options);
            }
            var json = this.toJSON();
            var formattedJSON = {};
            if (json instanceof Array) {
                formattedJSON.models = json;
            }
            else {
                formattedJSON.model = json;
            }
            _.extend(options.data, formattedJSON);
            options.emulateJSON = true;
            return Backbone.sync.call(this, 'create', object, options);
        }
    };
    $$.BaseModel = Backbone.Model.extend(_.defaults({}, $$.AdminAjaxSyncableMixin));
    $$.BaseCollection = Backbone.Collection.extend(_.defaults({}, $$.AdminAjaxSyncableMixin));
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.getFormData = function ($form) {
        var o = {};
        var a = $form.serializeArray();
        var n = {};
        var nameArray = function (s) {
            var result = [];
            var t = s.replace(/\[(.*?)\]/g, function (a) {
                result.push(a.replace('[', '').replace(']', ''));
                return '';
            });
            result.unshift(t);
            return result;
        };
        $.each(a, function () {
            var arr = nameArray(this.name);
            if (arr.length > 1) {
                n[this.name] = arr;
            }
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            }
            else {
                o[this.name] = this.value || '';
            }
        });
        var x = function (obj, keys, value) {
            if (!obj)
                return;
            var i, t;
            var l = keys.length - 1;
            for (i = 0; i < keys.length; i++) {
                if (!t) {
                    t = obj[keys[i]] = {};
                }
                else {
                    t[keys[i]] = i === l ? value : {};
                    t = t[keys[i]];
                }
            }
        };
        var result = {};
        $.each(n, function (index, namesArray) {
            var a = {};
            x(a, namesArray, o[index]);
            delete o[index];
            if (!result[namesArray[0]]) {
                result = $.extend({}, result, a);
            }
            else {
                result[namesArray[0]][namesArray[1]] = a[namesArray[0]][namesArray[1]];
            }
        });
        o = $.extend({}, o, result);
        return o;
    };
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.VideosCollection = $$.BaseCollection.extend({
        action: 'cvm_get_videos',
        info: {
            page: 1,
            results: 0,
            end: false
        },
        runningRequest: false,
        initialize: function () {
            this.model = $$.VideoModel;
            this.on('error', this.processError, this);
        },
        parse: function (response) {
            this.info = {
                page: (response.data.page + 1),
                results: response.data.results,
                end: response.data.end
            };
            if (response.data.end) {
                this.trigger('finished', this);
            }
            this.runningRequest = false;
            return response.data.videos;
        },
        processError: function () {
            this.info = {
                page: 1,
                results: 0,
                end: false
            };
            this.runningRequest = false;
        },
        fetch: function (options) {
            this.runningRequest = true;
            this.trigger('fetch', this, options);
            options.data.page = this.info.page;
            return Backbone.Collection.prototype.fetch.call(this, options);
        },
        reset: function (models, options) {
            if (!models) {
                this.info.page = 1;
                this.info.end = false;
            }
            Backbone.Collection.prototype.reset.call(this, models, options);
        },
        pageInfo: function () {
            return this.info;
        },
        getQueued: function () {
            return this.where({ status: 'queued' });
        },
        getImported: function () {
            return this.where({ status: 'done' });
        },
        getFilter: function (where) {
            if (!where) {
                return {};
            }
            var w;
            switch (where) {
                case 'queued':
                    w = { status: 'queued' };
                    break;
                case 'imported':
                    w = { status: 'done' };
                    break;
                default:
                    throw new Error("Unknown filtering method '" + where + "'.");
            }
            return w;
        },
        isRequestRunning: function () {
            return this.runningRequest;
        }
    });
})(jQuery, CvmVideos);
(function ($$) {
    $$.FilterModel = Backbone.Model.extend({
        defaults: {
            items: 0,
            queued: 0,
            imported: 0,
            current: 'all',
            hide: true,
            where: {}
        },
        initialize: function () {
            this.listenTo($$.videosCollection, 'all', this.update);
        },
        update: function (a, c, o) {
            switch (a) {
                case 'fetch':
                    this.set({
                        hide: true
                    });
                    break;
                case 'update':
                case 'finished':
                    var pageInfo = $$.videosCollection.pageInfo();
                    if (pageInfo.results == 0) {
                        this.set({
                            hide: true
                        });
                    }
                    else {
                        this.set({
                            items: $$.videosCollection.length,
                            queued: $$.videosCollection.getQueued().length,
                            imported: $$.videosCollection.getImported().length,
                            hide: false
                        });
                    }
                    break;
                case 'change:status':
                    this.set({
                        items: $$.videosCollection.length,
                        queued: $$.videosCollection.getQueued().length,
                        imported: $$.videosCollection.getImported().length,
                        hide: false
                    });
                    break;
            }
        }
    });
})(CvmVideos);
(function ($$) {
    var l10n = $$.strings;
    $$.LoadMoreModel = Backbone.Model.extend({
        defaults: {
            message: '',
            hide: true,
            css: 'ready'
        },
        initialize: function () {
            this.listenTo($$.videosCollection, 'all', this.update);
        },
        setHide: function (v) {
            this.set('hide', v);
        },
        update: function (a, c, o) {
            switch (a) {
                case 'fetch':
                    this.set({
                        message: l10n.loading,
                        hide: !(c.length > 0),
                        css: 'loading'
                    });
                    break;
                case 'error':
                    var message = '';
                    if (o.responseJSON) {
                    }
                    else {
                        message = o.responseText;
                    }
                    this.set({
                        message: message,
                        css: 'vimeo-error',
                        hide: false
                    });
                    break;
                case 'update':
                case 'finished':
                    var pageInfo = $$.videosCollection.pageInfo();
                    if (pageInfo.results == 0) {
                        this.set({
                            message: l10n.no_results,
                            css: 'vimeo-error',
                            hide: false
                        });
                    }
                    else {
                        this.set({
                            message: (pageInfo.end ? l10n.finished : l10n.load_more),
                            css: (pageInfo.end ? 'finished' : 'ready'),
                            hide: false
                        });
                    }
                    break;
            }
        }
    });
})(CvmVideos);
(function ($$) {
    $$.NoResultsModel = Backbone.Model.extend({
        defaults: {
            screen: 'all',
            hide: true
        }
    });
})(CvmVideos);
(function ($$) {
    var l10n = $$.strings;
    $$.QueryMessageModel = Backbone.Model.extend({
        defaults: {
            css: '',
            message: ''
        },
        initialize: function () {
            this.set({
                css: 'idle',
                message: l10n.info_search
            });
            this.listenTo($$.videosCollection, 'all', this.update);
        },
        update: function (a, c, o) {
            switch (a) {
                case 'fetch':
                    this.set({
                        css: 'loading',
                        message: l10n.loading
                    });
                    break;
                case 'error':
                    var message = '';
                    if (o.responseJSON) {
                    }
                    else {
                        message = o.responseText;
                    }
                    this.set({
                        message: message,
                        css: 'vimeo-error'
                    });
                    break;
                case 'update':
                case 'finished':
                    var pageInfo = c.pageInfo();
                    if (pageInfo.results == 0) {
                        this.set({
                            message: l10n.no_results,
                            css: 'vimeo-error'
                        });
                    }
                    else {
                        var map = {
                            '%1$d': c.length,
                            '%2$d': pageInfo.results
                        };
                        var m = l10n.query_results.replace(/\%1\$d|\%2\$d/g, function (a, b, c) {
                            return map[a];
                        });
                        if (pageInfo.end) {
                            m += ' ' + l10n.finished;
                        }
                        this.set({
                            message: m,
                            css: 'ready'
                        });
                    }
                    break;
                case 'reset':
                    this.set({
                        css: 'idle',
                        message: l10n.info_search
                    });
                    break;
            }
        }
    });
})(CvmVideos);
(function ($$) {
    $$.SearchFormModel = Backbone.Model.extend({
        defaults: {
            changed: false,
            data: false,
            emptyQuery: false
        }
    });
})(CvmVideos);
(function ($, $$) {
    var l10n = $$.strings;
    $$.VideoModel = $$.BaseModel.extend({
        idAttribute: 'video_id',
        action: 'cvm_import_video',
        defaults: {
            post_id: false,
            edit_link: false,
            permalink: false,
            video_id: '',
            title: '',
            description: '',
            image: '',
            url: '',
            status: 'none',
            error: ''
        },
        initialize: function () {
            this.on('sync', this.processResponse, this);
            this.on('error', this.processError, this);
        },
        save: function (key, val, options) {
            this.trigger('saving', this, key, val, options);
            return Backbone.Model.prototype.save.call(this, key, val, options);
        },
        processResponse: function (m, r, o) {
            var params = {
                status: 'done'
            };
            if (r.success && 1 == r.data.imported) {
                params.post_id = r.data.ids[0];
                params.edit_link = r.data.links[0].edit_link;
                params.permalink = r.data.links[0].permalink;
            }
            this.set(params);
        },
        processError: function (m, r, o) {
            this.set({
                status: 'error',
                error: r.responseJSON.data.error[0] || l10n.unknown_error
            });
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    var l10n = $$.strings;
    $$.AppView = Backbone.View.extend({
        el: '#cvm-video-import-grid',
        events: {
            'click #cvm-load-more-videos': 'loadMore'
        },
        initialize: function () {
            this.views = {
                messages: new $$.QueryMessageView({ model: new $$.QueryMessageModel }),
                filter: new $$.FilterView({ model: new $$.FilterModel }),
                loadMore: new $$.LoadMoreView({ model: new $$.LoadMoreModel }),
                searchForm: new $$.SearchFormView({ model: new $$.SearchFormModel }),
                noResults: new $$.NoResultsView({ model: new $$.NoResultsModel }),
                form: new $$.FormView
            };
            this.videoViews = {};
            this.container = $('#cvm-grid-container');
            this.container.before(this.views.filter.render().el);
            this.container.append(this.views.noResults.render().el);
            this.container.append(this.views.loadMore.render().el);
            this.listenTo($$.videosCollection, 'add', this.addVideo);
            this.listenTo($$.videosCollection, 'all', this.render);
            this.listenTo(this.views.filter.model, 'change:current', this.changeView);
            $$.videosCollection.on('reset', function (col, opts) {
                _.each(opts.previousModels, function (model) {
                    model.trigger('remove');
                });
            });
        },
        render: function (a, c, o) {
            switch (a) {
                case 'change:status':
                    if (this.views.filter.model.get('current') === 'queued') {
                        this.showVideoViews($$.videosCollection.getFilter('queued'), 'queued');
                    }
                    break;
            }
        },
        changeView: function (model) {
            this.views.loadMore.model.setHide(!('all' === model.get('current')));
            this.showVideoViews(model.get('where'), model.get('current'));
        },
        showVideoViews: function (params, screen) {
            this.removeVideoViews();
            var m = params ? $$.videosCollection.where(params) : $$.videosCollection.models;
            this.views.noResults.model.set({
                screen: screen,
                hide: (m.length > 0)
            });
            _.each(m, function (model) {
                var v = model.get('video_id');
                this.views.loadMore.$el.before(this.videoViews[v].render().el);
            }, this);
        },
        addVideo: function (video) {
            var view = new $$.VideoView({ model: video });
            this.views.loadMore.$el.before(view.render().el);
            this.videoViews[video.get('video_id')] = view;
        },
        removeVideoViews: function () {
            $.each(this.videoViews, function (index, view) {
                view.detachView();
            });
        },
        loadMore: function (e) {
            this.views.searchForm.$el.trigger('submit');
            return false;
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.FilterView = Backbone.View.extend({
        tagName: 'div',
        className: 'filter-views',
        events: {
            'click #filter-show-all': function (e) {
                this.model.set({
                    where: {},
                    current: 'all'
                });
                return false;
            },
            'click #filter-show-importing': function (e) {
                this.model.set({
                    where: $$.videosCollection.getFilter('queued'),
                    current: 'queued'
                });
                return false;
            },
            'click #filter-show-imported': function (e) {
                this.model.set({
                    where: $$.videosCollection.getFilter('imported'),
                    current: 'imported'
                });
                return false;
            }
        },
        initialize: function () {
            this.template = _.template($('#filter-view').html());
            this.listenTo(this.model, 'change', this.render);
            this.listenTo($$.videosCollection, 'reset', this.reset);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
        reset: function () {
            this.$el.html('');
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.FormView = Backbone.View.extend({
        el: '#cvm-grid-submit-form',
        events: {
            'submit': 'importVideos'
        },
        initialize: function () {
        },
        render: function () {
        },
        importVideos: function (e) {
            var data = this.formData();
            _.each($$.videosCollection.getQueued(), function (model) {
                model.save(data);
            });
            return false;
        },
        formData: function () {
            return { import: $$.getFormData(this.$el) };
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.LoadMoreView = Backbone.View.extend({
        tagName: 'div',
        className: 'cvm_video load-videos col-xs-12  col-sm-12 col-md-6 col-lg-3',
        initialize: function () {
            this.template = _.template($('#load-more-template').html());
            this.listenTo(this.model, 'change', this.render);
            this.listenTo($$.videosCollection, 'reset', this.reset);
        },
        render: function (params) {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get('hide')) {
                this.$el.hide();
            }
            else {
                this.$el.show();
            }
            return this;
        },
        reset: function () {
            this.$el.html('');
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.NoResultsView = Backbone.View.extend({
        tagName: 'div',
        className: 'no-results hide-if-js',
        initialize: function () {
            this.template = _.template($('#no-results-view').html());
            this.listenTo(this.model, 'change', this.render);
            this.listenTo($$.videosCollection, 'change:status', this.checkCollection);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            if (this.model.get('hide')) {
                this.$el.hide();
            }
            else {
                this.$el.show();
            }
            return this;
        },
        checkCollection: function () {
            switch (this.model.get('screen')) {
                case 'queued':
                    if ($$.videosCollection.getQueued().length == 0) {
                        this.model.set('hide', false);
                    }
                    break;
                case 'imported':
                    if ($$.videosCollection.getImported().length == 0) {
                        this.model.set('hide', false);
                    }
                    break;
            }
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.QueryMessageView = Backbone.View.extend({
        el: '#cvm-query-messages',
        initialize: function () {
            this.template = _.template('<span<% if(css) { %> class="<%= css %>"<% } %>><%= message %></span>');
            this.listenTo(this.model, 'change', this.render);
            this.render();
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.SearchFormView = Backbone.View.extend({
        el: '#cvm_load_feed_form',
        events: {
            'submit': 'doSubmit',
            'change #cvm_feed': 'formChanged'
        },
        initialize: function () {
            this.formData = false;
            this.requestRunning = false;
        },
        doSubmit: function (e) {
            var data = $(e.target).serializeArray();
            var formData = _.object(_.pluck(data, 'name'), _.pluck(data, 'value'));
            this.model.set({
                changed: this.hasChanged(),
                data: formData,
                emptyQuery: this.isEmptyQuery()
            });
            this.formData = this.$el.serialize();
            this.trigger('formSubmit', this.model.get('emptyQuery'));
            this.doSearch();
            return false;
        },
        doSearch: function () {
            if (this.model.get('emptyQuery') || $$.videosCollection.isRequestRunning()) {
                return false;
            }
            if (this.model.get('changed')) {
                $$.videosCollection.reset();
                this.route();
            }
            var pageInfo = $$.videosCollection.pageInfo();
            if (pageInfo.end) {
                return false;
            }
            var formData = this.model.get('data');
            $$.videosCollection.fetch({
                data: formData,
                type: 'POST',
                remove: false,
                success: function (c, r, o) {
                },
                error: function (c, r, o) {
                }
            });
            return false;
        },
        formChanged: function () {
            $('#cvm_query').val('');
            $('#cvm_album_user').val('');
            $('#cvm_search_results').val('');
            $$.videosCollection.reset();
        },
        hasChanged: function () {
            return !(this.$el.serialize() === this.formData);
        },
        isEmptyQuery: function () {
            return ('' === $('#cvm_query').val());
        },
        route: function () {
            var q = encodeURIComponent($('#cvm_query').val()), t = encodeURIComponent($('#cvm_feed').val()), a = encodeURIComponent($('#cvm_album_user').val() || 0), o = encodeURIComponent($('#cvm_order').val()), sr = encodeURIComponent($('#cvm_search_results').val()) || 0;
            $$.appRouter.navigate('load/' + t + '/' + q + '/' + o + '/' + a + '/' + sr);
        }
    });
})(jQuery, CvmVideos);
(function ($, $$) {
    $$.VideoView = Backbone.View.extend({
        tagName: 'div',
        className: 'col-xs-12  col-sm-12 col-md-6 col-lg-3 grid-element',
        events: {
            'click .button.import': function (e) {
                var s = 'none' === this.model.get('status') ?
                    'queued' : 'none';
                this.model.set({ status: s });
                return false;
            },
            'click .single-import': function (e) {
                this.model.save($$.appView.views.form.formData());
                return false;
            }
        },
        initialize: function () {
            this.element = false;
            this.template = _.template($('#video-template').html());
            this.embedTemplate = _.template($('#embed-template').html());
            this.listenTo(this.model, 'remove', this.removeView);
            this.listenTo(this.model, 'change', this.addToImportQueue);
            this.listenTo(this.model, 'saving', this.onSave);
            this.render();
        },
        render: function () {
            if (this.element) {
                if (!this.model.hasChanged()) {
                    return this;
                }
            }
            var json = this.model.toJSON();
            json.no_image = $$.assets.no_image;
            this.$el.html(this.template(json));
            return this;
        },
        addToImportQueue: function () {
            this.render();
        },
        removeView: function () {
            this.unbind();
            this.remove();
        },
        onSave: function (model) {
            model.set({ status: 'saving' });
        },
        detachView: function () {
            this.element = this.$el.detach();
        }
    });
})(jQuery, CvmVideos);
