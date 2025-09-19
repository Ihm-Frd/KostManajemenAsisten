<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ URL::to('assets/img/Logo_Anugrah Group.png') }}" type="image/x-icon">
    <title>KostManajemenAsisten</title>

    
     <!-- Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />

    <!-- TailWind CSS -->
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-524359a4.css') }}">
    
    <!-- JS -->
    <!--<script src="{{ url('build/assets/app-b0b3f613.js/') }}" defer></script>-->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- JS Form WhatsApp -->
    <script src="{{ asset('assets/js/WA.js') }}" defer></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    
    <!-- java Scriprt TailWind -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Simpan Settingan Dark Mode -->
    <script>
      if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    </script>

    
  </head>

  <body>

        <!-- Navbar -->
       <header class="bg-transparent absolute top-0 left-0 w-full flex items-center z-10 lg:dark:bg-transparent">
        <div class="container px-10">

          <!-- Tittle Web -->
          <div class="flex items-center justify-between relative">
            <div class="px-4">
              <a href="#home" class="font-bold text-lg text-ijo block py-6">Konsisten</a>
            </div>
            
            <!-- Element Tombol -->
            <div class="flex items-center px-4">
              <button id="hamburger" name="hamburger" type="button" class="block absolute right-4 lg:hidden">
                <span class="hamburger-line trasition duration-300 origin-top-left"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line trasition duration-300 origin-bottom-left"></span>
                
              </button>
              <nav id="nav-menu" class="hidden absolute py-5 bg-white shadow-lg rounded-lg max-w-[250px] w-full right-4 top-full lg:block lg:static lg:bg-transparent lg:max-w-full lg:shadow-none lg:rounded-none dark:bg-gelap dark:shadow-slate-500 lg:dark:bg-transparent">
                <ul class="block lg:flex">
                  <li class="group">
                    <a href="#home" class="text-base text-gelap py-2 mx-5 flex group-hover:text-ijo dark:text-white">Beranda</a>
                  </li>
                  <li class="group">
                    
                    <div class="dropdown-container">
                      <button id="dropdownTentang" data-dropdown-toggle="dropdown" class="text-base text-gelap py-2 mx-5 flex group-hover:text-ijo dark:text-white" type="button">Tentang Anugrah <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                      <!-- Dropdown menu -->
                      <div class="dropdown-menu dark:bg-gelap" id="dropdown">
                        <ul class="py-2 text-sm text-black dark:text-white bg-slate-50 dark:bg-gelap" aria-labelledby="dropdownTentang">
                          <li>
                            <a href="#about" class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Tentang Anugrah</a>
                          </li>
                          <li>
                            <a href="#Statistik" class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">Statsitik Data</a>
                          </li>
                        </ul>
                      </div>
                    </div>

                  </li>

                  <li class="group">
                    <a href="#portal_berita" class="text-base text-gelap py-2 mx-5 flex group-hover:text-ijo dark:text-white">Berita</a>
                  </li>

                  <li class="group">
                    <a href="#galeri" class="text-base text-gelap py-2 mx-5 flex group-hover:text-ijo dark:text-white">Galeri</a>
                  </li>
                  
                  <li class="group">
                    <a href="#contact" class="text-base text-gelap py-2 mx-5 flex group-hover:text-ijo dark:text-white">Kontak</a>
                  </li>

                  <!-- Dark Mode -->
                  <li class="flex items-center pl-8 mt-3 lg:mt-0">
                    <div class="flex items-center">
                     <span class="mr-2 text-bold text-amber-400 dark:text-slate-500"><i class="bi bi-brightness-high-fill"></i></span>
                      <input id="dark-toggle" type="checkbox" class="hidden" >
                      <label for="dark-toggle">
                        <div class="flex h-5 w-10 cursor-pointer items-center rounded-full bg-slate-400 p-1 dark:bg-slate-700 dark:shadow-slate-200 shadow-sm">
                          <div class="toggle-circle h-4 w-4 rounded-full bg-white transition duration-500 ease-in-out"></div>
                        </div>
                      </label>
                      <span class="ml-2 text-bold text-slate-400 dark:text-amber-300"><i class="bi bi-moon-stars-fill"></i></span>
                    </div>
                  </li>
                  <!-- Dark Mode Enddddddd -->

                </ul>
              </nav>
            </div>
            
          </div>
        </div>
       </header>

         <script>
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

    </script>
        <!-- Navbar End -->


        <!-- Hero Section -->

        <section id="home" class="pt-36 dark:bg-slate-800 lg:-mt-20">
          <div class="container">
            <div class="flex flex-wrap">
              <!-- Tulisan Di kiri -->
              <div class="w-full self-center px-4 lg:w-1/2 mb-10">
                <h1 class="text-base font-semibold text-ijo md:text-xl">Selamat Datang 👋🏽</h1>
                <h2 class="font-medium text-gelap text-lg lg:text-2xl mb-3 dark:text-white">Di Kost Manajemen Asisten <span class="block font-bold text-gelap text-4xl lg:text-5xl mt-1 dark:text-white">Konsisten</span></h2>
                <p class="font-medium text-pudar leading-relaxed dark:text-slate-400">Products from Anugrah Group</p>
                <p class="font-medium text-pudar mb-10 leading-relaxed dark:text-slate-400">Created by Ilham Firdaus</p>
                <a href="{{ url('/admin/login') }}" class="text-base font-bold text-white bg-teal-500 py-3 px-8 rounded-full hover:shadow-xl hover:bg-teal-600 transition duration-300 z-59 ">Login Sekarang</a>
              </div>

              <!-- Gambar Dikanan  -->
              <div class="w-full self-end px-4 lg:w-1/2">
                <div class="mt-10 relative lg:mt-10 lg:right-0 hover:animate-bounce">
                  <img src="{{  URL::to('assets/img/Logo_Anugrah Group.png') }}" alt="AnugrahGroup" class="max-w-full w-100 mx-auto relative z-10" />

                  <span class="absolute bottom-0 left-1/2 -translate-x-1/2 lg:scale-155">
                    <svg
                      class="h-[300px] lg:h-[600px] "
                      width="500"
                      height="300"
                      viewBox="0 0 200 200"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        fill="#14b8a6"
                        d="M62.6,-15.9C69.4,0.4,54.9,28.1,33.1,43.4C11.4,58.7,-17.8,61.6,-39.3,47.6C-60.8,33.5,-74.7,2.3,-66.9,-15.5C-59,-33.3,-29.5,-37.9,-0.8,-37.6C27.9,-37.4,55.9,-32.3,62.6,-15.9Z"
                        transform="translate(100 100) scale(1.4)"
                      />
                    </svg>

                    {{-- <svg width="500" height="500" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                      <path
                        fill="#14b8a6"
                        d="M62.6,-15.9C69.4,0.4,54.9,28.1,33.1,43.4C11.4,58.7,-17.8,61.6,-39.3,47.6C-60.8,33.5,-74.7,2.3,-66.9,-15.5C-59,-33.3,-29.5,-37.9,-0.8,-37.6C27.9,-37.4,55.9,-32.3,62.6,-15.9Z"
                        transform="translate(100 100) scale(1.4)"
                      />
                    </svg> --}}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <br>
          <br>
        </section>

        <!-- Hero Section End -->

    
        <!-- About Section -->
        <section id="about" class="pt-36 pb-32 bg-slate-100 dark:bg-slate-800 ">
          <h4 class="font-bold uppercase text-ijo text-lg mb-3 text-center pb-9">Sekilas Anugrah Group</h4>
          <div class="container">
            <div class="flex flex-wrap">
              <div class="w-full px-4 mb-10 lg:w-1/2">
                <h2 class="font-bold text-gelap text-3xl mb-5 max-w-md lg:text-4xl dark:text-white">Profil Anugrah Group</h2>
                <p class="text-justify font-medium text-base text-pudar max-w-xl lg:text-lg mb-5 dark:text-slate-300">Anugrah Group adalah salah satu penyedia properti sewa terkemuka di area
                  cikarang, berdiri sejak tahun 1993. Dengan ribuan unit sewa yang terletak di berbagai daerah, properti yang
                  disewakan Anugrah Group tidak berlokasi pada satu tempat. Tetapi tersebar di
                  banyak area. Fokus bisnis ini mencakup sewa rumah tinggal, apartemen, kost, toko,
                  dan ruko serta parcel tanah dan tempat parkir sepeda motor. Sebagai badan usaha
                  Anugrah Group juga memiliki kantor pusat, yang beralamat di jl. raya serang -
                  cibarusah no 33, Serang, Cikarang Selatan, Bekasi. Dari sini, semua kegiatan
                  operasional dan layanan pelanggan dikelola</p>
                <hr>
                <h2 class="font-bold text-gelap text-3xl mb-5 max-w-md lg:text-4xl mt-3 dark:text-white">Visi & Misi</h2>
                <p class="text-justify font-medium text-base text-pudar max-w-xl lg:text-lg dark:text-slate-300"><b>Visi :</b> Menjadi penyedia solusi properti sewa yang terpercaya, terjangkau, dan tersebar luas di seluruh wilayah Cikarang dan sekitarnya, dengan mengedepankan kenyamanan, pelayanan, dan keberlanjutan.</p>
                <p class="text-justify font-medium text-base text-pudar max-w-xl lg:text-lg dark:text-slate-300"><b>Misi :</b> Anugrah Group berkomitmen Menyediakan properti sewa yang nyaman dan terjangkau di berbagai lokasi, dengan layanan profesional dan sistem pengelolaan modern untuk mendukung kebutuhan tempat tinggal dan usaha masyarakat.</p>
                  </div>

              <!-- Info Kontak & Alamat -->
            
                <div class="w-full px-4 lg:w-1/2 ">
                <!-- Sosial Media -->
                <h3 class="text-bold text-gelap text-2xl mb-4 lg:text-3xl lg:pt-10 dark:text-white">Follow Media Sosial Kami</h3>
                <p class="font-medium text-base text-pudar max-w-xl mb-6 dark:text-slate-300">Follow media sosial kami untuk mendapatkan info tentang kost, apartemen, ruko, dan info promo menarik yang kami akan bagikan di media sosial kami .</p>

                <div class="flex items-center justify-center">
                  <!-- WhatsApp -->
                  <a href="#contact" class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border hover:dark:text-white text-pudar dark:text-slate-300 border-slate-400 hover:border-green-700 hover:bg-green-700 hover:text-white hover:animate-bounce"
                    ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <title>WhatsApp</title>
                      <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                      />
                    </svg>
                  </a>

                  <!-- Facebook -->
                  <a
                    href="https://www.facebook.com"
                    target="_blank"
                    class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border text-pudar dark:text-slate-300 hover:dark:text-white border-slate-400 hover:border-blue-700 hover:bg-blue-700 hover:text-white hover:animate-bounce"
                    ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <title>Facebook</title>
                      <path
                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                      />
                    </svg>
                  </a>

                  <!-- Twitter -->
                  <a
                    href="https://twitter.com"
                    target="_blank"
                    class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border text-pudar dark:text-slate-300 hover:dark:text-white border-slate-400 hover:border-ijo hover:bg-ijo hover:text-white hover:animate-bounce"
                    ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <title>Twitter</title>
                      <path
                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
                      />
                    </svg>
                  </a>

                  <!-- Youtube -->
                  <a
                    href="https://www.youtube.com"
                    target="_blank"
                    class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border text-pudar dark:text-slate-300 hover:dark:text-white border-slate-400 hover:border-red-600 hover:bg-red-600 hover:text-white hover:animate-bounce"
                    ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>YouTube</title><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                  </a>

                  <!-- Email -->
                  <a
                    href="mailto:anugrahgroup@gmail.com?subject=WebSite AnugrahGroup Official Mail Contact-layanan%20Penghuni"
                    target="_blank"
                    class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border text-pudar dark:hover:text-white dark:text-slate-400 border-slate-400 hover:border-red-900 hover:bg-red-900 hover:text-white hover:animate-bounce"
                    ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <title>Gmail</title>
                      <path
                        d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"
                      />
                    </svg>
                  </a>
                </div>
                <hr class="my-4 border-gray-300">
              

              <!-- Map Lokasi -->
              
                <div class="mt-10 relative lg:mt-0 lg:right-0">
                  <h3 class="text-semibold text-gelap text-2xl mb-4 dark:text-white">Alamat Kami</h3>
                  <p class="font-medium text-base text-pudar max-w-xl mb-6 dark:text-slate-300">Anugrah Group
                    Jl. Raya Serang - Cibarusah Serang, Kongsi No.33, RT.012/RW.06, Sukadami, Cikarang Sel., Kabupaten Bekasi, Jawa Barat 17530</p>
                  <div class="container max-w-fit rounded-lg overflow-hidden object-center mx-2">
                    <iframe
                      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3094625291037!2d107.11877014272393!3d-6.353970208952262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699b7146405827%3A0x8dbba005e8814db0!2sAnugrah%20Group!5e0!3m2!1sid!2sid!4v1751389901168!5m2!1sid!2sid"
                      allowfullscreen=""
                      loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"
                      class="max-w-2xl h-[200px] lg:h-[250px] lg:w-[430px] border-4 border-ijo rounded-xl mb-4 hover:shadow-lg hover:shadow-slate-700 dark:hover:shadow-white"
                      style="border-radius: 10px;"
                    ></iframe>
                  </div>
                </div>

              <!-- Info Kontak & Alamat Enddddddd -->
            </div>
          </div>
        </div>
        </section>

        <!-- About Section End -->

       {{-- Portal Berita --}}
        <section id="portal_berita" class="pt-20 pb-10 bg-slate-800 dark:bg-slate-900">
          <div class="w-full px-4">
            <div class="max-w-xl mx-auto text-center mb-16">
              <h4 class="font-semibold text-lg text-ijo mb-2">Portal Berita</h4>
              <h2 class="font-bold text-white text-3xl mb-4 sm:text-4xl lg:text-5xl">Anugrah Update</h2>
              <p class="font-medium text-md md:text-lg text-slate-300">
                Temukan kabar terbaru seputar aktivitas dan properti milik Anugrah Group.
              </p>
            </div>
          </div>
        
          <div class="relative w-full max-w-screen-xl mx-auto px-4 overflow-hidden">
            <div id="slider" class="flex transition-transform duration-700 ease-in-out">
              @foreach ($posters as $poster)
                <div class="w-full flex-shrink-0 px-4 btn-outline-danger">
                  <div class="relative aspect-[16/9] max-w-3xl mx-auto w-full overflow-hidden rounded-xl">
                    <!-- Background Gambar -->
                    <div class="absolute inset-0 w-full h-full bg-center bg-contain bg-no-repeat rounded-xl"
                         style="background-image: url('{{ asset('storage/' . $poster->gambar) }}')"></div>
                   
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-opacity-400"></div>
        
                    <!-- Konten Teks -->
                     <a href="{{ asset('storage/' . $poster->gambar) }}" target="_blank">
                         
                    <div class="relative z-10 h-full flex flex-col justify-end px-6 pb-6 text-white">
                      <div class="bg-black/60 backdrop-blur-sm p-4 rounded-lg">
                        <h3 class="text-3xl font-bold">{{ $poster->judul }}</h3>
                        <p class="mt-2 line-clamp-2">{{ $poster->deskripsi }}</p>
                        @if($poster->link)
                          <a href="{{ $poster->link }}" class="inline-block mt-4 bg-ijo text-white px-4 py-2 rounded-full text-sm hover:shadow-md transition">
                            Baca Selengkapnya
                          </a>
                        @endif
                      </div>
                    </div>
                    </a>
                    
                  </div>
                </div>
              @endforeach
            </div>
        
            <!-- Tombol Navigasi -->
            <button id="prev" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full text-slate-700 hover:opacity-90 z-20">
              <i class="bi bi-caret-left-fill text-2xl"></i>
            </button>
            <button id="next" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full text-slate-700 hover:opacity-90 z-20">
              <i class="bi bi-caret-right-fill text-2xl"></i>
            </button>
        
            <!-- Dots Navigasi -->
            <div class="absolute bottom-6 w-full flex justify-center gap-2 z-20">
              @foreach ($posters as $index => $poster)
                <div class="dot w-3 h-3 rounded-full cursor-pointer {{ $index === 0 ? 'bg-ijo opacity-100' : 'bg-white opacity-50' }}" data-index="{{ $index }}"></div>
              @endforeach
            </div>
          </div>
        </section>

        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('slider');
            const slides = document.querySelectorAll('#slider > div');
            const dots = document.querySelectorAll('.dot');
            const nextBtn = document.getElementById('next');
            const prevBtn = document.getElementById('prev');
        
            let current = 0;
            const total = slides.length;
        
            function updateSlider(index) {
              const slideWidth = slides[0].offsetWidth;
              slider.style.transform = `translateX(-${index * slideWidth}px)`;
        
              dots.forEach((dot, i) => {
                dot.classList.toggle('bg-ijo', i === index);
                dot.classList.toggle('bg-white', i !== index);
                dot.classList.toggle('opacity-100', i === index);
                dot.classList.toggle('opacity-50', i !== index);
              });
            }
        
            nextBtn?.addEventListener('click', () => {
              current = (current + 1) % total;
              updateSlider(current);
            });
        
            prevBtn?.addEventListener('click', () => {
              current = (current - 1 + total) % total;
              updateSlider(current);
            });
        
            dots.forEach((dot, i) => {
              dot.addEventListener('click', () => {
                current = i;
                updateSlider(current);
              });
            });
        
            setInterval(() => {
              current = (current + 1) % total;
              updateSlider(current);
            }, 5000);
        
            window.addEventListener('resize', () => updateSlider(current));
        
            updateSlider(0);
          });
        </script> 
       {{-- Portal Berita Enddddddd --}}

        <!-- Galeri -->
       <section id="galeri" class="pt-36 pb-16 bg-slate-200 dark:bg-slate-800">
          <div class="container px-4">
            
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-16">
              <h4 class="text-ijo font-semibold text-lg mb-2">Galeri Anugrah Group</h4>
              <h2 class="text-gelap dark:text-white font-bold text-3xl sm:text-4xl lg:text-5xl mb-4">Dokumentasi</h2>
              <p class="text-pudar dark:text-slate-400 text-md md:text-lg font-medium">
                Dokumentasi Kegiatan Anugrah Group Dalam Pengelolaan Kost atau Kontrakan Di Cikarang Selatan
              </p>
            </div>
        
            <!-- Card Container -->
            <div class="grid gap-2 grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:w-10/12 xl:mx-auto">
              @foreach($galeris as $index => $galeri)
              <div class="galeri-item bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden flex flex-col">
                
                <!-- Gambar -->
                 <a href="{{ asset('storage/' . $galeri->gambar) }}" target="_blank">
                     
                <div class="h-48 w-full">
                  <img src="{{ asset('storage/' . $galeri->gambar) }}"
                       alt="galeri-{{ $index }}"
                       class="w-full h-full object-cover object-center">
                </div>
                    </a>
        
                <!-- Konten -->
                <div x-data="{ expanded: false }" class="p-4 flex flex-col flex-1 justify-between">
                  
                  <!-- Judul -->
                  <h3 class="font-semibold text-xl text-gelap dark:text-white mb-2 line-clamp-2">
                    {{ $galeri->judul }}
                  </h3>
        
                  <!-- Deskripsi -->
                  <div class="text-pudar dark:text-slate-300 text-base relative overflow-hidden">
                    <div :class="expanded ? 'max-h-[600px]' : 'max-h-[3.5rem]'"
                         class="transition-all duration-300 ease-in-out overflow-hidden">
                      <p class="break-words">{{ $galeri->deskripsi }}</p>
                    </div>
                  </div>
        
                  <!-- Tombol Selengkapnya -->
                  <button @click="expanded = !expanded"
                          x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"
                          class="text-ijo mt-2 text-sm hover:underline self-start transition-colors duration-200">
                  </button>
        
                </div>
              </div>
              @endforeach
            </div>
        
          </div>
        </section>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
          const items = document.querySelectorAll(".galeri-item");
          const maxVisible = window.innerWidth <= 640 ? 2 : 6; // layar kecil 2, besar 6
          let currentStart = 0;
        
          function updateGaleri() {
            items.forEach((item, index) => {
              item.classList.add("hidden");
              if (
                index >= currentStart &&
                index < currentStart + maxVisible
              ) {
                item.classList.remove("hidden");
              }
            });
        
            currentStart += maxVisible;
            if (currentStart >= items.length) {
              currentStart = 0;
            }
          }
        
          updateGaleri(); // <<< tambahkan ini supaya langsung rapi di awal
          setInterval(updateGaleri, 6000);
        });
        </script>

          
        <!-- Galeri enddddd -->

        {{-- Statistik Data --}}

        <section id="Statistik" class="pt-36 pb-32 bg-slate-800 dark:bg-slate-900">
          <div class="container px-4">
            <div class="w-full px-4">
              <div class="mx-auto text-center mb-16">
                <h4 class="font-semibold text-lg text-ijo mb-2">Statistik</h4>
                <h2 class="font-bold text-white text-3xl sm:text-4xl lg:text-5xl">Statistik Data Kost</h2>
              </div>
            </div>
        
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto place-items-center">

              <!-- Card -->
              @php
                  $cards = [
                      [
                          'title' => 'Data Penghuni Kost',
                          'image' => 'Logo_dataPenghuni.png',
                          'bg' => 'bg-slate-300',
                          'items' => [
                              ['label' => 'Total Penghuni', 'value' => $jumlahPenghuni],
                              ['label' => 'Belum Bayar', 'value' => $penghuniBelumBayar, 'class' => 'text-red-500']
                          ]
                      ],
                      [
                          'title' => 'Data Kamar',
                          'image' => 'Logo_dataKamar.png',
                          'bg' => 'bg-blue-300',
                          'items' => [
                              ['label' => 'Terpakai', 'value' => $jumlahKamarTerpakai],
                              ['label' => 'Kosong', 'value' => $jumlahKamarKosong],
                              ['label' => 'Renovasi', 'value' => $jumlahKamarRenovasi],
                          ]
                      ],
                      [
                          'title' => 'Trend Kelas Fasilitas',
                          'image' => 'logo_Tren.png',
                          'bg' => 'bg-purple-300',
                          'items' => $statFasilitas,
                          'type' => 'list'
                        ],
                        [
                          'title' => 'Jumlah Penghuni Berdasarkan Lokasi',
                          'image' => 'Logo_Statistik.png',
                          'bg' => 'bg-purple-200',
                          'items' => $statLokasi,
                          'type' => 'list'
                      ],
                  ];
              @endphp
        
              @foreach($cards as $card)
              <div class="max-w-md mx-auto w-full bg-white dark:bg-slate-900 rounded-xl shadow-md overflow-hidden flex">
                <div class="w-1/3 {{ $card['bg'] }} dark:bg-slate-700 flex items-center justify-center">
                  <img src="{{ asset('assets/img/' . $card['image']) }}" alt="card icon"
                       class="object-cover w-full h-32">
                </div>
                <div class="p-5 w-2/3 flex flex-col justify-center">
                  <h3 class="text-xl font-bold text-gelap dark:text-white mb-2">{{ $card['title'] }}</h3>
        
                  @if(isset($card['type']) && $card['type'] === 'list')
                    <ul class="list-disc list-inside text-base text-pudar dark:text-slate-300 space-y-1">
                      @foreach($card['items'] as $label => $val)
                        <li>{{ $label }}: {{ $val }}</li>
                      @endforeach
                    </ul>
                  @else
                    @foreach($card['items'] as $item)
                      <p class="text-base text-pudar dark:text-slate-300">
                        {{ $item['label'] }}:
                        <span class="font-semibold {{ $item['class'] ?? 'text-black dark:text-white' }}">{{ $item['value'] }}</span>
                      </p>
                    @endforeach
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </section>
        {{-- Statistik Data End --}}


        <!-- Kontak Form -->
       <section id="contact" class="pt-36 pb-32 bg-slate-200 dark:bg-slate-800 ">
          <div class="container">
            <div class="w-full px-4">
              <div class="mx-auto text-center mb-16">
                <h4 class="font-semibold text-lg text-ijo mb-2">Kontak</h4>
                <h2 class="font-bold text-gelap text-3xl mb-4 sm:text-4xl lg:text-5xl dark:text-white">Form <span class="italic sm:not-italic md:italic lg:not-italic xl:italic">WhatsApp</span>  Fast</h2>
                <p class="font-medium text-md text-pudar md:text-lg dark:text-slate-300">Silahkan Bertanya Dengan Respond Cepat Disini</p>
              </div>
            </div>

            <!-- Form Whatsapp Fast -->
            <form id="whatsapp" class="contact100-form validate-form">
              <div class="w-full lg:w-2/3 lg:mx-auto">

                {{-- Nomor Tujuan (Ada Di WA.js)  --}}
                <input class="tujuan" type="hidden" id="noAdmin" />

                <!-- Nama -->
                <div class="w-full px-4 mb-8">
                  <label class="text-md font-bold text-ijo">Nama Lengkap</label>
                  <input type="text" id="nama" class=" nama w-full bg-slate-400 text-black p-3 rounded-lg focus:outline-slate-300 focus:placeholder-slate-700" placeholder="Nama Lengkap">
                </div>

                <!-- Alamat -->
                <div class="w-full px-4 mb-8">
                  <label class="text-md font-bold text-ijo">Alamat</label>
                  <input type="text" id="alamat" class="alamat w-full bg-slate-400 text-black p-3 rounded-lg focus:outline-slate-300 focus:placeholder-slate-700" placeholder="No.Rumah-Rt/Rw-Kampung/Perum">
                </div>

                <!-- Kolom Pesan -->
                <div class="w-full px-4 mb-8">
                  <label class="text-md font-bold text-ijo">Pesan</label>
                  <textarea type="text" id="pesan" class="pesan w-full bg-slate-400 text-black p-3 rounded-lg focus:outline-slate-200 h-32 focus:placeholder-slate-700" placeholder="Silahkan Tulis Apa Yang Ingin Anda Sampaikan"></textarea>
                </div>

                <div class="w-full">
                  <a id="submit" type="button" class="text-base font-bold text-center text-white bg-ijo py-3 px-8 w-full rounded-full hover:bg-teal-700 hover:shadow-lg duration-700">Kirim</a>
                </div>
          
                  
            </div>
            </form>
            <!-- Form Whatsapp Fast Enddddddd -->

          </div>
        </section>
        <!-- Kontak Form Endddddd -->


        <!-- fOOTER -->
        <footer class="bg-gelap pt-24 pb-12">
          <div class="container items-center">
            <div class="flex flex-wrap">

              <div class="w-full px-4 mb-12 text-slate-200 font-medium md:w-1/3">
                <h2 class="font-bold text-4xl text-white mb-5">Kantor Anugrah Group</h2>
                <h3 class="font-bold text-2xl mb-2">Hubungi Kami</h3>
                <p class="mb-2">anugrahgroup@gmail.com</p>
                <p>Anugrah Group
                    Jl. Raya Serang - Cibarusah Serang, Kongsi No.33, RT.012/RW.06, Sukadami, Cikarang Sel., Kabupaten Bekasi, Jawa Barat 17530</p>
              </div>
              <div class="w-full px-4 mb-12 md:w-1/3">
                <h3 class="font-semibold text-xl text-white mb-5">Tautan</h3>
                <ul class="text-slate-200 ">
                  <li>
                    <a href="#" class="inline-block text-base hover:text-ijo mb-3">Home</a>
                  </li>
                  <li>
                    <a href="#about" class="inline-block text-base hover:text-ijo mb-3">Tentang</a>
                  </li>
                  <li>
                    <a href="#portal_berita" class="inline-block text-base hover:text-ijo mb-3">Portal</a>
                  </li>
                  <li>
                    <a href="#galeri" class="inline-block text-base hover:text-ijo mb-3">Galeri</a>
                  </li>
                  <li>
                    <a href="#Statistik" class="inline-block text-base hover:text-ijo mb-3">Statistik</a>
                  </li>
                </ul>
              </div>
            </div>
            
                  


            <div class="w-full pt-10 border-t border-slate-800">
              <div class="flex items-center justify-center mb-3">

                <!-- INSTAGRAM -->
                <a
                  href="https://www.instagram.com/ihm_frd/?igshid=MTIzZWQxMDU%3D"
                  target="_blank"
                  class="w-12 h-12 mr-6 rounded-full flex justify-center items-center border text-pudar border-slate-400 hover:border-orange-900 hover:bg-orange-900 hover:text-white"
                  ><svg class="fill-current" width="30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Instagram</title><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                    <title></title>
                    <path
                      d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"
                    />
                  </svg>
                </a>
              </div>
              <p class="font-medium text-xs text-slate-400 text-center ">Created With ❤️ By <a href="https://www.instagram.com/ihm_frd" target="_blank" class="text-bold hover:text-ijo"> Ilham Firdaus</a></p>
            </div>

          </div>
        </footer>

        <!-- fOOTER Endddddddd -->
        

        <!-- Tombol Tetap Kembali Ke Home  -->
        <a href="#home" id="to-top" class="hidden fixed bottom-4 right-4 p-4 z-[9999] h-14 w-14 rounded-full bg-ijo text-white hover:animate-bounce">
          <span >
            <svg class="fill-current"  role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Google Home</title><path d="M12 0a1.44 1.44 0 0 0-.947.399L.547 10.762a1.26 1.26 0 0 0-.342.808v11.138c0 .768.53 1.292 1.311 1.292h20.968c.78 0 1.311-.522 1.311-1.292V11.57a1.25 1.25 0 0 0-.34-.804L15.68 3.097h-.001L12.947.4A1.454 1.454 0 0 0 12 0Zm0 6.727 6.552 6.456v5.65H5.446v-5.65z"/></svg>   
          </span>
        </a>
        <!-- Tombol Tetap Kembali Ke Home  Enddddddd -->

 
    <!-- Java skrip Submit WA  -->
    <script src="{{  URL::to('assets/js/WA.js') }}"></script>
    
            
  </body>
</html>
