<?php
require_once 'lang_config.php';
require_once 'db.php';

$current_lang = isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed_langs) ? $_SESSION['lang'] : 'cs';
$base_url = "https://terracomplexapartment.com";
$current_url = $base_url . "/galerie.php";
$og_locale_map = ['cs' => 'cs_CZ', 'en' => 'en_US', 'bg' => 'bg_BG'];
$og_locale = $og_locale_map[$current_lang] ?? 'cs_CZ';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['gal_page_title'] ?></title>
    <meta name="description" content="<?= htmlspecialchars($lang['gal_page_desc']) ?>">
    <meta name="keywords" content="terra complex, galerie, foto, bansko, apartmán">

    <!-- Open Graph tags for social media -->
    <meta property="og:title" content="<?= $lang['gal_page_title'] ?>">
    <meta property="og:description" content="<?= htmlspecialchars($lang['gal_page_desc']) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $current_url ?>?lang=<?= $current_lang ?>">
    <meta property="og:image" content="<?= $base_url ?>/img/lobby1.webp">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?= $og_locale ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $lang['gal_page_title'] ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($lang['gal_page_desc']) ?>">
    <meta name="twitter:image" content="<?= $base_url ?>/img/lobby1.webp">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $current_url ?>?lang=<?= $current_lang ?>">

    <!-- Hreflang for international SEO -->
    <link rel="alternate" hreflang="cs" href="<?= $current_url ?>?lang=cs" />
    <link rel="alternate" hreflang="en" href="<?= $current_url ?>?lang=en" />
    <link rel="alternate" hreflang="bg" href="<?= $current_url ?>?lang=bg" />
    <link rel="alternate" hreflang="x-default" href="<?= $current_url ?>?lang=cs" />

    <!-- Preconnect & Preload -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap"
        as="style">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lightbox2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-teal-500 selection:text-white pt-20">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass transition-all duration-300 shadow-md border-slate-200" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 relative">

                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-2 z-20">
                    <img src="img/newlogo_transparent.png" alt="Logo"
                        class="h-24 md:h-32 w-auto transform translate-y-3 md:translate-y-5 drop-shadow-md relative z-30">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex absolute inset-0 justify-center items-center pointer-events-none">
                    <div
                        class="flex items-center space-x-8 pointer-events-auto bg-slate-100/50 px-6 py-2.5 rounded-full border border-slate-200/50 shadow-sm backdrop-blur-md">
                        <a href="index.php#about"
                            class="text-slate-600 hover:text-teal-600 font-medium transition"><?= $lang['nav_hotel'] ?></a>
                        <a href="galerie.php" class="text-teal-600 font-bold transition"><?= $lang['nav_gallery'] ?></a>
                        <a href="index.php#location"
                            class="text-slate-600 hover:text-teal-600 font-medium transition"><?= $lang['nav_location'] ?></a>
                        <a href="index.php#booking"
                            class="bg-teal-600 text-white px-5 py-2 rounded-full hover:bg-teal-700 shadow-md shadow-teal-600/20 transition font-medium transform hover:scale-105"><?= $lang['nav_book'] ?></a>
                    </div>
                </div>

                <!-- Language Switcher & Mobile Menu Button -->
                <div class="flex items-center space-x-3 z-20">
                    <!-- Language Flags -->
                    <div
                        class="flex items-center space-x-2 bg-slate-100/50 px-3 py-1.5 rounded-full border border-slate-200/50 backdrop-blur-md">
                        <a href="?lang=cs"
                            class="text-xl hover:scale-110 transition <?= $current_lang == 'cs' ? 'opacity-100 drop-shadow-md' : 'opacity-40 hover:opacity-100' ?>"
                            title="Čeština">🇨🇿</a>
                        <a href="?lang=en"
                            class="text-xl hover:scale-110 transition <?= $current_lang == 'en' ? 'opacity-100 drop-shadow-md' : 'opacity-40 hover:opacity-100' ?>"
                            title="English">🇬🇧</a>
                        <a href="?lang=bg"
                            class="text-xl hover:scale-110 transition <?= $current_lang == 'bg' ? 'opacity-100 drop-shadow-md' : 'opacity-40 hover:opacity-100' ?>"
                            title="Български">🇧🇬</a>
                    </div>

                    <!-- Mobile Header Right actions -->
                    <div class="flex lg:hidden items-center space-x-3 z-20">
                        <a href="index.php#booking"
                            class="hidden sm:inline-block bg-teal-600 text-white text-sm px-4 py-2 rounded-full hover:bg-teal-700 shadow transition font-medium"><?= $lang['nav_book'] ?></a>
                        <button id="mobile-menu-btn"
                            class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 bg-slate-100 rounded-full transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                id="menu-icon-open">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                id="menu-icon-close">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu"
            class="hidden lg:hidden bg-white/95 backdrop-blur-xl border-t border-slate-100 absolute w-full left-0 shadow-xl transition-all max-h-0 overflow-hidden"
            style="transition: max-height 0.3s ease-out;">
            <div class="px-4 pt-2 pb-6 space-y-2 mt-2">
                <a href="index.php#about"
                    class="block px-4 py-3 text-base font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition"><?= $lang['nav_hotel'] ?></a>
                <a href="galerie.php"
                    class="block px-4 py-3 text-base font-bold text-teal-700 bg-teal-50 rounded-xl transition"><?= $lang['nav_gallery'] ?></a>
                <a href="index.php#location"
                    class="block px-4 py-3 text-base font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition"><?= $lang['nav_location'] ?></a>
            </div>
        </div>
    </nav>

    <!-- Gallery Section -->
    <section class="py-16 pt-28 bg-slate-900 text-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="text-teal-400 font-semibold tracking-wide uppercase text-sm"><?= $lang['gal_badge'] ?></span>
                <h1 class="text-4xl lg:text-5xl font-bold mt-2 mb-4"><?= $lang['gal_page_h1'] ?></h1>
                <p class="text-slate-400 max-w-2xl mx-auto"><?= $lang['gal_page_desc'] ?></p>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 auto-rows-[250px] lg:auto-rows-[300px] grid-flow-dense">
                <!-- Exterier -->
                <?php
                $images = ['img/int1.jpeg', 'img/int2.jpeg', 'img/int3.jpeg', 'img/int4.jpeg', 'img/int5.jpeg', 'img/int6.jpeg', 'img/int7.jpeg', 'img/ext1.gif', 'img/ext2.gif', 'img/ext3.gif', 'img/golf1.gif', 'img/golf2.gif', 'img/grdn1.gif', 'img/grdn2.gif', 'img/lobby1.webp', 'img/lobby2.webp', 'img/lobby3.gif', 'img/relax1.gif', 'img/relax2.gif', 'img/relax3.gif', 'img/relax4.gif', 'img/view1.webp', 'img/view2.gif'];

                $patterns = [
                    'md:col-span-2 md:row-span-2', // Velká
                    'col-span-1 row-span-1',       // Malá
                    'col-span-1 row-span-1',       // Malá
                    'md:col-span-1 md:row-span-2', // Vysoká
                    'md:col-span-2 md:row-span-1', // Široká
                    'col-span-1 row-span-1',       // Malá
                    'md:col-span-2 md:row-span-2', // Velká
                    'col-span-1 row-span-1',       // Malá
                    'md:col-span-2 md:row-span-1', // Široká
                    'col-span-1 row-span-1',       // Malá
                ];

                $index = 0;
                foreach ($images as $img):
                    if (file_exists($img)):
                        $class = $patterns[$index % count($patterns)];
                        // Dynamically adjust the last item to mathematically seal the masonry grid on both 3-col and 4-col breakpoint sizes
                        if ($index == 22) {
                            $class = 'col-span-1 row-span-1 md:col-span-1 md:row-span-2 lg:col-span-2 lg:row-span-1';
                        }
                        ?>
                        <a href="<?= $img ?>" data-lightbox="gallery"
                            class="group relative overflow-hidden rounded-2xl bg-slate-800 border border-slate-700 hover:border-teal-500 transition duration-300 <?= $class ?>">
                            <img src="<?= $img ?>" alt="<?= $lang['gal_page_title'] ?? 'Fotogalerie' ?>" loading="lazy"
                                width="800" height="600"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-110 opacity-90 group-hover:opacity-100">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                <span class="text-white font-medium"><?= $lang['gal_page_zoom'] ?></span>
                            </div>
                        </a>
                        <?php
                        $index++;
                    endif;
                endforeach;
                ?>
            </div>

            <div class="mt-16 text-center">
                <a href="index.php#booking"
                    class="bg-teal-600 text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-teal-700 hover:scale-105 transition transform shadow-xl shadow-teal-600/30 inline-flex items-center">
                    <?= $lang['gal_page_btn'] ?>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Column 1: Logo & Address -->
                <div class="flex flex-col items-center md:items-start">
                    <img src="img/newlogo_transparent.png" alt="Logo" class="h-28 w-auto mb-6 drop-shadow-md">
                    <p class="text-slate-300 flex items-center justify-center md:justify-start">
                        <svg class="w-5 h-5 mr-3 text-teal-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Terra Complex<br>2760 Razlog, Bulgaria
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="text-white font-bold text-lg mb-6 tracking-wide uppercase">Rychlé Odkazy</h4>
                    <ul class="space-y-3">
                        <li><a href="index.php#about"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_hotel'] ?></a>
                        </li>
                        <li><a href="#"
                                class="cursor-default text-teal-400 border-b border-transparent pb-1"><?= $lang['nav_gallery'] ?></a>
                        </li>
                        <li><a href="index.php#location"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_location'] ?></a>
                        </li>
                        <li><a href="index.php#booking"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_book'] ?></a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="text-white font-bold text-lg mb-6 tracking-wide uppercase">Kontakt</h4>
                    <ul class="space-y-4 text-slate-300">
                        <li>
                            <a href="tel:+420737921581"
                                class="flex items-center hover:text-teal-400 transition justify-center md:justify-start">
                                <svg class="w-5 h-5 mr-3 text-teal-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                +420 737 921 581
                            </a>
                        </li>
                        <li>
                            <a href="mailto:terracomplexap@gmail.com"
                                class="flex items-center hover:text-teal-400 transition justify-center md:justify-start">
                                <svg class="w-5 h-5 mr-3 text-teal-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                terracomplexap@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-12 pt-8 text-center text-sm">
                <p>&copy; 2026 Terra Complex AP. Všechna práva vyhrazena.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                menu.style.maxHeight = '0px';
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>