import Alpine from "alpinejs";
import { gsap } from "gsap";
import SplitType from "split-type";
// Export untuk penggunaan global
window.gsap = gsap;
window.SplitType = SplitType;

document.addEventListener("DOMContentLoaded", function () {
  window.Alpine = Alpine;

  Alpine.store("notifications", {
    items: [],
    add(message, type = "info") {
      const id = Date.now();
      this.items.push({ id, message, type });
      
      // Animasi masuk yang lebih smooth
      setTimeout(() => {
        gsap.from(`[data-notification-id="${id}"]`, {
          x: 30,
          opacity: 0,
          duration: 0.3,
          ease: "power2.out"
        });
      }, 100);

      // Durasi tampil lebih singkat
      setTimeout(() => {
        gsap.to(`[data-notification-id="${id}"]`, {
          x: 30,
          opacity: 0,
          duration: 0.2,
          ease: "power2.in",
          onComplete: () => {
            this.items = this.items.filter(item => item.id !== id);
          }
        });
      }, 2000);
    },
    remove(id) {
      gsap.to(`[data-notification-id="${id}"]`, {
        x: 50,
        opacity: 0,
        duration: 0.3,
        ease: "power2.in",
        onComplete: () => {
          this.items = this.items.filter(item => item.id !== id);
        }
      });
    }
  });

  Alpine.start();
  initializeGSAPAnimations();
  const startButton = document.querySelector(".bg-red-500.hover\\:bg-red-600");
  const formSection = document.querySelector("#formSection");

  if (startButton && formSection) {
    startButton.addEventListener("click", function (e) {
      e.preventDefault();
      formSection.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    });
  }

  // GSAP Animations
  try {
    initializeGSAPAnimations();
  } catch (error) {
    console.warn("GSAP animation error:", error);
  }

  // Radio Input Handling
  try {
    const questionContainer = document.getElementById("questionContainer");
    if (questionContainer) {
      const radioInputs = questionContainer.querySelectorAll(
        'input[type="radio"]'
      );

      radioInputs.forEach((radio) => {
        radio.addEventListener("change", function () {
          // Reset semua label
          questionContainer.querySelectorAll("label").forEach((label) => {
            label.classList.remove("text-blue-600");
          });

          questionContainer.querySelectorAll("label span").forEach((span) => {
            span.classList.remove(
              "font-medium",
              "text-blue-600",
              "font-semibold"
            );
          });

          // Style untuk input yang dipilih
          const label = this.closest("label");
          if (label) {
            label.classList.add("text-blue-600");
            const span = label.querySelector("span");
            if (span) {
              span.classList.add(
                "font-medium",
                "text-blue-600",
                "font-semibold",
                "mr-2"
              );
            }
          }
        });
      });
    }
  } catch (error) {
    console.warn("Radio input handling error:", error);
  }

  // Sidebar functionality
  try {
    const btn = document.getElementById("sliderBtn");
    const sideBar = document.getElementById("sideBar");
    const sideBarHideBtn = document.getElementById("sideBarHideBtn");

    if (btn && sideBar && sideBarHideBtn) {
      btn.addEventListener("click", function () {
        if (sideBar.classList.contains("md:-ml-64")) {
          sideBar.classList.replace("md:-ml-64", "md:ml-0");
          sideBar.classList.remove("md:slideOutLeft");
          sideBar.classList.add("md:slideInLeft");
        }
      });

      sideBarHideBtn.addEventListener("click", function () {
        if (sideBar.classList.contains("md:ml-0")) {
          sideBar.classList.remove("md:slideInLeft");
          sideBar.classList.add("md:slideOutLeft");

          setTimeout(() => {
            sideBar.classList.replace("md:ml-0", "md:-ml-64");
          }, 300);
        }
      });
    }
  } catch (error) {
    console.warn("Sidebar functionality error:", error);
  }

  // Dropdown functionality
  try {
    const dropdowns = document.getElementsByClassName("dropdown");

    if (dropdowns.length) {
      Array.from(dropdowns).forEach((dropdown) => {
        dropdown.addEventListener("click", function () {
          const menu = this.querySelector(".menu");
          const overflow = this.querySelector(".menu-overflow");

          if (menu && overflow) {
            if (menu.classList.contains("hidden")) {
              menu.classList.remove("hidden");
              menu.classList.add("fadeIn");
              overflow.classList.remove("hidden");
            } else {
              menu.classList.add("hidden");
              overflow.classList.add("hidden");
              menu.classList.remove("fadeIn");
            }
          }
        });
      });
    }
  } catch (error) {
    console.warn("Dropdown functionality error:", error);
  }

  try {
    initializeLoadingAnimations();
  } catch (error) {
    console.warn("Loading animation error:", error);
  }

  // Fungsi untuk menangani aktivasi input teks berdasarkan checkbox/radio
  handleTextInputActivation();
});

function initializeGSAPAnimations() {
  // Animasi untuk elemen dengan atribut 'animate'
  const textElements = document.querySelectorAll("[animate]");
  textElements.forEach((element) => {
    const text = new SplitType(element, { types: "words" });
    gsap.from(text.words, {
      duration: 0.8,
      y: 80,
      rotationX: -90,
      stagger: 0.1,
      opacity: 0,
      scale: 1, // Pastikan scale tetap 1
      transformOrigin: "0% 50% -50",
    });
  });

  // Animasi untuk elemen dengan atribut 'animate2'
  const textElements2 = document.querySelectorAll("[animate2]");
  textElements2.forEach((element) => {
    const text = new SplitType(element, { types: "words" });
    gsap.from(text.words, {
      duration: 0.5,
      opacity: 0,
      y: 20,
      scale: 1, // Pastikan scale tetap 1
      stagger: 0.05,
      delay: 0.8,
    });
  });

  // Animasi untuk elemen dengan atribut 'animate3'
  const textElements3 = document.querySelectorAll("[animate3]");
  textElements3.forEach((element) => {
    const text = new SplitType(element, { types: "words" });
    gsap.from(text.words, {
      duration: 0.5,
      opacity: 0,
      y: 20,
      scale: 1, // Pastikan scale tetap 1
      stagger: 0.05,
      delay: 0.8,
    });
  });
}

function initializeLoadingAnimations() {
  // Animasi untuk logo loading
  gsap.to("#loadingLogo", {
    scale: 1.2,
    duration: 1,
    repeat: -1,
    yoyo: true,
    ease: "power1.inOut",
  });

  // Animasi untuk teks loading
  const loadingText = document.getElementById("loadingText");
  if (loadingText) {
    const text = new SplitType(loadingText, { types: "chars" });

    gsap.to(text.chars, {
      y: -20,
      duration: 0.5,
      stagger: 0.1,
      repeat: -1,
      yoyo: true,
      ease: "power1.inOut",
    });
  }
}

// Panggil fungsi setelah DOM loaded
document.addEventListener("DOMContentLoaded", function () {
  initializeGSAPAnimations();
});
// get the close btn
var alert_button = document.getElementsByClassName("alert-btn-close");

// looping into all alert close btns
for (let i = 0; i < alert_button.length; i++) {
  const btn = alert_button[i];

  btn.addEventListener("click", function () {
    var dad = this.parentNode;
    dad.classList.add("animated", "fadeOut");
    setTimeout(() => {
      dad.remove();
    }, 1000);
  });
}

// Fungsi untuk animasi perpindahan section
function initSectionTransitions() {
  const heroSection = document.getElementById("heroSection");
  const formSection = document.getElementById("formSection");
  const startButton = document.getElementById("startButton");

  if (!heroSection || !formSection || !startButton) {
    console.warn("Beberapa elemen tidak ditemukan untuk animasi section");
    return;
  }

  // Sembunyikan form section di awal
  gsap.set(formSection, {
    display: "none",
    opacity: 0,
    y: 50,
  });

  startButton.addEventListener("click", () => {
    // Animasi fade out untuk hero section
    gsap.to(heroSection, {
      opacity: 0,
      y: -50,
      duration: 0.5,
      ease: "power2.inOut",
      onComplete: () => {
        heroSection.style.display = "none";
        formSection.style.display = "block";

        // Animasi fade in untuk form section
        gsap.to(formSection, {
          opacity: 1,
          y: 0,
          duration: 0.5,
          ease: "power2.out",
        });
      },
    });
  });
}

// Panggil fungsi setelah DOM loaded
document.addEventListener("DOMContentLoaded", () => {
  initSectionTransitions();
});

// Modifikasi questionMap di quest.js
const questionMap = {
  1: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
  2: [10, 13, 14, 15, 16, 17, 18, 19],
  3: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
  4: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
  5: [10, 13, 14, 15, 16, 17, 18, 19],
};

// Tambahkan fungsi untuk memeriksa visibilitas pertanyaan
function isQuestionVisible(questionId) {
  const firstQuestionAnswer = document.querySelector(
    'input[name="question_1"]:checked'
  )?.value;
  if (!firstQuestionAnswer) return questionId === 1;

  return (
    questionId === 1 ||
    (questionMap[firstQuestionAnswer] &&
      questionMap[firstQuestionAnswer].includes(questionId))
  );
}

// const form = document.getElementById('questionForm');
// // Modifikasi validasi form
// form.addEventListener('submit', function(e) {
//     e.preventDefault();

//     let isValid = true;
//     const unansweredQuestions = [];

//     document.querySelectorAll('.question-item').forEach((item) => {
//         const questionId = parseInt(item.getAttribute('data-question-id'));

//         if (!isQuestionVisible(questionId)) return;

//     });
// });

function initializeLoadingScreen() {
  gsap.set("body > *:not(.loading)", { opacity: 0 });

  const loadingTimeline = gsap.timeline();

  // Animasi logo tetap sama
  loadingTimeline.to("#loadingLogo", {
    scale: 1.2,
    duration: 1,
    repeat: 3,
    yoyo: true,
    ease: "power1.inOut",
  });

  // Animasi teks loading tetap sama
  const loadingText = document.getElementById("loadingText");
  if (loadingText) {
    const text = new SplitType(loadingText, { types: "chars" });
    loadingTimeline.to(
      text.chars,
      {
        y: -20,
        duration: 0.5,
        stagger: 0.1,
        repeat: 3,
        yoyo: true,
        ease: "power1.inOut",
      },
      "<"
    );
  }

  // Persingkat durasi fade out loading
  loadingTimeline.to(".loading", {
    opacity: 0,
    duration: 0.2,
    onComplete: () => {
      document.querySelector(".loading").style.display = "none";
      startWebsiteAnimations();
    },
  });
}

function startWebsiteAnimations() {
  gsap.to("body > *:not(.loading)", {
    opacity: 1,
    duration: 0.3,
    stagger: 0.1,
    ease: "power2.out",
  });

  initializeGSAPAnimations();
  initSectionTransitions();
}

document.addEventListener("DOMContentLoaded", function () {
  // Mulai dengan loading screen
  initializeLoadingScreen();

  // Inisialisasi Alpine.js dan fungsi lainnya
  window.Alpine = Alpine;

  Alpine.store("notifications", {
    items: [],
    add(message, type = "info") {
      const id = Date.now();
      this.items.push({ id, message, type });
      
      // Animasi masuk yang lebih smooth
      setTimeout(() => {
        gsap.from(`[data-notification-id="${id}"]`, {
          x: 30,
          opacity: 0,
          duration: 0.3,
          ease: "power2.out"
        });
      }, 100);

      // Durasi tampil lebih singkat
      setTimeout(() => {
        gsap.to(`[data-notification-id="${id}"]`, {
          x: 30,
          opacity: 0,
          duration: 0.2,
          ease: "power2.in",
          onComplete: () => {
            this.items = this.items.filter(item => item.id !== id);
          }
        });
      }, 2000);
    },
    remove(id) {
      gsap.to(`[data-notification-id="${id}"]`, {
        x: 50,
        opacity: 0,
        duration: 0.3,
        ease: "power2.in",
        onComplete: () => {
          this.items = this.items.filter(item => item.id !== id);
        }
      });
    }
  });

  Alpine.start();

  // ... rest of the existing code ...
});

// Fungsi untuk menangani aktivasi input teks berdasarkan checkbox/radio
function handleTextInputActivation() {
  // Ambil semua checkbox dan radio button
  const options = document.querySelectorAll(
    'input[type="checkbox"].substituted, input[type="radio"].substituted'
  );

  options.forEach((option) => {
    // Cari input teks terkait saat halaman dimuat
    const wrapper = option.closest(".checkbox-wrapper-1");
    if (!wrapper) return;

    const textInput =
      wrapper.nextElementSibling?.querySelector('input[type="text"]');
    if (!textInput) return;

    // Set kondisi awal
    if (!option.checked) {
      textInput.disabled = true;
      textInput.value = "";
      textInput.classList.remove("bg-white");
      textInput.classList.add("bg-gray-100");
    }

    option.addEventListener("change", function () {
      if (this.checked) {
        // Aktifkan input teks
        textInput.disabled = false;
        textInput.classList.remove("bg-gray-100");
        textInput.classList.add("bg-white");
        textInput.focus();
      } else {
        // Nonaktifkan dan kosongkan input teks
        textInput.disabled = true;
        textInput.value = "";
        textInput.classList.remove("bg-white");
        textInput.classList.add("bg-gray-100");
      }
    });

    // Tambahkan event listener untuk radio button group
    if (option.type === "radio") {
      const radioGroup = document.querySelectorAll(
        `input[name="${option.name}"]`
      );
      radioGroup.forEach((radio) => {
        if (radio !== option) {
          radio.addEventListener("change", function () {
            if (this.checked) {
              // Nonaktifkan dan kosongkan input teks saat radio button lain dipilih
              textInput.disabled = true;
              textInput.value = "";
              textInput.classList.remove("bg-white");
              textInput.classList.add("bg-gray-100");
            }
          });
        }
      });
    }
  });
}

// Panggil fungsi setelah DOM loaded
document.addEventListener("DOMContentLoaded", () => {
  handleTextInputActivation();
  initSectionTransitions();
});

// Bungkus dalam IIFE untuk menghindari variable scope global
(function() {
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    
    if (!form) return;

    // Fungsi untuk menampilkan loading
    function showLoading() {
      const loadingEl = document.createElement('div');
      loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
      loadingEl.innerHTML = `
        <div class="bg-white p-5 rounded-lg flex flex-col items-center">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
          <p class="mt-3 text-gray-700">Menyimpan data...</p>
        </div>
      `;
      document.body.appendChild(loadingEl);
    }

    // Fungsi untuk menyembunyikan loading
    function hideLoading() {
      const loadingEl = document.querySelector('.fixed.inset-0.bg-black');
      if (loadingEl) loadingEl.remove();
    }

    // Fungsi untuk validasi form
    function validateForm() {
      let isValid = true;
      let errors = [];

      // Reset validasi
      document.querySelectorAll("input, select").forEach((field) => {
        field.classList.remove("border-red-500", "ring-red-200");
        field.classList.add("border-slate-200");
      });

      // Validasi field required
      const requiredFields = {
        nama: "Nama",
        nim: "NIM",
        email: "Email",
        tgl_lahir: "Tanggal Lahir", 
        thn_lulus: "Tahun Lulus",
        perguruan: "Program Studi"
      };

      Object.entries(requiredFields).forEach(([fieldName, label]) => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
          field.classList.remove("border-slate-200");
          field.classList.add("border-red-500", "ring-red-200");
          isValid = false;
          errors.push(`${label} harus diisi`);
        }
      });

      // Validasi format nama
      const namaField = document.querySelector('[name="nama"]');
      if (namaField.value && !/^[a-zA-Z\s]+$/.test(namaField.value)) {
        namaField.classList.add("border-red-500", "ring-red-200");
        isValid = false;
        errors.push("Nama hanya boleh berisi huruf");
      }

      // Validasi NIK
      const nikField = document.querySelector('[name="nik"]');
      if (nikField.value && nikField.value.length !== 16) {
        nikField.classList.add("border-red-500", "ring-red-200");
        isValid = false;
        errors.push("NIK harus 16 digit");
      }

      // Validasi NPWP
      const npwpField = document.querySelector('[name="npwp"]');
      if (npwpField.value && npwpField.value.length !== 15) {
        npwpField.classList.add("border-red-500", "ring-red-200");
        isValid = false;
        errors.push("NPWP harus 15 digit");
      }

      return { isValid, errors };
    }

    // Handle form submission
    form.addEventListener("submit", async function(e) {
      e.preventDefault();

      const { isValid, errors } = validateForm();

      if (!isValid) {
        Alpine.store("notifications").add(errors.join(", "), "error");
        return;
      }

      try {
        showLoading();

        const formData = new FormData(form);
        
        // Simpan data ke localStorage sebelum submit
        const formDataObj = {};
        formData.forEach((value, key) => {
          formDataObj[key] = value;
        });
        localStorage.setItem('formData', JSON.stringify(formDataObj));

        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const result = await response.json();

        if (result.status === 'success') {
          Alpine.store("notifications").add(result.message, "success");
          if (result.redirect) {
            window.location.href = result.redirect;
          }
        } else {
          // Tampilkan error dan highlight field yang bermasalah
          document.querySelectorAll("input, select").forEach((field) => {
            if (result.message.toLowerCase().includes(field.name.toLowerCase())) {
              field.classList.remove("border-slate-200");
              field.classList.add("border-red-500", "ring-red-200");
            }
          });
          Alpine.store("notifications").add(result.message, "error");
        }
      } catch (error) {
        console.error('Error:', error);
        Alpine.store("notifications").add("Terjadi kesalahan sistem", "error");
      } finally {
        hideLoading();
      }
    });

    // Restore form data dari localStorage jika ada
    const savedData = localStorage.getItem('formData');
    if (savedData) {
      const formData = JSON.parse(savedData);
      Object.entries(formData).forEach(([key, value]) => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) field.value = value;
      });
    }
  });
})();

function checkAnswerStatus() {
  // Cek apakah user sudah menjawab
  const hasAnswered = localStorage.getItem('hasAnswered');
  const userId = localStorage.getItem('userId');
  const hash = localStorage.getItem('userHash');

  if (hasAnswered === 'true' && userId && hash) {
    // Redirect ke halaman show_answers
    window.location.href = `/traceritesa/tracer/lihatapcb?q=${hash}`;
    return true;
  }
  return false;
}

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
  const currentPath = window.location.pathname;
  if (currentPath === '/traceritesa/tracer/' || 
      currentPath === '/traceritesa/tracer/index.php') {
    checkAnswerStatus();
  }
});
