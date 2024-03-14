cgJsClassAdmin.index.indexeddb = {
    error: false,
    initiated: false,
    reInitiated: false,
    instance: null,
    versionNumber: 1,
    cgVersion: '',
    adminData: 1,
    init: function (cgVersionFromSiteLoad) {

        // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
        return;
        if(cgJsClassAdmin.index.indexeddb.initiated){
            return;
        }

        if(!cgJsClassAdmin.index.indexeddb.initiated){
            cgJsClassAdmin.index.indexeddb.initiated = true;
        }

        cgJsClassAdmin.index.indexeddb.error = true;

        // OLD BROWSER SUPPORT
        // Internet Explorer 10, Firefox 16, Chrome 24.

        // In der folgenden Zeile sollten Sie die Präfixe einfügen, die Sie testen wollen.
        window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        // Verwenden Sie "var indexedDB = ..." NICHT außerhalb einer Funktion.
        // Ferner benötigen Sie evtl. Referenzen zu einigen window.IDB* Objekten:
        window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
        window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
        // (Mozilla hat diese Objekte nie mit Präfixen versehen, also brauchen wir kein window.mozIDB*)

        // ZWISCHENPRÜFUNG OB BROWSER ERLAUBT
        if (!window.indexedDB) {
            console.log("Dieser Browser unterstützt kein indexedDb");
            return false;
        }

        var request = indexedDB.open('cgJsClassAdminIndexedDB'+'/'+location.hostname+location.pathname,cgJsClassAdmin.index.indexeddb.versionNumber);// zweiter parameter ist versionsnummer

        request.onerror = function(event) {
            cgJsClassAdmin.index.indexeddb.error = true;
            console.log("Connection with IndexedDB is not possible");
            console.log(event);
            return false;
        };

        // onupgradeneeded runs before on success!!!!
        // AUTOCREATING DATABASE IF NOT EXISTS
        // version number when indexedDB open will be checked. If higher then before then this onupgradeneeded will be running
        // there is no other option to check if the database already exists or not then this one
        request.onupgradeneeded = function(event) { // BEISPIEL WENN ES EINEN VERSIONSUPGRADE DER DATENBANK GAB
            //  is the only place where you can alter the structure of the database !!!!!

            var db = event.target.result;

            try{
                db.createObjectStore("adminData", { keyPath: "versionNumber" });
            }catch (e){
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('adminData storage could not be created, must be already crated');
                console.log(e)
            }

        };

        request.onsuccess = function() {

            var db = request.result;

            cgJsClassAdmin.index.indexeddb.instance = db;

            var getAdminDataRequest = db.transaction("adminData").objectStore("adminData").get(cgJsClassAdmin.index.indexeddb.versionNumber);

            getAdminDataRequest.onsuccess = function(event) {

                cgJsClassAdmin.index.indexeddb.error = false;

                if(typeof event.target.result == 'undefined'){
                    console.log('cgJsClassAdminIndexedDB undefined result');
                    return;
                }

                var data = event.target.result;

                if(data.hasOwnProperty('cgVersion')){
                    cgJsClassAdmin.index.indexeddb.cgVersion = data.cgVersion;
                }

                cgJsClassAdmin.index.indexeddb.adminData = data;

                if(cgVersionFromSiteLoad){// !Important. Check first if it is send. Otherwise recursion because unequal undefined
                    if(cgVersionFromSiteLoad!=data.cgVersion){
                        location.reload();
                    }
                }


            };

            getAdminDataRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('cgJsClassAdminIndexedDB request error');
                console.log(event);
            }

        }
    },
    setAdminData: function (cgVersion,isReloadOnSuccess) {

        cgJsClassAdmin.index.indexeddb.cgVersion = cgVersion;

        try{

            var setAdminDataRequest = cgJsClassAdmin.index.indexeddb.instance.transaction('adminData','readwrite').objectStore('adminData').put({
                versionNumber:cgJsClassAdmin.index.indexeddb.versionNumber,
                cgVersion:cgVersion
            });

            setAdminDataRequest.onsuccess = function() {

                if(isReloadOnSuccess){
                    requestSucceed = true;
                    location.reload();
                }

            };

            setAdminDataRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('setAdminDataRequest error');
                console.log(event);
            };

            if(isReloadOnSuccess && setAdminDataRequest.readyState=='done'){// some browsers like firefox does not provide onsucces butt provide instant readyState update when put
                location.reload();
            }else{

                if(isReloadOnSuccess){

                    var i = 0;

                    setInterval(function () {// some browsers like firefox does not react on onsuccess when put

                        i = i + 1;

                        if(setAdminDataRequest.readyState=='done'){
                            location.reload();
                        }else{
                            if(i==3){
                                location.reload();
                            }
                        }

                    },1000);

                }

            }

        }catch(e){

            cgJsClassAdmin.index.indexeddb.error = true;

            console.log('save cgVersion did not worked');
            console.log(e);

            if(isReloadOnSuccess){

                location.reload();

            }else{

                cgJsClassAdmin.index.indexeddb.deleteAndRecreateIndexedDB();

            }

        }

    },
    getAdminData: function () {

        // !IMPORTANT
        // 11 July 2020: IN THE MOMENT NOT REALLY USED, BUT HAVE TO BE DONE LIKE setAdminData, because of firefox!!!!!!

        if(cgJsClassAdmin.index.indexeddb.instance){

            var db = cgJsClassAdmin.index.indexeddb.instance;

            var getAdminDataRequest = db.transaction("adminData").objectStore("adminData").get(cgJsClassAdmin.index.indexeddb.cgVersion);

            getAdminDataRequest.onsuccess = function(event) {

                if(typeof event.target.result == 'undefined'){
                    console.log('cgJsClassAdminIndexedDB undefined result on getAdminData');
                    return;
                }

                var data = event.target.result;

                if(data.hasOwnProperty('cgVersion')){
                    cgJsClassAdmin.index.indexeddb.cgVersion = data.cgVersion;
                }

                cgJsClassAdmin.index.indexeddb.adminData = data;

            };

            getAdminDataRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest error');
                console.log(event);
            }

        }

    },
    deleteAndRecreateIndexedDB: function () {// DELETE DATABASE AND TRY TO RECREATE!!!

        // CHROME MIGHT USE - location.origin as full name!!!!!!
        // FIREFOX MIGHT USE - (default)!!!!!!
        // This why trhee tries!!!!
        // seems currently 2nd August 2020 only Safari uses original name for indexdDB :) 'cgJsClassAdminIndexedDB'+'/'+location.hostname+location.pathname

        try{

            var deleteDatabaseRequest = indexedDB.deleteDatabase('cgJsClassAdminIndexedDB'+'/'+location.hostname+location.pathname);


            deleteDatabaseRequest.onsuccess = function(event) {

                console.log('index db deleted');
                setTimeout(function () {

                    if(!cgJsClassAdmin.index.indexeddb.reInitiated){
                        cgJsClassAdmin.index.indexeddb.initiated = false;
                        cgJsClassAdmin.index.indexeddb.reInitiated = true;
                        cgJsClassAdmin.index.indexeddb.init();
                    }

                },10);

            };

            deleteDatabaseRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest error');
                console.log(event);
            };

            deleteDatabaseRequest.onblocked  = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest blocked');
                console.log(event);
            };

        }catch(e){
            console.log('index db database could not be deleted');
            console.log(e);
        }

        try{


            var deleteDatabaseRequest = indexedDB.deleteDatabase('cgJsClassAdminIndexedDB'+'/'+location.hostname+location.pathname+' - '+location.origin);

            deleteDatabaseRequest.onsuccess = function(event) {

                console.log('index db deleted');
                setTimeout(function () {

                    if(!cgJsClassAdmin.index.indexeddb.reInitiated){
                        cgJsClassAdmin.index.indexeddb.initiated = false;
                        cgJsClassAdmin.index.indexeddb.reInitiated = true;
                        cgJsClassAdmin.index.indexeddb.init();
                    }

                },10);

            };

            deleteDatabaseRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest error');
                console.log(event);
            };

            deleteDatabaseRequest.onblocked  = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest blocked');
                console.log(event);
            };

        }catch(e){
            console.log('index db database could not be deleted');
            console.log(e);
        }

        try{


            var deleteDatabaseRequest = indexedDB.deleteDatabase('cgJsClassAdminIndexedDB'+'/'+location.hostname+location.pathname+' (default)');

            deleteDatabaseRequest.onsuccess = function(event) {

                console.log('index db deleted');
                setTimeout(function () {

                    if(!cgJsClassAdmin.index.indexeddb.reInitiated){
                        cgJsClassAdmin.index.indexeddb.initiated = false;
                        cgJsClassAdmin.index.indexeddb.reInitiated = true;
                        cgJsClassAdmin.index.indexeddb.init();
                    }

                },10);

            };

            deleteDatabaseRequest.onerror = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest error');
                console.log(event);
            };

            deleteDatabaseRequest.onblocked  = function (event) {
                cgJsClassAdmin.index.indexeddb.error = true;
                console.log('getAdminDataRequest blocked');
                console.log(event);
            };

        }catch(e){
            console.log('index db database could not be deleted');
            console.log(e);
        }


    }
};