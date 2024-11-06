document.addEventListener("DOMContentLoaded", function () {

  let typeSplit = new SplitType("[animate]", {
    types: "lines, words, chars",
    tagName: "span",
  });
  let typeSplit2 = new SplitType("[animate2]", {
    types: "lines, words, chars",
    tagName: "span",
  });

  function animateElements() {
    gsap.from("[animate] .word", {
      y: "100%",
      opacity: 0,
      duration: 0.5,
      ease: "power1.out",
      stagger: 0.1,
    });
    gsap.from("[animate2] .line", {
      y: "100%",
      opacity: 0,
      duration: 0.5,
      ease: "power1.out",
      stagger: 0.1,
    });
  }

  animateElements(); // Initial animation

  window.addEventListener("resize", function () {
    gsap.killTweensOf("[animate] .word, [animate2] .line"); // Stop current animations
    animateElements(); // Re-run animations on resize
  });

  const startButton = Array.from(document.querySelectorAll("button")).find(
    (button) => button.textContent.trim() === "Mulai"
  );
  const page1 = document.querySelector("section:nth-of-type(1)");
  const formSection = document.querySelector("section:nth-of-type(2)");

  if (startButton) {
    startButton.addEventListener("click", function () {
      gsap.to(page1, {
        duration: 0.5,
        opacity: 0,
        onComplete: function () {
          page1.style.display = "none"; // Menghapus tampilan page 1
          formSection.classList.remove("hidden");
          gsap.fromTo(
            formSection,
            { opacity: 0 },
            { duration: 0.5, opacity: 1, display: "flex" }
          );
        },
      });
    });
  }
});