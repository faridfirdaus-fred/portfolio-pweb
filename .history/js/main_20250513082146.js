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

