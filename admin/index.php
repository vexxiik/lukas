<?php
$token = hash('sha256', 'admin_logged_in_secret_123!');
if (!isset($_COOKIE['admin_auth']) || $_COOKIE['admin_auth'] !== $token) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../db.php';

// Fetch all reservations
$stmt = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to translate status
function getStatusCzech($status)
{
    if ($status === 'approved')
        return 'Schváleno';
    if ($status === 'rejected')
        return 'Zamítnuto';
    if ($status === 'pending')
        return 'Čeká na vyřízení';
    return $status;
}

// Prepare events for FullCalendar
$events = [];
foreach ($reservations as $res) {
    $color = '#f59e0b'; // pending (yellow)
    if ($res['status'] == 'approved')
        $color = '#0d9488'; // teal
    if ($res['status'] == 'rejected')
        $color = '#ef4444'; // red

    $statusCz = getStatusCzech($res['status']);

    $events[] = [
        'id' => $res['id'],
        'title' => $res['name'] . ' (' . $statusCz . ')',
        'start' => $res['start_date'],
        'end' => date('Y-m-d', strtotime($res['end_date'] . ' +1 day')), // FullCalendar exclusive end date
        'color' => $color,
        'description' => "Tel: {$res['phone']} | Email: {$res['email']}"
    ];
}
$eventsJson = json_encode($events);
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Terra Complex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .fc-event {
            cursor: pointer;
        }

        /* Mobile calendar fix */
        .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .fc .fc-button {
            padding: 0.4em 0.8em;
            font-size: 0.9em;
            text-transform: capitalize;
        }

        .fc-toolbar-chunk {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 5px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar / Mobile Header -->
        <aside
            class="w-full md:w-64 bg-slate-900 text-white flex flex-col md:min-h-screen z-50 sticky top-0 shadow-lg md:shadow-none">
            <div
                class="h-16 md:h-20 flex items-center justify-between md:justify-center px-4 md:px-0 border-b border-slate-800">
                <h1 class="flex justify-center items-center">
                    <img src="../img/newlogo_transparent.png" alt="Logo" class="h-12 md:h-16 w-auto drop-shadow-sm">
                </h1>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn"
                    class="md:hidden text-slate-300 hover:text-white focus:outline-none p-2 rounded-lg bg-slate-800">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="menu-icon-open">
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

            <!-- Navigation Links -->
            <div id="mobile-menu"
                class="hidden md:flex flex-col flex-1 bg-slate-900 md:bg-transparent absolute md:relative top-16 md:top-0 w-full md:w-auto overflow-hidden">
                <nav class="flex-1 px-4 py-4 md:py-6 space-y-2">
                    <a href="index.php"
                        class="flex items-center px-4 py-3 bg-teal-600 text-white rounded-xl transition shadow-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="../index.php" target="_blank"
                        class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Zobrazit web
                    </a>
                </nav>
                <div class="p-4 border-t border-slate-800 mb-2 md:mb-0">
                    <a href="logout.php"
                        class="flex items-center px-4 py-3 text-red-400 hover:text-white hover:bg-red-500/10 rounded-xl transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Odhlásit se
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 md:p-8">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-slate-800">Správa rezervací</h2>

            <?php if (isset($_GET['success'])): ?>
                <div
                    class="bg-teal-100 border-l-4 border-teal-500 text-teal-700 p-4 rounded-xl mb-6 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Akce byla úspěšně provedena.
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8">
                <!-- Data List (Mobile-Optimized) -->
                <div
                    class="xl:col-span-1 border border-slate-200 bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div
                        class="px-5 py-4 lg:px-6 lg:py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-lg lg:text-xl font-bold text-slate-800">Seznam žádostí</h3>
                        <span
                            class="bg-slate-200 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full"><?= count($reservations) ?></span>
                    </div>
                    <div class="p-0 overflow-y-auto max-h-[500px] lg:max-h-[700px] flex flex-col">
                        <?php if (empty($reservations)): ?>
                            <div class="p-8 text-center flex flex-col items-center">
                                <span class="bg-slate-100 text-slate-300 rounded-full p-4 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </span>
                                <p class="text-slate-500 font-medium">Zatím žádné rezervace.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <div
                                    class="border-b border-slate-100 hover:bg-slate-50 transition p-4 md:p-5 flex flex-col justify-between">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="font-bold text-slate-800 text-base lg:text-lg">
                                            <?= htmlspecialchars($res['name']) ?>
                                        </div>
                                        <span <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        <?= $res['status'] == 'approved' ? 'bg-teal-100 text-teal-800' : ($res['status'] == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                            <?= getStatusCzech($res['status']) ?>
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-500 mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <?= date('d.m.Y', strtotime($res['start_date'])) ?> &nbsp;—&nbsp;
                                        <?= date('d.m.Y', strtotime($res['end_date'])) ?>
                                    </div>

                                    <div
                                        class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100/60 mt-auto">
                                        <?php if ($res['status'] == 'pending'): ?>
                                            <form method="POST" action="manage_reservation.php" class="inline m-0">
                                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit"
                                                    class="text-teal-700 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg text-sm font-semibold transition"
                                                    title="Schválit">Schválit</button>
                                            </form>
                                            <form method="POST" action="manage_reservation.php" class="inline m-0">
                                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit"
                                                    class="text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-sm font-semibold transition"
                                                    title="Zamítnout">Zamítnout</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="manage_reservation.php" class="inline m-0 ml-auto"
                                            onsubmit="return confirm('Opravdu smazat?');">
                                            <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit"
                                                class="text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 p-1.5 rounded-lg transition"
                                                title="Smazat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calendar View -->
                <div
                    class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-4 lg:p-6 overflow-hidden">
                    <div id="calendar" class="h-auto"></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // FullCalendar Init
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'cs',
                firstDay: 1,
                height: 'auto',
                contentHeight: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'dnes',
                    month: 'měsíc',
                    list: 'seznam'
                },
                events: <?= $eventsJson ?>,
                eventClick: function (info) {
                    alert('Rezervace: ' + info.event.title + '\n' + info.event.extendedProps.description);
                }
            });
            calendar.render();
        });

        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            } else {
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>