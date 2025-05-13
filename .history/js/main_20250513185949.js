// Theme toggle functionality
function initThemeToggle() {
  const themeToggle = document.getElementById("theme-toggle");

  // Check for saved theme preference or prefer-color-scheme
  const savedTheme = localStorage.getItem("theme");
  const prefersDark =
    window.matchMedia &&
    window.matchMedia("(prefers-color-scheme: dark)").matches;

  // Apply theme
  if (savedTheme === "dark" || (!savedTheme && prefersDark)) {
    document.documentElement.setAttribute("data-theme", "dark");
  }

  // Toggle theme
  themeToggle.addEventListener("click", function () {
    const currentTheme = document.documentElement.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";

    document.documentElement.setAttribute("data-theme", newTheme);
    localStorage.setItem("theme", newTheme);
  });
}

// Create animated particles
function createParticles() {
  const container = document.getElementById("particles-container");
  if (!container) return;

  const particleCount = 15;

  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement("div");
    particle.className = "particle";

    // Random position
    const left = Math.random() * 100;
    const top = Math.random() * 100;

    particle.style.left = `${left}%`;
    particle.style.top = `${top}%`;

    // Random animation delay and duration
    const delay = Math.random() * 10;
    const duration = 5 + Math.random() * 10;

    particle.style.animation = `particleAnimation ${duration}s ease-in-out ${delay}s infinite`;

    container.appendChild(particle);
  }
}

// Update active nav item based on scroll position
function updateActiveNavItem() {
  const sections = document.querySelectorAll("section[id]");
  const navLinks = document.querySelectorAll(".nav-link");

  const scrollPosition = window.scrollY + 100; // Offset for navbar height

  sections.forEach((section) => {
    const sectionTop = section.offsetTop;
    const sectionHeight = section.offsetHeight;
    const sectionId = section.getAttribute("id");

    if (
      scrollPosition >= sectionTop &&
      scrollPosition < sectionTop + sectionHeight
    ) {
      navLinks.forEach((link) => {
        link.classList.remove("active");
        if (link.getAttribute("href") === `#${sectionId}`) {
          link.classList.add("active");
        }
      });
    }
  });
}

// Animate progress bars
function animateProgressBars() {
  const skillsSection = document.querySelector(".skills-section");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          document.querySelectorAll(".progress-bar").forEach((bar) => {
            const width = bar.getAttribute("aria-valuenow") + "%";
            bar.style.width = width;
          });
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  if (skillsSection) {
    observer.observe(skillsSection);
  }
}

// Initialize EmailJS
(function () {
  emailjs.init("YOUR_ACTUAL_PUBLIC_KEY");
})();

// Document ready function with all initializations
$(document).ready(function () {
  // Initialize theme toggle
  initThemeToggle();

  // Create particles
  createParticles();

  // Animate progress bars
  animateProgressBars();

  // Initialize AOS
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: false,
    mirror: true,
    anchorPlacement: "top-bottom",
  });

  // Mobile menu handling
  let isMenuOpen = false;

  $(".navbar-toggler").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    if (isMenuOpen) {
      // Close menu
      isMenuOpen = false;
      $(".navbar-collapse").removeClass("show");
      $(this).attr("aria-expanded", "false");
    } else {
      // Open menu
      isMenuOpen = true;
      $(".navbar-collapse").addClass("show");
      $(this).attr("aria-expanded", "true");
    }
  });

  // Close mobile menu when clicking outside
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".navbar").length && isMenuOpen) {
      isMenuOpen = false;
      $(".navbar-collapse").removeClass("show");
      $(".navbar-toggler").attr("aria-expanded", "false");
    }
  });

  // Close mobile menu when clicking on a nav link
  $(".nav-link").on("click", function () {
    isMenuOpen = false;
    $(".navbar-collapse").removeClass("show");
    $(".navbar-toggler").attr("aria-expanded", "false");
  });

  // Prevent clicks inside navbar from closing the menu
  $(".navbar").on("click", function (e) {
    e.stopPropagation();
  });

  // Typing animation for hero section
  if ($(".typing-text").length) {
    const text = $(".typing-text").data("text").split(",");
    const typed = new Typed(".typing-text", {
      strings: text,
      typeSpeed: 80,
      backSpeed: 40,
      backDelay: 3000,
      loop: true,
    });
  }

  // Work filter functionality
  $(".work-filter button").on("click", function () {
    const filter = $(this).data("filter");

    // Update active button
    $(".work-filter button").removeClass("active");
    $(this).addClass("active");

    if (filter === "all") {
      $(".work-item").show();
    } else {
      $(".work-item").hide();
      $(`.work-item[data-category="${filter}"]`).show();
    }
  });

  // Form validation and submission
  const contactForm = $("#contactForm");
  const submitBtn = $("#submitBtn");
  const btnText = submitBtn.find(".btn-text");
  const spinner = submitBtn.find(".spinner-border");
  const formStatus = $("#formStatus");
  const charCount = $("#charCount");

  // Real-time validation functions
  function validateName(input) {
    const value = $(input).val();
    const minLength = 3;
    const maxLength = 50;

    if (!value) {
      return "Nama lengkap harus diisi";
    } else if (value.length < minLength) {
      return `Nama minimal ${minLength} karakter`;
    } else if (value.length > maxLength) {
      return `Nama maksimal ${maxLength} karakter`;
    }
    return "";
  }

  function validateEmail(input) {
    const value = $(input).val();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!value) {
      return "Email harus diisi";
    } else if (!emailRegex.test(value)) {
      return "Format email tidak valid";
    }
    return "";
  }

  function validatePhone(input) {
    const value = $(input).val();
    const phoneRegex = /^[0-9]{10,13}$/;

    if (!value) {
      return "Nomor handphone harus diisi";
    } else if (!phoneRegex.test(value)) {
      return "Nomor handphone harus berupa angka (10-13 digit)";
    }
    return "";
  }

  function validateMessage(input) {
    const value = $(input).val();
    const minLength = 10;
    const maxLength = 500;

    if (!value) {
      return "Pesan harus diisi";
    } else if (value.length < minLength) {
      return `Pesan minimal ${minLength} karakter`;
    } else if (value.length > maxLength) {
      return `Pesan maksimal ${maxLength} karakter`;
    }
    return "";
  }

  // Real-time validation on input
  $("#name").on("input", function () {
    const error = validateName(this);
    const feedback = $(this).siblings(".invalid-feedback");

    if (error) {
      $(this).addClass("is-invalid").removeClass("is-valid");
      feedback.text(error).show();
    } else {
      $(this).addClass("is-valid").removeClass("is-invalid");
      feedback.hide();
    }
  });

  $("#email").on("input", function () {
    const error = validateEmail(this);
    const feedback = $(this).siblings(".invalid-feedback");

    if (error) {
      $(this).addClass("is-invalid").removeClass("is-valid");
      feedback.text(error).show();
    } else {
      $(this).addClass("is-valid").removeClass("is-invalid");
      feedback.hide();
    }
  });

  $("#phone").on("input", function () {
    const error = validatePhone(this);
    const feedback = $(this).siblings(".invalid-feedback");

    if (error) {
      $(this).addClass("is-invalid").removeClass("is-valid");
      feedback.text(error).show();
    } else {
      $(this).addClass("is-valid").removeClass("is-invalid");
      feedback.hide();
    }
  });

  $("#message").on("input", function () {
    const error = validateMessage(this);
    const feedback = $(this).siblings(".invalid-feedback");
    const currentLength = $(this).val().length;

    // Update character counter
    charCount.text(currentLength);
    if (currentLength >= 500) {
      charCount.addClass("text-danger");
    } else {
      charCount.removeClass("text-danger");
    }

    if (error) {
      $(this).addClass("is-invalid").removeClass("is-valid");
      feedback.text(error).show();
    } else {
      $(this).addClass("is-valid").removeClass("is-invalid");
      feedback.hide();
    }
  });

  // Form submission validation
  contactForm.on("submit", function (e) {
    e.preventDefault();

    // Validate all fields
    const nameError = validateName($("#name")[0]);
    const emailError = validateEmail($("#email")[0]);
    const phoneError = validatePhone($("#phone")[0]);
    const messageError = validateMessage($("#message")[0]);

    // If any field has an error, don't submit
    if (nameError || emailError || phoneError || messageError) {
      return false;
    }

    // Get form data
    const formData = {
      from_name: $("#name").val(),
      from_email: $("#email").val(),
      phone_number: $("#phone").val(),
      message: $("#message").val(),
      to_email: "fdiraf77@gmail.com",
    };

    // Disable submit button and show spinner
    submitBtn.prop("disabled", true);
    btnText.text("Mengirim...");
    spinner.removeClass("d-none");
    formStatus.html("");

    // Send email using EmailJS
    emailjs
      .send("service_fed5lpw", "template_gbepq5g", formData)
      .then(function () {
        // Success
        formStatus.html(
          '<div class="alert alert-success">Pesan berhasil dikirim! Saya akan segera menghubungi Anda.</div>'
        );
        contactForm[0].reset();
        charCount.text("0");

        // Reset validation states
        $(".form-control").removeClass("is-valid is-invalid");
        $(".invalid-feedback").hide();
      })
      .catch(function (error) {
        // Error
        formStatus.html(
          '<div class="alert alert-danger">Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.</div>'
        );
        console.error("EmailJS error:", error);
      })
      .finally(function () {
        // Re-enable submit button and hide spinner
        submitBtn.prop("disabled", false);
        btnText.text("Kirim Pesan");
        spinner.addClass("d-none");
      });
  });

  // Smooth scrolling for anchor links
  $('a[href^="#"]').on("click", function (e) {
    e.preventDefault();

    const target = $(this.getAttribute("href"));
    if (target.length) {
      $("html, body").animate(
        {
          scrollTop: target.offset().top - 80,
        },
        800
      );
    }
  });

  // Update active navigation on scroll
  window.addEventListener("scroll", updateActiveNavItem);

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
      $(".navbar-container").addClass("scrolled");
    } else {
      $(".navbar-container").removeClass("scrolled");
    }

    if ($(this).scrollTop() > 300) {
      $(".back-to-top").addClass("active");
    } else {
      $(".back-to-top").removeClass("active");
    }
  });

  $(".back-to-top").click(function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, 800);
  });

  // SINGLE LOGO ANIMATION - Clean implementation
  const movingLogo = document.getElementById("moving-logo");
  const heroHeader = document.getElementById("hero-header");
  const navbar = document.querySelector(".navbar-container");
  const heroContent = document.querySelector(".hero-content");
  const logoPlaceholder = document.getElementById("navbar-logo-placeholder");

  // Track states
  let isFixed = false;
  let initialLogoRect = null;
  let navbarPlaceholderRect = null;

  // Initialize measurements
  function initLogoTransition() {
    if (!movingLogo || !logoPlaceholder) return;

    // Get initial positions and sizes
    initialLogoRect = movingLogo.getBoundingClientRect();
    navbarPlaceholderRect = logoPlaceholder.getBoundingClientRect();

    // Reset any transformations
    if (!isFixed) {
      movingLogo.style.transform = "none";
      movingLogo.style.width = "";
      movingLogo.style.height = "";
      movingLogo.style.top = "";
      movingLogo.style.left = "";
      movingLogo.classList.remove("fixed");
    }
  }

  // Initialize on page load
  window.addEventListener("load", initLogoTransition);
  window.addEventListener("resize", initLogoTransition);

  // Handle scroll for logo animation
  window.addEventListener("scroll", function () {
    if (!movingLogo || !logoPlaceholder) return;

    const scrollY = window.scrollY;
    const triggerPoint = 50;
    const fixedPoint = 150;

    if (scrollY > triggerPoint) {
      // Apply white background to navbar
      navbar.classList.add("scrolled");

      // Fade hero content
      heroContent.classList.add("fade-out");

      // Calculate how far we've scrolled past the trigger point
      const scrollProgress = Math.min(
        1,
        (scrollY - triggerPoint) / (fixedPoint - triggerPoint)
      );

      if (!initialLogoRect || !navbarPlaceholderRect) {
        initLogoTransition();
        if (!initialLogoRect || !navbarPlaceholderRect) return;
      }

      // Calculate scale - from original size to navbar size
      const targetWidth = navbarPlaceholderRect.width;
      const scaleX = targetWidth / initialLogoRect.width;

      // Calculate position
      const initialTop = initialLogoRect.top + window.scrollY;
      const initialLeft = initialLogoRect.left;
      const targetTop = navbarPlaceholderRect.top + window.scrollY;
      const targetLeft = navbarPlaceholderRect.left;

      // Apply fixed positioning once we've scrolled enough
      if (!isFixed && scrollY >= fixedPoint) {
        isFixed = true;
        movingLogo.classList.add("fixed");
        movingLogo.style.top = targetTop + "px";
        movingLogo.style.left = targetLeft + "px";
        movingLogo.style.width = targetWidth + "px";
      }
      // Remove fixed positioning when scrolling back up
      else if (isFixed && scrollY < fixedPoint) {
        isFixed = false;
        movingLogo.classList.remove("fixed");
      }

      // Apply transformation during the transition phase
      if (scrollY < fixedPoint) {
        // Apply transforms for smooth transition
        movingLogo.style.transform = `translate(${
          (targetLeft - initialLeft) * scrollProgress
        }px, 
                                     ${
                                       (targetTop - initialTop) * scrollProgress
                                     }px) 
                                     scale(${
                                       1 - (1 - scaleX) * scrollProgress
                                     })`;
      }
    } else {
      // Reset everything when back at the top
      navbar.classList.remove("scrolled");
      heroContent.classList.remove("fade-out");

      if (isFixed) {
        isFixed = false;
        movingLogo.classList.remove("fixed");
      }

      // Reset any transformations
      movingLogo.style.transform = "none";
      movingLogo.style.width = "";
      movingLogo.style.height = "";
    }
  });
});
