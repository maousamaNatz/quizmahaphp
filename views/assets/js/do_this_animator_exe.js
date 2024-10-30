document.addEventListener("DOMContentLoaded", function () {
  const logos = document.getElementById("logos");
  const logosDesc = document.getElementById("logos-desc");
  const form = document.getElementById("form-question-one");
  const inputs = form.querySelectorAll(".input-field");
  const checkbox = document.querySelector(
    '.btn-mode .checkbox-wrapper-2 input[type="checkbox"]'
  );

  // Check if dark mode is enabled in localStorage
  if (localStorage.getItem('darkMode') === 'enabled') {
    document.documentElement.classList.add('dark');
    checkbox.checked = true;
  }

  checkbox.addEventListener("change", function () {
    if (checkbox.checked) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('darkMode', 'enabled');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('darkMode', 'disabled');
    }
  });

  inputs.forEach((input) => {
    const label = input.previousElementSibling;

    if (input.value !== "") {
      label.classList.add("active");
    }

    input.addEventListener("focus", () => {
      label.classList.add("active");
    });

    input.addEventListener("blur", () => {
      if (input.value === "") {
        label.classList.remove("active");
      }
    });
  });

  // Custom select input logic
  const customSelect = document.getElementById("customSelect");
  const dropdown = customSelect.nextElementSibling;
  // Show dropdown on input click
  customSelect.addEventListener("click", () => {
    dropdown.classList.toggle("hidden");
    customSelect.classList.add("dropdown-open"); // Menandai bahwa dropdown sedang terbuka
    customSelect.previousElementSibling.classList.add("active"); // Label tetap di atas
  });

  // Select dropdown item
  dropdown.querySelectorAll(".dropdown-item").forEach((item) => {
    item.addEventListener("click", () => {
      customSelect.value = item.textContent;
      customSelect.setAttribute("data-value", item.getAttribute("data-value"));
      dropdown.classList.add("hidden");
      customSelect.classList.remove("dropdown-open"); // Hapus tanda dropdown terbuka
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", (e) => {
    if (!customSelect.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden");
      customSelect.classList.remove("dropdown-open"); // Hapus tanda dropdown terbuka
      if (customSelect.value === "") {
        customSelect.previousElementSibling.classList.remove("active"); // Kembalikan posisi label jika kosong
      }
    }
  });
});
