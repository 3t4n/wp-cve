(function ($) {
  $(window).on("elementor/frontend/init", function () {
    //this is for HTML5 VIDEO player
    elementorFrontend.hooks.addAction("frontend/element_ready/Html5VideoPlayer.default", function (scope, $) {
      //this is for HTML5 vedio player
      const playerElement = $(scope).find("video");
      let controls = $(playerElement).attr("data-settings");
      /*    console.log(controls);*/
      controls = JSON.parse(controls);
      controls = Object.keys(controls).map((index) => {
        if (controls[index] == "yes") {
          return index;
        }
      });
      const player = new Plyr(playerElement, {
        tooltips: { controls: true },
      });
    });

    //this is for HTML5 Audio player
    elementorFrontend.hooks.addAction("frontend/element_ready/Html5AudioPlayer.default", function (scope, $) {
      const playerElement = $(scope).find(".audio_player");

      let controls = $(playerElement).attr("data-settings");
      controls = JSON.parse(controls);
      controls = Object.keys(controls).map((index) => {
        if (controls[index] == "yes") {
          return index;
        }
      });
      const player = new Plyr(playerElement, {
        tooltips: { controls: true },
      });
    });

    //this is for HTML5 youtube player
    elementorFrontend.hooks.addAction("frontend/element_ready/YoutubeVideoPlayer.default", function (scope, $) {
      const playerElement = $(scope).find(".youtube_player");

      let controls = $(playerElement).attr("data-settings");
      controls = JSON.parse(controls);
      controls = Object.keys(controls).map((index) => {
        if (controls[index] == "yes") {
          return index;
        }
      });
      const player = new Plyr(playerElement, {
        tooltips: { controls: true },
      });
    });

    //this is for vimeo player
    elementorFrontend.hooks.addAction("frontend/element_ready/VemioVideoPlayer.default", function (scope, $) {
      const playerElement = $(scope).find(".vimeo_player");

      let controls = $(playerElement).attr("data-settings");
      controls = JSON.parse(controls);
      controls = Object.keys(controls).map((index) => {
        if (controls[index] == "yes") {
          return index;
        }
      });
      const player = new Plyr(playerElement, {
        tooltips: { controls: true },
      });
    });

    // this is a art player
    elementorFrontend.hooks.addAction("frontend/element_ready/artvideoplayer.default", function (scope, $) {
      const playerElement = $(scope).find(".artplayer-app");

      let settings = $(playerElement).attr("data-settings");
      let controls = $(playerElement).data("controls");
      const id = $(playerElement).attr("id");

      settings = JSON.parse(settings);

      const { multiple_quality } = controls;

      Object.keys(controls).map((index) => {
        if (controls[index] == "yes") {
          controls[index] = true;
        } else {
          controls[index] = false;
        }
      });

      //for single video
      let single_video = "";
      if (settings.srrc_type == "liink") {
        single_video = settings.vxideoos_link;
      } else {
        single_video = settings.vxideoos_upload.url;
      }

      //for subtitle
      const subtitle = {
        color: settings.sub_bg,
      };
      if (settings.sxrc_typed === "linkks") {
        subtitle.url = settings.sxubtitle_link;
      } else {
        subtitle.url = settings.sxubtitle_upload.url;
      }

      //for multiple quality
      const video_list = settings.vxideo_list;
      let newVideiList = [];
      if (multiple_quality == "yes") {
        newVideiList = video_list.map((item) => {
          const video = {};
          video.name = item.vxideo_size;
          if (item.sxrc_type == "link") {
            video.url = item.vxideoos_link;
          } else {
            video.url = item.vxideoos_upload.url;
          }

          return video;
        });

        if (newVideiList.length > 0) {
          single_video = newVideiList[0].url;
        }
      }

      const player = new Artplayer({
        container: "#" + id,
        url: `${single_video}`,
        poster: `${settings.bar_img.url}`,
        volume: 0.5,
        isLive: false,
        muted: false,
        autoplay: false,
        pip: true,
        autoSize: true,
        autoMini: true,
        screenshot: true,
        setting: true,
        loop: true,
        flip: true,
        rotate: true,
        playbackRate: true,
        aspectRatio: true,
        fullscreen: true,
        fullscreenWeb: true,
        subtitleOffset: true,
        miniProgressBar: true,
        localVideo: true,
        localSubtitle: true,
        networkMonitor: false,
        mutex: true,
        light: true,
        backdrop: true,
        theme: `${settings.vd_color}`,

        lang: navigator.language.toLowerCase(),
        moreVideoAttr: {
          crossOrigin: "anonymous",
        },
        contextmenu: [
          {
            html: "Custom menu",
            click: function (contextmenu) {
              console.info("You clicked on the custom menu");
              contextmenu.show = false;
            },
          },
        ],

        quality: newVideiList,
        subtitle: {
          url: `${subtitle.url}`,
          style: {
            color: `${subtitle.color}`,
          },
          encoding: "utf-8",
          bilingual: true,
        },

        icons: {
          state: `<img src="${settings.video_album_poster.url}">`,
        },
      });
    });

    elementorFrontend.hooks.addAction("frontend/element_ready/dPlayer.default", function (scope, $) {

      const playerElement = $(scope).find("#dplayer");

      let settings = $(playerElement).attr("data-settings");
      settings = JSON.parse(settings);

      let subtitleUrl = "";
      if (typeof settings.dplayer_upload != "undefined") {
        subtitleUrl = settings.dplayer_upload.url;
      }
      //for multiple quality
      const video_d_list = settings.video_d_list;
      let newVideiList = [];
      if (settings.choose_v_source === "yes") {
        newVideiList = video_d_list.map((item) => {
          const video = {};
          video.name = item.video_d_size;
          video.type = "auto";
          if (item.src_v_type == "link") {
            video.url = item.video_v_link;
          } else {
            video.url = item.video_v_upload.url;
          }

          return video;
        });
      } else {
        if (settings.srrc_type == "liink") {
          single_video = settings.videoos_link;
        } else {
          single_video = settings.videoos_upload.url;
        }
        newVideiList = [
          {
            url: single_video,
            type: "auto",
          },
        ];
      }
	
      const options = {
//    container: document.getElementById("dplayer"),
        container:  playerElement[0],
        theme: settings.player_theme,
        lang: "en",
        screenshot: true,
        hotkey: true,
        preload: "auto",
        volume: 0.7,
        mutex: true,
        video: {
          quality: newVideiList,
          defaultQuality: 0,
        },
        subtitle: {
          url: subtitleUrl,
          type: "webvtt",
          fontSize: settings.p_font,
          bottom: "10%",
          color: settings.sub_d_bg,
        },
      };

      if (settings.custom_banner_d === "true") {
        options.video.thumbnails = settings.banner.url;
        options.video.pic = settings.banner.url;
      }

      if (settings.custom_logo_d === "true") {
        options.logo = settings.d_logo.url;
      }

      if (settings.auto_play === "yes") {
        options.autoplay = true;
      }
      if (settings.video_loop === "yes") {
        options.loop = true;
      }

      const dp = new DPlayer(options);
    });
  });
})(jQuery);
