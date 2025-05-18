let tomSelectInstances = {};

function initAddressDropdowns() {
    const provinsiSelect = document.getElementById("provinsi_id");
    const kabupatenSelect = document.getElementById("kabupaten_id");
    const kecamatanSelect = document.getElementById("kecamatan_id");
    const kelurahanSelect = document.getElementById("kelurahan_id");
    const kodePos = document.getElementById("kode_pos");

    console.log("Address dropdowns initialized.");

    if (
        !provinsiSelect ||
        !kabupatenSelect ||
        !kecamatanSelect ||
        !kelurahanSelect
    )
        return;

    resetAndDisableDropdowns("kabupaten_id", "kecamatan_id", "kelurahan_id");

    fetch("/provinces")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((provinsi) => {
                provinsiSelect.innerHTML += `<option value="${provinsi.id}">${provinsi.nama}</option>`;
            });

            initTomSelect(provinsiSelect, "Pilih Provinsi");
        });

    provinsiSelect.addEventListener("change", function () {
        resetAndDisableDropdowns(
            "kabupaten_id",
            "kecamatan_id",
            "kelurahan_id"
        );
        if (this.value) {
            kabupatenSelect.disabled = false;
            fetch(`/cities/${this.value}`)
                .then((res) => res.json())
                .then((data) =>
                    populateDropdown(kabupatenSelect, data, "Pilih kabupaten")
                );
        }
    });

    kabupatenSelect.addEventListener("change", function () {
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
        kabupaten_id: "Pilih kabupaten",
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

const accessToken = window.Laravel?.auth_token;
const perangkatId = window.Laravel?.perangkat_id;

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

document.addEventListener("DOMContentLoaded", () => {
    // DOM Elements
    const radioAmbilDitempat = document.getElementById("ambil_ditempat");
    const checkboxAmbilDiTempat = document.getElementById(
        "checkbox_ambil_ditempat"
    );
    const ambilDitempatWrapper = document.getElementById(
        "ambilDitempatWrapper"
    );
    const alamatPengiriman = document.getElementById("alamatPengiriman");
    const isCpUserCheckbox = document.getElementById("isCpUser");
    const isAddressUserCheckbox = document.getElementById("isAddressUser");
    const checkoutButton = document.getElementById("button-checkout");

    const penerimaFields = ["nama", "no_telp", "email"];
    const alamatFields = [
        "provinsi_id",
        "kabupaten_id",
        "kecamatan_id",
        "kelurahan_id",
        "rt",
        "rw",
        "kode_pos",
        "detail",
        "latitude",
        "longitude",
    ];

    // Util Functions
    const toggleRequired = (fields, required) => {
        fields.forEach((id) => {
            const el = document.getElementById(id);
            if (el) {
                el.required = required;
            }
        });
    };

    const toggleDisplay = (fields, show) => {
        fields.forEach((id) => {
            const el = document.getElementById(id);
            if (el?.closest("div")) {
                el.closest("div").style.display = show ? "flex" : "none";
            }
        });
    };

    const isFieldsFilled = (fields) =>
        fields.every((id) => {
            const el = document.getElementById(id);
            return el && el.value.trim() !== "";
        });

    const isEkspedisiSelected = () =>
        [...document.querySelectorAll('input[name="ekspedisi"]')].some(
            (input) => input.checked
        );

    const validateForm = () => {
        const cpValid =
            isCpUserCheckbox.checked || isFieldsFilled(penerimaFields);
        const addressValid =
            isAddressUserCheckbox.checked ||
            checkboxAmbilDiTempat.checked ||
            isFieldsFilled(alamatFields);
        const ekspedisiValid = isEkspedisiSelected();

        checkoutButton.disabled = !(cpValid && addressValid && ekspedisiValid);
    };

    // Handle alamat pengiriman visibility & required
    const updateAlamatPengirimanDisplay = () => {
        const selected = document.querySelector(
            'input[name="ekspedisi"]:checked'
        );
        const isAmbilDitempat = selected?.id === "ambil_ditempat";

        ambilDitempatWrapper.style.display = isAmbilDitempat ? "flex" : "none";
        alamatPengiriman.style.display = isAmbilDitempat ? "none" : "grid";
        checkboxAmbilDiTempat.checked = isAmbilDitempat;

        toggleRequired(
            alamatFields,
            !isAmbilDitempat && !isAddressUserCheckbox.checked
        );
        validateForm();
    };

    // Event Handlers
    const handleCpCheckboxChange = () => {
        const required = !isCpUserCheckbox.checked;
        toggleRequired(penerimaFields, required);
        toggleDisplay(penerimaFields, required);
        validateForm();
    };

    const handleAddressCheckboxChange = () => {
        const shouldShow = !isAddressUserCheckbox.checked;
        toggleRequired(
            alamatFields,
            shouldShow && !checkboxAmbilDiTempat.checked
        );
        toggleDisplay(alamatFields, shouldShow);

        const mapSection = document
            .querySelector("#main-map")
            ?.closest(".relative");
        if (mapSection)
            mapSection.style.display = isAddressUserCheckbox.checked
                ? "none"
                : "flex";

        if (
            isAddressUserCheckbox.checked &&
            window.Laravel?.latitude &&
            window.Laravel?.longitude
        ) {
            panggilApiCourier(
                window.Laravel.latitude,
                window.Laravel.longitude
            );
        }

        validateForm();
    };

    const attachInputListeners = (fields) => {
        fields.forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.addEventListener("input", validateForm);
        });
    };

    const setupEkspedisiListeners = () => {
        document
            .querySelectorAll('input[name="ekspedisi"]')
            .forEach((radio) =>
                radio.addEventListener("change", updateAlamatPengirimanDisplay)
            );
    };

    // Manual checkbox for Ambil di Tempat
    checkboxAmbilDiTempat.addEventListener("change", () => {
        const isChecked = checkboxAmbilDiTempat.checked;
        alamatPengiriman.style.display = isChecked ? "none" : "grid";
        ambilDitempatWrapper.style.display = isChecked ? "flex" : "none";
        radioAmbilDitempat.checked = isChecked;
        handleAddressCheckboxChange();
    });

    // Courier API fetch
    const panggilApiCourier = (lat, lng) => {
        const button = document.getElementById("saveLocationButton");
        const buttonText = button.querySelector(".button-text");
        const spinner = button.querySelector(".spinner");

        button.disabled = true;
        buttonText.textContent = "Memuat...";
        spinner.classList.remove("hidden");

        fetch("/api/courier", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + window.Laravel?.auth_token,
            },
            body: JSON.stringify({
                destination_latitude: lat,
                destination_longitude: lng,
                device_id: perangkatId,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                const courierList = document.getElementById("courierList");
                courierList.innerHTML = "";

                (data?.data || []).forEach((courier) => {
                    const courierId = `${courier.id}`;
                    const price = new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        maximumFractionDigits: 0,
                    }).format(courier.price);

                    courierList.insertAdjacentHTML(
                        "beforeend",
                        `
                        <label for="${courierId}" class="flex flex-row justify-between items-start gap-4 cursor-pointer p-2 rounded hover:bg-gray-50">
                            <div class="flex flex-row gap-3">
                                <input type="radio" name="ekspedisi" id="${courierId}" value="${courierId}" data-price="${courier.price}">
                                <div class="flex flex-col">
                                    <label for="${courierId}" class="font-medium text-base">${courier.courier_name} - ${courier.courier_service_name}</label>
                                    <label for="${courierId}" class="text-sm text-gray-500">Estimasi ${courier.duration}</label>
                                </div>
                            </div>
                            <div class="text-md font-bold">${price}</div>
                        </label>
                    `
                    );
                });

                document.getElementById("mapModal").classList.add("hidden");
                setupEkspedisiListeners();
                updateAlamatPengirimanDisplay();
            })
            .finally(() => {
                button.disabled = false;
                buttonText.textContent = "Simpan Lokasi";
                spinner.classList.add("hidden");
            });
    };

    // Init
    attachInputListeners(penerimaFields);
    attachInputListeners(alamatFields);
    setupEkspedisiListeners();
    handleCpCheckboxChange();
    handleAddressCheckboxChange();
    updateAlamatPengirimanDisplay();
    validateForm();

    // Trigger change if checkbox is pre-checked
    if (isAddressUserCheckbox.checked) {
        isAddressUserCheckbox.dispatchEvent(new Event("change"));
    }

    // Checkbox event listeners
    isCpUserCheckbox.addEventListener("change", handleCpCheckboxChange);
    isAddressUserCheckbox.addEventListener(
        "change",
        handleAddressCheckboxChange
    );
});

function formatCurrency(value) {
    return (
        "IDR" +
        new Intl.NumberFormat("id-ID", {
            style: "decimal",
            maximumFractionDigits: 0,
        }).format(value)
    );
}

function calculateTotal() {
    const perangkatPrice =
        parseInt(document.getElementById("perangkatPrice").dataset.price) || 0;
    const depositPrice =
        parseInt(document.getElementById("depositPrice").dataset.price) || 0;
    const courierPriceText = document
        .getElementById("selectedCourierPrice")
        .textContent.replace(/[^\d]/g, "");
    const courierPrice = parseInt(courierPriceText) || 0;
    const statusPerusahaan = window.Laravel?.status_perusahaan;
    console.log("Status Perusahaan:", statusPerusahaan);

    let totalBiaya = perangkatPrice + depositPrice + courierPrice;
    console.log("Total Biaya:", totalBiaya);
    let ppn = Math.round(totalBiaya * 0.11);
    console.log("PPN:", ppn);
    let totalKeseluruhan = totalBiaya + ppn;
    let totalPembayaran = totalKeseluruhan;
    let ringkasanTotalBiaya = totalBiaya;
    let ringkasanTotalKeseluruhan = totalKeseluruhan;
    let ringkasanTotalPembayaran = totalPembayaran;

    // Sesuaikan perhitungan berdasarkan status_perusahaan
    if (statusPerusahaan === "1") {
        // Wajib Pungut (WAPU)
        const pph = (depositPrice + courierPrice) * 0.02;
        ringkasanTotalPembayaran = ringkasanTotalBiaya - pph;
        document.getElementById("pph").textContent = `- ${formatCurrency(pph)}`;
    } else if (statusPerusahaan === "2") {
        // Non-Wajib Pungut (non-WAPU) tanpa potongan PPH 23
        ringkasanTotalKeseluruhan = totalKeseluruhan;
        ringkasanTotalPembayaran = totalKeseluruhan;
    } else if (statusPerusahaan === "3") {
        // Non-Wajib Pungut (non-WAPU) potong PPH 23
        const pph = (depositPrice + courierPrice) * 0.02;
        ringkasanTotalKeseluruhan = totalKeseluruhan;
        ringkasanTotalPembayaran = ringkasanTotalKeseluruhan - pph;
        document.getElementById("pph").textContent = `- ${formatCurrency(pph)}`;
    }

    // Update elemen-elemen HTML untuk ringkasan
    document.getElementById("totalBiaya").textContent =
        formatCurrency(totalBiaya);
    if (statusPerusahaan === "1") {
        document.getElementById("ringkasanTotalBiaya").textContent =
            formatCurrency(ringkasanTotalBiaya);
    }
    if (statusPerusahaan === "2" || statusPerusahaan === "3") {
        document.getElementById("ringkasanTotalKeseluruhan").textContent =
            formatCurrency(ringkasanTotalKeseluruhan);
    }
    document.getElementById("totalKeseluruhan").textContent =
        formatCurrency(totalKeseluruhan);
    document.getElementById("ringkasanTotalPembayaran").textContent =
        formatCurrency(ringkasanTotalPembayaran);
    document.getElementById("ppn").textContent = formatCurrency(ppn);
}

function updateCourierPriceListeners() {
    document.querySelectorAll('input[name="ekspedisi"]').forEach((input) => {
        input.addEventListener("change", function () {
            const selectedPrice = parseInt(this.dataset.price) || 0;
            document.getElementById("selectedCourierPrice").textContent =
                formatCurrency(selectedPrice);
            calculateTotal();
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    updateCourierPriceListeners();
    calculateTotal();
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
