/*
 * jQuery.ball Plugin 0.6.2 for jQuery(gt Ver 1.3.2)
 * Author: Gavriel Fleischer
 * Homepage: http://neswork.com/jquery/ball/
 *
 * based on StringBall ver2.1 by Coichiro Aso (C) Codesign.verse 2009　http://codesign.verse.jp/
 * Licensed under the MIT license:http://www.opensource.org/licenses/mit-license.php/
 */

(function($){
/*
	$.fn.slide=function(selector,options){
		var slidet=false;
		var slidee=false;
		var slidei=false;
		function show(){
			slidee[slidei].find("a").click();
		};
		this.each(function(i,target){
			var target=$(target);
			var elem;
			if("undefined"==typeof selector){elem=target.children();}
			else{elem=$(selector, target);}
			if(false===slidet){
				slidet=target;
				slidee=elem;
				slidei=0;
				show();
			}
		});
		return this;
	};
*/
	$.fn.ball=function(selector,options){
		var defaults={
			speed:25,
			framerate:25,
			scale:1.0
		};
		var options=$.extend(defaults,options);

//		var scrz=options.scrz;
//		var radi=options.radi;
//		var camd=options.camd;
		var speed=options.speed;
		var frn=Math.floor(1000/options.framerate);

		this.each(function(i,target){
//			var xs=[];
//			var ys=[];
//			var zs=[];
			var zi=[];
			var img=[];
			var tw;
			var th;
			var maxw=0;
			var maxh=0;
			var radi;
			var scrz;

			var ac=0;
			var as=0;
			var roteang=0;
			var looptimer=0;
			var slowdownani=0;

			var target=$(target);
			var elem;
			if("undefined"==typeof selector){elem=target.children();}
			else{elem=$(selector, target);}
			var itxpos=target.offset().left;
			var itypos=target.offset().top;

			function resizet(){
				tw=target.width()>>1;
				th=target.height()>>1;
				scrz=Math.min(tw,th);
			}

			function calcpos(){
				var anga=0;
				var angb=0;
				for(var i=0;i<elem.length;i++){
					anga=Math.acos((2 * (i + 1) - 1) / elem.length - 1);
					angb=Math.sqrt(elem.length * Math.PI) * anga;
					var e=elem[i];
					var je=$(e);
					img[i]=$.map($("img", je), function(el){
						var el=$(el);
						return {obj:el,w:el.width(),h:el.height()};
					});
					var w=je.width();
					var h=je.height();
					je.css("position", "absolute");
					if(maxw<w){
						maxw=w;
					}
					if(maxh<h){
						maxh=h;
					}
/*
					xs[i]=radi*Math.cos(angb)*Math.sin(anga);
					ys[i]=radi*Math.cos(anga);
					zs[i]=radi*Math.sin(angb)*Math.sin(anga);
					zi[i]=i;
*/
					e.jqball={};
//					var eb=elem[i].ball;
					e.jqball.x=radi*Math.cos(angb)*Math.sin(anga);
					e.jqball.z=radi*Math.cos(anga);
					e.jqball.y=radi*Math.sin(angb)*Math.sin(anga);
					zi[i]=i;
				}
			}

			function movecam(){
				var mr=Math.min(Math.max(maxw/tw,maxh/th),1);
				var d=Math.sqrt(1-mr*mr);
				//var a=-1+d;
				var a=-1-d;
				camd=radi-a*radi;
			}

			function init(){
				resizet();
				radi=scrz;
				calcpos();
				movecam();
				rotate();
			}

			function resizer(newr){
				if (newr>0){
					var d=newr/radi;
					for(var i=0;i<elem.length;i++){
/*
						xs[i]=xs[i]*d;
						ys[i]=ys[i]*d;
						zs[i]=zs[i]*d;
*/
						var e=elem[i];
						e.jqball.x*=d;
						e.jqball.y*=d;
						e.jqball.z*=d;
					}
					radi=newr;
				}
			}

			function resize(){
				resizet();
				resizer(scrz);
				movecam();
				rotate();
			}

			function zsort(a,b){
				return elem[a].jqball.z-elem[b].jqball.z;
//				return zs[a]-zs[b];
			}

			function rotate(){
				var rotec=Math.cos(roteang);
				var rotes=Math.sin(roteang);
//$("#console").html("roteang="+roteang+",<br/>rotec="+rotec+",<br/>rotes="+rotes);
				zi.sort(zsort);
				for(var z=0;z<elem.length;z++){
					var i=zi[z];
					/*
					var xpos=elem[i].xpos*(Math.pow(ac,2)+(1-Math.pow(ac,2))*rotec)	+elem[i].ypos*(ac*as*(1-rotec))-elem[i].zpos*(as*rotes);
					var ypos=elem[i].xpos*(ac*as*(1-rotec))+elem[i].ypos*(Math.pow(as,2)+(1-Math.pow(as,2))*rotec)+elem[i].zpos*(ac*rotes);
					var zpos=elem[i].xpos*as*rotes-elem[i].ypos*ac*rotes+elem[i].zpos*rotec;
					*/
					var acas=ac*as;
/*
					var xpos=xs[i]*(ac*ac*(1-rotec)+rotec)+ys[i]*(acas*(1-rotec))-zs[i]*(as*rotes);
					var ypos=xs[i]*(acas*(1-rotec))+ys[i]*(as*as*(1-rotec)+rotec)+zs[i]*(ac*rotes);
					var zpos=xs[i]*as*rotes-ys[i]*ac*rotes+zs[i]*rotec;
					xs[i]=xpos;
					ys[i]=ypos;
					zs[i]=zpos;
*/
					var e=elem[i];
					var xpos=e.jqball.x*(ac*ac*(1-rotec)+rotec)+e.jqball.y*(acas*(1-rotec))-e.jqball.z*(as*rotes);
					var ypos=e.jqball.x*(acas*(1-rotec))+e.jqball.y*(as*as*(1-rotec)+rotec)+e.jqball.z*(ac*rotes);
					var zpos=e.jqball.x*as*rotes-e.jqball.y*ac*rotes+e.jqball.z*rotec;
					e.jqball.x=xpos;
					e.jqball.y=ypos;
					e.jqball.z=zpos;

					var scale=options.scale*(camd-scrz)/(camd-zpos);

					var myscale=scale*100 | 0 ;
					$.each(img[i], function(ndx,img){
						img.obj.width(img.w*scale).height(img.h*scale);
					});

					e.style.fontSize=myscale+"%";
					var w=(e.style.pixelWidth | e.offsetWidth)>>1;
					var h=(e.style.pixelHeight | e.offsetHeight)>>1;
					/*
					var e=$(elem[i]);
					e.css("font-size", myscale+"%");
				// it seems this doesn't work:
					var w=e.width();
					var h=e.height();
					*/
					var myx=tw+xpos*scale-w | 0;
					var myy=th+ypos*scale-h | 0;

					e.style.zIndex=z;
					e.style.left=myx+"px";
					e.style.top=myy+"px";
					e.style.opacity=scale;
					if(e.filters){e.style.filter='alpha(opacity='+myscale+')';}//IE
					/*
					e.css({
						"z-index":z,
						"left":myx+"px",
						"top":myy+"px",
						"opacity":scale,
						"filter":"alpha(opacity="+myscale+")"
					});
					*/
				}
				return false;
			}

			function stop(){
				clearInterval(looptimer);
				looptimer=0;
				slowdownani=0;
			}

			function slowdownsin(){
				if (0===slowdownani){
					stop();
					slowdownani={m:roteang,a:0.99};
					looptimer=setTimeout(slowdownsin,frn);
				}else if(slowdownani.a>0.20){
					slowdownani.a=Math.sin(slowdownani.a);
					roteang=slowdownani.m*slowdownani.a;
					rotate();
					looptimer=setTimeout(slowdownsin,frn);
				}else{
					stop();
				}
			}

			function slowdownlin(){
				if(0===slowdownani){
					stop();
					slowdownani={m:roteang,a:1.0};
					looptimer=setTimeout(slowdownlin,frn);
				}else if(slowdownani.a>0){
					slowdownani.a-=0.05;
					roteang=slowdownani.m*slowdownani.a;
					rotate();
					looptimer=setTimeout(slowdownlin,frn);
				}else{
					stop();
				}
			}

			target.hover(
				function(){
					slowdownani=0;
					clearInterval(looptimer);
					looptimer=setInterval(rotate,frn);
				},
				function(){
					slowdownlin();
				}
			);

			target.mousemove(function(e){
				var accx = (e.pageX-itxpos-tw)/(tw<<1);
				var accy = (e.pageY-itypos-th)/(th<<1);
				var axang=Math.PI/2+Math.atan2(accy,accx);

				roteang=(Math.max(Math.abs(accx),Math.abs(accy)))/100*speed;
				ac=Math.cos(axang);
				as=Math.sin(axang);
			});

			target.resize(function(e){
				resize();
			});

			init();

			function turnTo(i){
				alert(i);
				$("#console").html(i+".(x:"+xs[i]+",<br/>y:"+ys[i]+",<br/>z:"+zs[i]+")");
			};
		});
		if("function" == typeof options.onReady){
			var jqball = this;
			setTimeout(function(){options.onReady(jqball)},0);
		}
		return this;
	};
//	$.ball=function(){
//	};
	$.fn.turnTo=function(){
		alert("turnTo:"+this/*+":"+this.ball*/+":"+this.jqball.x);
/*
		this.each(function(i,target){
			var target=$(target);
		});
*/
//		alert(i);
//$("#console").html(i+".(x:"+xs[i]+",<br/>y:"+ys[i]+",<br/>z:"+zs[i]+")");
		if(this.jqball.x){$("#console").html("(x:"+this.jqball.x+",<br/>y:"+this.jqball.y+",<br/>z:"+this.jqball.z+")")}
		return this;
	};
})(jQuery);
