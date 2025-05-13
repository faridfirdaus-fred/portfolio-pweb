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



$(document).ready(() => {
  // Initialize AOS library with enhanced settings
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: false,
    mirror: true,
    anchorPlacement: "top-bottom",
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

  // Contact form submission
  $("#contactForm").on("submit", (e) => {
    e.preventDefault();

    const formData = {
      name: $("#name").val(),
      email: $("#email").val(),
      message: $("#message").val(),
    };

    // Form submission logic
    alert("Message sent successfully!");
    $("#contactForm")[0].reset();
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
});
// Function to load work items from the database
function loadWorkItems() {
  $.ajax({
    type: "GET",
    url: "https://farid-firdaus.lovestoblog.com/php/get_works.php",
    success: (response) => {
      const works = JSON.parse(response);
      let html = "";

      works.forEach((work) => {
        // Determine if image is external URL or local file
        let imgSrc = work.image;
        if (
          !work.image.startsWith("http://") &&
          !work.image.startsWith("https://")
        ) {
          imgSrc =
            "https://farid-firdaus.lovestoblog.com/uploads/" + work.image;
        }

        html += `
          <div class="col-md-4 work-item" data-category="${work.category}">
            <div class="card">
              <img src="${imgSrc}" class="card-img-top" alt="${work.title}">
              <div class="card-body">
                <h5 class="card-title">${work.title}</h5>
                <p class="card-text">${work.description}</p>
              </div>
            </div>
          </div>
        `;
      });

      $("#work-gallery").html(html);
    },
    error: (error) => {
      console.error("Error loading work items:", error);
      $("#work-gallery").html(
        '<p class="text-center">Failed to load work items.</p>'
      );
    },
  });
}

$(window).scroll(function () {
  if ($(this).scrollTop() > 300) {
    $("#backToTop").addClass("active");
  } else {
    $("#backToTop").removeClass("active");
  }
});

$("#backToTop").click(function (e) {
  e.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, 800);
});

// Combined Header and Hero Section with Logo Transition

// Replace all logo animation code with this clean implementation

// Perfect logo transition with a single element
$(document).ready(function() {
  // Get DOM elements
  const movingLogo = document.getElementById('moving-logo');
  const heroHeader = document.getElementById('hero-header');
  const navbar = document.querySelector('.navbar-container');
  const heroContent = document.querySelector('.hero-content');
  const logoPlaceholder = document.getElementById('navbar-logo-placeholder');
  
  // Track states
  let isFixed = false;
  let initialLogoRect = null;
  let navbarPlaceholderRect = null;
  let headerHeight = 0;
  
  // Initialize measurements after all resources are loaded
  window.addEventListener('load', initLogoTransition);
  window.addEventListener('resize', initLogoTransition);
  
  function initLogoTransition() {
    if (!movingLogo || !logoPlaceholder) return;
    
    // Get initial positions and sizes
    initialLogoRect = movingLogo.getBoundingClientRect();
    navbarPlaceholderRect = logoPlaceholder.getBoundingClientRect();
    headerHeight = heroHeader.offsetHeight;
    
    // Reset any transformations
    if (!isFixed) {
      movingLogo.style.transform = 'none';
      movingLogo.style.width = '';
      movingLogo.style.height = '';
      movingLogo.style.top = '';
      movingLogo.style.left = '';
      movingLogo.classList.remove('fixed');
    }
  }
  
  // Handle scroll
  window.addEventListener('scroll', function() {
    if (!movingLogo || !logoPlaceholder) return;
    
    const scrollY = window.scrollY;
    const triggerPoint = 50;
    const fixedPoint = 150;
    
    if (scrollY > triggerPoint) {
      // Apply white background to navbar
      navbar.classList.add('scrolled');
      
      // Fade hero content
      heroContent.classList.add('fade-out');
      
      // Calculate how far we've scrolled past the trigger point
      const scrollProgress = Math.min(1, (scrollY - triggerPoint) / (fixedPoint - triggerPoint));
      
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
        movingLogo.classList.add('fixed');
        movingLogo.style.top = targetTop + 'px';
        movingLogo.style.left = targetLeft + 'px';
        movingLogo.style.width = targetWidth + 'px';
      }
      // Remove fixed positioning when scrolling back up
      else if (isFixed && scrollY < fixedPoint) {
        isFixed = false;
        movingLogo.classList.remove('fixed');
      }
      
      // Apply transformation during the transition phase
      if (scrollY < fixedPoint) {
        // Apply transforms for smooth transition
        movingLogo.style.transform = `translate(${(targetLeft - initialLeft) * scrollProgress}px, 
                                     ${(targetTop - initialTop) * scrollProgress}px) 
                                     scale(${1 - (1 - scaleX) * scrollProgress})`;
      }
    } else {
      // Reset everything when back at the top
      navbar.classList.remove('scrolled');
      heroContent.classList.remove('fade-out');
      
      if (isFixed) {
        isFixed = false;
        movingLogo.classList.remove('fixed');
      }
      
      // Reset any transformations
      movingLogo.style.transform = 'none';
      movingLogo.style.width = '';
      movingLogo.style.height = '';
    }
  });
});

// Remove the duplicate resetLogoState functions and other conflicting code
// Comment out or delete all these lines:
/*
window.addEventListener('scroll', resetLogoState, { passive: true });
function resetLogoState() {
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  
  if (!heroLogo || !navbarLogo) return;
  
  if (window.scrollY < 50) {
    $(navbarLogo).removeClass('visible');
    $(navbarLogo).css({
      'opacity': '0',
      'transform': 'scale(0)'
    });
    heroLogo.style.opacity = '1';
    heroLogo.classList.remove('scrolling');
    animationInProgress = false;
  }
}
*/

// Add this to your scroll event handler
window.addEventListener('scroll', resetLogoState, { passive: true });



// Add this to your scroll event handler
window.addEventListener('scroll', resetLogoState, { passive: true });
// Add this to your existing JavaScript
function resetLogoState() {
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  
  if (window.scrollY < 50) {
    // Reset to initial state when scrolled back to top
    $(navbarLogo).removeClass('visible');
    heroLogo.style.opacity = '1';
    heroLogo.classList.remove('scrolling');
    animationInProgress = false;
  }
}

// Add this to your scroll event handler
window.addEventListener('scroll', resetLogoState, { passive: true });