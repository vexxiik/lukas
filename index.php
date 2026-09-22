<?php
require_once 'lang_config.php';
require_once 'db.php';

// Fetch approved & pending reservations to disable dates in the calendar
$stmt = $pdo->prepare("SELECT start_date, end_date FROM reservations WHERE status IN ('approved', 'pending')");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$disabledDates = [];
foreach ($reservations as $res) {
    $disabledDates[] = [
        "from" => $res['start_date'],
        "to" => $res['end_date']
    ];
}
$disabledDatesJson = json_encode($disabledDates);

$success = isset($_GET['success']) ? $_GET['success'] : false;
$error = isset($_GET['error']) ? $_GET['error'] : false;
$current_lang = isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed_langs) ? $_SESSION['lang'] : 'cs';

$base_url = "https://terracomplexapartment.com";
$current_url = $base_url . "/";
$og_locale_map = ['cs' => 'cs_CZ', 'en' => 'en_US', 'bg' => 'bg_BG'];
$og_locale = $og_locale_map[$current_lang] ?? 'cs_CZ';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['seo_title'] ?></title>
    <meta name="description" content="<?= htmlspecialchars($lang['seo_desc']) ?>">
    <meta name="keywords" content="terra complex, apartmán, bansko, razlog, dovolená, ubytování, bulharsko">

    <!-- Open Graph tags for social media -->
    <meta property="og:title" content="<?= $lang['seo_title'] ?>">
    <meta property="og:description" content="<?= htmlspecialchars($lang['seo_desc']) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $current_url ?>?lang=<?= $current_lang ?>">
    <meta property="og:image" content="<?= $base_url ?>/img/view4.webp">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?= $og_locale ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $lang['seo_title'] ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($lang['seo_desc']) ?>">
    <meta name="twitter:image" content="<?= $base_url ?>/img/view4.webp">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $current_url ?>?lang=<?= $current_lang ?>">

    <!-- JSON-LD Structured Data (LodgingBusiness) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LodgingBusiness",
      "name": "<?= addslashes($lang['seo_title']) ?>",
      "description": "<?= addslashes($lang['seo_desc']) ?>",
      "image": [
        "<?= $base_url ?>/img/view4.webp",
        "<?= $base_url ?>/img/lobby1.webp"
      ],
      "url": "<?= $base_url ?>",
      "telephone": "+420737921581",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Terra Complex",
        "addressLocality": "Razlog",
        "addressRegion": "Blagoevgrad",
        "postalCode": "2760",
        "addressCountry": "BG"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 41.8741,
        "longitude": 23.4243
      },
      "amenityFeature": [
        {"@type": "LocationFeatureSpecification", "name": "Swimming Pool", "value": true},
        {"@type": "LocationFeatureSpecification", "name": "Free WiFi", "value": true},
        {"@type": "LocationFeatureSpecification", "name": "Kitchen", "value": true},
        {"@type": "LocationFeatureSpecification", "name": "Parking", "value": true}
      ],
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.9",
        "reviewCount": "24"
      }
    }
    </script>

    <!-- JSON-LD FAQ Schema (SEO) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "<?= addslashes($lang['faq1_q']) ?>",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?= addslashes($lang['faq1_a']) ?>"
          }
        },
        {
          "@type": "Question",
          "name": "<?= addslashes($lang['faq2_q']) ?>",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?= addslashes($lang['faq2_a']) ?>"
          }
        },
        {
          "@type": "Question",
          "name": "<?= addslashes($lang['faq3_q']) ?>",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?= addslashes($lang['faq3_a']) ?>"
          }
        },
        {
          "@type": "Question",
          "name": "<?= addslashes($lang['faq4_q']) ?>",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?= addslashes($lang['faq4_a']) ?>"
          }
        }
      ]
    }
    </script>

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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
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

        /* Flatpickr Enhancements - Clean Modern Style */
        .flatpickr-calendar.inline {
            width: 100% !important;
            max-width: 420px;
            /* Width for single month */
            margin: 0 auto;
            box-shadow: none;
            border: 0;
            padding: 0 10px;
            background: transparent;
        }

        .flatpickr-month {
            height: auto !important;
            margin-bottom: 20px;
        }

        .flatpickr-current-month {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            padding: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
        }

        .flatpickr-current-month .numInputWrapper {
            display: none;
        }

        /* Hide year input spin buttons to just show text */
        .flatpickr-months {
            position: relative;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            position: absolute;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: #f8fafc;
            border-radius: 12px;
            color: #334155;
            transition: all 0.2s;
            padding: 0;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: #f1f5f9;
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
            stroke: currentColor;
            stroke-width: 2px;
        }

        .flatpickr-prev-month {
            left: 0;
        }

        .flatpickr-next-month {
            right: 0;
        }

        .flatpickr-weekdays {
            height: 30px;
            margin-bottom: 12px;
        }

        span.flatpickr-weekday {
            color: #475569;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .flatpickr-innerContainer {
            display: flex;
            justify-content: center;
            width: 100%;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .flatpickr-days {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .dayContainer {
            width: 100%;
            max-width: 100%;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px 6px;
            justify-items: center;
            padding: 0;
        }

        /* The days themselves */
        .flatpickr-day {
            display: flex !important;
            align-items: center;
            justify-content: center;
            max-width: 100%;
            width: 44px;
            height: 44px;
            border-radius: 8px !important;
            line-height: unset;
            margin: 0;
            font-weight: 500;
            font-size: 1rem;
            border: 2px solid transparent !important;
            color: #94a3b8;
            /* Light gray for outside month days */
            transition: all 0.2s;
            box-shadow: none !important;
        }

        .flatpickr-day:not(.flatpickr-disabled):not(.prevMonthDay):not(.nextMonthDay) {
            color: #64748b;
        }

        .flatpickr-day:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        /* Today marker */
        .flatpickr-day.today,
        .flatpickr-day.today.booked-day,
        .flatpickr-day.today.flatpickr-disabled {
            border-color: #3b82f6 !important;
            font-weight: 700;
            color: #1e293b;
            background: transparent;
        }

        /* Today marker inside selected/inRange */
        .flatpickr-day.today.selected,
        .flatpickr-day.today.inRange {
            border-color: #3b82f6 !important;
            background: #d1fae5 !important;
        }

        /* Selected range */
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #d1fae5 !important;
            border-color: #6ee7b7 !important;
            color: #065f46 !important;
            border-radius: 8px !important;
            font-weight: 700;
        }

        /* In between range */
        .flatpickr-day.inRange {
            background: #d1fae5 !important;
            border-color: #d1fae5 !important;
            color: #065f46 !important;
            border-radius: 8px !important;
        }

        /* Highlight explicitly disabled (booked) dates using the custom JS class */
        span.flatpickr-day.booked-day,
        span.flatpickr-day.booked-day:hover,
        .flatpickr-day.flatpickr-disabled {
            color: #ef4444 !important;
            background-color: #fee2e2 !important;
            border-color: #fca5a5 !important;
            opacity: 1 !important;
            font-weight: 500;
            cursor: not-allowed;
        }

        /* Ensure past days that aren't booked remain grayed out normally */
        .flatpickr-day.flatpickr-disabled.past-day,
        .flatpickr-day.flatpickr-disabled.past-day:hover {
            color: #cbd5e1 !important;
            background: transparent !important;
            border-color: transparent !important;
            text-decoration: none;
            font-weight: 400;
        }

        /* Calendar header styles */
        .flatpickr-months {
            padding: 8px 0;
            margin-bottom: 10px;
        }

        .flatpickr-current-month {
            display: none !important;
            /* Hide the native one just in case */
        }

        .flatpickr-months .flatpickr-month {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px;
        }

        .custom-title {
            color: #1e293b;
        }

        /* Mobile specific spacing */
        @media (max-width: 640px) {
            .flatpickr-innerContainer {
                padding-bottom: 5px;
                margin-bottom: 10px;
            }

            .dayContainer {
                gap: 6px 2px;
            }

            .flatpickr-day {
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
        }

        /* Overridden specifically for dates in disabled array passed manually via Flatpickr */
        /* Flatpickr doesn't easily let us distinguish past disabled vs array disabled by default HTML class alone
           However, we can reset styling for days before "today" via native CSS or via JS. 
           But actually, Flatpickr adds 'disabled' class to ANY unselectable date. 
           Since minDate: 'today' is set, all past dates are disabled. 
           We will use JS to inject a specific class to booked dates instead. Let's adjust JS too. */
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-teal-500 selection:text-white pt-20">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 relative">

                <!-- Logo -->
                <a href="#" class="flex items-center space-x-2 z-20">
                    <img src="img/newlogo_transparent.png" alt="Apartmán Terra Complex Razlog Logo"
                        class="h-24 md:h-32 w-auto transform translate-y-3 md:translate-y-5 drop-shadow-md relative z-30">
                </a>

                <!-- Desktop Menu (Centered as requested) -->
                <div class="hidden lg:flex absolute inset-0 justify-center items-center pointer-events-none">
                    <div
                        class="flex items-center space-x-8 pointer-events-auto bg-slate-100/50 px-6 py-2.5 rounded-full border border-slate-200/50 shadow-sm backdrop-blur-md">
                        <a href="#about"
                            class="text-slate-600 hover:text-teal-600 font-medium transition"><?= $lang['nav_hotel'] ?></a>
                        <a href="galerie.php"
                            class="text-slate-600 hover:text-teal-600 font-medium transition"><?= $lang['nav_gallery'] ?></a>
                        <a href="#location"
                            class="text-slate-600 hover:text-teal-600 font-medium transition"><?= $lang['nav_location'] ?></a>
                        <a href="#booking"
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
                        <a href="#booking"
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
                <a href="#about"
                    class="block px-4 py-3 text-base font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition"><?= $lang['nav_hotel'] ?></a>
                <a href="galerie.php"
                    class="block px-4 py-3 text-base font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition"><?= $lang['nav_gallery'] ?></a>
                <a href="#location"
                    class="block px-4 py-3 text-base font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition"><?= $lang['nav_location'] ?></a>
            </div>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div id="success-msg" class="max-w-4xl mx-auto mt-8 px-4 scroll-mt-32">
            <div class="p-4 bg-teal-100 border-l-4 border-teal-500 text-teal-800 rounded-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <?php if (isset($_GET['success']) && $_GET['success'] === 'verify'): ?>
                            <p class="font-bold"><?= $lang['flash_verify_title'] ?></p>
                            <p class="text-sm mt-1"><?= $lang['flash_verify_desc'] ?></p>
                        <?php else: ?>
                            <p class="font-bold"><?= $lang['flash_success_title'] ?></p>
                            <p class="text-sm mt-1"><?= $lang['flash_success_desc'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <main>
        <!-- Split Hero Section -->
        <section class="relative pt-4 pb-20 lg:pt-20 lg:pb-32 overflow-hidden bg-slate-50">
            <!-- Abstract background blob -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-teal-100/50 blur-[100px]">
                </div>
                <div
                    class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[50%] rounded-full bg-blue-100/50 blur-[100px]">
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                    <!-- Text Content -->
                    <div class="text-center lg:text-left z-10 order-1 lg:order-1 lg:mt-0">
                        <div
                            class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-sm font-semibold tracking-wide uppercase mb-6 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2L13.5 5.5L17 7L13.5 8.5L12 12L10.5 8.5L7 7L10.5 5.5L12 2ZM18 14L19 16L21 17L19 18L18 20L17 18L15 17L17 16L18 14ZM6 16L7 18L9 19L7 20L6 22L5 20L3 19L5 18L6 16Z">
                                </path>
                            </svg>
                            <?= $lang['hero_badge'] ?>
                        </div>
                        <h1 class="text-2xl lg:text-3xl text-teal-600 mb-2 font-semibold">
                            <?= $lang['seo_h1'] ?>
                        </h1>
                        <div class="text-5xl lg:text-6xl xl:text-7xl font-bold text-slate-800 mb-6 leading-tight">
                            <?= $lang['hero_title'] ?>
                        </div>
                        <p class="text-lg text-slate-600 mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                            <?= $lang['hero_subtitle'] ?>
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            <a href="#booking"
                                class="w-full sm:w-auto text-center bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-blue-700 hover:scale-105 transition transform shadow-xl shadow-blue-600/30">
                                <?= $lang['btn_calendar'] ?>
                            </a>
                            <a href="#about"
                                class="w-full sm:w-auto text-center bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-full text-lg font-medium hover:bg-slate-50 transition shadow-sm">
                                <?= $lang['btn_more'] ?>
                            </a>
                        </div>
                    </div>

                    <!-- Hero Images -->
                    <div class="relative z-10 order-2 lg:order-2">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-slate-300">
                            <img src="img/ext3.gif" alt="<?= htmlspecialchars($lang['alt_hero'] ?? '') ?>" width="800"
                                height="600"
                                class="w-full h-[400px] lg:h-[600px] object-cover transition duration-700 hover:scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none">
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- New Official Text Translated block -->
                <div
                    class="mb-20 text-center lg:text-left grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <h2 class="text-3xl lg:text-4xl xl:text-5xl font-bold text-slate-800 mt-2 mb-6">
                            <?= $lang['seo_h2_why'] ?>
                        </h2>
                        <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                            <?= $lang['about_subtitle'] ?>
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-slate-600 mt-10">
                            <div
                                class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col items-center text-center group relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div
                                    class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-sm relative z-10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-xl mb-3 relative z-10">
                                    <?= $lang['seo_h3_ski'] ?>
                                </h3>
                                <p class="text-slate-500 leading-relaxed relative z-10"><?= $lang['feat1_desc'] ?></p>
                            </div>

                            <div
                                class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col items-center text-center group relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div
                                    class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-sm relative z-10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-xl mb-3 relative z-10">
                                    <?= $lang['seo_h3_summer'] ?>
                                </h3>
                                <p class="text-slate-500 leading-relaxed relative z-10"><?= $lang['feat2_desc'] ?></p>
                            </div>

                            <div
                                class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col items-center text-center group relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div
                                    class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-sm relative z-10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-xl mb-3 relative z-10">
                                    <?= $lang['seo_h3_pool'] ?>
                                </h3>
                                <p class="text-slate-500 leading-relaxed relative z-10"><?= $lang['feat3_desc'] ?></p>
                            </div>

                            <div
                                class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col items-center text-center group relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div
                                    class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-sm relative z-10">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-xl mb-3 relative z-10">
                                    <?= $lang['feat4_title'] ?>
                                </h3>
                                <p class="text-slate-500 leading-relaxed relative z-10"><?= $lang['feat4_desc'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Image Collage for About Section -->
                    <div class="grid grid-cols-2 gap-4 lg:gap-8">
                        <img src="img/view1.webp" alt="<?= htmlspecialchars($lang['alt_hero'] ?? '') ?>" loading="lazy"
                            width="400" height="300"
                            class="rounded-3xl shadow-lg w-full h-48 md:h-64 lg:h-96 xl:h-[28rem] object-cover transform translate-y-8 lg:translate-y-12">
                        <img src="img/golf1.gif" alt="<?= htmlspecialchars($lang['alt_room'] ?? '') ?>" loading="lazy"
                            width="400" height="300"
                            class="rounded-3xl shadow-lg w-full h-48 md:h-64 lg:h-96 xl:h-[28rem] object-cover">
                    </div>
                </div>

                <!-- Property Details Box (Apartment Details) -->
                <div
                    class="bg-blue-50 rounded-3xl p-8 lg:p-12 mb-20 shadow-sm border border-blue-100 flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1 w-full">
                        <span
                            class="text-blue-600 font-semibold tracking-wide uppercase text-sm"><?= $lang['apt_badge'] ?></span>
                        <h2 class="text-3xl font-bold text-slate-800 mt-2 mb-6"><?= $lang['seo_h2_apt'] ?></h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-700">
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <div><strong><?= $lang['apt_cap_title'] ?></strong> <?= $lang['apt_cap_desc'] ?></div>
                            </div>
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <div><h3 class="inline text-base font-bold text-slate-700"><?= $lang['seo_h3_bedroom'] ?></h3>: <?= $lang['apt_rooms_desc'] ?>
                                </div>
                            </div>
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div><strong><?= $lang['apt_eq_title'] ?></strong> <?= $lang['apt_eq_desc'] ?></div>
                            </div>
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                </svg>
                                <div><h3 class="inline text-base font-bold text-slate-700"><?= $lang['seo_h3_kitchen'] ?></h3>: <?= $lang['apt_kit_desc'] ?></div>
                            </div>
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div><strong><?= $lang['apt_bath_title'] ?></strong> <?= $lang['apt_bath_desc'] ?>
                                </div>
                            </div>
                            <div class="flex"><svg class="w-6 h-6 text-teal-500 mr-3 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <div><strong><?= $lang['apt_other_title'] ?></strong> <?= $lang['apt_other_desc'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="py-24 bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span
                    class="text-teal-400 font-semibold tracking-wide uppercase text-sm"><?= $lang['gal_badge'] ?></span>
                <h2 class="text-3xl lg:text-4xl font-bold mt-2 mb-10"><?= $lang['gal_title'] ?></h2>
                <p class="text-slate-400 max-w-2xl mx-auto mb-12"><?= $lang['gal_subtitle'] ?></p>

                <!-- Preview Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-12">
                    <div
                        class="overflow-hidden rounded-3xl aspect-[4/3] bg-slate-800 border border-slate-700 shadow-2xl">
                        <img src="img/int3.jpeg" alt="<?= htmlspecialchars($lang['alt_living'] ?? '') ?>" loading="lazy"
                            width="500" height="375"
                            class="w-full h-full object-cover hover:scale-110 transition duration-700 opacity-90 hover:opacity-100">
                    </div>
                    <div
                        class="overflow-hidden rounded-3xl aspect-[4/3] bg-slate-800 border border-slate-700 shadow-2xl">
                        <img src="img/int5.jpeg" alt="<?= htmlspecialchars($lang['alt_golf'] ?? '') ?>" loading="lazy"
                            width="500" height="375"
                            class="w-full h-full object-cover hover:scale-110 transition duration-700 opacity-90 hover:opacity-100">
                    </div>
                    <div
                        class="overflow-hidden rounded-3xl aspect-[4/3] bg-slate-800 border border-slate-700 shadow-2xl sm:hidden lg:block">
                        <img src="img/int2.jpeg" alt="<?= htmlspecialchars($lang['alt_pool'] ?? '') ?>" loading="lazy"
                            width="500" height="375"
                            class="w-full h-full object-cover hover:scale-110 transition duration-700 opacity-90 hover:opacity-100">
                    </div>
                </div>

                <a href="galerie.php"
                    class="bg-teal-600 text-white px-10 py-5 rounded-full text-lg font-bold hover:bg-teal-700 transition transform hover:scale-105 shadow-xl shadow-teal-600/30 inline-flex items-center">
                    <?= $lang['gal_btn_enter'] ?>
                    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Tips for Trips / Surrounding Area Section (SEO Content) -->
        <section class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span
                        class="text-teal-600 font-semibold tracking-wide uppercase text-sm"><?= $lang['trips_badge'] ?></span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mt-2"><?= $lang['seo_h2_trips'] ?></h2>
                    <p class="text-slate-600 max-w-2xl mx-auto mt-4"><?= $lang['trips_desc'] ?></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Trip 1 -->
                    <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-shadow">
                        <div
                            class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3"><?= $lang['trip1_title'] ?></h3>
                        <p class="text-slate-600 leading-relaxed"><?= $lang['trip1_desc'] ?></p>
                    </div>

                    <!-- Trip 2 -->
                    <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-shadow">
                        <div
                            class="w-14 h-14 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mb-6">
                            <!-- Mountain SVG Icon -->
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3"><?= $lang['trip2_title'] ?></h3>
                        <p class="text-slate-600 leading-relaxed"><?= $lang['trip2_desc'] ?></p>
                    </div>

                    <!-- Trip 3 -->
                    <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-shadow">
                        <div
                            class="w-14 h-14 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-6">
                            <!-- Hot spring / Spa SVG Icon -->
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3"><?= $lang['trip3_title'] ?></h3>
                        <p class="text-slate-600 leading-relaxed"><?= $lang['trip3_desc'] ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Location map & Calendar Booking Form -->
        <section class="py-24 bg-slate-50 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span
                        class="text-teal-600 font-semibold tracking-wide uppercase text-sm"><?= $lang['book_badge'] ?></span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mt-2"><?= $lang['seo_h2_book'] ?></h2>
                    <div class="w-16 h-1 bg-gradient-to-r from-teal-400 to-blue-500 mx-auto rounded-full mt-4"></div>
                </div>

                <!-- Booking App Card (Wide layout like Booking.com) -->
                <div id="booking"
                    class="bg-white rounded-[1rem] shadow-lg overflow-hidden border border-slate-200 mb-16 max-w-5xl mx-auto">
                    <div class="flex flex-col">

                        <!-- Form Header -->
                        <div class="bg-slate-800 text-white p-6">
                            <h3 class="text-xl font-bold mb-1"><?= $lang['book_form_title'] ?></h3>
                            <p class="text-slate-300 text-sm"><?= $lang['book_form_subtitle'] ?></p>
                        </div>

                        <form id="reservationForm" action="process_reservation.php" method="POST"
                            class="p-3 sm:p-6 md:p-8" novalidate>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">

                                <!-- Calendar Side -->
                                <div>
                                    <h3 class="font-bold text-slate-800 mb-4 text-lg border-b pb-2">
                                        <?= $lang['seo_h3_avail'] ?>
                                    </h3>
                                    <div class="bg-white rounded-lg p-0 sm:p-2 border-0 sm:border border-slate-200">
                                        <div style="display:none;">
                                            <input type="hidden" id="dateRange" name="dateRange">
                                        </div>
                                        <div id="inlineCalendarContainer" class="w-full relative z-10 mx-auto"></div>
                                    </div>

                                    <div
                                        class="mt-4 flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-sm text-slate-600 font-medium">
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 bg-[#d1fae5] border border-[#6ee7b7] rounded shadow-sm mr-2">
                                            </div> <?= $lang['book_avail_yes'] ?>
                                        </div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 bg-[#fee2e2] border border-[#fca5a5] rounded shadow-sm mr-2">
                                            </div> <?= $lang['book_avail_no'] ?>
                                        </div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 bg-white border-2 border-[#3b82f6] rounded shadow-sm mr-2">
                                            </div> <?= $lang['book_avail_today'] ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inputs Side -->
                                <div>
                                    <h3 class="font-bold text-slate-800 mb-4 text-lg border-b pb-2">
                                        <?= $lang['book_step2'] ?>
                                    </h3>
                                    <div class="space-y-5">
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2"><?= $lang['book_name'] ?></label>
                                            <input type="text" name="name" required
                                                class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#0071c2] focus:border-[#0071c2] outline-none"
                                                placeholder="<?= $lang['book_name_ph'] ?>">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2"><?= $lang['book_phone'] ?></label>
                                            <input type="tel" name="phone" required
                                                class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#0071c2] focus:border-[#0071c2] outline-none"
                                                placeholder="<?= $lang['book_phone_ph'] ?>">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2"><?= $lang['book_email'] ?></label>
                                            <input type="email" name="email" required
                                                class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#0071c2] focus:border-[#0071c2] outline-none"
                                                placeholder="<?= $lang['book_email_ph'] ?>">
                                        </div>
                                        <!-- Guests input removed per user request -->

                                        <!-- Selected dates summary -->
                                        <div
                                            class="bg-[#f2f8fa] border border-[#d6eff8] p-4 rounded-lg flex flex-col mt-6">
                                            <span
                                                class="text-xs text-slate-500 font-semibold mb-1 uppercase"><?= $lang['book_selection'] ?></span>
                                            <span id="selected-dates-display"
                                                class="font-bold text-[#0071c2]"><?= $lang['book_selection_ph'] ?></span>
                                        </div>

                                        <!-- Error Message Container (Hidden by default) -->
                                        <div id="formErrorMsg"
                                            class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mt-6 rounded-lg shadow-sm"
                                            role="alert">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <p class="font-bold"><?= $lang['form_err_title'] ?></p>
                                            </div>
                                            <p id="formErrorText" class="mt-1 text-sm ml-7"><?= $lang['form_err_desc'] ?></p>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mt-6 flex justify-end">
                                            <button type="submit"
                                                class="w-full bg-[#0071c2] text-white font-bold py-3.5 px-8 rounded-lg hover:bg-[#005999] transition shadow-md">
                                                <?= $lang['book_btn_submit'] ?>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map Layout -->
                <div id="location"
                    class="scroll-mt-32 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="lg:col-span-1 px-4 lg:px-8">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4"><?= $lang['loc_title'] ?></h2>
                        <p class="text-slate-600 mb-6"><?= $lang['loc_desc'] ?></p>
                        <ul class="space-y-4 text-slate-600 mb-6">
                            <li class="flex items-center"><svg class="w-5 h-5 text-teal-500 mr-3 shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"></path>
                                </svg> <?= $lang['loc_point1'] ?></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-teal-500 mr-3 shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd"></path>
                                </svg> <?= $lang['loc_point2'] ?></li>
                            <li class="flex items-center"><svg class="w-5 h-5 text-teal-500 mr-3 shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                    <path
                                        d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2v1h-2v-1z" />
                                </svg> <?= $lang['loc_point3'] ?></li>
                        </ul>
                    </div>
                    <!-- Map Embed - Google Maps showing Terra Complex, Razlog -->
                    <div
                        class="lg:col-span-2 w-full h-[350px] rounded-2xl overflow-hidden shadow-inner border border-slate-200 bg-slate-100 relative">
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0"
                            marginwidth="0"
                            src="https://maps.google.com/maps?q=Terra%20complex%2C%20Kukurevo%2C%20Razlog%2C%20Bulgaria&t=&z=14&ie=UTF8&iwloc=&output=embed"
                            style="border: 0;">
                        </iframe>
                    </div>
                </div>

            </div>
        </section>

        <!-- FAQ Section (SEO Content) -->
        <section class="py-24 bg-slate-50 border-t border-slate-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span
                        class="text-teal-600 font-semibold tracking-wide uppercase text-sm"><?= $lang['faq_badge'] ?></span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mt-2"><?= $lang['seo_h2_faq'] ?></h2>
                    <p class="text-slate-600 mt-4"><?= $lang['faq_desc'] ?></p>
                </div>

                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                        <button
                            class="faq-toggle w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-800 text-lg"><?= $lang['faq1_q'] ?></span>
                            <svg class="w-5 h-5 text-teal-600 transform transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-5 text-slate-600 leading-relaxed">
                            <p><?= $lang['faq1_a'] ?></p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                        <button
                            class="faq-toggle w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-800 text-lg"><?= $lang['faq2_q'] ?></span>
                            <svg class="w-5 h-5 text-teal-600 transform transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-5 text-slate-600 leading-relaxed">
                            <p><?= $lang['faq2_a'] ?></p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                        <button
                            class="faq-toggle w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-800 text-lg"><?= $lang['faq3_q'] ?></span>
                            <svg class="w-5 h-5 text-teal-600 transform transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-5 text-slate-600 leading-relaxed">
                            <p><?= $lang['faq3_a'] ?></p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                        <button
                            class="faq-toggle w-full px-6 py-5 text-left flex justify-between items-center focus:outline-none">
                            <span class="font-bold text-slate-800 text-lg"><?= $lang['faq4_q'] ?></span>
                            <svg class="w-5 h-5 text-teal-600 transform transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-content hidden px-6 pb-5 text-slate-600 leading-relaxed">
                            <p><?= $lang['faq4_a'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
                        <li><a href="#about"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_hotel'] ?></a>
                        </li>
                        <li><a href="galerie.php"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_gallery'] ?></a>
                        </li>
                        <li><a href="#location"
                                class="hover:text-teal-400 border-b border-transparent hover:border-teal-400 transition pb-1"><?= $lang['nav_location'] ?></a>
                        </li>
                        <li><a href="#booking"
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
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <?php if ($current_lang !== 'en'): ?>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/<?= $current_lang ?>.js"></script>
    <?php endif; ?>
    <script>
        // Use Flatpickr to handle date selection INLINE
        const disabledDates = <?= $disabledDatesJson; ?>;

        function updateCustomHeader(instance) {
            // Flatpickr natively hides elements when config minDate/etc are in play with 'static'.
            // Let's create our own title inside the header.
            let header = instance.monthNav;
            let currentMonthTxt = instance.l10n.months.longhand[instance.currentMonth];
            let currentYearTxt = instance.currentYear;

            let customTitle = header.querySelector('.custom-title');
            if (!customTitle) {
                customTitle = document.createElement('div');
                customTitle.className = 'custom-title text-xl font-bold text-slate-800 mx-auto capitalize';
                // Insert it before the nextMonth button or inside the container
                let monthContainer = header.querySelector('.flatpickr-month');
                if (monthContainer) {
                    monthContainer.innerHTML = '';
                    monthContainer.appendChild(customTitle);
                }
            }
            customTitle.innerText = currentMonthTxt + ' ' + currentYearTxt;
        }

        flatpickr("#dateRange", {
            mode: "range",
            inline: true,
            appendTo: document.getElementById('inlineCalendarContainer'),
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: disabledDates,
            locale: "<?= $current_lang === 'en' ? 'default' : $current_lang ?>",
            showMonths: 1,
            monthSelectorType: "static", // static mode to prevent confusing dropdowns
            onReady: function (selectedDates, dateStr, instance) {
                // Flatpickr often incorrectly hides the static month selector in some languages.
                // We will manually inject a powerful title block identical to the admin panel.
                updateCustomHeader(instance);
            },
            onMonthChange: function (selectedDates, dateStr, instance) {
                updateCustomHeader(instance);
            },
            onYearChange: function (selectedDates, dateStr, instance) {
                updateCustomHeader(instance);
            },
            onChange: function (selectedDates, dateStr, instance) {
                // Update the visual display of selected dates
                const displayEl = document.getElementById('selected-dates-display');
                if (selectedDates.length === 2) {

                    // VALIDATION: Check if there are booked dates between selected dates
                    let isInvalid = false;
                    for (let n = selectedDates[0].getTime(); n <= selectedDates[1].getTime(); n += 86400000) {
                        let currDate = new Date(n);
                        let dateIso = currDate.getFullYear() + "-" +
                            String(currDate.getMonth() + 1).padStart(2, '0') + "-" +
                            String(currDate.getDate()).padStart(2, '0');

                        let booked = disabledDates.some(range => {
                            return dateIso >= range.from && dateIso <= range.to;
                        });
                        if (booked) {
                            isInvalid = true;
                            break;
                        }
                    }

                    if (isInvalid) {
                        alert("<?= addslashes($lang['cal_overlap']) ?>");
                        instance.clear();
                        displayEl.innerText = "<?= addslashes($lang['cal_not_selected']) ?>";
                        return;
                    }

                    const formatOpt = { day: 'numeric', month: 'long', year: 'numeric' };
                    <?php
                    $jsLocaleMap = ['cs' => 'cs-CZ', 'en' => 'en-GB', 'bg' => 'bg-BG'];
                    $jsLocaleStr = $jsLocaleMap[$current_lang] ?? 'cs-CZ';
                    ?>
                    const d1 = selectedDates[0].toLocaleDateString('<?= $jsLocaleStr ?>', formatOpt);
                    const d2 = selectedDates[1].toLocaleDateString('<?= $jsLocaleStr ?>', formatOpt);
                    displayEl.innerText = `${d1} - ${d2}`;
                } else if (selectedDates.length === 1) {
                    displayEl.innerText = "<?= addslashes($lang['cal_select_end']) ?>";
                } else {
                    displayEl.innerText = "<?= addslashes($lang['cal_not_selected']) ?>";
                }
            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                // Determine if date is in the past
                let today = new Date();
                today.setHours(0, 0, 0, 0);

                if (dayElem.dateObj < today) {
                    dayElem.classList.add('past-day');
                }

                // Check if this specific day is in our disabled array
                let dateIso = dayElem.dateObj.getFullYear() + "-" +
                    String(dayElem.dateObj.getMonth() + 1).padStart(2, '0') + "-" +
                    String(dayElem.dateObj.getDate()).padStart(2, '0');

                let isBooked = disabledDates.some(range => {
                    return dateIso >= range.from && dateIso <= range.to;
                });

                if (isBooked) {
                    dayElem.classList.add('booked-day');
                }
            }
        });

        // Navbar effect on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md');
                nav.classList.replace('border-white/20', 'border-slate-200');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.replace('border-slate-200', 'border-white/20');
            }
        });

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

        // Close menu on link click
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                menu.style.maxHeight = '0px';
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });

        // FAQ Accordion functionality
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');

                // Toggle current FAQ
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });
        // Form Validation before submit
        const resForm = document.getElementById('reservationForm');
        const errorMsg = document.getElementById('formErrorMsg');
        const errorText = document.getElementById('formErrorText');

        if (resForm) {
            resForm.addEventListener('submit', function (e) {
                // Reset errors
                errorMsg.classList.add('hidden');
                resForm.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                let hasError = false;
                let missingFields = [];

                // Check date field
                const dateRange = document.getElementById('dateRange').value;
                if (!dateRange) {
                    hasError = true;
                    missingFields.push('<?= addslashes($lang['form_err_date']) ?>');
                    // Highlight calendar container
                    const calContainer = document.getElementById('inlineCalendarContainer');
                    calContainer.classList.add('border', 'border-red-500', 'rounded-lg');
                } else {
                    document.getElementById('inlineCalendarContainer').classList.remove('border', 'border-red-500', 'rounded-lg');
                }

                // Check other required inputs
                ['name', 'phone', 'email'].forEach(fieldName => {
                    const input = resForm.querySelector(`input[name="${fieldName}"]`);
                    if (input && !input.value.trim()) {
                        hasError = true;
                        input.classList.add('border-red-500');
                        missingFields.push(input.previousElementSibling.innerText);
                    }
                });

                if (hasError) {
                    e.preventDefault(); // Stop submission
                    errorText.innerText = "<?= addslashes($lang['form_err_prefix']) ?>" + missingFields.join(', ');
                    errorMsg.classList.remove('hidden');

                    // Scroll to error message 
                    errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    </script>
</body>

</html>