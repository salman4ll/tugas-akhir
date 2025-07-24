let map;
let marker;
let geocoder;
let autocomplete;
let placesService;

// Initialize Google Maps
function initMap() {
    // Default position (Bogor, Indonesia)
    const defaultPos = { lat: -6.5944, lng: 106.7892 };
    
    const mapElement = document.getElementById("map");
    if (!mapElement) return;
    
    map = new google.maps.Map(mapElement, {
        zoom: 13,
        center: defaultPos,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl: true,
    });

    geocoder = new google.maps.Geocoder();
    placesService = new google.maps.places.PlacesService(map);

    // Create marker
    marker = new google.maps.Marker({
        position: defaultPos,
        map: map,
        draggable: true,
        title: "Lokasi Perusahaan",
        animation: google.maps.Animation.DROP
    });

    // Initialize search functionality
    initializeSearch();

    // Map click event
    map.addListener("click", (event) => {
        updateMarkerPosition(event.latLng);
        reverseGeocode(event.latLng);
    });

    // Marker drag event
    marker.addListener("dragend", (event) => {
        updateMarkerPosition(event.latLng);
        reverseGeocode(event.latLng);
    });

    // Initialize with default position
    updateMarkerPosition(defaultPos);
    
    // Setup additional controls
    setupLocationControls();
}

// Initialize search functionality
function initializeSearch() {
    const searchInput = document.getElementById('address-search');
    if (!searchInput) return;

    // Initialize autocomplete
    autocomplete = new google.maps.places.Autocomplete(searchInput, {
        types: ['establishment', 'geocode'],
        componentRestrictions: { country: 'ID' }, // Restrict to Indonesia
        fields: ['place_id', 'geometry', 'name', 'formatted_address', 'address_components']
    });

    // Bias the autocomplete towards the map's current bounds
    autocomplete.bindTo('bounds', map);

    // Listen for place selection
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        
        if (!place.geometry || !place.geometry.location) {
            showNotification('Tempat tidak ditemukan. Silakan coba lagi.', 'error');
            return;
        }

        // Update map and marker
        const location = place.geometry.location;
        map.setCenter(location);
        map.setZoom(17);
        
        updateMarkerPosition(location);
        
        // Try to extract address components
        if (place.address_components) {
            parseAddressComponents(place.address_components);
        }
        
        // Show success notification
        showNotification(`Lokasi ditemukan: ${place.name || place.formatted_address}`, 'success');
    });

    // Search on Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            performTextSearch(searchInput.value);
        }
    });
}

// Perform text search when autocomplete doesn't work
function performTextSearch(query) {
    if (!query.trim()) return;

    const request = {
        query: query,
        fields: ['place_id', 'geometry', 'name', 'formatted_address', 'address_components'],
        locationBias: map.getCenter()
    };

    placesService.textSearch(request, (results, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
            const place = results[0];
            const location = place.geometry.location;
            
            map.setCenter(location);
            map.setZoom(17);
            updateMarkerPosition(location);
            
            showNotification(`Lokasi ditemukan: ${place.name || place.formatted_address}`, 'success');
        } else {
            showNotification('Lokasi tidak ditemukan. Silakan coba kata kunci lain.', 'error');
        }
    });
}

// Parse address components to fill form fields
function parseAddressComponents(components) {
    const addressData = {
        street_number: '',
        route: '',
        sublocality_level_4: '', // RT equivalent
        sublocality_level_3: '', // RW equivalent  
        administrative_area_level_4: '', // Kelurahan/Desa
        administrative_area_level_3: '', // Kecamatan
        administrative_area_level_2: '', // Kabupaten/Kota
        administrative_area_level_1: '', // Provinsi
    };

    components.forEach(component => {
        const type = component.types[0];
        if (addressData.hasOwnProperty(type)) {
            addressData[type] = component.long_name;
        }
    });

    // Try to auto-fill detail alamat
    let detailAlamat = '';
    if (addressData.route) {
        detailAlamat += addressData.route;
        if (addressData.street_number) {
            detailAlamat += ' No. ' + addressData.street_number;
        }
    }

    if (detailAlamat) {
        const detailAlamatField = document.getElementById('detail_alamat');
        if (detailAlamatField && !detailAlamatField.value.trim()) {
            detailAlamatField.value = detailAlamat;
            validateAlamatFields();
        }
    }

    console.log('Parsed address components:', addressData);
}

// Reverse geocoding to get address from coordinates
function reverseGeocode(latLng) {
    geocoder.geocode({ location: latLng }, (results, status) => {
        if (status === 'OK' && results[0]) {
            const searchInput = document.getElementById('address-search');
            if (searchInput) {
                searchInput.value = results[0].formatted_address;
            }
            
            // Try to parse address components
            if (results[0].address_components) {
                parseAddressComponents(results[0].address_components);
            }
        }
    });
}

// Setup location controls (current location, clear search)
function setupLocationControls() {
    const currentLocationBtn = document.getElementById('current-location-btn');
    const clearSearchBtn = document.getElementById('clear-search-btn');
    
    if (currentLocationBtn) {
        currentLocationBtn.addEventListener('click', getCurrentLocation);
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', clearSearch);
    }
}

// Get current location
function getCurrentLocation() {
    const btn = document.getElementById('current-location-btn');
    
    if (!navigator.geolocation) {
        showNotification('Geolocation tidak didukung oleh browser ini.', 'error');
        return;
    }

    // Show loading state
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Mencari...
    `;

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };

            map.setCenter(pos);
            map.setZoom(17);
            updateMarkerPosition(pos);
            reverseGeocode(pos);
            
            showNotification('Lokasi berhasil ditemukan!', 'success');
            
            // Reset button
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi Saya
            `;
        },
        (error) => {
            let message = 'Gagal mendapatkan lokasi.';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'Akses lokasi ditolak. Silakan izinkan akses lokasi.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    message = 'Waktu pencarian lokasi habis.';
                    break;
            }
            
            showNotification(message, 'error');
            
            // Reset button
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lokasi Saya
            `;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000 // 5 minutes
        }
    );
}

// Clear search
function clearSearch() {
    const searchInput = document.getElementById('address-search');
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Reset to default position
    const defaultPos = { lat: -6.5944, lng: 106.7892 };
    map.setCenter(defaultPos);
    map.setZoom(13);
    updateMarkerPosition(defaultPos);
    
    showNotification('Pencarian dibersihkan', 'info');
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.search-notification');
    existingNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `search-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
    
    const colors = {
        'success': 'bg-green-500 text-white',
        'error': 'bg-red-500 text-white', 
        'info': 'bg-blue-500 text-white',
        'warning': 'bg-yellow-500 text-black'
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <span class="text-sm">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 hover:opacity-75">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Update marker position and coordinates
function updateMarkerPosition(position) {
    const lat = typeof position.lat === 'function' ? position.lat() : position.lat;
    const lng = typeof position.lng === 'function' ? position.lng() : position.lng;
    
    marker.setPosition(position);
    
    // Update hidden fields
    const latField = document.getElementById('latitude');
    const lngField = document.getElementById('longitude');
    const latDisplay = document.getElementById('lat-display');
    const lngDisplay = document.getElementById('lng-display');
    
    if (latField) latField.value = lat;
    if (lngField) lngField.value = lng;
    if (latDisplay) latDisplay.textContent = lat.toFixed(6);
    if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);
    
    // Validate form
    validateAlamatFields();
}

function validateAlamatFields() {
    const fields = [
        "provinsi_id",
        "kabupaten_id",
        "kecamatan_id", 
        "kelurahan_id",
        "rt",
        "rw",
        "detail_alamat",
        "latitude",
        "longitude"
    ];

    const submitBtn = document.getElementById('submitBtn');
    if (!submitBtn) return;

    function check() {
        const allFilled = fields.every((id) => {
            const el = document.getElementById(id);
            return el && el.value.trim() !== "";
        });

        const isValid = allFilled;
        submitBtn.disabled = !isValid;

        if (!isValid) {
            submitBtn.classList.add(
                "bg-gray-300",
                "text-gray-500", 
                "cursor-not-allowed"
            );
            submitBtn.classList.remove("bg-[#ED0226]", "text-white");
        } else {
            submitBtn.classList.remove(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
            submitBtn.classList.add("bg-[#ED0226]", "text-white");
        }
    }

    fields.forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            // Remove existing listeners to prevent duplicates
            el.removeEventListener("change", check);
            el.removeEventListener("input", check);
            // Add new listeners
            el.addEventListener("change", check);
            el.addEventListener("input", check);
        }
    });

    check();
}

function initAddressDropdowns() {
    const provinsiSelect = document.getElementById("provinsi_id");
    const kabupatenSelect = document.getElementById("kabupaten_id");
    const kecamatanSelect = document.getElementById("kecamatan_id");
    const kelurahanSelect = document.getElementById("kelurahan_id");

    if (!provinsiSelect || !kabupatenSelect || !kecamatanSelect || !kelurahanSelect)
        return;

    resetAndDisableDropdowns("kabupaten_id", "kecamatan_id", "kelurahan_id");

    // Load provinces from your API
    fetch("/provinces")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((provinsi) => {
                provinsiSelect.innerHTML += `<option value="${provinsi.id}">${provinsi.nama}</option>`;
            });
            // Initialize TomSelect if available
            if (typeof TomSelect !== 'undefined') {
                new TomSelect(provinsiSelect, {
                    placeholder: "Pilih Provinsi",
                    clearButton: true,
                });
            }
        })
        .catch((error) => {
            console.error('Error loading provinces:', error);
        });

    provinsiSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kabupaten_id", "kecamatan_id", "kelurahan_id");
        if (this.value) {
            kabupatenSelect.disabled = false;
            fetch(`/cities/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kabupatenSelect, data))
                .catch((error) => {
                    console.error('Error loading cities:', error);
                });
        }
        validateAlamatFields();
    });

    kabupatenSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kecamatan_id", "kelurahan_id");
        if (this.value) {
            kecamatanSelect.disabled = false;
            fetch(`/districts/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kecamatanSelect, data))
                .catch((error) => {
                    console.error('Error loading districts:', error);
                });
        }
        validateAlamatFields();
    });

    kecamatanSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kelurahan_id");
        if (this.value) {
            kelurahanSelect.disabled = false;
            fetch(`/subdistricts/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kelurahanSelect, data))
                .catch((error) => {
                    console.error('Error loading subdistricts:', error);
                });
        }
        validateAlamatFields();
    });

    kelurahanSelect.addEventListener("change", validateAlamatFields);
}

function resetAndDisableDropdowns(...ids) {
    ids.forEach((id) => {
        const select = document.getElementById(id);
        if (select) {
            const placeholder = select.getAttribute('data-placeholder') || `Pilih ${select.name || id}`;
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }
    });
}

function populateDropdown(selectElement, data) {
    const placeholder = selectElement.getAttribute('data-placeholder') || 
                       `Pilih ${selectElement.name || selectElement.id}`;
    selectElement.innerHTML = `<option value="">${placeholder}</option>`;
    
    data.forEach((item) => {
        selectElement.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
    });
    
    // Initialize TomSelect if available
    if (typeof TomSelect !== 'undefined') {
        new TomSelect(selectElement, {
            placeholder: placeholder,
            clearButton: true,
        });
    }
}

// RT/RW input formatting
function setupRTRWFormatting() {
    const rtInput = document.getElementById('rt');
    const rwInput = document.getElementById('rw');

    [rtInput, rwInput].forEach(input => {
        if (!input) return;
        
        input.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 3 characters
            if (this.value.length > 3) {
                this.value = this.value.slice(0, 3);
            }
        });

        input.addEventListener('blur', function(e) {
            // Pad with zeros if needed and has value
            if (this.value.length > 0 && this.value.length < 3) {
                this.value = this.value.padStart(3, '0');
            }
            validateAlamatFields();
        });

        input.addEventListener('change', validateAlamatFields);
    });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize address dropdowns
    initAddressDropdowns();
    
    // Setup RT/RW formatting
    setupRTRWFormatting();
    
    // Initialize validation
    validateAlamatFields();
    
    // Setup detail alamat validation
    const detailAlamatInput = document.getElementById('detail_alamat');
    if (detailAlamatInput) {
        detailAlamatInput.addEventListener('input', validateAlamatFields);
        detailAlamatInput.addEventListener('change', validateAlamatFields);
    }
});

// Ensure map initialization when Google Maps API is loaded
window.initMap = initMap;