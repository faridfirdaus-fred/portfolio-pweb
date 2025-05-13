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

/// Replace all the logo animation code with this improved version

// Define these variables globally
let logoAnimationActive = false;
let floatingLogo = null;
let startScrollPosition = 0;
let endScrollPosition = 300; // This will be calculated dynamically
let heroLogoInitialPosition = { top: 0, left: 0 };
let navbarLogoPosition = { top: 0, left: 0 };

$(document).ready(function() {
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  
  // Make sure navbar logo is hidden initially
  $(navbarLogo).css({
    'opacity': '0',
    'transform': 'scale(0)'
  });
  
  // Wait for images to load to get correct positions
  window.addEventListener('load', initializeLogoPositions);
  
  // Recalculate on window resize
  window.addEventListener('resize', initializeLogoPositions);
  
  // Initialize the position variables
  function initializeLogoPositions() {
    if (!heroLogo || !navbarLogo) return;
    
    // Get hero section position
    const heroSection = document.getElementById('home');
    if (!heroSection) return;
    
    // Calculate starting and ending scroll positions
    startScrollPosition = heroSection.offsetTop;
    // End position is when navbar becomes sticky (approx 150px scroll)
    endScrollPosition = startScrollPosition + 150;
    
    // Store initial positions
    const heroRect = heroLogo.getBoundingClientRect();
    heroLogoInitialPosition = {
      top: heroRect.top + window.scrollY,
      left: heroRect.left,
      width: heroRect.width,
      height: heroRect.height
    };
    
    const navRect = navbarLogo.getBoundingClientRect();
    navbarLogoPosition = {
      top: navRect.top + window.scrollY,
      left: navRect.left,
      width: navRect.width,
      height: navRect.height
    };
    
    // Create floating logo if it doesn't exist
    if (!floatingLogo && window.scrollY > 10) {
      createFloatingLogo();
    }
  }
  
  function createFloatingLogo() {
    if (floatingLogo) return;
    
    // Create clone for animation
    floatingLogo = document.createElement("img");
    floatingLogo.src = heroLogo.src;
    floatingLogo.classList.add("floating-logo");
    floatingLogo.style.width = `${heroLogoInitialPosition.width}px`;
    floatingLogo.style.height = `${heroLogoInitialPosition.height}px`;
    document.body.appendChild(floatingLogo);
    
    // Hide the original hero logo
    heroLogo.style.opacity = "0";
    heroLogo.classList.add("scrolling");
    
    logoAnimationActive = true;
  }
  
  function removeFloatingLogo() {
    if (!floatingLogo) return;
    
    // Show the navbar logo
    $(navbarLogo).addClass('visible');
    
    // Remove the floating logo with fade out
    $(floatingLogo).fadeOut(300, function() {
      document.body.removeChild(floatingLogo);
      floatingLogo = null;
    });
  }
  
  // Handle scroll events to update logo position
  function handleScroll() {
    const currentScroll = window.scrollY;
    
    // Create floating logo if scrolling starts
    if (currentScroll > 10 && !floatingLogo && !logoAnimationActive) {
      createFloatingLogo();
    }
    
    // Reset if scrolled back to top
    if (currentScroll < 10 && logoAnimationActive) {
      if (floatingLogo) {
        document.body.removeChild(floatingLogo);
        floatingLogo = null;
      }
      $(navbarLogo).removeClass('visible');
      $(navbarLogo).css({
        'opacity': '0',
        'transform': 'scale(0)'
      });
      heroLogo.style.opacity = '1';
      heroLogo.classList.remove("scrolling");
      logoAnimationActive = false;
      return;
    }
    
    // Update logo position based on scroll
    if (floatingLogo && logoAnimationActive) {
      // Calculate progress (0 to 1)
      let progress = Math.min(1, Math.max(0, (currentScroll - startScrollPosition) / 
                                         (endScrollPosition - startScrollPosition)));
      
      // Calculate interpolated position
      const newTop = heroLogoInitialPosition.top + (navbarLogoPosition.top - heroLogoInitialPosition.top) * progress;
      const newLeft = heroLogoInitialPosition.left + (navbarLogoPosition.left - heroLogoInitialPosition.left) * progress;
      
      // Calculate interpolated size
      const newWidth = heroLogoInitialPosition.width + (navbarLogoPosition.width - heroLogoInitialPosition.width) * progress;
      const newHeight = heroLogoInitialPosition.height + (navbarLogoPosition.height - heroLogoInitialPosition.height) * progress;
      
      // Apply new position and size
      floatingLogo.style.top = `${newTop - currentScroll}px`;
      floatingLogo.style.left = `${newLeft}px`;
      floatingLogo.style.width = `${newWidth}px`;
      floatingLogo.style.height = `${newHeight}px`;
      
      // If we've reached the end position, remove the floating logo and show the navbar logo
      if (progress >= 1 && $(navbarLogo).hasClass('visible') === false) {
        removeFloatingLogo();
      }
    }
  }
  
  // Add scroll event listener with throttling for performance
  let scrollTimeout;
  window.addEventListener('scroll', function() {
    if (!scrollTimeout) {
      scrollTimeout = setTimeout(function() {
        handleScroll();
        scrollTimeout = null;
      }, 10); // Small delay for performance
    }
  }, { passive: true });
  
  // Initialize the positions on page load
  setTimeout(initializeLogoPositions, 100);
});

// Remove all other resetLogoState functions as they're now unnecessary

// Add this to your scroll event handler
window.addEventListener('scroll', resetLogoState, { passive: true });

// Fix resetLogoState to use global variables
function resetLogoState() {
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  
  if (!heroLogo || !navbarLogo) return;
  
  if (window.scrollY < 50) {
    // Reset to initial state when scrolled back to top
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