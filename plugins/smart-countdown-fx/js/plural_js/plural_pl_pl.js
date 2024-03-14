function smartcountdown_plural(n) {
	var rest10 = n % 10;
	var rest100 = n % 100;
	if(n == 1) {
		return '_1';
	} else if(rest10 >=2 && rest10 <= 4 && (rest100 < 10 || rest100 >= 20)) {
		return '_2';
	} else {
		return '';
	}
}
