export default function locationPickerInputScript({ location, config }) {
    return {
        location: null,
        map: null,
        service: null,
        infowindow: null,
        marker: null,
        debounceTimeout: null,
        defaultLocation: {
            lat: 24.7136,
            lng: 46.6753,
        },
        config: {
            draggable: true,
            clickable: false,
            defaultLocation: null,
            statePath: "",
            controls: {
                mapTypeControl: true,
                scaleControl: true,
                streetViewControl: true,
                rotateControl: true,
                fullscreenControl: true,
                zoomControl: false,
            },
            defaultZoom: 8,
        },
        elements: {
            lat: null,
            lng: null,
        },
        init() {
            try {
                this.location = location;
                this.config = { ...this.config, ...config };

                this.elements.lat = document.getElementById(
                    "location.picker.input.lat"
                );
                this.elements.lng = document.getElementById(
                    "location.picker.input.lng"
                );

                if (this.config.defaultLocation) {
                    this.defaultLocation = this.config.defaultLocation;
                }

                const initialLocation = new google.maps.LatLng(
                    this.defaultLocation.lat,
                    this.defaultLocation.lng
                );

                this.elements.lat.value = this.defaultLocation.lat;
                this.elements.lng.value = this.defaultLocation.lng;

                let debounceTimeout;

                this.elements.lat.addEventListener("keyup", (event) => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        const latValue = parseFloat(event.target.value);
                        const lngValue = parseFloat(this.elements.lng.value);
                        if (!isNaN(latValue) && !isNaN(lngValue)) {
                            const newLatLng = new google.maps.LatLng(
                                latValue,
                                lngValue
                            );
                            this.map.setCenter(newLatLng);
                            this.marker.setPosition(newLatLng);
                            this.updateState(newLatLng);
                        }
                    }, 1000);
                });

                this.elements.lng.addEventListener("keyup", (event) => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        const latValue = parseFloat(this.elements.lat.value);
                        const lngValue = parseFloat(event.target.value);
                        if (!isNaN(latValue) && !isNaN(lngValue)) {
                            const newLatLng = new google.maps.LatLng(
                                latValue,
                                lngValue
                            );
                            this.map.setCenter(newLatLng);
                            this.marker.setPosition(newLatLng);
                            this.updateState(newLatLng);
                        }
                    }, 1000);
                });

                this.map = new google.maps.Map(this.$refs.map, {
                    center: initialLocation,
                    zoom: 20,
                    streetViewControl: false,
                });

                this.infowindow = new google.maps.InfoWindow();

                // const pin = new google.maps.marker.PinElement({
                //     background: "#4b2e83",
                //     borderColor: "#b7a57a",
                //     glyphColor: "#b7a57a",
                //     scale: 2.0,
                //   });

                // this.marker = new google.maps.marker.AdvancedMarkerElement({
                //     position: initialLocation,
                //     map: this.map,
                //     title: "position",
                //     content: pin.element,
                // });

                this.marker = new google.maps.Marker({
                    position: initialLocation,
                    map: this.map,
                });

                this.addYourLocationButton(this.map, this.marker);

                this.map.addListener("click", (event) => {
                    this.map.setCenter(event.latLng);
                    this.marker.setPosition(event.latLng);
                    this.updateState(event.latLng);
                });

                this.$watch("location", (value) => {});
            } catch (error) {
                window.location = window.location;
            }
        },
        addYourLocationButton(map, marker) {
            var controlDiv = document.createElement("div");

            var firstChild = document.createElement("button");
            firstChild.type = "button";
            firstChild.style.backgroundColor = "#fff";
            firstChild.style.border = "none";
            firstChild.style.outline = "none";
            firstChild.style.width = "28px";
            firstChild.style.height = "28px";
            firstChild.style.borderRadius = "2px";
            firstChild.style.boxShadow = "0 1px 4px rgba(0,0,0,0.3)";
            firstChild.style.cursor = "pointer";
            firstChild.style.marginRight = "10px";
            firstChild.style.padding = "0";
            firstChild.title = "Your Location";
            controlDiv.appendChild(firstChild);

            var secondChild = document.createElement("div");
            secondChild.style.margin = "5px";
            secondChild.style.width = "18px";
            secondChild.style.height = "18px";
            secondChild.style.backgroundImage =
                "url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-2x.png)";
            secondChild.style.backgroundSize = "180px 18px";
            secondChild.style.backgroundPosition = "0 0";
            secondChild.style.backgroundRepeat = "no-repeat";
            firstChild.appendChild(secondChild);

            google.maps.event.addListener(map, "center_changed", function () {
                secondChild.style["background-position"] = "0 0";
            });

            const _this = this;

            firstChild.addEventListener("click", function () {
                var imgX = "0",
                    animationInterval = setInterval(function () {
                        imgX = imgX === "-18" ? "0" : "-18";
                        secondChild.style["background-position"] =
                            imgX + "px 0";
                    }, 500);

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (
                        position
                    ) {
                        var latlng = new google.maps.LatLng(
                            position.coords.latitude,
                            position.coords.longitude
                        );
                        map.setCenter(latlng);
                        map.setZoom(20);
                        map.setCenter(latlng);
                        marker.setPosition(latlng);
                        _this.updateState(latlng);
                        clearInterval(animationInterval);
                        secondChild.style["background-position"] = "-144px 0";
                    });
                } else {
                    clearInterval(animationInterval);
                    secondChild.style["background-position"] = "0 0";
                }
            });

            controlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(
                controlDiv
            );
        },
        searchLocation(input) {
            const options = {
                fields: ["address_components", "geometry", "icon", "name"],
                strictBounds: false,
            };

            const autocomplete = new google.maps.places.Autocomplete(
                input,
                options
            );
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    console.warn(
                        `No details available for input: '${place.name}'`
                    );
                    return;
                }

                const location = place.geometry.location;
                this.map.setCenter(location);
                this.map.setZoom(20);
                this.marker.setPosition(location);
                this.updateState(location);
            });
        },
        searchLocation(input) {
            const options = {
                fields: ["address_components", "geometry", "icon", "name"],
                strictBounds: false,
            };

            const autocomplete = new google.maps.places.Autocomplete(
                input,
                options
            );
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    console.warn(
                        "No details available for input: '" + place.name + "'"
                    );
                    return;
                }

                this.map.setCenter(place.geometry.location);
                this.map.setZoom(20);

                this.marker.setPosition(place.geometry.location);
                this.marker.setVisible(true);
                console.log({
                    place: [
                        place.geometry.location.lat(),
                        place.geometry.location.lng(),
                    ],
                });
                this.updateState(place.geometry.location);

                // this.map.setCenter(place.geometry.location);
                // this.map.setZoom(20);
                // this.marker.setPosition(place.geometry.location);
                // this.updateState(place.geometry.location);
            });
        },
        updateState(location) {
            this.setCoordinates(location);
            this.elements.lat.value = location.lat();
            this.elements.lng.value = location.lng();
        },
        clearMarker() {
            this.marker.setMap(null);
            this.marker = null;
        },
        debounceSearch(input) {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.searchLocation(input);
            }, 500);
        },
        setCoordinates: function (position) {
            const newLocation = {
                lat: position.lat(),
                lng: position.lng(),
            };
            this.$wire.set(this.config.statePath, newLocation);
            console.log(this.$wire.get("data.location"));
            console.log(this.$wire.get("data.lat"));
            console.log(this.$wire.get("data.long"));
        },
    };
}
