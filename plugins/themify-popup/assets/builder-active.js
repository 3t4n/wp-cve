((api, Themify, doc) => {
    'use strict';
    let url=doc.currentScript.src.split('.js')[0].replace( /assets\/builder-active(\.min)?/, '' );
    if(!url.endsWith('/')){
        url+='/';
    }
    Themify.on('tb_layout_loaded',(LayoutList,webp)=>{
        class PopupLayout extends LayoutList{
            constructor(){
                const id='popup';
                super(id);
                this.id=id;
                this.title=themifyPopupBuilder.title;
            }
            getList(){
                const zipArr=themifyPopupBuilder.data,
                    data=[],
                    imgUrl=url+'sample/',
                    ext=webp?'.webp':'.jpg',
                    upperCase=str=>{
                        const arr = str.replace(/  +/g, ' ').split(' ');
                        for (let i = 0,len=arr.length; i < len; ++i) {
                            arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
                        }
                        return arr.join(' ');
                    };
                for(let i=0,len=zipArr.length;i<len;++i){
                    let slug=zipArr[i].replace('.zip','');
                    data[i]={
                        title:upperCase(slug.replaceAll('-',' ')),
                        slug: slug,
                        thumbnail:imgUrl+slug+ext
                    };
                }
               return Promise.resolve(data);
            }
            async getItem(slug){
                const promises=[api.Helper.loadJsZip()],
                   zipUrl=url+'sample/';

                   promises.push(Themify.fetch('', 'blob',{
                    credentials: 'omit',
                    method: 'GET',
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/zip'
                    }
                   },zipUrl+slug+'.zip'));
                const res=await Promise.all(promises),
                    zip=await JSZip.loadAsync(res[1]),
                    files=zip.files;
                    if(files){
                        const builderFileName='builder_data_export.txt',
                               gsFileName='builder_gs_data_export.txt';
                        if(files[builderFileName]!==undefined){
                            const prm=[];
                            prm.push(zip.file(files[builderFileName].name).async('text'));
                            if(files[gsFileName]!==undefined){
                                prm.push(zip.file(files[gsFileName].name).async('text'));
                            }
                            const res=await Promise.all(prm),
							tmp=JSON.parse(res[0]),
							data={builder_data:(tmp.builder_data || tmp)};
                            if(res[1]){
                                data.used_gs=JSON.parse(res[1]);
                            }
                            return data;
                        }
                        else{
                            throw themifyBuilder.i18n.importBuilderNotExist;
                        }
                    }
                    else{
                        throw themifyBuilder.i18n.zipFileEmpty;
                    }
            }
        }
        new PopupLayout();
    },true);
    
})(tb_app, Themify, document);