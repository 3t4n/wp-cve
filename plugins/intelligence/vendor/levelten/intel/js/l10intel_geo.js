var _ioq = _ioq || [];
function L10iGeo (ioq, name) {
    var _ioq = ioq;
    var io = ioq.io;
    var n = name;

    ths = {};
    ths.geoSets = {};
    _ioq.setSchema('GeoCoord', {
        props: {
            elev: {
                type: 'Number',
                aliases: ['elevation']
            },
            lat: {
                type: 'Number',
                required: 1,
                aliases: ['latitude']
            },
            lon: {
                type: 'Number',
                required: 1,
                aliases: ['longitude']
            }
        },
        inherit: ['Thing'],
        aliases: ['GeoCoordinates']
    });
    /*
    _ioq.setSchema('ContactPoint', {
        props: {
            email: {
                type: 'String'
            },
            tel: {
                type: 'String'
            },
            address: {
              type: 'PostalAddress'
            },
            contactType: {
                type: 'String'
            }
        },
        inherit: ['Thing']
    });
    */
    _ioq.setSchema('PostalAddress', {
        props: {
            country: {
                type: 'String'
            },
            locality: {
                type: 'String'
            },
            region: {
                type: 'String'
            },
            postalCode: {
                type: 'String'
            },
            street: {
                type: 'String'
            },
            street2: {
                type: 'String'
            }
        },
        inherit: ['Thing', 'ContactPoint']
    });
}

L10iGeo.prototype.constrGeoCoord = function (lat, lon, props, options) {
    var geo = {};
    if (!lat || !lon) {
        return geo;
    }
    if (_ioq.isObject(props)) {
        geo = _ioq.objectMerge(geo, props);
    }

    geo._schema = 'GeoCoord';

    geo.lat = lat;
    geo.lon = lon;

    return geo;
};

L10iGeo.prototype.calcDistLatLon = function (lat1, lon1, lat2, lon2, unit) {
    var radlat1 = Math.PI * lat1/180
    var radlat2 = Math.PI * lat2/180
    var radlon1 = Math.PI * lon1/180
    var radlon2 = Math.PI * lon2/180
    var theta = lon1-lon2
    var radtheta = Math.PI * theta/180
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist)
    dist = dist * 180/Math.PI
    dist = dist * 60 * 1.1515
    if (unit=="K") { dist = dist * 1.609344 }
    if (unit=="N") { dist = dist * 0.8684 }
    return dist;
}

L10iGeo.prototype.calcDistGeoCoord = function (pt1, pt2, unit) {
    if (!_ioq.isObject(pt1) || !_ioq.isObject(pt2)){ //|| !pt1.lat || !pt1.lon || !pt2.lat || !pt2.lon) {
        return false;
    }
    return this.calcDistLatLon(parseFloat(pt1.lat), parseFloat(pt1.lon), parseFloat(pt2.lat), parseFloat(pt1.lon))
}

_ioq.push(['providePlugin', 'geo', L10iGeo, {}]);