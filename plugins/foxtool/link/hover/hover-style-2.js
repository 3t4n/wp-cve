function snowIt(flakes = 200) {
	const randInt = (min, max) => {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	};
	let hh = window.innerHeight;
	let ww = window.innerWidth;
	console.log(hh, ww);
	let animatedEllipses = "";
	if (!flakes || Number.isNaN(flakes * 1)){
		flakes = 200;
	}
	for (let i = 0; i < flakes; i++) {
		animatedEllipses += `<g transform="translate(${randInt(ww * -0.1, ww * 0.1)} -10) scale(1.${randInt(0, 4)})">
		<ellipse id="snowflake${i}" fill="#fff" cx="0" cy="0" rx="${randInt(1,3)}" ry="${randInt(1, 3)}" filter="url(#blur${randInt(1, 2)})" />
	</g>
	<animateMotion xlink:href="#snowflake${i}" dur="${randInt(70, 130)}s" begin="-${randInt(0, 130)}s" repeatCount="indefinite" rotate="auto-reverse">
		<mpath xlink:href="#motionPath${randInt(1, 2)}" />
	</animateMotion>`;
	}
	let svg = `<svg id="snowverlay" viewbox="0 0 ${ww} ${hh}" height="${hh}" width="${ww}" preserveAspectRatio="none" style="z-index:99999; user-select:none; pointer-events:none; top:50%;
	left:50%; position:fixed; transform:translate(-50%,-50%)">
	<filter id="blur1" x="-100%" y="-100%" width="300%" height="300%">
		<feGaussianBlur in="SourceGraphic" stdDeviation="2" />
	</filter>
	<filter id="blur2" x="-100%" y="-100%" width="300%" height="300%">
		<feGaussianBlur in="SourceGraphic" stdDeviation="1" />
	</filter>
	<path id="motionPath1" fill="none" stroke="none" d="M ${ww} -${hh * 0.1} Q ${ww * 0.8} ${hh * 0.25} ${ww} ${hh * 0.5} Q ${ww * 1.2} ${hh * 0.75} ${ww} ${hh * 1.1} M ${ww * 0.9} -${hh * 0.1} Q ${ww * 0.7} ${hh * 0.25} ${ww * 0.9
	} ${hh * 0.5} Q ${ww * 1.1} ${hh * 0.75} ${ww * 0.9} ${hh * 1.1} M ${ww * 0.8} -${hh * 0.1} Q ${ww * 0.6} ${hh * 0.25} ${ww * 0.8} ${hh * 0.5} Q ${ww} ${hh * 0.75} ${ww * 0.8} ${hh * 1.1}M ${ww * 0.7} -${hh * 0.1} Q ${ww * 0.5} ${hh * 0.25} ${ww * 0.7} ${hh * 0.5} Q ${ww * 0.9} ${hh * 0.75} ${ww * 0.7} ${hh * 1.1} M ${ww * 0.6} -${hh * 0.1} Q ${ww * 0.4} ${hh * 0.25} ${ww * 0.6} ${hh * 0.5} Q ${ww * 0.8} ${hh * 0.75} ${ww * 0.6} ${hh * 1.1} M ${ww * 0.5} -${hh * 0.1} Q ${ww * 0.3} ${hh * 0.25} ${ww * 0.5} ${hh * 0.5} Q ${ww * 0.7} ${hh * 0.75} ${ww * 0.5} ${hh * 1.1}M ${ww * 0.4} -${hh * 0.1} Q ${ww * 0.2} ${hh * 0.25} ${ww * 0.4} ${hh * 0.5} Q ${ww * 0.6} ${hh * 0.75} ${ww * 0.4} ${hh * 1.1} M ${ww * 0.3} -${hh * 0.1} Q ${ww * 0.1} ${hh * 0.25} ${ww * 0.3} ${hh * 0.5} Q ${ww * 0.5} ${hh * 0.75} ${ww * 0.3} ${hh * 1.1} M ${ww * 0.2} -${hh * 0.1} Q ${ww * 0} ${hh * 0.25} ${ww * 0.2} ${hh * 0.5} Q ${ww * 0.4} ${hh * 0.75} ${ww * 0.2} ${hh * 1.1} M ${ww * 0.1} -${hh * 0.1} Q ${ww * -0.1} ${hh * 0.25} ${ww * 0.1} ${hh * 0.5} Q ${ww * 0.3} ${hh * 0.75} ${ww * 0.1} ${hh * 1.1} M 0 -${hh * 0.1} Q ${ww * -0.2} ${hh * 0.25} ${ww * 0} ${hh * 0.5} Q ${ww * 0.2} ${hh * 0.75} ${ww * 0} ${hh * 1.1}" />
	<path id="motionPath2" fill="none" stroke="none" d="M ${ww * 0.0} -${hh * 0.1} Q ${ww * 0.2} ${hh * 0.25} ${ww * 0} ${hh * 0.5} Q ${ww * -0.2} ${hh * 0.75} ${ww * 0} ${hh * 1.1} M ${ww * 0.1} -${hh * 0.1} Q ${ww * 0.3} ${hh * 0.25} ${ww * 0.1} ${hh * 0.5} Q ${ww * -0.1} ${hh * 0.75} ${ww * 0.1} ${hh * 1.1} M ${ww * 0.2} -${hh * 0.1} Q ${ww * 0.4} ${hh * 0.25} ${ww * 0.2} ${hh * 0.5} Q ${ww * 0} ${hh * 0.75} ${ww * 0.2} ${hh * 1.1} M ${ww * 0.3} -${hh * 0.1} Q ${ww * 0.5} ${hh * 0.25} ${ww * 0.3} ${hh * 0.5} Q ${ww * 0.1} ${hh * 0.75} ${ww * 0.3} ${hh * 1.1} M ${ww * 0.4} -${hh * 0.1} Q ${ww * 0.6} ${hh * 0.25} ${ww * 0.4} ${hh * 0.5} Q ${ww * 0.2} ${hh * 0.75} ${ww * 0.4} ${hh * 1.1} M ${ww * 0.5} -${hh * 0.1} Q ${ww * 0.7} ${hh * 0.25} ${ww * 0.5} ${hh * 0.5} Q ${ww * 0.3} ${hh * 0.75} ${ww * 0.5} ${hh * 1.1} M ${ww * 0.6} -${hh * 0.1} Q ${ww * 0.8} ${hh * 0.25} ${ww * 0.6} ${hh * 0.5} Q ${ww * 0.4} ${hh * 0.75} ${ww * 0.6} ${hh * 1.1} M ${ww * 0.7} -${hh * 0.1} Q ${ww * 0.9} ${hh * 0.25} ${ww * 0.7} ${hh * 0.5} Q ${ww * 0.5} ${hh * 0.75} ${ww * 0.7} ${hh * 1.1} M ${ww * 0.8} -${hh * 0.1} Q ${ww} ${hh * 0.25} ${ww * 0.8} ${hh * 0.5} Q ${ww * 0.6} ${hh * 0.75} ${ww * 0.8} ${hh * 1.1} M ${ww * 0.9} -${hh * 0.1} Q ${ww * 1.1} ${hh * 0.25} ${ww * 0.9} ${hh * 0.5} Q ${ww * 0.7} ${hh * 0.75} ${ww * 0.9} ${hh * 1.1} M ${ww} -${hh * 0.1} Q ${ww * 1.2} ${hh * 0.25} ${ww} ${hh * 0.5} Q ${ww * 0.8} ${hh * 0.75} ${ww} ${hh * 1.1}" />
	${animatedEllipses}
</svg>`;
	//Make it a node to avoid the dangerous "document.body.innerHTML = svg"
	let wrapper = document.createElement("div");
	wrapper.innerHTML = svg;
	let doc = wrapper.firstChild;
	const element = document.getElementById("snowverlay");
	element?.remove();
	document.body.appendChild(doc);
}

snowIt();
window.onresize = snowIt;