function smartcountdown_plural(n) {
	return (n==0 ? '_0' : n==1 ? '_1' : n==2 ? '_2' : n%100>=3 && n%100<=10 ? '_3' : n%100>=11 && n%100<=99 ? '' : '_4');
}
