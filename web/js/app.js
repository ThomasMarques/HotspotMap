var module = angular.module('hotspotmap', []).config(function($interpolateProvider){
        $interpolateProvider.startSymbol('((').endSymbol('))');
    }
);

var serviceBroadcastKeys = {
    'userLocationFound': 1,
    'placeAdded': 2
};

module.factory('placeService', ['$rootScope', function($rootScope) {

    var serviceInstance = {};

    serviceInstance.places = [
        {id:1, name:'starbucks', pos:new google.maps.LatLng('60', '105')},
        {id:2, name:'soCofee', pos:new google.maps.LatLng('20', '135')}
    ];

    serviceInstance.createPlace = function (place) {
        serviceInstance.places.push(place);
        $rootScope.$broadcast( serviceBroadcastKeys.placeAdded );
    }

    serviceInstance.getPlaceFromLocation = function (location) {
        // match to $result = $geocoder->reverse($latitude, $longitude);
        return {id:3, name:'test', pos:new google.maps.LatLng('22', '33')};
    }

    serviceInstance.getPlaceFromAddress = function (address) {
        // match to $geocoder->geocode('10 rue Gambetta, Paris, France');
        return {id:4, name:'test2', pos:new google.maps.LatLng('19', '338')};
    }

    return serviceInstance;

}]);

module.factory('hotspotMainService', ['$rootScope', 'placeService', function($rootScope, placeService) {

    var serviceInstance = {};

    serviceInstance.map;
    serviceInstance.userLocation;
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
            console.log(place);
            var marker = new google.maps.Marker({
                position: place.pos,
                map: serviceInstance.map,
                title: place.name
            });
        }
    }

    serviceInstance.initialize = function () {
        serviceInstance.createMapInstance();
    }

    serviceInstance.createMapInstance = function () {
        serviceInstance.searchUserLocation();
    }

    serviceInstance.goTo = function (place) {
        serviceInstance.map.setCenter(place.pos);
    }

    serviceInstance.addTempPlace = function (place) {

        var marker = new google.maps.Marker({
            position: place.pos,
            map: serviceInstance.map,
            title: place.name
        });
        marker.id = place.id;

        serviceInstance.tempMarker = marker;
        serviceInstance.tempPlace = place;
        serviceInstance.map.setCenter(place.pos);
    }

    serviceInstance.removeTempMarker = function () {
        serviceInstance.tempMarker.setMap(null);
    }

    $rootScope.$on(serviceBroadcastKeys.userLocationFound, function($scope) {

        var options = {
            pos : serviceInstance.userLocation,
            zoom: 8
        }
        serviceInstance.map = new google.maps.Map(document.getElementById("map-canvas"), options);
        serviceInstance.map.setCenter(serviceInstance.userLocation);

        serviceInstance.setMarkers();

    });

    $rootScope.$on(serviceBroadcastKeys.placeAdded, function($scope) {

        serviceInstance.setMarkers();

    });

    return serviceInstance;

}]);

function AddCtrl($scope, hotspotMainService, placeService) {

    $scope.disabled = false;
    $scope.addressDisplayed = true;
    $scope.locationDisplayed = true;

    $scope.resetDisplay = function () {
        $scope.addressDisplayed = true;
        $scope.locationDisplayed = true;
    }

    $scope.addressFocusTrigger = function () {
        console.log("addressFocusedTrigger");
        $scope.locationDisplayed = false;
    }

    $scope.locationFocusTrigger = function () {
        console.log("locationFocusedTrigger");
        $scope.addressDisplayed = false;
    }

    $scope.resetAddForm = function () {
        $scope.place = {};
        $scope.resetDisplay();
        $scope.disabled = false;
    }

    $scope.searchPlace = function () {

        var place = $scope.place;
        if ($scope.addressDisplayed) {
            place = placeService.getPlaceFromAddress(place.address+', '+place.town+', '+place.country);
        } else if ($scope.locationDisplayed) {
            place = placeService.getPlaceFromLocation(new google.maps.LatLng(place.lat,place.lon));
        }

        hotspotMainService.addTempPlace(place);

        $scope.resetDisplay();
        $scope.disabled = true;

    }

    $scope.addPlace = function () {

        placeService.createPlace(hotspotMainService.tempPlace);
        hotspotMainService.removeTempMarker();
        $scope.resetAddForm();

    }

}

function MainCtrl($scope, $rootScope, hotspotMainService, placeService) {

    $scope.createMap = function (place) {
        hotspotMainService.initialize();
    }

    $scope.showNearestLocation = function () {
        $scope.places = placeService.places;
        $("#list").toggleClass('expand');
    }

    $scope.showPlace = function (place) {
        hotspotMainService.goTo(place);
    }

    $scope.toggleAddForm = function () {
        $("#add").toggleClass('expand');
    }

    $scope.addPlace = function () {
        $scope.toggleAddForm();
    }

    $rootScope.$on(serviceBroadcastKeys.placeAdded, function() {
        $scope.toggleAddForm();
    });

    google.maps.event.addDomListener(window, 'load', function() {
        $scope.createMap();
        $('#loader').toggleClass('hidden');
        $('#body').toggleClass('show');
    });

}