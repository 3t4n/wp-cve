// External Dependencies
import $ from 'jquery';
import React, { Component } from 'react';
class QuadMenu extends Component {

  static slug = 'et_pb_quadmenu';
  constructor(props) {
    super(props);
    this.state = {
      error: false
    }
  }

  changeTheme(oldTheme) {
    this.$el = $(this.el);
    this.$el.find('nav#quadmenu').removeClass('quadmenu-' + oldTheme).addClass('quadmenu-' + this.props.menu_theme);
  }

  createQuadMenu() {

    this.$el = $(this.el);

    var body = new FormData();
    body.append('action', 'ajax_quadmenu_divi_module');
    body.append('post_id', window.ETBuilderBackend.postId);
    body.append('menu_id', this.props.menu_id);
    body.append('menu_theme', this.props.menu_theme);

    fetch(window.et_fb_options.ajaxurl, {body: body, method: 'POST'})
            .then(res => res.json())
            .then((response) => {
              if (response.success) {
                this.$el.html($(response.data).quadmenu());
              } else {
                this.setState({
                  error: response.data
                });
              }
            }, (error) => {
              this.setState({
                error: error
              });
            });
  }

  componentDidMount() {
    this.createQuadMenu(this.props.menu_id);
  }

  componentDidUpdate(prevProps) {
    if (prevProps.menu_id !== this.props.menu_id) {
      this.createQuadMenu(this.props.menu_id);
    } else if (prevProps.menu_theme !== this.props.menu_theme) {
      this.changeTheme(prevProps.menu_theme);
    }
  }

  render() {

    if (this.state.error) {
      return (<div>{this.state.error}</div>);
    }

    return (<div className="et_pb_row et_pb_fullwidth_menu clearfix" ref={(el) => {
            this.el = el
         }}></div>);

  }

}

export default QuadMenu;