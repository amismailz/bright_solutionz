<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        map: null,
        service: null,
        infowindow: null,
        marker: null,
        debounceTimeout: null,
        defaultLocation: {
            lat: 24.7136,
            lng: 46.6753
        },
        latElement: document.getElementById('office.lat'),
        longElement: document.getElementById('office.long'),
        initMap() {
            const _this = this;
            window.initMap = function() {
                _this.initMap();
            };
            if (this.latElement?.value && this.longElement?.value) {
                if (this.latElement?.value == 0.000000 && this.longElement?.value == 0.000000) {
                    this.latElement.value = this.defaultLocation.lat;
                    this.longElement.value = this.defaultLocation.lng;
                } else {
                    this.defaultLocation.lat = this.latElement?.value;
                    this.defaultLocation.lng = this.longElement?.value;
                }
            } else {
                this.latElement.value = this.defaultLocation.lat;
                this.longElement.value = this.defaultLocation.lng;
            }

            this.latElement.addEventListener('keydown', () => {
                this.map.setCenter(new google.maps.LatLng(this.latElement.value, this.longElement.value));
                this.marker.setPosition(new google.maps.LatLng(this.latElement.value, this.longElement.value));
                this.updateState(new google.maps.LatLng(this.latElement.value, this.longElement.value));
            })

            this.longElement.addEventListener('keydown', () => {
                this.map.setCenter(new google.maps.LatLng(this.latElement.value, this.longElement.value));
                this.marker.setPosition(new google.maps.LatLng(this.latElement.value, this.longElement.value));
                this.updateState(new google.maps.LatLng(this.latElement.value, this.longElement.value));
            })

            const initialLocation = new google.maps.LatLng(this.defaultLocation.lat, this.defaultLocation.lng);

            this.map = new google.maps.Map(this.$refs.map, {
                center: initialLocation,
                zoom: 20,
                streetViewControl: false,
            });

            this.infowindow = new google.maps.InfoWindow();

            this.marker = new google.maps.Marker({
                position: initialLocation,
                map: this.map,
            });

            this.addYourLocationButton(this.map, this.marker);

            this.map.addListener('click', (event) => {
                this.map.setCenter(event.latLng);
                this.marker.setPosition(event.latLng);
                this.updateState(event.latLng);
            });
            window.initMap = this.initMap;
        },
        addYourLocationButton(map, marker) {
            var controlDiv = document.createElement('div');

            var firstChild = document.createElement('button');
            firstChild.type = 'button';
            firstChild.style.backgroundColor = '#fff';
            firstChild.style.border = 'none';
            firstChild.style.outline = 'none';
            firstChild.style.width = '28px';
            firstChild.style.height = '28px';
            firstChild.style.borderRadius = '2px';
            firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
            firstChild.style.cursor = 'pointer';
            firstChild.style.marginRight = '10px';
            firstChild.style.padding = '0';
            firstChild.title = 'Your Location';
            controlDiv.appendChild(firstChild);

            var secondChild = document.createElement('div');
            secondChild.style.margin = '5px';
            secondChild.style.width = '18px';
            secondChild.style.height = '18px';
            secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-2x.png)';
            secondChild.style.backgroundSize = '180px 18px';
            secondChild.style.backgroundPosition = '0 0';
            secondChild.style.backgroundRepeat = 'no-repeat';
            firstChild.appendChild(secondChild);

            google.maps.event.addListener(map, 'center_changed', function() {
                secondChild.style['background-position'] = '0 0';
            });

            const _this = this;

            firstChild.addEventListener('click', function() {
                var imgX = '0',
                    animationInterval = setInterval(function() {
                        imgX = imgX === '-18' ? '0' : '-18';
                        secondChild.style['background-position'] = imgX + 'px 0';
                    }, 500);

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        map.setCenter(latlng);
                        map.setZoom(20);
                        map.setCenter(latlng);
                        marker.setPosition(latlng);
                        _this.updateState(latlng);
                        clearInterval(animationInterval);
                        secondChild.style['background-position'] = '-144px 0';
                    });
                } else {
                    clearInterval(animationInterval);
                    secondChild.style['background-position'] = '0 0';
                }
            });

            controlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
        },
        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    this.map.setZoom(20);
                    this.map.setCenter(place.geometry.location);
                    this.marker.setPosition(place.geometry.location);
                    this.updateState(place.geometry.location);
                });
            }
        },
        searchLocation(query) {
            const request = {
                query: query,
                fields: ['name', 'geometry'],
            };

            this.service = new google.maps.places.PlacesService(this.map);
            this.service.findPlaceFromQuery(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                    for (let i = 0; i < results.length; i++) {
                        const place = results[i];
                        this.map.setCenter(place.geometry.location);
                        this.map.setZoom(20);
                        this.marker.setPosition(place.geometry.location);
                        this.updateState(place.geometry.location);
                        break;
                    }
                }
            });
        },
        updateState(location) {
            this.state = {
                lat: location.lat(),
                lng: location.lng(),
            };
            this.latElement.value = location.lat();
            this.longElement.value = location.lng();
        },
        clearMarker() {
            this.marker.setMap(null);
            this.marker = null;
        },
        debounceSearch(query) {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.searchLocation(query);
            }, 500);
        }
    }" x-init="initMap();">
        <div
            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-text-input overflow-hidden">
            <div class="min-w-0 flex-1">
                <input type="text" placeholder="{{ __('Search for places') }}"
                    @input="debounceSearch($event.target.value)"
                    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3" />
            </div>
        </div>

        <div x-ref="map" style="height: 400px; width: 100%;"></div>
    </div>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GMAP_API') }}&libraries=places&callback=initMap"></script>

</x-dynamic-component>
