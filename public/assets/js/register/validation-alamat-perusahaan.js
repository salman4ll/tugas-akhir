function validateAlamatFields() {
    const fields = [
        "provinsi_id",
        "kabupaten_id",
        "kecamatan_id",
        "kelurahan_id",
    ];

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
        if (el) el.addEventListener("change", check);
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

    fetch("/provinces")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((provinsi) => {
                provinsiSelect.innerHTML += `<option value="${provinsi.id}">${provinsi.nama}</option>`;
            });
            new TomSelect(provinsiSelect, {
                placeholder: "Pilih Provinsi",
                clearButton: true,
            });
        });

    provinsiSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kabupaten_id", "kecamatan_id", "kelurahan_id");
        if (this.value) {
            kabupatenSelect.disabled = false;
            fetch(`/cities/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kabupatenSelect, data));
        }
    });

    kabupatenSelect.addEventListener("change", function () {
        resetAndDisableDropdowns("kecamatan_id", "kelurahan_id");
        if (this.value) {
            kecamatanSelect.disabled = false;
            fetch(`/districts/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kecamatanSelect, data));
        }
    });

    kecamatanSelect.addEventListener("change", function () {
        kelurahanSelect.disabled = true;
        if (this.value) {
            kelurahanSelect.disabled = false;
            fetch(`/subdistricts/${this.value}`)
                .then((res) => res.json())
                .then((data) => populateDropdown(kelurahanSelect, data));
        }
    });
}

function resetAndDisableDropdowns(...ids) {
    ids.forEach((id) => {
        const select = document.getElementById(id);
        if (select) {
            select.innerHTML = `<option value="">Pilih ${select.name}</option>`;
            select.disabled = true;
        }
    });
}

function populateDropdown(selectElement, data) {
    data.forEach((item) => {
        selectElement.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
    });
    new TomSelect(selectElement, {
        placeholder: `Pilih ${selectElement.name}`,
        clearButton: true,
    });
}
