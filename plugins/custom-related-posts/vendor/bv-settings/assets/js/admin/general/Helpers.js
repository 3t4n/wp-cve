export default {
    checkSetting(setting, settings) {
        if ( ! this.dependencyMet(setting, settings) ) {
            return false;
        }

        return setting;
    },
    dependencyMet(object, settings) {
        if (object.hasOwnProperty('dependency')) {
            let dependencies = object.dependency;
            
            // Make sure dependencies is an array.
            if ( ! Array.isArray( dependencies ) ) {
                dependencies = [dependencies];
            }

            // Check all dependencies.
            for ( let dependency of dependencies ) {
                let dependency_value = settings[dependency.id];

                if ( dependency.hasOwnProperty('type') && 'inverse' == dependency.type ) {
                    if (dependency_value == dependency.value) {
                        return false;
                    }
                } else {
                    if (dependency_value != dependency.value) {
                        return false;
                    }
                }
            }
        }

        return true;
    },
    beforeSettingDisplay(id, settings) {
        let value = settings[id];
        return value;
    },
    beforeSettingSave(value, id, settings) {
        return value;
    }
};
