var _ioq = _ioq || [];
function L10iDyn (_ioq) {
    var ioq = _ioq;
    var io = _ioq.io;
};

L10iDyn.prototype.display = function (set, opt) {
    var $set = jQuery(".intel-dynset" + set);
    var $showOpt = $set.find(".intel-dynopt" + opt);
    if ($showOpt.length == 0) {
        return;
    }
    $set.find("[class*='intel-dynopt']").hide(0);
    $showOpt.show(0);
};

_ioq.push(['providePlugin', 'dyn', L10iDyn, {}]);