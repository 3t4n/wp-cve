jQuery.fn.velocityAsync = function (property, duration, easing) {
    var $element = this;
    return new Promise((function (resolve) {
        $element.velocity(property, duration, easing, resolve);
    }));
};
//# sourceMappingURL=velocityAsync.js.map