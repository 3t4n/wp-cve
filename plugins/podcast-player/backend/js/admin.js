import ImageUpload from './partials/widgets/imageupload';
import ColorPicker from './partials/widgets/colorpicker';
import FetchMethod from './partials/widgets/fetchMethod';
import ChangeDetect from './partials/widgets/changeDetect';

jQuery(function() {
	new ImageUpload();
	new ColorPicker();
	new FetchMethod();
	new ChangeDetect();
});
