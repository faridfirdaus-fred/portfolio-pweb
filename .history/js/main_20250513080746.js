xampp\htdocs\portfolio-pweb-main\js\main.js
$(document).ready(() => {
  // Initialize AOS library
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  });

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

// Function to load testimonials from the database
function loadTestimonials() {
  $.ajax({
    type: "GET",
    url: "https://farid-firdaus.lovestoblog.com/php/get_testimonials.php",
    success: (response) => {
      const testimonials = JSON.parse(response);
      let html = "";

      testimonials.forEach((testimonial) => {
        // Determine if image is external URL or local file
        let imgSrc = testimonial.image;
        if (
          !testimonial.image.startsWith("http://") &&
          !testimonial.image.startsWith("https://")
        ) {
          imgSrc =
            "https://farid-firdaus.lovestoblog.com/uploads/" +
            testimonial.image;
        }

        html += `
          <div class="col-md-4">
            <div class="testimonial-item">
              <p>"${testimonial.content}"</p>
              <div class="testimonial-author">
                <img src="${imgSrc}" alt="${testimonial.name}">
                <div class="testimonial-author-info">
                  <h5>${testimonial.name}</h5>
                  <p>${testimonial.position}</p>
                </div>
              </div>
            </div>
          </div>
        `;
      });

      $("#testimonials-container").html(html);
    },
    error: (error) => {
      console.error("Error loading testimonials:", error);
      $("#testimonials-container").html(
        '<p class="text-center">Failed to load testimonials.</p>'
      );
    },
  });
}
