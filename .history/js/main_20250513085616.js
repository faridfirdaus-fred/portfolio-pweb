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

$(document).ready(function() {
  const heroHeader = document.getElementById('hero-header');
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  const navbar = document.querySelector('.navbar-container');
  const heroContent = document.querySelector('.hero-content');
  
  let floatingLogo = null;
  let isAnimating = false;
  let hasScrolled = false;
  
  // Store initial positions
  let heroLogoRect;
  let navbarLogoRect;
  
  // Calculate positions after all images and assets are loaded
  window.addEventListener('load', function() {
    heroLogoRect = heroLogo.getBoundingClientRect();
    navbarLogoRect = navbarLogo.getBoundingClientRect();
  });
  
  // Handle scroll events
  window.addEventListener('scroll', function() {
    const scrollY = window.scrollY;
    
    // Apply scrolled class to navbar when scrolling starts
    if (scrollY > 50) {
      navbar.classList.add('scrolled');
      
      // Start the logo transition if we haven't already
      if (!hasScrolled && !isAnimating) {
        hasScrolled = true;
        animateLogoTransition();
      }
      
      // Start fading out hero content
      heroContent.classList.add('fade-out');
    } else {
      // Reset everything when back at the top
      navbar.classList.remove('scrolled');
      heroContent.classList.remove('fade-out');
      
      if (hasScrolled) {
        resetAnimation();
        hasScrolled = false;
      }
    }
  });
  
  function animateLogoTransition() {
    if (isAnimating || !heroLogo || !navbarLogo) return;
    
    isAnimating = true;
    
    // Get updated positions
    const updatedHeroLogoRect = heroLogo.getBoundingClientRect();
    const updatedNavbarLogoRect = navbarLogo.getBoundingClientRect();
    
    // Create floating logo for the transition
    floatingLogo = document.createElement('img');
    floatingLogo.src = heroLogo.src;
    floatingLogo.className = 'floating-logo';
    
    // Set initial position and size
    floatingLogo.style.width = updatedHeroLogoRect.width + 'px';
    floatingLogo.style.height = updatedHeroLogoRect.height + 'px';
    floatingLogo.style.top = (updatedHeroLogoRect.top + window.scrollY) + 'px';
    floatingLogo.style.left = updatedHeroLogoRect.left + 'px';
    
    // Add to DOM
    document.body.appendChild(floatingLogo);
    
    // Hide original hero logo
    heroLogo.classList.add('fade-out');
    
    // Animate to navbar position
    setTimeout(() => {
      floatingLogo.style.transition = 'all 0.8s cubic-bezier(0.68, -0.55, 0.27, 1.55)';
      floatingLogo.style.width = '40px';  
      floatingLogo.style.height = 'auto';
      floatingLogo.style.top = (updatedNavbarLogoRect.top + window.scrollY) + 'px';
      floatingLogo.style.left = updatedNavbarLogoRect.left + 'px';
      
      // After animation completes
      setTimeout(() => {
        // Show navbar logo
        navbarLogo.classList.add('visible');
        
        // Remove floating logo
        floatingLogo.style.opacity = '0';
        setTimeout(() => {
          if (floatingLogo && floatingLogo.parentNode) {
            document.body.removeChild(floatingLogo);
            floatingLogo = null;
          }
        }, 300);
      }, 800);
    }, 50);
  }
  
  function resetAnimation() {
    // Reset navbar logo
    navbarLogo.classList.remove('visible');
    
    // Reset hero logo
    heroLogo.classList.remove('fade-out');
    
    // Clean up any floating logo
    if (floatingLogo && floatingLogo.parentNode) {
      document.body.removeChild(floatingLogo);
      floatingLogo = null;
    }
    
    isAnimating = false;
  }
  
  // Reset on window resize
  window.addEventListener('resize', function() {
    if (!hasScrolled) {
      heroLogoRect = heroLogo.getBoundingClientRect();
      navbarLogoRect = navbarLogo.getBoundingClientRect();
    }
  });
});

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