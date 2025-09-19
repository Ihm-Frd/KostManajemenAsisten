import './bootstrap';

// Navbar fixed
window.onscroll = function () {
  const header = document.querySelector("header");
  const fixedNav = header.offsetTop;
  const toTop = document.querySelector("#to-top");

  if (window.pageYOffset > fixedNav) {
    header.classList.add("navbar-fixed");
    toTop.classList.remove("hidden");
  } else {
    header.classList.remove("navbar-fixed");
    toTop.classList.add("hidden");
  }
};

//hamburger menu (muncul saat mode android saja)

const hamburger = document.querySelector("#hamburger");
const navMenu = document.querySelector("#nav-menu");

hamburger.addEventListener("click", function () {
  hamburger.classList.toggle("hamburger-active");
  navMenu.classList.toggle("hidden");
});

// Klik Sembarang Utk Close Pop Up Navbar Android
window.addEventListener("click", function (e) {
  if (e.target != hamburger && e.target != navMenu) {
    hamburger.classList.remove("hamburger-active");
    navMenu.classList.add("hidden");
  }
});

// Dropdown Navbar (Tentang Desa )
var dropdownButton = document.getElementById("dropdownTentang");
var dropdownMenu = document.getElementById("dropdown");

dropdownButton.addEventListener("click", function () {
  dropdownMenu.classList.toggle("hidden");
});

// Menutup dropdown saat mengklik di luar dropdown
window.addEventListener("click", function (event) {
  if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
    dropdownMenu.classList.add("hidden");
  }
});


// Dark Mode
const darkToggle = document.querySelector('#dark-toggle');
const html = document.querySelector('html');

darkToggle.addEventListener('click', function()
{
  if (darkToggle.checked) {
    html.classList.add('dark');
    localStorage.theme = 'dark'
  } else {
    html.classList.remove('dark');
    localStorage.theme = 'light'

  }
});

// Pindah Posisi Togle Sesuai Mode Yng Disimpan
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
     darkToggle.checked = true;
    } else {
      darkToggle.checked = false;
    } 
