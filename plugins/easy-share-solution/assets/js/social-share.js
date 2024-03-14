/*
    SocialShare - jQuery plugin
*/
(function ($) {

   /*  $(document).ready(function() {
        const EasyShareBTN = document.querySelector('.all-share-button');
        EasyShareBTN.classList.add('esblock');
    }); */

    function get_class_list(elem){
        if(elem.classList){
            return elem.classList;
        }else{
            return $(elem).attr('class').match(/\S+/gi);
        }
    }
    

    $.fn.ShareLink = function(options){
        var defaults = {
            title: '',
            text: '',
            image: '',
            url: window.location.href,
            class_prefix: 's_'
        };

        var options = $.extend({}, defaults, options);

        var class_prefix_length = options.class_prefix.length;

        var templates = {
            Twitter: 'https://twitter.com/intent/tweet?url={url}&text={title}',
            Pinterest: 'https://www.pinterest.com/pin/create/button/?media={image}&url={url}&description={text}',
            Facebook: 'https://www.facebook.com/sharer.php?s=100&p[title]={title}&p[summary]={text}&p[url]={url}&p[images][0]={image}',
            Vk: 'https://vkontakte.ru/share.php?url={url}&title={title}&description={text}&image={image}&noparse=true',
            Linkedin: 'https://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}&summary={text}&source={url}',
            Myworld: 'https://connect.mail.ru/share?url={url}&title={title}&description={text}&imageurl={image}',
            Ok: 'http://odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={url}&st.comments={text}',
            Tumblr: 'https://tumblr.com/share?s=&v=3&t={title}&u={url}',
            Blogger: 'https://blogger.com/blog-this.g?t={text}&n={title}&u={url}',
            Delicious: 'https://delicious.com/save?url={url}&title={title}',
            Googleplus: 'https://plus.google.com/share?url={url}',
            Digg: 'https://digg.com/submit?url={url}&title={title}',
            Reddit: 'http://reddit.com/submit?url={url}&title={title}',
            Stumbleupon: 'https://www.stumbleupon.com/submit?url={url}&title={title}',
            Pocket: 'https://getpocket.com/edit?url={url}&title={title}',
            chiq: 'http://www.chiq.com/create/bookmarklet?u={url}&i={image}&d={title}&c={url}',
            Qrifier: 'http://qrifier.com/q?inc=qr&type=url&size=350&string={url}',
            qrsrc: 'http://www.qrsrc.com/default.aspx?shareurl={url}',
            qzone: 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={url}',
            tulinq: 'http://www.tulinq.com/enviar?url={url}&title={title}',
            Misterwong: 'http://www.mister-wong.com/index.php?action=addurl&bm_url={url}&bm_description={title}&bm_notice=',
            Sto_zakladok: 'http://www.100zakladok.ru/save/?bmurl={url}&bmtitle={title}',
            Two_linkme: 'http://www.2linkme.com/?collegamento={url}&id=2lbar',
            Adifni: 'http://www.adifni.com/account/bookmark/?bookmark_url={url}',
            Amazon: 'http://www.amazon.com/gp/wishlist/static-add?u={url}&t={title}',
            Amenme: 'http://www.amenme.com/AmenMe/Amens/AmenToThis.aspx?url={url}&title={title}',
            Aim: 'http://lifestream.aol.com/share/?url={url}&title={title}&description={text} ',
            Aolmail: 'http://webmail.aol.com/25045/aol/en-us/Mail/compose-message.aspx?to=&subject={title}&body={{content}}',
            Arto: 'http://www.arto.com/section/linkshare/?lu={url}&ln={title}',
            baidu: 'http://cang.baidu.com/do/add?it={title}&iu={url}&fr=ien&dc={text}',
            Bitly: 'https://bitly.com/a/bitmarklet?u={url}',
            Bizsugar: 'http://www.bizsugar.com/bizsugarthis.php?url={url}',
            Blinklist: 'http://www.blinklist.com/blink?u={url}&t={title}&d={text}',
            Blip: 'http://blip.pl/dashboard?body={title}%3A%20{url}',
            Blogmarks: 'http://blogmarks.net/my/new.php?mini=1&simple=1&url={url}&title={title}&content={text}',
            Blurpalicious: 'http://www.blurpalicious.com/submit/?url={url}&title={title}&desc={text}',
            Bobrdobr: 'http://bobrdobr.ru/addext.html?url={url}&title={title}&desc={text}',
            Bonzobox: 'http://bonzobox.com/toolbar/add?u={url}&t={title}&desc={text}',
            Bookmerkende: 'http://www.bookmerken.de/?url={url}&title={title}',
            Box: 'https://www.box.net/api/1.0/import?import_as=link&url={url}&name={title}&description={text}',
            Bryderi: 'http://bryderi.se/add.html?u={url}',
            Buddymarks: 'http://buddymarks.com/add_bookmark.php?bookmark_title={title}&bookmark_url={url}&bookmark_desc={text}',
            Camyoo: 'http://www.camyoo.com/note.html?url={url}',
            Care2: 'http://www.care2.com/news/compose?sharehint=news&share[share_type]news&bookmarklet=Y&share[title]={title}&share[link_url]={url}&share[content]={text}',
            citeulike: 'http://www.citeulike.org/posturl?url={url}&title={title}',
            Classicalplace: 'http://www.classicalplace.com/?u={url}&t={title}&c={text}',
            cosmiq: 'http://www.cosmiq.de/lili/my/add?url={url}',
            diggita: 'http://www.diggita.it/submit.php?url={url}&title={title}',
            Diigo: 'http://www.diigo.com/post?url={url}&title={title}&desc={text}',
            domelhor: 'http://domelhor.net/submit.php?url={url}&title={title}',
            dotnetshoutout: 'http://dotnetshoutout.com/Submit?url={url}&title={title}',
            douban: 'http://www.douban.com/recommend/?url={url}&title={title}',
            dropjack: 'http://www.dropjack.com/submit.php?url={url}',
            edelight: 'http://www.edelight.de/geschenk/neu?purl={url}',
            ekudos: 'http://www.ekudos.nl/artikel/nieuw?url={url}&title={title}&desc={text}',
            elefantapl: 'http://elefanta.pl/member/bookmarkNewPage.action?url={url}&title={title}&bookmarkVO.notes=',
            embarkons: 'http://www.embarkons.com/sharer.php?u={url}&t={title}',
            Evernote: 'http://www.evernote.com/clip.action?url={url}&title={title}',
            extraplay: 'http://www.extraplay.com/members/share.php?url={url}&title={title}&desc={text}',
            ezyspot: 'http://www.ezyspot.com/submit?url={url}&title={title}',
            fabulously40: 'http://fabulously40.com/writeblog?subject={title}&body={url}',
            informazione: 'http://fai.informazione.it/submit.aspx?url={url}&title={title}&desc={text}',
            fark: 'http://www.fark.com/cgi/farkit.pl?u={url}&h={title}',
            farkinda: 'http://www.farkinda.com/submit?url={url}',
            favable: 'http://www.favable.com/oexchange?url={url}&title={title}&desc={text}',
            favlogde: 'http://www.favlog.de/submit.php?url={url}',
            flaker: 'http://flaker.pl/add2flaker.php?title={title}&url={url}',
            folkd: 'http://www.folkd.com/submit/{url}',
            fresqui: 'http://fresqui.com/enviar?url={url}',
            Friendfeed: 'http://friendfeed.com/share?url={url}&title={title}',
            funp: 'http://funp.com/push/submit/?url={url}',
            fwisp: 'http://fwisp.com/submit.php?url={url}',
            givealink: 'http://givealink.org/bookmark/add?url={url}&title={title}',
            Gmail: 'http://mail.google.com/mail/?view=cm&fs=1&to=&su={title}&body={text}&ui=1',
            goodnoows: 'http://goodnoows.com/add/?url={url}',
            Google: 'http://www.google.com/bookmarks/mark?op=add&bkmk={url}&title={title}&annotation={text}',
            googletranslate: 'http://translate.google.com/translate?hl=en&u={url}&tl=en&sl=auto',
            greaterdebater: 'http://greaterdebater.com/submit/?url={url}&title={title}',
            Hackernews: 'http://news.ycombinator.com/submitlink?u={url}&t={title}',
            hatena: 'http://b.hatena.ne.jp/bookmarklet?url={url}&btitle={title}',
            hedgehogs: 'http://www.hedgehogs.net/mod/bookmarks/add.php?address={url}&title={title}',
            hotmail: 'http://www.hotmail.msn.com/secure/start?action=compose&to=&subject={title}&body={{content}}',
            w3validator: 'http://validator.w3.org/check?uri={url}&charset=%28detect+automatically%29&doctype=Inline&group=0',
            ihavegot: 'http://www.ihavegot.com/share/?url={url}&title={title}&desc={text}',
            Instapaper: 'http://www.instapaper.com/edit?url={url}&title={title}&summary={text}',
            isociety: 'http://isociety.be/share/?url={url}&title={title}&desc={text}',
            iwiw: 'http://iwiw.hu/pages/share/share.jsp?v=1&u={url}&t={title}',
            jamespot: 'http://www.jamespot.com/?action=spotit&u={url}&t={title}',
            jumptags: 'http://www.jumptags.com/add/?url={url}&title={title}',
            kaboodle: 'http://www.kaboodle.com/grab/addItemWithUrl?url={url}&pidOrRid=pid=&redirectToKPage=true',
            kaevur: 'http://kaevur.com/submit.php?url={url}',
            kledy: 'http://www.kledy.de/submit.php?url={url}&title={title}',
            librerio: 'http://www.librerio.com/inbox?u={url}&t={title}',
            linkuj: 'http://linkuj.cz?id=linkuj&url={url}&title={title}&description={text}&imgsrc=',
            livejournal: 'http://www.livejournal.com/update.bml?subject={title}&event={url}',
            logger24: 'http://logger24.com/?url={url}',
            Mashbord: 'http://mashbord.com/plugin-add-bookmark?url={url}',
            Meinvz: 'http://www.meinvz.net/Suggest/Selection/?u={url}&desc={title}&prov=addthis.com',
            Mekusharim: 'http://mekusharim.walla.co.il/share/share.aspx?url={url}&title={title}',
            Memori: 'http://memori.ru/link/?sm=1&u_data[url]={url}',
            Meneame: 'http://www.meneame.net/submit.php?url={url}',
            Mixi: 'http://mixi.jp/share.pl?u={url}',
            moemesto: 'http://moemesto.ru/post.php?url={url}&title={title}',
            Myspace: 'http://www.myspace.com/Modules/PostTo/Pages/?u={url}&t={title}&c=',
            n4g: 'http://www.n4g.com/tips.aspx?url={url}&title={title}',
            netlog: 'http://www.netlog.com/go/manage/links/?view=save&origin=external&url={url}&title={title}&description={text}',
            netvouz: 'http://netvouz.com/action/submitBookmark?url={url}&title={title}&popup=no&description={text}',
            newstrust: 'http://newstrust.net/submit?url={url}&title={title}&ref=addthis',
            newsvine: 'http://www.newsvine.com/_tools/seed&save?u={url}&h={title}&s={text}',
            nujij: 'http://nujij.nl/jij.lynkx?u={url}&t={title}&b={text}',
            oknotizie: 'http://oknotizie.virgilio.it/post?title={title}&url={url}',
            oyyla: 'http://www.oyyla.com/gonder?phase=2&url={url}',
            pdfonline: 'http://savepageaspdf.pdfonline.com/pdfonline/pdfonline.asp?cURL={url}',
            pdfmyurl: 'http://pdfmyurl.com?url={url}',
            phonefavs: 'http://phonefavs.com/bookmarks?action=add&address={url}&title={title}',
            plaxo: 'http://www.plaxo.com/events?share_link={url}&desc={text}',
            Plurk: 'http://www.plurk.com/m?content={url}+({title})&qualifier=shares ',
            posteezy: 'http://posteezy.com/node/add/story?title={title}&body={url}',
            pusha: 'http://www.pusha.se/posta?url={url}&title={title}&description={text}',
            rediff: 'http://share.rediff.com/bookmark/addbookmark?title={title}&bookmarkurl={url}',
            redkum: 'http://www.redkum.com/add?url={url}&step=1&title={title}',
            scoopat: 'http://scoop.at/submit?url={url}&title={title}&body={text}',
            sekoman: 'http://sekoman.lv/home?status={title}&url={url}',
            shaveh: 'http://shaveh.co.il/submit.php?url={url}&title={title}',
            shetoldme: 'http://shetoldme.com/publish?url={url}&title={title}&body={text}',
            sinaweibo: 'http://v.t.sina.com.cn/share/share.php?url={url}&title={title}',
            sodahead: 'http://www.sodahead.com/news/submit/?url={url}&title={title}',
            sonico: 'http://www.sonico.com/share.php?url={url}&title={title}',
            springpad: 'http://springpadit.com/s?type=lifemanagr.Bookmark&url={url}&name={title}',
            startaid: 'http://www.startaid.com/index.php?st=AddBrowserLink&type=Detail&v=3&urlname={url}&urltitle={title}&urldesc={text}',
            startlap: 'http://www.startlap.hu/sajat_linkek/addlink.php?url={url}&title={title}',
            studivz: 'http://www.studivz.net/Suggest/Selection/?u={url}&desc={title}&prov=addthis.com',
            stuffpit: 'http://www.stuffpit.com/add.php?produrl={url}',
            stumpedia: 'http://www.stumpedia.com/submit?url={url}',
            svejo: 'http://svejo.net/story/submit_by_url?url={url}&title={title}&summary={text}',
            symbaloo: 'http://www.symbaloo.com/en/add/?url={url}&title={title}',
            thewebblend: 'http://thewebblend.com/submit?url={url}&title={title}',
            thinkfinity: 'http://www.thinkfinity.org/favorite-bookmarklet.jspa?url={url}&subject={title}',
            thisnext: 'http://www.thisnext.com/pick/new/submit/url/?description={text}&name={title}&url={url}',
            tuenti: 'http://www.tuenti.com/share?url={url}',
            typepad: 'http://www.typepad.com/services/quickpost/post?v=2&qp_show=ac&qp_title={title}&qp_href={url}&qp_text={text}',
            Viadeo: 'http://www.viadeo.com/shareit/share/?url={url}&title={title}&urlaffiliate=32005&encoding=UTF-8',
            virb: 'http://virb.com/share?external&v=2&url={url}&title={title}',
            visitezmonsite: 'http://www.visitezmonsite.com/publier?url={url}&title={title}&body={text}',
            vybralisme: 'http://vybrali.sme.sk/sub.php?url={url}',
            webnews: 'http://www.webnews.de/einstellen?url={url}&title={title}',
            wirefan: 'http://www.wirefan.com/grpost.php?d=&u={url}&h={title}&d={text}',
            Wordpress: 'http://wordpress.com/wp-admin/press-this.php?u={url}&t={title}&s={text}&v=2',
            wowbored: 'http://www.wowbored.com/submit.php?url={url}',
            wykop: 'http://www.wykop.pl/dodaj?url={url}&title={title}&desc={text}',
            Yahoo: 'http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u={url}&t={title}&d={text}',
            Yahoomail: 'http://compose.mail.yahoo.com/?To=&Subject={title}&body={{content}}',
            Yammer: 'https://www.yammer.com/home/bookmarklet?bookmarklet_pop=1&u={url}&t={title}',
            Yardbarker: 'http://www.yardbarker.com/author/new/?pUrl={url}&pHead={title}',
            yigg: 'http://www.yigg.de/neu?exturl={url}&exttitle={title}&extdesc={text}',
            Yoolink: 'http://go.yoolink.to/addorshare?url_value={url}&title={title}',
            yorumcuyum: 'http://www.yorumcuyum.com/?baslik={title}&link={url}',
            youmob: 'http://youmob.com/mobit.aspx?title={title}&mob={url}',
            zakladoknet: 'http://zakladok.net/link/?u={url}&t={title}',
            ziczac: 'http://ziczac.it/a/segnala/?gurl={url}&gtit={title}',
			Buffer: 'https://buffer.com/add?url={url}&text={title}'
        }

        function link(network){
            var url = templates[network];
            url = url.replace('{url}', encodeURIComponent(options.url));
            url = url.replace('{title}', encodeURIComponent(options.title));
           url = url.replace('{ttitle}', encodeURIComponent(options.ttitle));
            url = url.replace('{text}', encodeURIComponent(options.text));
            url = url.replace('{image}', encodeURIComponent(options.image));
            return url;
        }

        return this.each(function(i, elem){
            var classlist = get_class_list(elem);
            for(var i = 0; i < classlist.length; i++){
                var cls = classlist[i];
                if(cls.substr(0, class_prefix_length) == options.class_prefix && templates[cls.substr(class_prefix_length)]){
                    var final_link = link(cls.substr(class_prefix_length));
                    $(elem).attr('href', final_link).click(function(){
                        var screen_width = screen.width;
                        var screen_height = screen.height;
                        var popup_width = options.width ? options.width : (screen_width - (screen_width*0.2));
                        var popup_height = options.height ? options.height : (screen_height - (screen_height*0.2));
                        var left = (screen_width/2)-(popup_width/2);
                        var top = (screen_height/2)-(popup_height/2);
                        var parameters = 'toolbar=0,status=0,width=' + popup_width + ',height=' + popup_height + ',top=' + top + ',left=' + left;
                        return window.open($(this).attr('href'), '', parameters) && false;
                    });
                }
            }
        });
    }

    $.fn.ShareCounter = function(options){
        var defaults = {
            url: window.location.href,
            class_prefix: 'c_',
            display_counter_from: 0
        };

        var options = $.extend({}, defaults, options);

        var class_prefix_length = options.class_prefix.length

        var social = {
            'Facebook': facebook,
            'Vk': vk,
            'Myworld': myworld,
            'Linkedin': linkedin,
            'Ok': Ok,
            'Pinterest': pinterest,
            'Googleplus': plus
        }

        return this.each(function(i, elem){
            var classlist = get_class_list(elem);
            for(var i = 0; i < classlist.length; i++){
                var cls = classlist[i];
                if(cls.substr(0, class_prefix_length) == options.class_prefix && social[cls.substr(class_prefix_length)]){
                    social[cls.substr(class_prefix_length)](options.url, function(count){
                        if (count >= options.display_counter_from){
                            $(elem).text(count);
                        }
                    })
                }
            }
        });

        function facebook(url, callback){
            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://api.facebook.com/restserver.php',
                data: {'method': 'links.getStats', 'urls': [url], 'format': 'json'}
            })
            .done(function (data){callback(data[0].share_count)})
            .fail(function(){callback(0);})
        }

        function vk(url, callback){
            if(window.VK === undefined){VK = {};}

            VK.Share = {
                count: function(idx, value){
                    callback(value);
                }
            }

            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://vk.com/share.php',
                data: {'act': 'count', 'index': 0, 'url': url}
            })
            .fail(function(data, status){
                if(status != 'parsererror'){
                    callback(0);
                }
            })
        }

        function myworld(url, callback){
            var results = [];
            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://connect.mail.ru/share_count',
                jsonp: 'func',
                data: {'url_list': url, 'callback': '1'}
            })
            .done(function(data){callback(data[url].shares)})
            .fail(function(data){callback(0)})
        }

        function linkedin(url, callback){
            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://www.linkedin.com/countserv/count/share',
                data: {'url': url, 'format': 'jsonp'}
            })
            .done(function(data){callback(data.count)})
            .fail(function(){callback(0)})
        }

        function Ok(url, callback){

            ODKL = {
                updateCount: function(param1, value){
                    callback(value);
                }
            }

            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://ok.ru/dk',
                data: {'st.cmd': 'extLike', 'ref': url}
            })
            .fail(function(data, status){
                if(status != 'parsererror'){
                    callback(0);
                }
            })
        }

        function pinterest(url, callback){
            $.ajax({
                type: 'GET',
                dataType: 'jsonp',
                url: 'https://api.pinterest.com/v1/urls/count.json',
                data: {'url': url}
            })
            .done(function(data){callback(data.count)})
            .fail(function(){callback(0)})
        }

        function plus(url, callback){
            $.ajax({
                type: 'POST',
                url: 'https://clients6.google.com/rpc',
                processData: true,
                contentType: 'application/json',
                data: JSON.stringify({
                    'method': 'pos.plusones.get',
                    'id': location.href,
                    'params': {
                        'nolog': true,
                        'id': url,
                        'source': 'widget',
                        'userId': '@viewer',
                        'groupId': '@self'
                    },
                    'jsonrpc': '2.0',
                    'key': 'p',
                    'apiVersion': 'v1'
                })
            })
            .done(function(data){callback(data.result.metadata.globalCounts.count)})
            .fail(function(){callback(0)})
        }

        
    }
})(jQuery);
