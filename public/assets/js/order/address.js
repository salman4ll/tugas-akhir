let tomSelectInstances = {};

function initAddressDropdowns() {
    const provinsiSelect = document.getElementById("provinsi_id");
    const kotaSelect = document.getElementById("kota_id");
    const kecamatanSelect = document.getElementById("kecamatan_id");
    const kelurahanSelect = document.getElementById("kelurahan_id");
    const kodePos = document.getElementById("kode_pos");

    console.log("Address dropdowns initialized.");

    if (!provinsiSelect || !kotaSelect || !kecamatanSelect || !kelurahanSelect)
        return;

    resetAndDisableDropdowns("kota_id", "kecamatan_id", "kelurahan_id");

    fetch("/provinces")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((provinsi) => {
                provinsiSelect.innerHTML += `<option value="${provinsi.id}">${provinsi.nama}</option>`;
            });

            initTomSelect(provinsiSelect, "Pilih Provinsi");
        });

    provinsiSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kota_id", "kecamatan_id", "kelurahan_id");
        if (this.value) {
            kotaSelect.disabled = false;
            fetch(`/cities/${this.value}`)
                .then((res) => res.json())
                .then((data) =>
                    populateDropdown(kotaSelect, data, "Pilih Kota")
                );
        }
    });

    kotaSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kecamatan_id", "kelurahan_id");
        if (this.value) {
            kecamatanSelect.disabled = false;
            fetch(`/districts/${this.value}`)
                .then((res) => res.json())
                .then((data) =>
                    populateDropdown(kecamatanSelect, data, "Pilih Kecamatan")
                );
        }
    });

    kecamatanSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kelurahan_id");
        if (this.value) {
            kelurahanSelect.disabled = false;
            fetch(`/subdistricts/${this.value}`)
                .then((res) => res.json())
                .then((data) => {
                    populateDropdown(kelurahanSelect, data, "Pilih Kelurahan");

                    kelurahanSelect.addEventListener("change", function () {
                        const selectedId = this.value;
                        const selectedKelurahan = data.find(
                            (item) => item.id == selectedId
                        );

                        if (selectedKelurahan) {
                            kodePos.value = selectedKelurahan.zip_code || "";
                        } else {
                            kodePos.value = "";
                        }
                    });
                });
        }
    });
}

function resetAndDisableDropdowns(...ids) {
    const placeholders = {
        kota_id: "Pilih Kota",
        kecamatan_id: "Pilih Kecamatan",
        kelurahan_id: "Pilih Kelurahan",
    };

    ids.forEach((id) => {
        const select = document.getElementById(id);
        if (select) {
            if (tomSelectInstances[id]) {
                tomSelectInstances[id].destroy();
                delete tomSelectInstances[id];
            }
            const placeholder = placeholders[id] || "Pilih";
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }
    });
}

function populateDropdown(selectElement, data, placeholder = "") {
    const id = selectElement.id;

    if (tomSelectInstances[id]) {
        tomSelectInstances[id].destroy();
        delete tomSelectInstances[id];
    }

    selectElement.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach((item) => {
        selectElement.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
    });

    initTomSelect(selectElement, placeholder);
}

function initTomSelect(selectElement, placeholder = "") {
    const id = selectElement.id;
    tomSelectInstances[id] = new TomSelect(selectElement, {
        placeholder: placeholder,
        plugins: ["clear_button"],
        sortField: {
            field: "text",
            direction: "asc",
        },
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initAddressDropdowns();
    console.log("Address dropdowns initialized.");
});

let mainMap;
let modalMap;
let mainMarker;
let modalMarker;
let selectedLatLng;

document
    .getElementById("openModalButton")
    .addEventListener("click", function () {
        document.getElementById("mapModal").classList.remove("hidden");
        document.getElementById("mapModal").classList.add("flex");
        initModalMap();
    });

document.getElementById("cancelButton").addEventListener("click", function () {
    document.getElementById("mapModal").classList.add("hidden");
});

document
    .getElementById("saveLocationButton")
    .addEventListener("click", function () {
        if (selectedLatLng) {
            const lat = selectedLatLng.lat();
            const lng = selectedLatLng.lng();

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            mainMap.setCenter(selectedLatLng);
            mainMarker.setPosition(selectedLatLng);
        }
        document.getElementById("mapModal").classList.add("hidden");
    });

function initMap() {
    const mainOptions = {
        center: { lat: -6.542589755264702, lng: 106.77215270271967 },
        zoom: 13,
    };

    mainMap = new google.maps.Map(
        document.getElementById("main-map"),
        mainOptions
    );

    mainMarker = new google.maps.Marker({
        position: mainOptions.center,
        map: mainMap,
        draggable: true,
    });

    mainMarker.addListener("dragend", function () {
        selectedLatLng = mainMarker.getPosition();
    });
}

function initModalMap() {
    const defaultCenter = { lat: -6.846066390644554, lng: 106.75876386931151 };

    modalMap = new google.maps.Map(document.getElementById("modalMap"), {
        center: defaultCenter,
        zoom: 13,
    });

    modalMarker = new google.maps.Marker({
        position: defaultCenter,
        map: modalMap,
        draggable: true,
    });

    selectedLatLng = modalMarker.getPosition();

    modalMap.addListener("click", function (e) {
        modalMarker.setPosition(e.latLng);
        selectedLatLng = e.latLng;
    });

    const input = document.getElementById("searchInput");
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", modalMap);

    autocomplete.addListener("place_changed", function () {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) {
            alert("Lokasi tidak ditemukan!");
            return;
        }

        modalMap.panTo(place.geometry.location);
        modalMap.setZoom(15);

        modalMarker.setPosition(place.geometry.location);
        selectedLatLng = place.geometry.location;
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initMap();
});
