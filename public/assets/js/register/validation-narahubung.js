function validateNarahubungFields() {
    const fields = ["nama", "email", "no_telp"];

    const errorFields = [
        "nama-narahubung-error",
        "email-narahubung-error",
        "no_telp-narahubung-error",
    ];

    function check() {
        const allFilled = fields.every((id) => {
            const el = document.getElementById(id);
            return el && el.value.trim() !== "";
        });

        const noErrors = errorFields.every((id) => {
            const err = document.getElementById(id);
            return (
                err &&
                (err.textContent.trim() === "" ||
                    err.classList.contains("hidden"))
            );
        });

        const isValid = allFilled && noErrors;
        nextBtn.disabled = !isValid;

        if (!isValid) {
            nextBtn.classList.add(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
            nextBtn.classList.remove("bg-[#ED0226]", "text-white");
        } else {
            nextBtn.classList.remove(
                "bg-gray-300",
                "text-gray-500",
                "cursor-not-allowed"
            );
            nextBtn.classList.add("bg-[#ED0226]", "text-white");
        }
    }

    fields.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener("input", check);
    });

    errorFields.forEach((id) => {
        const el = document.getElementById(id.replace("-error", ""));
        if (el) el.addEventListener("input", check);
    });

    check();
}

document.addEventListener("DOMContentLoaded", validateNarahubungFields);

function hasSequentialOrSameNumbers(str) {
    for (let i = 0; i < str.length - 3; i++) {
        let num1 = parseInt(str[i]);
        let num2 = parseInt(str[i + 1]);
        let num3 = parseInt(str[i + 2]);
        let num4 = parseInt(str[i + 3]);

        if (num1 === num2 && num2 === num3 && num3 === num4) return true;
        if (num1 + 1 === num2 && num2 + 1 === num3 && num3 + 1 === num4)
            return true;
        if (num1 - 1 === num2 && num2 - 1 === num3 && num3 - 1 === num4)
            return true;
    }
    return false;
}

const namaNarahubung = document.getElementById("nama");
const emailNarahubung = document.getElementById("email");
const noTelpNarahubung = document.getElementById("no_telp");

const namaNarahubungError = document.getElementById("nama-narahubung-error");
const emailNarahubungError = document.getElementById("email-narahubung-error");
const noTelpNarahubungError = document.getElementById(
    "no_telp-narahubung-error"
);

if (namaNarahubung) {
    namaNarahubung.addEventListener("input", function () {
        if (this.value.trim() === "") {
            namaNarahubungError.textContent =
                "Nama Narahubung tidak boleh kosong";
            namaNarahubungError.classList.remove("hidden");
        } else {
            namaNarahubungError.textContent = "";
            namaNarahubungError.classList.add("hidden");
        }
    });
}

if (emailNarahubung) {
    emailNarahubung.addEventListener("input", function (e) {
        const value = e.target.value.trim();
        let errorMessage = "";

        if (value === "") {
            errorMessage = "Email tidak boleh kosong.";
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            errorMessage = "Email tidak valid.";
        }

        emailNarahubungError.textContent = errorMessage;
        emailNarahubungError.classList.toggle("hidden", errorMessage === "");
        emailNarahubung.classList.toggle("border-red-500", errorMessage !== "");
    });
}

if (noTelpNarahubung) {
    noTelpNarahubung.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");

        if (rawValue.startsWith("0")) {
            rawValue = "62" + rawValue.slice(1);
        } else if (rawValue.startsWith("8")) {
            rawValue = "62" + rawValue;
        }

        const limitedValue = rawValue.substring(0, 15);
        const isValidStart = limitedValue.startsWith("62");
        const isWithinLimit = limitedValue.length <= 15;
        const hasSeqOrSame = hasSequentialOrSameNumbers(limitedValue);

        e.target.value = limitedValue;

        let errorMessage = "";

        if (!isValidStart) {
            errorMessage = "Nomor harus dimulai dengan 62.";
        } else if (!isWithinLimit) {
            errorMessage = "Nomor telepon tidak boleh lebih dari 15 digit.";
        } else if (hasSeqOrSame) {
            errorMessage =
                "Nomor tidak boleh berisi angka yang sama atau berurutan sebanyak 4 digit.";
        }

        noTelpNarahubungError.textContent = errorMessage;
        noTelpNarahubungError.classList.toggle("hidden", errorMessage === "");
        noTelpNarahubung.classList.toggle(
            "border-red-500",
            errorMessage !== ""
        );
    });
}
