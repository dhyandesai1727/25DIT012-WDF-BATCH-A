/* ============================================================
   Practical 5: JavaScript Registration Form Validation
   Regex-based real-time and submit validation + strength meter
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("studentRegisterForm");
    const successBanner = document.getElementById("successBanner");

    // Form Fields
    const fullName = document.getElementById("fullName");
    const email = document.getElementById("email");
    const mobile = document.getElementById("mobile");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const course = document.getElementById("course");
    const year = document.getElementById("year");
    const terms = document.getElementById("terms");

    // Error Message Spans
    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const mobileError = document.getElementById("mobileError");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmPasswordError");
    const courseError = document.getElementById("courseError");
    const yearError = document.getElementById("yearError");
    const genderError = document.getElementById("genderError");
    const termsError = document.getElementById("termsError");

    // Password Strength UI
    const strengthFill = document.getElementById("strengthFill");
    const strengthText = document.getElementById("strengthText");

    // ------------------------------------------------------------
    // Regular Expression Patterns
    // ------------------------------------------------------------
    const nameRegex = /^[A-Za-z\s]{3,40}$/;
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const mobileRegex = /^[6-9]\d{9}$/;

    // ------------------------------------------------------------
    // Helper Functions
    // ------------------------------------------------------------
    function setError(input, errorElement, message) {
        if (input) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
        }
        errorElement.textContent = message;
    }

    function setSuccess(input, errorElement) {
        if (input) {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
        }
        errorElement.textContent = "";
    }

    // ------------------------------------------------------------
    // Individual Validation Rules
    // ------------------------------------------------------------
    function validateName() {
        const val = fullName.value.trim();
        if (val === "") {
            setError(fullName, nameError, "Full name is required.");
            return false;
        } else if (!nameRegex.test(val)) {
            setError(fullName, nameError, "Name must contain only letters & spaces (min 3 chars).");
            return false;
        } else {
            setSuccess(fullName, nameError);
            return true;
        }
    }

    function validateEmail() {
        const val = email.value.trim();
        if (val === "") {
            setError(email, emailError, "Email address is required.");
            return false;
        } else if (!emailRegex.test(val)) {
            setError(email, emailError, "Please enter a valid email (e.g., student@charusat.edu.in).");
            return false;
        } else {
            setSuccess(email, emailError);
            return true;
        }
    }

    function validateMobile() {
        const val = mobile.value.trim();
        if (val === "") {
            setError(mobile, mobileError, "Mobile number is required.");
            return false;
        } else if (!mobileRegex.test(val)) {
            setError(mobile, mobileError, "Enter a valid 10-digit mobile number starting with 6-9.");
            return false;
        } else {
            setSuccess(mobile, mobileError);
            return true;
        }
    }

    function checkPasswordStrength(val) {
        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[!@#$%^&*]/.test(val)) score++;

        if (val.length === 0) {
            strengthFill.style.width = "0%";
            strengthFill.style.backgroundColor = "transparent";
            strengthText.textContent = "";
            return 0;
        } else if (score <= 2) {
            strengthFill.style.width = "30%";
            strengthFill.style.backgroundColor = "#dc2626"; // Red
            strengthText.textContent = "Weak password";
            strengthText.style.color = "#dc2626";
            return 1;
        } else if (score <= 4) {
            strengthFill.style.width = "65%";
            strengthFill.style.backgroundColor = "#f59e0b"; // Orange
            strengthText.textContent = "Medium password";
            strengthText.style.color = "#d97706";
            return 2;
        } else {
            strengthFill.style.width = "100%";
            strengthFill.style.backgroundColor = "#16a34a"; // Green
            strengthText.textContent = "Strong password";
            strengthText.style.color = "#16a34a";
            return 3;
        }
    }

    function validatePassword() {
        const val = password.value;
        const strength = checkPasswordStrength(val);

        if (val === "") {
            setError(password, passwordError, "Password is required.");
            return false;
        } else if (val.length < 6) {
            setError(password, passwordError, "Password must be at least 6 characters long.");
            return false;
        } else {
            setSuccess(password, passwordError);
            return true;
        }
    }

    function validateConfirmPassword() {
        const pVal = password.value;
        const cpVal = confirmPassword.value;

        if (cpVal === "") {
            setError(confirmPassword, confirmPasswordError, "Please confirm your password.");
            return false;
        } else if (pVal !== cpVal) {
            setError(confirmPassword, confirmPasswordError, "Passwords do not match.");
            return false;
        } else {
            setSuccess(confirmPassword, confirmPasswordError);
            return true;
        }
    }

    function validateCourse() {
        if (course.value === "") {
            setError(course, courseError, "Please select an academic branch.");
            return false;
        } else {
            setSuccess(course, courseError);
            return true;
        }
    }

    function validateYear() {
        if (year.value === "") {
            setError(year, yearError, "Please select your year of study.");
            return false;
        } else {
            setSuccess(year, yearError);
            return true;
        }
    }

    function validateGender() {
        const checkedGender = document.querySelector('input[name="gender"]:checked');
        if (!checkedGender) {
            genderError.textContent = "Please select your gender.";
            return false;
        } else {
            genderError.textContent = "";
            return true;
        }
    }

    function validateTerms() {
        if (!terms.checked) {
            termsError.textContent = "You must agree to the terms to register.";
            return false;
        } else {
            termsError.textContent = "";
            return true;
        }
    }

    // ------------------------------------------------------------
    // Real-Time Event Listeners (Intermediate Requirement)
    // ------------------------------------------------------------
    fullName.addEventListener("input", validateName);
    email.addEventListener("input", validateEmail);
    mobile.addEventListener("input", validateMobile);
    password.addEventListener("input", function () {
        validatePassword();
        if (confirmPassword.value.length > 0) validateConfirmPassword();
    });
    confirmPassword.addEventListener("input", validateConfirmPassword);
    course.addEventListener("change", validateCourse);
    year.addEventListener("change", validateYear);
    terms.addEventListener("change", validateTerms);
    document.querySelectorAll('input[name="gender"]').forEach(function (radio) {
        radio.addEventListener("change", validateGender);
    });

    // ------------------------------------------------------------
    // Form Submission Handler
    // ------------------------------------------------------------
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isMobileValid = validateMobile();
        const isPasswordValid = validatePassword();
        const isConfirmValid = validateConfirmPassword();
        const isCourseValid = validateCourse();
        const isYearValid = validateYear();
        const isGenderValid = validateGender();
        const isTermsValid = validateTerms();

        if (
            isNameValid &&
            isEmailValid &&
            isMobileValid &&
            isPasswordValid &&
            isConfirmValid &&
            isCourseValid &&
            isYearValid &&
            isGenderValid &&
            isTermsValid
        ) {
            successBanner.style.display = "block";
            successBanner.textContent = "🎉 Registration successful! Welcome to StudentHub, " + fullName.value + ".";
            form.reset();
            strengthFill.style.width = "0%";
            strengthText.textContent = "";

            // Clear green borders after 4 seconds
            setTimeout(function () {
                document.querySelectorAll(".is-valid").forEach(function (el) {
                    el.classList.remove("is-valid");
                });
            }, 4000);
        } else {
            successBanner.style.display = "none";
        }
    });

});
