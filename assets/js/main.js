document.addEventListener("DOMContentLoaded", () => {
  // Navbar scroll effect
  const navbar = document.querySelector(".navbar");
  const isHomepage = document.querySelector(".hero") !== null;
  const hasPageHero = document.querySelector(".page-hero") !== null;

  if (navbar) {
    // Add scrolled class initially if not at the top of the page
    // or if we're not on the homepage or a page with a hero
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else if (isHomepage || hasPageHero) {
      // Only make transparent on homepage or pages with hero
      navbar.classList.remove("scrolled");
    } else {
      // For other pages without hero, always show scrolled navbar
      navbar.classList.add("scrolled");
    }

    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
      } else if (isHomepage || hasPageHero) {
        // Only make transparent on homepage or pages with hero
        navbar.classList.remove("scrolled");
      }
    });
  }

  // Mobile menu functionality
  const mobileMenuToggle = document.querySelector(".navbar-toggler");
  const mobileMenu = document.querySelector(".mobile-menu");
  const mobileMenuOverlay = document.querySelector(".mobile-menu-overlay");
  const mobileMenuClose = document.querySelector(".mobile-menu-close");
  const mobileDropdownToggles = document.querySelectorAll(
    ".mobile-dropdown-toggle"
  );

  if (mobileMenuToggle && mobileMenu && mobileMenuOverlay) {
    // Open mobile menu
    mobileMenuToggle.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      mobileMenuOverlay.classList.add("active");
      document.body.style.overflow = "hidden"; // Prevent scrolling
    });

    // Close mobile menu
    const closeMenu = () => {
      mobileMenu.classList.remove("active");
      mobileMenuOverlay.classList.remove("active");
      document.body.style.overflow = ""; // Enable scrolling
    };

    mobileMenuClose.addEventListener("click", closeMenu);
    mobileMenuOverlay.addEventListener("click", closeMenu);

    // Mobile dropdown functionality
    mobileDropdownToggles.forEach((toggle) => {
      toggle.addEventListener("click", (e) => {
        e.preventDefault();
        const parent = toggle.parentElement;
        const dropdownMenu = toggle.nextElementSibling;

        if (parent.classList.contains("active")) {
          parent.classList.remove("active");
          dropdownMenu.classList.remove("active");
        } else {
          // Close other open dropdowns
          document
            .querySelectorAll(".mobile-dropdown.active")
            .forEach((item) => {
              if (item !== parent) {
                item.classList.remove("active");
                item
                  .querySelector(".mobile-dropdown-menu")
                  .classList.remove("active");
              }
            });

          parent.classList.add("active");
          dropdownMenu.classList.add("active");
        }
      });
    });
  }

  // Enhanced Hero Slideshow
  const initHeroSlideshow = () => {
    const hero = document.querySelector(".hero");
    if (!hero) return;

    const slides = document.querySelectorAll(".hero-slide");
    if (slides.length === 0) return;

    let currentSlide = 0;
    let slideInterval;

    // Function to set active slide
    const setActiveSlide = (index) => {
      // Remove active class from all slides
      slides.forEach((slide) => slide.classList.remove("active"));

      // Add active class to current slide
      slides[index].classList.add("active");

      // Update current slide index
      currentSlide = index;
    };

    // Set first slide as active initially
    setActiveSlide(0);

    // Start slideshow
    const startSlideshow = () => {
      slideInterval = setInterval(() => {
        const nextSlide = (currentSlide + 1) % slides.length;
        setActiveSlide(nextSlide);
      }, 6000); // Change slide every 6 seconds
    };

    // Scroll down functionality
    const scrollDown = document.querySelector(".scroll-down");
    if (scrollDown) {
      scrollDown.addEventListener("click", () => {
        const nextSection = hero.nextElementSibling;
        if (nextSection) {
          window.scrollTo({
            top: nextSection.offsetTop - 80, // Adjust for navbar height
            behavior: "smooth",
          });
        }
      });
    }

    // Start the slideshow
    startSlideshow();
  };

  // Initialize hero slideshow
  initHeroSlideshow();

  // Back to top button
  const backToTopButton = document.querySelector(".back-to-top");

  if (backToTopButton) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        backToTopButton.classList.add("show");
      } else {
        backToTopButton.classList.remove("show");
      }
    });

    backToTopButton.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Enhanced scroll animations
  const animateOnScroll = () => {
    const elements = document.querySelectorAll(
      ".fade-in, .slide-in-left, .slide-in-right"
    );

    elements.forEach((element) => {
      const elementPosition = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;

      if (elementPosition < windowHeight - 50) {
        element.style.opacity = "1";
        element.style.transform = element.classList.contains("slide-in-left")
          ? "translateX(0)"
          : element.classList.contains("slide-in-right")
          ? "translateX(0)"
          : "translateY(0)";
      }
    });
  };

  // Run once on page load
  animateOnScroll();

  // Run on scroll
  window.addEventListener("scroll", animateOnScroll);

  // Make product cards clickable
  const productCards = document.querySelectorAll(".product-card");
  productCards.forEach((card) => {
    const link = card.getAttribute("data-href");
    if (link) {
      card.addEventListener("click", () => {
        window.location.href = link;
      });
    }
  });

  // Fix for mobile browsers - adjust hero height
  const adjustHeroHeight = () => {
    const hero = document.querySelector(".hero");
    if (hero) {
      // Check if it's a mobile device
      if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
          navigator.userAgent
        )
      ) {
        // Set a specific height for mobile devices
        const viewportHeight = window.innerHeight;
        hero.style.height = `${viewportHeight}px`;
      }
    }
  };

  // Call on load and resize
  adjustHeroHeight();
  window.addEventListener("resize", adjustHeroHeight);
});
