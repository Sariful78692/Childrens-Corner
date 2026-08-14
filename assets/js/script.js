window.addEventListener("load", function () {
  // Loader functions for new loader
  const pageLoader = document.getElementById("page-loader");

  function showLoader() {
    if (pageLoader) {
      pageLoader.classList.remove("page-loader-hidden");
      document.body.style.overflow = "hidden"; // Prevent scrolling while loader is active
    }
  }

  function hideLoader() {
    if (pageLoader) {
      pageLoader.classList.add("page-loader-hidden");
      document.body.style.overflow = ""; // Restore scrolling
    }
  }

  // Hide loader on page load, back/forward navigation, and tab visibility changes
  function initialHideLoader() {
    // Hide loader right away on initial script execution
    hideLoader();

    // Re-hide if the page is shown from the back-forward cache
    window.addEventListener("pageshow", function (event) {
      if (event.persisted) {
        hideLoader();
      }
    });

    // Hide when a user switches back to the tab
    document.addEventListener("visibilitychange", function () {
      if (document.visibilityState === "visible") {
        hideLoader();
      }
    });
  }

  initialHideLoader();

  const topBar = document.querySelector(".top-bar");
  if (!topBar) {
    console.error("Error: .top-bar element not found!");
    return;
  }

  let isTopBarHidden = false;
  const hideThreshold = 100;
  const showThreshold = 50;

  window.addEventListener("scroll", () => {
    const currentScrollY = window.scrollY;
    if (currentScrollY > hideThreshold && !isTopBarHidden) {
      topBar.classList.add("hide-top-bar");
      isTopBarHidden = true;
    } else if (currentScrollY <= showThreshold && isTopBarHidden) {
      topBar.classList.remove("hide-top-bar");
      isTopBarHidden = false;
    }
  });



  // Scroll to Top Button
  const scrollTopBtn = document.getElementById("scrollTopBtn");

  if (scrollTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollTopBtn.style.display = "block";
      } else {
        scrollTopBtn.style.display = "none";
      }
    });

    scrollTopBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Scroll Animation Logic (for general elements)
  const generalObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
        } else {
          entry.target.classList.remove("visible"); // Remove class when element is out of view
        }
      });
    },
    {
      threshold: 0.1, // Trigger when 10% of the element is visible
    },
  );

  const animatedElements = document.querySelectorAll(".animate-on-scroll");
  animatedElements.forEach((el) => generalObserver.observe(el));

  // Number Scrolling Animation Logic
  // Easing function for smoother animation
  const easeOutQuad = (t) => t * (2 - t);

  const animateNumber = (element, targetNumber, duration, suffix = "") => {
    let start = 0;
    const startTime = performance.now();

    const updateNumber = (currentTime) => {
      const elapsedTime = currentTime - startTime;
      let progress = Math.min(elapsedTime / duration, 1);
      const easedProgress = easeOutQuad(progress); // Apply easing

      const currentValue = Math.floor(easedProgress * targetNumber);
      element.textContent = suffix + currentValue;

      if (progress < 1) {
        requestAnimationFrame(updateNumber);
      } else {
        element.textContent = suffix + targetNumber; // Ensure final value is exact with suffix
      }
    };
    requestAnimationFrame(updateNumber);
  };

  const numberObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const numberElement = entry.target;
          const targetNumber = parseInt(numberElement.dataset.target, 10);
          const suffix = numberElement.dataset.suffix || ""; // Get suffix from data attribute
          if (!numberElement.classList.contains("animated")) {
            // Prevent re-animation
            animateNumber(numberElement, targetNumber, 3000, suffix); // Pass suffix to animation function
            numberElement.classList.add("animated"); // Mark as animated
          }
        } else {
          // Optionally reset number if scrolled out of view and want to re-animate
          // entry.target.textContent = '0';
          // entry.target.classList.remove('animated');
        }
      });
    },
    {
      threshold: 0.5, // Trigger when 50% of the element is visible
    },
  );

  const numberElements = document.querySelectorAll(".animate-number");
  numberElements.forEach((el) => numberObserver.observe(el));

  // Initialize Fancybox for gallery images
  if (typeof Fancybox !== "undefined") {
    Fancybox.bind("[data-fancybox]", {
      // Appearance options
      compact: false, // Display controls in a compact mode
      idle: 3000, // Hide controls after 3 seconds of inactivity
      animated: true, // Enable animations
      transition: "fade", // Set transition type (fade, slide, zoom)

      // Toolbar options
      Toolbar: {
        display: {
          left: ["infobar"],
          middle: [
            "zoomIn",
            "zoomOut",
            "toggle1to1",
            "rotateCCW",
            "rotateCW",
            "flipX",
            "flipY",
          ],
          right: ["slideshow", "thumbs", "close"],
        },
      },

      // Other options
      wheel: "slide", // Change slides with mouse wheel
      showClass: "fancybox-zoomIn", // Custom show animation class
      hideClass: "fancybox-fadeOut", // Custom hide animation class
    });
  }

  // Keep dropdown open on click for About (or any dropdown)
  document
    .querySelectorAll(".main-nav .nav-item.dropdown > .nav-link")
    .forEach((link) => {
      link.addEventListener("click", function (e) {
        // prevent link navigation if dropdown exists
        e.preventDefault();

        const parentLi = this.closest(".dropdown");
        const menu = parentLi.querySelector(".dropdown-menu");

        // close other open dropdowns
        document
          .querySelectorAll(".main-nav .nav-item.dropdown")
          .forEach((item) => {
            if (item !== parentLi) {
              item.classList.remove("dropdown-click-open");
            }
          });

        // toggle current dropdown
        parentLi.classList.toggle("dropdown-click-open");
      });
    });

  // Close dropdown if click outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".main-nav .nav-item.dropdown")) {
      document
        .querySelectorAll(".main-nav .nav-item.dropdown")
        .forEach((item) => {
          item.classList.remove("dropdown-click-open");
        });
    }
  });

  // Set up exit animations on links
  const navLinks = document.querySelectorAll("a");
  navLinks.forEach((link) => {
    // Ensure the link is for page navigation, not for Fancybox or other special functions
    if (
      link.hostname === window.location.hostname &&
      !link.href.includes("#") &&
      !link.hasAttribute("data-fancybox") &&
      !link.hasAttribute("data-bs-toggle") &&
      !link.hasAttribute("data-no-loader") &&
      !link.hasAttribute("download") &&
      link.target !== "_blank"
    ) {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        showLoader(); // Show loader before navigating
        setTimeout(() => {
          window.location.href = link.href;
        }, 500); // Simulate a short delay for the loader animation
      });
    }
  });

  // Handle form-floating labels for Select2 dropdowns
  function updateSelect2Label(selectElement) {
    const formFloatingDiv = selectElement.closest(".form-floating");
    if (formFloatingDiv) {
      // Check if select2 has a selected value
      if ($(selectElement).val() && $(selectElement).val().length > 0) {
        formFloatingDiv.classList.add("label-floated");
      } else {
        formFloatingDiv.classList.remove("label-floated");
      }
    }
  }

  // Initialize Select2 dropdowns and their labels
  const select2Elements = document.querySelectorAll(
    ".form-floating select.form-select",
  );
  select2Elements.forEach((selectElement) => {
    // Use the placeholder from the first option, or a generic one if not available
    const placeholderText =
      $(selectElement).find("option:first").text() || "Select an option";

    $(selectElement).select2({
      placeholder: placeholderText,
      theme: "bootstrap-5",
      allowClear: true, // This allows the "x" to clear the selection
      minimumResultsForSearch: 0, // Always show the search box
      dropdownParent: $(selectElement).parent(), // Ensure correct z-index when opened
    });

    // Update label state on change
    $(selectElement).on("change", function () {
      updateSelect2Label(this);
    });

    // Set initial label state on page load
    updateSelect2Label(selectElement);
  });
});
