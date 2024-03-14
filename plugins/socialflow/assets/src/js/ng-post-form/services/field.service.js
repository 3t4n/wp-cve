export default class FieldService {
	/* @ngInject */
	constructor() {}

	getAccountName( account ) {
		return account.field_meta.name;
	}

	getAccountId( account ) {
		return account.field_meta.id;
	}

	getMessageId( social, index, name ) {
		return `${social.field_meta.id_prefix}_${index}_${name}`;
	}

	getMessagePrefix( social, index ) {
		return `${social.field_meta.name_prefix}[${index}]`;
	}

	getMessageName( social, index, name ) {
		let prefix = this.getMessagePrefix( social, index );

		return `${prefix}[fields][${name}]`;
	}

	getSettingName( social, msgIndex, index, name ) {
		let prefix = this.getMessagePrefix( social, msgIndex );

		return `${prefix}[settings][${index}][${name}]`;
	}
	


}