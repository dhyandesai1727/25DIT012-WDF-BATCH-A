/* ============================================================
   Practical 4: JavaScript DOM Manipulation & Event Handling
   Beginner-friendly vanilla JavaScript without external libraries
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {

    // ------------------------------------------------------------
    // 1. Notification Banner Dismissal
    // ------------------------------------------------------------
    const banner = document.getElementById("notificationBanner");
    const closeBannerBtn = document.getElementById("closeBannerBtn");

    if (closeBannerBtn && banner) {
        closeBannerBtn.addEventListener("click", function () {
            banner.style.display = "none";
        });
    }

    // ------------------------------------------------------------
    // 2. Light / Dark Theme Switcher with localStorage
    // ------------------------------------------------------------
    const themeToggleBtn = document.getElementById("themeToggleBtn");
    
    // Check saved theme from previous browser session
    const savedTheme = localStorage.getItem("studenthub_theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark-mode");
        if (themeToggleBtn) themeToggleBtn.textContent = "☀️ Light Mode";
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("studenthub_theme", "dark");
                themeToggleBtn.textContent = "☀️ Light Mode";
            } else {
                localStorage.setItem("studenthub_theme", "light");
                themeToggleBtn.textContent = "🌙 Dark Mode";
            }
        });
    }

    // ------------------------------------------------------------
    // 3. Mobile Hamburger Menu Toggle
    // ------------------------------------------------------------
    const hamburgerBtn = document.getElementById("hamburgerBtn");
    const navLinks = document.getElementById("navLinks");

    if (hamburgerBtn && navLinks) {
        hamburgerBtn.addEventListener("click", function () {
            navLinks.classList.toggle("open");
        });
    }

    // ------------------------------------------------------------
    // 4. Collapsible FAQ Accordion
    // ------------------------------------------------------------
    const faqQuestions = document.querySelectorAll(".faq-question");

    faqQuestions.forEach(function (button) {
        button.addEventListener("click", function () {
            const currentItem = button.parentElement;

            // Optional: close other open items
            document.querySelectorAll(".faq-item").forEach(function (item) {
                if (item !== currentItem) {
                    item.classList.remove("active");
                }
            });

            // Toggle active state
            currentItem.classList.toggle("active");
        });
    });

    // ------------------------------------------------------------
    // 5. Image / Content Slider
    // ------------------------------------------------------------
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    const prevBtn = document.getElementById("prevSlideBtn");
    const nextBtn = document.getElementById("nextSlideBtn");
    let currentSlideIndex = 0;

    function showSlide(index) {
        if (index >= slides.length) {
            currentSlideIndex = 0;
        } else if (index < 0) {
            currentSlideIndex = slides.length - 1;
        } else {
            currentSlideIndex = index;
        }

        slides.forEach(function (slide, i) {
            slide.classList.remove("active-slide");
            if (dots[i]) dots[i].classList.remove("active-dot");
        });

        if (slides[currentSlideIndex]) {
            slides[currentSlideIndex].classList.add("active-slide");
        }
        if (dots[currentSlideIndex]) {
            dots[currentSlideIndex].classList.add("active-dot");
        }
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", function () {
            showSlide(currentSlideIndex + 1);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", function () {
            showSlide(currentSlideIndex - 1);
        });
    }

    dots.forEach(function (dot, i) {
        dot.addEventListener("click", function () {
            showSlide(i);
        });
    });

    // Auto-advance slider every 5 seconds
    setInterval(function () {
        showSlide(currentSlideIndex + 1);
    }, 5000);

    // ------------------------------------------------------------
    // 6. Modal Popup Component
    // ------------------------------------------------------------
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const modalOverlay = document.getElementById("modalOverlay");
    const modalActionBtn = document.getElementById("modalActionBtn");

    if (openModalBtn && modalOverlay) {
        openModalBtn.addEventListener("click", function () {
            modalOverlay.classList.add("open");
        });
    }

    if (closeModalBtn && modalOverlay) {
        closeModalBtn.addEventListener("click", function () {
            modalOverlay.classList.remove("open");
        });
    }

    if (modalActionBtn && modalOverlay) {
        modalActionBtn.addEventListener("click", function () {
            alert("Action confirmed successfully!");
            modalOverlay.classList.remove("open");
        });
    }

    // Close modal when clicking outside content
    if (modalOverlay) {
        modalOverlay.addEventListener("click", function (event) {
            if (event.target === modalOverlay) {
                modalOverlay.classList.remove("open");
            }
        });
    }

    // Close modal with 'Escape' key for accessibility
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && modalOverlay && modalOverlay.classList.contains("open")) {
            modalOverlay.classList.remove("open");
        }
    });

});
