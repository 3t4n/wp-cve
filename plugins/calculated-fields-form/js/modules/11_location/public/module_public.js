fbuilderjQuery = ( typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'location' ] = {
	'prefix' : '',
	'callback' : function()
		{
			fbuilderjQuery[ 'fbuilder' ][ 'extend_window' ](fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'location' ][ 'prefix' ], CF_LOCATION);
		}
};