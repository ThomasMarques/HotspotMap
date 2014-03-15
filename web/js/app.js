var module = angular.module('hotspotmap', []).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('((').endSymbol('))');
    }
);

var serviceBroadcastKeys = {
    'userLocationFound': 1,
    'placeAdded': 2,
    'markerClicked': 3,
    'displayPlaceDetails' : 4,
    'pickUpLocation': 5,
    'placeFounded': 6,
    'placesFounded': 7,
    'placesInitialized': 8
};

module.factory('sectionService', ['$rootScope', function($rootScope) {
    var serviceInstance = {};

    serviceInstance.toggleActiveNavState = function (item) {
        $("#navigation").find("li").not("#"+item).removeClass('active');
        $("#"+item).addClass('active');
    }

    serviceInstance.toggleExpandSection = function (item) {
        $("section").not("#"+item).removeClass('expand');
        $("#"+item).addClass('expand');
    }

    serviceInstance.closeSection = function () {
        serviceInstance.toggleActiveNavState("null");
        serviceInstance.toggleExpandSection("null");
    }

    return serviceInstance;
}]);

module.factory('iconFactory', ['$rootScope', function($rootScope) {

    var serviceInstance = {};
    serviceInstance.type = {
        'temp': {
            size: new google.maps.Size(60, 65),
            origin: new google.maps.Point(0,-6)
        },
        'dev': {
            size: new google.maps.Size(60, 65),
            origin: new google.maps.Point(60,-6)
        },
        'location': {
            size: new google.maps.Size(19, 19),
            origin: new google.maps.Point(140,20),
            anchor: new google.maps.Point(10, 10)
        }
    };

    serviceInstance.markerClicked = function (marker) {

        $rootScope.$broadcast( serviceBroadcastKeys.markerClicked, marker );
    }

    serviceInstance.getMarker = function (type, options) {

        type['url'] = 'images/markers.png';
        var image = type;
        options['icon'] = image;
        var marker = new google.maps.Marker(options);

        google.maps.event.addListener(marker, 'click', function() {
            serviceInstance.markerClicked(marker);
        });

        return marker;

    }

    return serviceInstance;
}]);

module.factory('placeService', ['$rootScope', '$http', function($rootScope, $http) {

    var serviceInstance = {};

    serviceInstance.places = [];

    serviceInstance.getPlaces = function (broadcastKey) {

        $http({
            method: 'GET',
            url: '/places'
        }).
        success(function(data, status, headers, config) {
            serviceInstance.places = data._embedded.places;
            $rootScope.$broadcast( broadcastKey );
        }).
        error(function(data, status, headers, config) {
        });

    }

    serviceInstance.createPlace = function (place) {

        serviceInstance.places.push(place);

        $http({
            method: 'POST',
            url: '/places?' +
                'name='+place.name+'' +
                '&latitude='+place.latitude+'' +
                '&longitude='+place.longitude+'' +
                '&schedules=07:30 – 21:00' +
                '&description=ma description' +
                '&hotspotType=0' +
                '&coffee=1' +
                '&internetAccess=1' +
                '&placesNumber=5' +
                '&comfort=1' +
                '&frequenting=1'
        }).
        success(function(data, status, headers, config) {

            console.log(data, status);

        }).
        error(function(data, status, headers, config) {
        });

        $rootScope.$broadcast( serviceBroadcastKeys.placeAdded );

    }

    serviceInstance.getPlaceById = function (id) {
        return {id:id, name:'test', pos:new google.maps.LatLng('45.7714425', '3.1212492')};
    }

    serviceInstance.searchPlaceFromLocation = function (pos) {

        $http({method: 'GET', url: '/geoloc/'+pos.k.toString().replace('.',',')+'/'+pos.A.toString().replace('.',',')}).
        success(function(data, status, headers, config) {

                $place = {
                    latitude:data.latitude,
                    longitude:data.longitude,
                    address: data.street_number + ' ' + data.street_name,
                    town: data.city,
                    country: data.country
                };

                $rootScope.$broadcast( serviceBroadcastKeys.placeFounded, $place );


        }).
        error(function(data, status, headers, config) {
        });

    }

    serviceInstance.searchPlaceFromAddress = function (address) {

        $http({method: 'GET', url: '/geoloc/'+address}).
        success(function(data, status, headers, config) {

            $place = {
                latitude:data.latitude,
                longitude:data.longitude,
                address: data.street_number + ' ' + data.street_name,
                town: data.city,
                country: data.country
            };

            $rootScope.$broadcast( serviceBroadcastKeys.placeFounded, $place );


        }).
        error(function(data, status, headers, config) {
        });

    }

    return serviceInstance;

}]);

module.factory('hotspotMainService', ['$rootScope', 'placeService', 'iconFactory', function($rootScope, placeService, iconFactory) {

    var serviceInstance = {};

    serviceInstance.map;
    serviceInstance.currentZoom;
    serviceInstance.currentClickPos;

    serviceInstance.userLocation;
    serviceInstance.userLocationMarker;
    serviceInstance.userLocationInfo;

    serviceInstance.tempPlace;
    serviceInstance.tempMarker;

    serviceInstance.searchUserLocation = function () {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                serviceInstance.userLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                $rootScope.$broadcast( serviceBroadcastKeys.userLocationFound );
            }, function() {});
        }
    }

    serviceInstance.setMarkers = function () {

        for (var i = 0; i < placeService.places.length; i++) {

            var place = placeService.places[i];
            var options = {
                position: new google.maps.LatLng(place.latitude, place.longitude),
                map: serviceInstance.map,
                title: place.name
            };

            var marker = iconFactory.getMarker(iconFactory.type.dev, options);
        }
    }

    serviceInstance.initialize = function () {
        serviceInstance.createMapInstance();
    }

    serviceInstance.mapClicked = function (pos) {
        if ( serviceInstance.map.getZoom() == serviceInstance.currentZoom ) {
            serviceInstance.currentClickPos = pos;
            $rootScope.$broadcast( serviceBroadcastKeys.pickUpLocation );
        }
    }

    serviceInstance.listenPickupClick = function () {

        google.maps.event.addListener(serviceInstance.map, 'click', function(position) {
            serviceInstance.currentZoom = serviceInstance.map.getZoom();
            setTimeout(function() {
                serviceInstance.mapClicked(position.latLng);
                google.maps.event.clearListeners(serviceInstance.map, 'click');
            }, 200);

        });
    }

    serviceInstance.createMapInstance = function () {

        serviceInstance.searchUserLocation();

        // Geolocalisation
        var options = {
            zoom: 8
        }
        serviceInstance.map = new google.maps.Map(document.getElementById("map-canvas"), options);

        placeService.getPlaces(serviceBroadcastKeys.placesInitialized);

    }

    serviceInstance.goTo = function (place) {
        serviceInstance.map.setCenter(new google.maps.LatLng(place.latitude, place.longitude));
    }

    serviceInstance.addTempPlace = function (place) {

        var options = {
            position: new google.maps.LatLng(place.latitude, place.longitude),
            map: serviceInstance.map,
            title: place.name
        };
        var marker = iconFactory.getMarker(iconFactory.type.temp, options);
        marker.set("id", place.id);

        serviceInstance.tempMarker = marker;
        serviceInstance.tempPlace = place;
        serviceInstance.map.setCenter(new google.maps.LatLng(place.latitude, place.longitude));
    }

    serviceInstance.removeTempMarker = function () {
        if (serviceInstance.tempMarker)
            serviceInstance.tempMarker.setMap(null);
    }

    $rootScope.$on(serviceBroadcastKeys.userLocationFound, function($scope) {

        serviceInstance.map.setCenter(serviceInstance.userLocation);

        // Location Marker
        var options = {
            map: serviceInstance.map
        };
        if (!serviceInstance.userLocationMarker)
            serviceInstance.userLocationMarker = iconFactory.getMarker(iconFactory.type.location, options);
        serviceInstance.userLocationMarker.set("id",0);
        serviceInstance.userLocationMarker.setPosition(serviceInstance.userLocation);

        // Current location information
        if (!serviceInstance.userLocationInfo)
            serviceInstance.userLocationInfo = new google.maps.InfoWindow({
                map: serviceInstance.map,
                content: 'Your current location.'
            });
        serviceInstance.userLocationInfo.setPosition(serviceInstance.userLocation);

    });

    $rootScope.$on(serviceBroadcastKeys.placeAdded, function($scope) {
        serviceInstance.setMarkers();
    });
    $rootScope.$on(serviceBroadcastKeys.placesInitialized, function() {
        serviceInstance.setMarkers();
    });

    return serviceInstance;

}]);

function AddCtrl($scope, $rootScope, hotspotMainService, placeService, sectionService) {

    $scope.disabled = false;
    $scope.addressDisplayed = true;
    $scope.locationDisplayed = false;
    $scope.pickupDisplayed = true;

    $scope.resetDisplay = function () {
        $scope.addressDisplayed = true;
        $scope.locationDisplayed = false;
        $scope.pickupDisplayed = true;
    }

    $scope.addressFocusTrigger = function () {
        $scope.locationDisplayed = false;
        $scope.pickupDisplayed = false;
    }

    $scope.locationFocusTrigger = function () {
        $scope.addressDisplayed = false;
    }

    $scope.resetAddForm = function () {
        $scope.place = {};
        $scope.resetDisplay();
        $scope.disabled = false;
        hotspotMainService.removeTempMarker();
    }

    $scope.placeFounded = function ($place) {

        hotspotMainService.addTempPlace($place);

        $scope.addressDisplayed = true;
        $scope.locationDisplayed = true;
        $scope.pickupDisplayed = false;
        $scope.disabled = true;

        $place.name = $scope.place.name;
        $scope.place = $place;

        $('#loader').toggleClass('hidden');

    }

    $scope.searchPlace = function () {

        $('#loader').toggleClass('hidden');

        var place = $scope.place;
        if ($scope.addressDisplayed) {
            placeService.searchPlaceFromAddress(place.address+', '+place.town+', '+place.country);
        } else if ($scope.locationDisplayed) {
            placeService.searchPlaceFromLocation(new google.maps.LatLng(place.latitude, place.longitude));
        }

    }

    $scope.addPlace = function () {

        placeService.createPlace(hotspotMainService.tempPlace);
        hotspotMainService.removeTempMarker();
        $scope.resetAddForm();
        sectionService.closeSection();

    }

    $scope.pickTarget = function () {

        hotspotMainService.map.setOptions({ draggableCursor : "url(http://s3.amazonaws.com/besport.com_images/status-pin.png) 8 14, auto" });
        hotspotMainService.listenPickupClick();
        $scope.addressDisplayed = false;
        $scope.locationDisplayed = false;
        $scope.pickupDisplayed = false;

    }

    $rootScope.$on(serviceBroadcastKeys.pickUpLocation, function($broadcastScope) {
        hotspotMainService.map.setOptions({ draggableCursor : null });

        $scope.addressDisplayed = false;
        $scope.locationDisplayed = true;
        $scope.$digest();

        $scope.place.latitude = hotspotMainService.currentClickPos.k;
        $scope.place.longitude = hotspotMainService.currentClickPos.A;
        $scope.searchPlace();
        $scope.$digest();

    });

    $rootScope.$on(serviceBroadcastKeys.placeFounded, function($broadcastScope, $place) {
        $scope.placeFounded($place);
    });

}

function DetailCtrl($scope, $rootScope, hotspotMainService, placeService) {

    $scope.place = {
        name: "bonjour"
    };

    $scope.getDetail = function (marker) {
        var place = placeService.getPlaceById(marker.id);
        $scope.place = place;
        $rootScope.$broadcast( serviceBroadcastKeys.displayPlaceDetails, true );
    }

    $rootScope.$on(serviceBroadcastKeys.markerClicked, function($broadcastScope, marker) {
        $scope.getDetail(marker);
        // digest modification into angularjs scope
        $scope.$digest();
    });

}

function MainCtrl($scope, $rootScope, hotspotMainService, placeService, sectionService) {

    $scope.createMap = function (place) {
        hotspotMainService.initialize();
    }

    $scope.showNearestLocation = function () {
        $('#loader').toggleClass('hidden');
        placeService.getPlaces(serviceBroadcastKeys.placesFounded);
    }

    $rootScope.$on(serviceBroadcastKeys.placesFounded, function() {
        $scope.places = placeService.places;
        sectionService.toggleActiveNavState("listNav");
        sectionService.toggleExpandSection("list");
        $('#loader').toggleClass('hidden');
    });

    $scope.showPlace = function (place) {
        hotspotMainService.goTo(place);
    }

    $scope.toggleAddForm = function () {
        sectionService.toggleActiveNavState("addNav");
        sectionService.toggleExpandSection("add");
    }

    $scope.closeSection = function () {
        sectionService.closeSection();
    }

    $scope.home = function () {
        sectionService.toggleActiveNavState("homeNav");
        sectionService.toggleExpandSection("home");
        hotspotMainService.searchUserLocation();
    }

    $rootScope.$on(serviceBroadcastKeys.placeAdded, function() {
        sectionService.toggleActiveNavState("addNav");
        sectionService.toggleExpandSection("add");
    });

    $rootScope.$on(serviceBroadcastKeys.displayPlaceDetails, function($broadcastScope, display) {
        sectionService.toggleExpandSection("detail");
    });

    google.maps.event.addDomListener(window, 'load', function() {
        $scope.createMap();
        $('#loader').toggleClass('hidden');
        $('#body').toggleClass('show');
    });

}