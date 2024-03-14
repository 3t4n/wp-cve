function RNTranslate(key) {
    if (typeof RNTranslatorDictionary[key] == 'undefined')
        return key;
    return RNTranslatorDictionary[key];
}
