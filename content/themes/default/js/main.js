/**
 * Boltix Portal Interactive Engine
 */
document.addEventListener("DOMContentLoaded", function() {
    // 1. 8-Second Moving Carousel Logic
    const slides = document.querySelectorAll(".carousel-slide");
    const thumbs = document.querySelectorAll(".thumb-item");
    let currentIndex = 0;
    let carouselTimer = null;

    function showSlide(index) {
        if (slides.length === 0) return;
        slides.forEach(s => s.classList.remove("active"));
        thumbs.forEach(t => t.classList.remove("active"));
        
        currentIndex = index;
        if (currentIndex >= slides.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = slides.length - 1;

        slides[currentIndex].classList.add("active");
        if (thumbs[currentIndex]) thumbs[currentIndex].classList.add("active");
    }

    function startCarousel() {
        if (slides.length <= 1) return;
        carouselTimer = setInterval(() => {
            showSlide(currentIndex + 1);
        }, 8000); // 8 seconds exact
    }

    thumbs.forEach((thumb, idx) => {
        thumb.addEventListener("click", () => {
            clearInterval(carouselTimer);
            showSlide(idx);
            startCarousel();
        });
    });

    startCarousel();

    // 2. Back to Top Button Logic
    const backToTopBtn = document.getElementById("backToTop");
    if (backToTopBtn) {
        backToTopBtn.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});
