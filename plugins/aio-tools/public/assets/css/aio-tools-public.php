/*	Contact Buttons	*/
/* Default Position Right */
#w2w-widget-flyout{
	bottom:50px;
	width:50px;
	height:50px;
	border-radius:50%;
	box-shadow: 0 5px 5px rgba(0,0,0,.1);
	cursor:pointer;
	right:50px;
	font-family: -apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif,apple color emoji,segoe ui emoji,segoe ui symbol,noto color emoji;
}
#w2w-widget-flyout.right{
	
}
#w2w-widget-flyout.left{
	left:50px;
	right:unset;
}
#w2w-widget-flyout.w2w-pos-fixed {
    position: fixed;
}
.w2w-pinkBg {
    background-color: #ed184f;
    background-image: linear-gradient(90deg, #fd5581, #fd8b55);
}
#w2w-widget-flyout > span .ripple{
    position:absolute;
    width:160px;
    height:160px;
    z-index:-1;
    left:50%;
    top:50%;
    opacity:0;
    margin:-80px 0 0 -80px;
    border-radius:100px;
    -webkit-animation:ripple 1.8s infinite;
    animation:ripple 1.8s infinite
}
@-webkit-keyframes ripple{
    0%{
        opacity:1;
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    100%{
        opacity:0;
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}
@keyframes ripple{
    0%{
        opacity:1;
        -webkit-transform:scale(0);
        transform:scale(0)
    }
    100%{
        opacity:0;
        -webkit-transform:scale(1);
        transform:scale(1)
    }
}
#w2w-widget-flyout > span .ripple:nth-child(2){
    animation-delay:.3s;
    -webkit-animation-delay:.3s
}
#w2w-widget-flyout > span .ripple:nth-child(3){
    animation-delay:.6s;
    -webkit-animation-delay:.6s
}
#w2w-widget-flyout > span{
	position:relative;
	width:100%;
	height:100%;
	display:flex;
	justify-content:center;
	align-items:center;
	transition: .3s ease-in-out;
}
#w2w-widget-flyout > span > i{
	color:#fff; font-size: 25px;
}
#w2w-widget-flyout span:hover{}
#w2w-widget-flyout ul{
	position:absolute;
	bottom:50px;
	background: url(../images/background.png);
	min-width:250px;
	padding: 20px 10px;
	border-radius:5px;
	opacity:0;
	visibility:hidden;
	transition:.3s;
	border: 1px solid rgb(123 123 123 / 10%);
	box-shadow: 0 0 20px 0 rgb(0 0 0 / 8%);
	right:0;
}
#w2w-widget-flyout.right ul{
	right:0;
}
#w2w-widget-flyout.left ul{
	left:0;
	right:unset;
}
#w2w-widget-flyout ul.active{
	bottom:50px;
	opacity:1;
	visibility:visible;
	/*transition:.3s;*/
	animation: 0.5s ease 0s 1 normal none running FadeIn;
}
#w2w-widget-flyout ul li{
	list-style:none;
	display:flex;
	justify-content: flex-start;
	align-items:center;
	padding:10px 0;
	transition:.3s;
	margin-bottom: 0;
}
#w2w-widget-flyout ul li:hover{
	
}
#w2w-widget-flyout ul li:not(:last-child){
	border-bottom: 1px dashed rgba(0,0,0,.1);
}
#w2w-widget-flyout ul li .w2w-icon{
	margin-right: 10px;
	width: 30px;
	min-width: 30px;
	height: 30px;
	border-radius: 50%;
	overflow: hidden;
	-webkit-justify-content: center;
	justify-content: center;
	display: flex;
	align-items: center;
}
#w2w-widget-flyout ul li .w2w-icon{display:inline-flex;box-shadow: 0 5px 5px rgba(0,0,0,.1);}
.icon-zalo {
    background: url("../images/icon-zalo.png") no-repeat 50% 50%;
    width: 16px;
    height: 16px;
    display: inline-block;
    background-size: 16px;
    vertical-align: middle;
    margin-right: 2px;
    position: relative;
}
#w2w-widget-flyout ul li.btn-zalo > .w2w-icon{
    background: #018fe5;
}
#w2w-widget-flyout ul li a {text-decoration:none;}
#w2w-widget-flyout ul li.btn-zalo:hover > a .w2w-text{
    color: #018fe5;
}
#w2w-widget-flyout ul li.btn-facebook > .w2w-icon{
    background: linear-gradient(200deg, #ff6a68 10%, #a033fe 60%, #0595ff 100%);
	color:#fff;
}
#w2w-widget-flyout ul li.btn-facebook:hover > a .w2w-text{
    color:#306199;
}
#w2w-widget-flyout ul li.btn-phone > .w2w-icon{
    background:#46CA57;
	color:#fff;
}
#w2w-widget-flyout ul li.btn-phone:hover > a .w2w-text{
    color:#46CA57;
}
#w2w-widget-flyout ul li.btn-email > .w2w-icon{
    background:#d26e4b;
	color:#fff;
}
#w2w-widget-flyout ul li.btn-email:hover > a .w2w-text{
    color:#d26e4b;
}