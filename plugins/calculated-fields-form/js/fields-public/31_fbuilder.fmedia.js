	$.fbuilder.controls['fMedia']=function(){};
	$.extend(
		$.fbuilder.controls['fMedia'].prototype,
		$.fbuilder.controls['ffields'].prototype,
		{
			ftype:"fMedia",
            sMediaType:"image", // image, audio, video
            data:{
                image:{
                    sWidth:"",
                    sHeight:"",
                    sSrc:"",
                    sAlt:"",
                    sLink:"",
                    sTarget:"",
                    sFigcaption: ""
                },
                audio:{
                    sWidth:"",
                    sSrc:"",
                    sSrcAlt:"",
                    sControls:1,
                    sLoop:0,
                    sAutoplay:0,
                    sMuted:0,
                    sPreload: "auto",
                    sFallback: "",
                    sFigcaption: "",
					sHideDownload:0
                },
                video:{
                    sWidth:"",
                    sHeight:"",
                    sSrc:"",
                    sSrcAlt:"",
                    sPoster:"",
                    sControls:1,
                    sLoop:0,
                    sAutoplay:0,
                    sMuted:0,
                    sPreload: "auto",
                    sFallback: "",
                    sFigcaption: "",
					sHideDownload:0
                }
            },
            _show_image: function()
                {
                    var d = this.data.image,
                        esc = cff_esc_attr,
                        a = [],
                        l = [],
                        r = '';

                    if(String(d.sWidth).trim()) a.push('width="'+esc(d.sWidth)+'"');
                    if(String(d.sHeight).trim()) a.push('height="'+esc(d.sHeight)+'"');
                    if(String(d.sSrc).trim()) a.push('src="'+esc(d.sSrc)+'"');
                    if(String(d.sAlt).trim()) a.push('alt="'+esc(d.sAlt)+'"');
                    if(String(d.sLink).trim())
                    {
                        l.push('href="'+esc(d.sLink)+'"');
                        if(String(d.sTarget).trim()) l.push('target="'+esc(d.sTarget)+'"');
                        r = '<a '+l.join(' ')+' ><img '+a.join(' ')+' /></a>';
                    }
                    else
                    {
                        r = '<img '+a.join(' ')+' />';
                    }

                    return r;
                },
			_show_audio_video: function(d, isV)
                {
                    var esc = cff_esc_attr,
                        a = [],
						s = [],
                        t = (isV) ? 'video' : 'audio' ;

                    if(String(d.sWidth).trim()) s.push('width:'+esc(d.sWidth)+';');
                    if(isV && String(d.sHeight).trim()) s.push('height:'+esc(d.sHeight)+';');
                    if(isV && String(d.sPoster).trim()) a.push('poster="'+esc(d.sPoster)+'"');
                    if(String(d.sSrc).trim()) a.push('src="'+esc(d.sSrc)+'"');
                    if(d.sAutoplay) a.push('autoplay');
                    if(d.sControls) a.push('controls');
                    if(d.sLoop) a.push('loop');
                    if(d.sMuted) a.push('muted');
                    if(d.sHideDownload) a.push('controlsList="nodownload"');
                    a.push('preload="'+esc(d.sPreload)+'"');

                    return '<'+t+' '+a.join(' ')+' style="'+s.join(' ')+'">'+((String(d.sSrcAlt).trim()) ? '<source src="'+esc(d.sSrcAlt)+'" />' : '')+'<p>'+d.sFallback+'</p></'+t+'>';
                },
            _show_audio: function()
                {
                    return this._show_audio_video(this.data.audio, false);
                },
            _show_video: function()
                {
                    return this._show_audio_video(this.data.video, true);
                },
            show:function()
				{
						return '<div class="fields '+cff_esc_attr(this.csslayout)+' '+this.name+' cff-media-field" id="field'+this.form_identifier+'-'+this.index+'"><div class="clearer"><div class="field" id="'+this.name+'">'+this['_show_'+this.sMediaType]()+'</div></div><span class="uh">'+this.data[this.sMediaType].sFigcaption+'</span><div class="clearer"></div></div>';
				}
		}
	);