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

// Logo animation on scroll 
// Define these variables globally
let animationInProgress = false;
let floatingLogo = null;

// Logo animation on scroll
$(document).ready(function() {
  const heroLogo = document.getElementById('hero-logo');
  const navbarLogo = document.getElementById('navbar-logo');
  
  // Initial state - make sure navbar logo is fully invisible initially
  $(navbarLogo).css({
    'opacity': '0',
    'transform': 'scale(0)'
  });
  
  function handleScrollForLogo() {
    const scrollY = window.scrollY;
    const triggerPoint = 150; // When to trigger the animation
    
    if (scrollY > triggerPoint && !animationInProgress && heroLogo) {
      animateLogo();
    }
  }
  
  function animateLogo() {
    // Check if elements exist
    if (!heroLogo || !navbarLogo) {
      console.error('Logo elements not found');
      return;
    }
    
    animationInProgress = true;
    
    // Get positions
    const heroLogoRect = heroLogo.getBoundingClientRect();
    const navbarLogoRect = navbarLogo.getBoundingClientRect();
    
    // Create clone for animation
    floatingLogo = document.createElement('img');
    floatingLogo.src = heroLogo.src;
    floatingLogo.classList.add('floating-logo');
    
    // Set initial position and size
    floatingLogo.style.width = `${heroLogoRect.width}px`;
    floatingLogo.style.height = `${heroLogoRect.height}px`;
    floatingLogo.style.top = `${heroLogoRect.top + window.scrollY}px`;
    floatingLogo.style.left = `${heroLogoRect.left}px`;
    
    // Add to DOM
    document.body.appendChild(floatingLogo);
    
    // Stop the floating animation and hide hero logo
    heroLogo.classList.add('scrolling');
    heroLogo.style.opacity = '0';
    
    // Animate the floating logo to navbar position
    setTimeout(() => {
      // Fix for navbar logo position
      const updatedNavbarLogoRect = navbarLogo.getBoundingClientRect();
      floatingLogo.style.width = `40px`; // Explicitly set width
      floatingLogo.style.height = `40px`; // Explicitly set height
      floatingLogo.style.top = `${updatedNavbarLogoRect.top + window.scrollY}px`;
      floatingLogo.style.left = `${updatedNavbarLogoRect.left}px`;
      
      // After animation completes
      setTimeout(() => {
        // Show the real navbar logo
        $(navbarLogo).addClass('visible');
        
        // Remove the floating logo
        if (floatingLogo) {
          document.body.removeChild(floatingLogo);
          floatingLogo = null;
        }
      }, 800); // Match this with the CSS transition duration
    }, 50); // Small delay to ensure the animation runs
  }
  
  // Add scroll event listener
  window.addEventListener('scroll', handleScrollForLogo, { passive: true });
  
  // Reset on page reload by scrolling to top
  window.onbeforeunload = function() {
    window.scrollTo(0, 0);
  };
});

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