(function () {
    // Core
    (function(e,t){var n=e.amplitude||{_q:[],_iq:{}};var r=t.createElement("script")
    ;r.type="text/javascript"
    ;r.integrity="sha384-AUydfiSe1Ky1zDY/KCJrSDvNC/Rb1TyoiQ10xfyB/LUYw8GOwJ07SUTa9SxvinL2"
    ;r.crossOrigin="anonymous";r.async=true
    ;r.src="https://cdn.amplitude.com/libs/amplitude-7.4.1-min.gz.js"
    ;r.onload=function(){if(!e.amplitude.runQueuedFunctions){
        console.log("[Amplitude] Error: could not load SDK")}}
    ;var i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)
    ;function s(e,t){e.prototype[t]=function(){
        this._q.push([t].concat(Array.prototype.slice.call(arguments,0)));return this}}
        var o=function(){this._q=[];return this}
        ;var a=["add","append","clearAll","prepend","set","setOnce","unset"]
        ;for(var c=0;c<a.length;c++){s(o,a[c])}n.Identify=o;var u=function(){this._q=[]
            ;return this}
        ;var l=["setProductId","setQuantity","setPrice","setRevenueType","setEventProperties"]
        ;for(var p=0;p<l.length;p++){s(u,l[p])}n.Revenue=u
        ;var d=["init","logEvent","logRevenue","setUserId","setUserProperties","setOptOut","setVersionName","setDomain","setDeviceId","enableTracking","setGlobalUserProperties","identify","clearUserProperties","setGroup","logRevenueV2","regenerateDeviceId","groupIdentify","onInit","logEventWithTimestamp","logEventWithGroups","setSessionId","resetSessionId"]
        ;function v(e){function t(t){e[t]=function(){
            e._q.push([t].concat(Array.prototype.slice.call(arguments,0)))}}
            for(var n=0;n<d.length;n++){t(d[n])}}v(n);n.getInstance=function(e){
            e=(!e||e.length===0?"$default_instance":e).toLowerCase()
            ;if(!n._iq.hasOwnProperty(e)){n._iq[e]={_q:[]};v(n._iq[e])}return n._iq[e]}
        ;e.amplitude=n})(window,document);
    // Core ---

    const init = (isProd) => {
        const API_KEY_DEV = "90dc46eadb765c371155f07b87b5c18c";
        const API_KEY_PROD = "d59f9d3c9355095783c11939df104e91";
        const API_KEY = isProd ? API_KEY_PROD : API_KEY_DEV;
        amplitude.getInstance().init(API_KEY);
    }


    const setUser = (email) => {
        amplitude.getInstance().setUserId(email)
    };

    const  eventsByPathname = {
        '/wp-admin/admin.php?page=wtotem_dashboard'     : 'DASHBOARD_PAGE_VIEWED',
        '/wp-admin/admin.php?page=wtotem_firewall'      : 'FIREWALL_PAGE_VIEWED',
        '/wp-admin/admin.php?page=wtotem_antivirus'     : 'ANTIVIRUS_PAGE_VIEWED',
        '/wp-admin/admin.php?page=wtotem_settings'      : 'OPTIONS_PAGE_VIEWED',
        '/wp-admin/admin.php?page=wtotem_documentation' : 'HELP_CENTER_PAGE_VIEWED',
        '/wp-admin/admin.php?page=wtotem_reports'       : 'REPORTS_PAGE_VIEWED',
    }

    const getEventTypeByPage = (pathname) => {
        for (const [path, eventType] of Object.entries(eventsByPathname)) {
            if (pathname?.includes(path)) {
                return eventType;
            }
        }

        return null;
    }

    const analytics =  {

        loggedIn: (
            email, // string,
            tariff, // string,
            sitesCount, // number,
            teammatesCount, // number
        ) => {
            const identify = new amplitude.Identify()
                .set('PLAN', tariff)
                .set('WEBSITES', sitesCount)
                .set('TEAMMATES', teammatesCount)
            amplitude.getInstance().identify(identify)
            amplitude.getInstance().setUserId(email)
            amplitudeEvent('LOGGED_IN')
        },

        loggedOut: () => {
            amplitudeEvent('LOGGED_OUT')
        },

        accountVerified: (
            email, // string,
            tariff, // string,
            sitesCount, // number,
            teammatesCount, // number,
            source, // 'appsumo' | 'regular' | 'teammate'
        ) => {
            const identify = new amplitude.Identify()
                .set('PLAN', tariff)
                .set('WEBSITES', sitesCount)
                .set('TEAMMATES', teammatesCount)
                .set('SIGN_UP_DATE', new Date().toISOString())
            amplitude.getInstance().identify(identify)
            amplitude.getInstance().setUserId(email)
            amplitudeEvent('ACCOUNT_VERIFIED', {
                source,
            })
        },

        // status: 'finished' | 'skipped'
        onboarding: (status) => {
            if (status === 'finished') {
                amplitudeEvent('ONBOARDING_COMPLETED')
            } else {
                amplitudeEvent('ONBOARDING_CLOSED')
            }
        },

        // source: 'preonboarding' | 'other'
        agentInstallPopup: (source) => {
            amplitudeEvent('AGENT_INSTALL_CLICKED', {
                source,
            })
        },

        // type: 'automatic' | 'manual' | 'WP Plugin'
        agentInstallType: (type) => {
            amplitudeEvent('AGENT_INSTALL_CHOSEN', {
                type,
            })
        },

        // antivirus: boolean, firewall: boolean
        agentInstallConfirm: (antivirus, firewall) => {
            amplitudeEvent('AGENT_INSTALLED', {
                antivirus,
                firewall,
            })
        },

        siteAdded: () => {
            amplitudeEvent('SITE_ADDED')
        },

        teammateAdded: () => {
            amplitudeEvent('TEAMMATE_ADDED')
        },

        // source: 'report' | 'profile'
        whiteLabelLogoAdded: (source) => {
            amplitudeEvent('LOGO_ADDED', {
                source,
            })
        },
        reportGenerated: (period) => {
            amplitudeEvent('REPORT_GENERATED', {
                period,
            })
        },
        // type: 'slack'
        addIntegrations: (type) => {
            amplitudeEvent('INTEGRATION_ADDED', {
                type,
            })
        },

        wafSettingsChanged: (
            gdn, // boolean,
            dos, // boolean,
            loginAttempts, // boolean,
            dosLimit, // number,
            loginAttemptsLimit, // number
        ) => {
            amplitudeEvent('WAF_SETTINGS_CHANGED', {
                gdn,
                dos,
                loginAttempts,
                dosLimit,
                loginAttemptsLimit,
            })
        },

        fileQuarantined: () => {
            amplitudeEvent('FILE_QUARANTINED')
        },

        fileRestored: () => {
            amplitudeEvent('FILE_RESTORED')
        },

        avRescanDemanded: () => {
            amplitudeEvent('AV_RESCAN_DEMANDED')
        },

        apiKeyActivated: () => {
            amplitudeEvent('API_KEY_ACTIVATED')
        },

        errorNotification: (
            error, // string,
        ) => {
            amplitudeEvent('ERROR_NOTIFICATION', {
                error,
            })
        },

        /** Dashboard triggers */
        showTooltip: (
            service, // string,
        ) => {
            amplitudeEvent('SHOW_TOOLTIP', {
                service,
            })
        },
        selectGraphPeriod: (
            period, // string,
            service, // string,
        ) => {
            amplitudeEvent('SELECT_GRAPH_PERIOD', {
                period,
                service,
            })
        },
        selectGraphStartDay: (
            service, // string,
        ) => {
            amplitudeEvent('SELECT_GRAPH_START_DAY', {
                service,
            })
        },
        selectGraphEndDay: (
            service, // string,
        ) => {
            amplitudeEvent('SELECT_GRAPH_END_DAY', {
                service,
            })
        },
        showGraphDetailed: (
            service, // string,
            period,
            start_date,
            end_date,
        ) => {
            amplitudeEvent('SHOW_GRAPH_DETAILED', {
                service,
                period,
                start_date,
                end_date,
            })
        },
        openSupportDialog: (
            service, // string,
        ) => {
            amplitudeEvent('OPEN_SUPPORT_DIALOG', {
                service,
            })
        },
        addPsExclusion: (
            user_input, // string,
        ) => {
            amplitudeEvent('ADD_PS_EXCLUSION', {
                user_input,
            })
        },
        savePsExclusion: () => {
            amplitudeEvent('SAVE_PS_EXCLUSION')
        },
        removePsExclusionPort: (
            user_input, // string,
        ) => {
            amplitudeEvent('REMOVE_PS_EXCLUSION_PORT', {
                user_input,
            })
        },
        closePsExclusion: () => {
            amplitudeEvent('CLOSE_PS_EXCLUSION')
        },

        /** Firewall triggers */
        worldMap: (
            attack_count, // string,
            block_count, // string,
        ) => {
            amplitudeEvent('WORLD_MAP', {
                attack_count,
                block_count,
            })
        },
        addCountry: (
            country_list, // string,
        ) => {
            amplitudeEvent('ADD_COUNTRY', {
                country_list,
            })
        },
        searchCountry: (
            user_input, // string,
        ) => {
            amplitudeEvent('SEARCH_COUNTRY', {
                user_input,
            })
        },
        addAllCountry: (
            country_list, // string,
        ) => {
            amplitudeEvent('ADD_ALL_COUNTRY', {
                country_list,
            })
        },
        addContinent: (
            continent, // string,
        ) => {
            amplitudeEvent('ADD_CONTINENT', {
                continent,
            })
        },
        saveCountryBlock: (
            country_list, // string,
        ) => {
            amplitudeEvent('SAVE_COUNTRY_BLOCK', {
                country_list,
            })
        },
        closeCountry: () => {
            amplitudeEvent('CLOSE_COUNTRY')
        },

        /** Antivirus triggers */
        avFileType: () => {
            amplitudeEvent('AV_FILE_TYPE')
        },
        avChangedFiles: () => {
            amplitudeEvent('AV_CHANGED_FILES')
        },
        downloadAvExcel: () => {
            amplitudeEvent('DOWNLOAD_AV_EXCEL')
        },
        rescan: (
            service, // string,
        ) => {
            amplitudeEvent('RESCAN', {
                service,
            })
        },

        /** Settings triggers */
        flipModules: (
            service, // string,
        ) => {
            amplitudeEvent('FLIP_MODULES', {
                service,
            })
        },
        flipNotification: (
            service, // string,
        ) => {
            amplitudeEvent('FLIP_NOTIFICATION', {
                service,
            })
        },
        addWhiteIp: (
            user_input, // string,
        ) => {
            amplitudeEvent('ADD_WHITE_IP', {
                user_input,
            })
        },
        addWhiteIpList: () => {
            amplitudeEvent('ADD_WHITE_IP_LIST')
        },
        inputWhiteIpList: (
            user_input, // string,
        ) => {
            amplitudeEvent('INPUT_WHITE_IP_LIST', {
                user_input,
            })
        },
        saveWhiteIpList: (
            user_input, // string,
        ) => {
            amplitudeEvent('SAVE_WHITE_IP_LIST', {
                user_input,
            })
        },
        closeWhiteIp: () => {
            amplitudeEvent('CLOSE_WHITE_IP')
        },
        addBlackIp: (
            user_input, // string,
        ) => {
            amplitudeEvent('ADD_BLACK_IP', {
                user_input,
            })
        },
        addBlackIpList: () => {
            amplitudeEvent('ADD_BLACK_IP_LIST')
        },
        inputBlackIpList: (
            user_input, // string,
        ) => {
            amplitudeEvent('INPUT_BLACK_IP_LIST', {
                user_input,
            })
        },
        saveBlackIpList: (
            user_input, // string,
        ) => {
            amplitudeEvent('SAVE_BLACK_IP_LIST', {
                user_input,
            })
        },
        closeBlackIp: () => {
            amplitudeEvent('CLOSE_BLACK_IP')
        },
        reinstallAgent: () => {
            amplitudeEvent('REINSTALL_AGENT')
        },

        /** Reports triggers */
        generateReport: () => {
            amplitudeEvent('GENERATE_REPORT')
        },
        includeReportModule: (
            service, // string,
        ) => {
            amplitudeEvent('INCLUDE_REPORT_MODULE', {
                service,
            })
        },
        createReport: (
            select_report_period, // string,
            select_report_start_day, // string,
            select_report_end_day, // string,
            include_report_module, // string,
            select_site, // string,
        ) => {
            amplitudeEvent('CREATE_REPORT', {
                select_report_period,
                select_report_start_day,
                select_report_end_day,
                include_report_module,
                select_site,
            })
        },
        downloadReport: (
            site_name, // string,
        ) => {
            amplitudeEvent('DOWNLOAD_REPORT', {
                site_name,
            })
        },

        pageVisited: ()=>{
            const path = document.location.href;

            const eventType = getEventTypeByPage(path);
            if (eventType) {
                amplitudeEvent(eventType)
            }
        },

        init,
        setUser,
    }

    const amplitudeEvent = (event, props) => {
        const identify = new amplitude.Identify().set('LAST_SEEN_DATE', new Date().toISOString())
        amplitude.getInstance().identify(identify)

        const additionalProps = {'WT_PLATFORM': 'wordpress'};
        const resultProps = props ? Object.assign(props, additionalProps) : additionalProps;
        amplitude.getInstance().logEvent(event, resultProps)
    }

    window.AmplitudeAnalytics = analytics;
})();
// *** Events list ***
// pageVisited
// reportGenerated (with params)
// wafSettingsChanged
// fileQuarantined
// fileRestored
// avRescanDemanded
// agentInstallConfirm (with params)
// apiKeyActivated
// loggedOut
// loggedIn (with params)
// accountVerified
// onboarding (with params)
// agentInstallPopup (with params)
// agentInstallType (with params)
// siteAdded
// teammateAdded
// whiteLabelLogoAdded (with params)
// addIntegrations (with params)


// *** Additional functions ***
// init
// setUser(email)

// *** Example
// AmplitudeAnalytics.reportGenerated(period);
// AmplitudeAnalytics.loggedOut();

// *** For every page need to add page visited event
// * check pages pathnames in eventsByPathname variable
// AmplitudeAnalytics.pageVisited();

// if not prod - use test api key
const isProd = true;
AmplitudeAnalytics.init(isProd)