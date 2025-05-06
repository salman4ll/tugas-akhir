const steps = document.querySelectorAll(".step-pane");

let currentStep = 0;

const showStep = (index) => {
    steps.forEach((step, i) => {
        step.classList.toggle("hidden", i !== index);
    });

    document.querySelectorAll("#step-indicator .step").forEach((el, i) => {
        el.classList.toggle("opacity-50", i !== index);
    });

    prevBtn.classList.toggle("hidden", index === 0);
    nextBtn.classList.toggle("hidden", index === steps.length - 1);
    submitBtn.classList.toggle("hidden", index !== steps.length - 1);

    if (index === 0) {
        validateCompanyFields();
    } else if (index === 1) {
        validateNarahubungFields();
    } else if (index === 2) {
        initAddressDropdowns();
        validateAlamatFields();
    }
};

prevBtn.addEventListener("click", () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
});

nextBtn.addEventListener("click", () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
});

showStep(currentStep);
