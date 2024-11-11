import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import SplitType from 'split-type';

// Inisialisasi Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Export untuk penggunaan global
window.gsap = gsap;
window.SplitType = SplitType;

// Kode yang sudah ada
document.querySelector(".mobile-menu")?.addEventListener("click", function () {
  const nav = document.querySelector("nav");
  nav?.classList.toggle("hidden");
});

document.querySelector("form").addEventListener("submit", function (e) {
  const notification = document.querySelector("[x-data]").__x.$data;

  // Reset semua field
  document.querySelectorAll("input, select").forEach((field) => {
    field.classList.remove("border-red-500", "ring-red-200");
    field.classList.add("border-slate-200");
  });

  let isValid = true;
  let emptyFields = [];

  // Validasi field wajib
  const requiredFields = {
    nama: "Nama",
    nim: "NIM",
    email: "Email",
    tgl_lahir: "Tanggal Lahir",
    thn_lulus: "Tahun Lulus",
    perguruan: "Program Studi",
  };

  Object.entries(requiredFields).forEach(([fieldName, label]) => {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (!field.value.trim()) {
      field.classList.remove("border-slate-200");
      field.classList.add("border-red-500", "ring-red-200");
      isValid = false;
      emptyFields.push(label);
    }
  });

  if (!isValid) {
    e.preventDefault();
    notification.showNotif(
      `Field berikut harus diisi: ${emptyFields.join(", ")}`,
      "error"
    );
    return;
  }

  // Validasi format email
  const emailField = document.querySelector('[name="email"]');
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(emailField.value)) {
    emailField.classList.remove("border-slate-200");
    emailField.classList.add("border-red-500", "ring-red-200");
    e.preventDefault();
    notification.showNotif("Format email tidak valid", "error");
    return;
  }

  notification.showNotif("Data berhasil divalidasi", "success");
});

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

const questionContainer = document.getElementById('questionContainer'), questions = questionContainer.querySelectorAll('.input[type="radio"]'), forms = document.querySelector('.form');
questions.forEach((question) => {
    question.addEventListener("change", function () {
        document.querySelectorAll("label").forEach((label) => {
            label.classList.remove("text-blue-600");
        });

        document.querySelectorAll("label span").forEach((span) => {
            span.classList.remove("font-medium", "text-blue-600", "font-semibold");
        });

        const span = this.nextElementSibling;
        span.classList.add("font-medium");
        const letterSpan = span.querySelector("span");
        if (letterSpan) {
            letterSpan.classList.add("text-blue-600", "font-semibold", "mr-2");
        }
    });
});


forms.addEventListener('submit', function(e) {
    const requiredInputs = document.querySelectorAll('input[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua pertanyaan yang wajib dijawab');
    }
});