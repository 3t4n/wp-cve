function getLocale(){
    if(hqLocale.includes("es_")){
        return "es"
    }
    if(hqLocale.includes("fr_")){
        return "fr"
    }
    if(hqLocale.includes("pt_")){
        return "pt"
    }
    if(hqLocale.includes("es_")){
        return "es"
    }
    if(hqLocale === "ar" || hqLocale === "ar_SA"){
        return "ar";
    }
    return "en";
}