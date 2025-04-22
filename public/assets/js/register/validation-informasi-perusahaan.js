function validateCompanyFields() {
    const fields = [
        "nama_perusahaan",
        "npwp_perusahaan",
        "no_telp_perusahaan",
        "username",
        "email_perusahaan",
        "password",
        "password_confirmation",
    ];

    const errorFields = [
        "telp-error",
        "npwp-error",
        "email-error",
        "password-error",
    ];

    const nextBtn = document.getElementById("nextBtn");

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

document.addEventListener("DOMContentLoaded", validateCompanyFields);

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

const phoneInput = document.getElementById("no_telp_perusahaan");
const telpError = document.getElementById("telp-error");

if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "");

        // Normalisasi ke awalan 62
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

        telpError.textContent = errorMessage;
        telpError.classList.toggle("hidden", errorMessage === "");
        phoneInput.classList.toggle("border-red-500", errorMessage !== "");
    });
}

const npwpInput = document.getElementById("npwp_perusahaan");
const npwpError = document.getElementById("npwp-error");

if (npwpInput) {
    npwpInput.addEventListener("input", function (e) {
        let rawValue = e.target.value.replace(/\D/g, "").substring(0, 16);

        let formatted = rawValue.match(/.{1,4}/g)?.join(" ") || "";
        e.target.value = formatted;

        const isValid = rawValue.length <= 16;

        if (!isValid) {
            npwpError.textContent = "NPWP harus terdiri dari 15 digit angka.";
        } else {
            npwpError.textContent = "";
        }
        npwpError?.classList.toggle("hidden", isValid);
        npwpInput.classList.toggle("border-red-500", !isValid);
    });
}

const emailInput = document.getElementById("email_perusahaan");
const emailError = document.getElementById("email-error");
if (emailInput) {
    emailInput.addEventListener("input", function (e) {
        const value = e.target.value;
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

        if (!isValid) {
            emailError.textContent = "Email tidak valid.";
        } else {
            emailError.textContent = "";
        }
        emailError?.classList.toggle("hidden", isValid);
        emailInput.classList.toggle("border-red-500", !isValid);
    });
}

const passwordInput = document.getElementById("password");
const passwordConfirmInput = document.getElementById("password_confirmation");
const passwordError = document.getElementById("password-error");

if (passwordInput && passwordConfirmInput) {
    passwordConfirmInput.addEventListener("input", function (e) {
        const value = e.target.value;
        const isValid = value === passwordInput.value;

        if (!isValid) {
            passwordError.textContent = "Password tidak cocok.";
        } else {
            passwordError.textContent = "";
        }
        passwordError?.classList.toggle("hidden", isValid);
        passwordConfirmInput.classList.toggle("border-red-500", !isValid);
    });
}

const usernameInput = document.getElementById("username");
const usernameError = document.getElementById("username-error");

if (usernameInput) {
    usernameInput.addEventListener("input", function (e) {
        const value = e.target.value;

        const isValidFormat = /^[a-zA-Z0-9_]+$/.test(value);
        const isMinLength = value.length >= 5;
        const hasNumber = /\d/.test(value);

        let errorMessage = "";

        if (!isValidFormat) {
            errorMessage =
                "Username hanya boleh mengandung huruf, angka, dan garis bawah.";
        } else if (!isMinLength) {
            errorMessage = "Username minimal harus 5 karakter.";
        } else if (!hasNumber) {
            errorMessage = "Username harus mengandung setidaknya 1 angka.";
        }

        usernameError.textContent = errorMessage;
        usernameError.classList.toggle("hidden", errorMessage === "");
        usernameInput.classList.toggle("border-red-500", errorMessage !== "");
    });
}
