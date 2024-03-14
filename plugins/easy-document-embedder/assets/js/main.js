// tabs controller
var Tabs = (function () {
    return {
        switchTab: function (e) {
            var clickedTab, anchor, activePaneID;
            e.preventDefault();
            document.querySelector(".nav-tabs > li.active").classList.remove("active");
            document.querySelector("div.tab-content > div.tab-pane.active").classList.remove("active");

            clickedTab = e.currentTarget;
            anchor = e.target;
            activePaneID = anchor.getAttribute("href");
            clickedTab.classList.add("active");
            document.querySelector(activePaneID).classList.add("active");
        }
    }
})();

// Media controller
var Media = (function () {
    var mediaFunc = value => {
        var file = wp.media({
            title: 'Upload File',
            multiple: false
        })
        .on('select',() => {
            var upload_file = file.state().get('selection').first();
            var file_url = upload_file.toJSON().url;
            value.value = file_url;
        })
        .open();
    }
    return {
        uploadFunc: e => {
            e.preventDefault();
            var file_value = document.getElementById("ede_upload_file_url");
            mediaFunc(file_value);
        }
        
    }
})();

// change meta content 
var SourceType = (function () {
    return {
        chanageSection: e => {
            if (e.target.value === "ML") {
                const extrlContent = document.querySelector('.external-content');
                extrlContent.style.display = 'none';
                const gdocContent = document.querySelector('.gdoc-content');
                gdocContent.style.display = 'none';
                const mediaContent = document.querySelector('.media-content');
                mediaContent.style.display = 'block';
                
            } else if(e.target.value === "EL") {
                const gdocContent = document.querySelector('.gdoc-content');
                gdocContent.style.display = 'none';
                const mediaContent = document.querySelector('.media-content');
                mediaContent.style.display = 'none';
                const extrlContent = document.querySelector('.external-content');
                extrlContent.style.display = 'block';
            } else {
                const mediaContent = document.querySelector('.media-content');
                mediaContent.style.display = 'none';
                const extrlContent = document.querySelector('.external-content');
                extrlContent.style.display = 'none';
                const gdocContent = document.querySelector('.gdoc-content');
                gdocContent.style.display = 'block';
            }
        }
    }
})();

// GLOBAL SCOPE CONTROLLER
var Controller = (function (menuTab,media,srcType) {

    var NodeListLoop = function (list,callback) {
        for(let i = 0; i<list.length; i++) {
            callback(list[i],i);
        }
    }

    // all event setup function
    var setupEvenetListerner = function () {
        
        window.addEventListener("load",() => {
            var tabs,uploadBtn, selectSource;
            tabs = document.querySelectorAll(".nav-tabs > li");
            
            if (tabs) {
                NodeListLoop(tabs,function (cur) {
                    cur.addEventListener("click",menuTab.switchTab);
                });
            }

            uploadBtn = document.querySelector("#ede_upload_file_btn");
            selectSource = document.querySelector("#ede_source_type");

            if (uploadBtn || selectSource) {
                uploadBtn.addEventListener("click",media.uploadFunc);
                selectSource.addEventListener('change', srcType.chanageSection );
            }

            
        });

    };

    return {
        init: function () {
            // call event listener function
            setupEvenetListerner();
        }
    }
})(Tabs,Media,SourceType);

// initialize
Controller.init();