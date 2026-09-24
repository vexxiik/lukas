<?php
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Hardcoded credentials for simplicity as requested
    if ($username === 'Lukas' && $password === 'L924011s/*') {
        $token = hash('sha256', 'admin_logged_in_secret_123!');
        setcookie('admin_auth', $token, time() + 86400 * 30, "/");
        header("Location: index.php");
        exit;
    } else {
        $error = "Nesprávné uživatelské jméno nebo heslo.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <link rel="icon" type="image/svg+xml" href="../img/logo.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Terra Complex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="flex justify-center items-center"><img src="../img/newlogo_transparent.png" alt="Logo"
                    class="h-20 sm:h-28 w-auto"></h1>
            <p class="text-slate-500 mt-2 font-medium">Administrace rezervací</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 border border-red-100 flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Uživatelské jméno</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition outline-none">
            </div>
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Heslo</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition outline-none">
            </div>
            <button type="submit"
                class="w-full bg-teal-600 text-white font-bold py-3.5 rounded-xl hover:bg-teal-700 transition transform hover:-translate-y-0.5 shadow-lg shadow-teal-500/30 flex justify-center items-center">
                Přihlásit se
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </button>
        </form>
        <div class="text-center mt-8 pt-6 border-t border-slate-100">
            <a href="../index.php"
                class="text-sm font-medium text-slate-500 hover:text-teal-600 transition flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zpět na web
            </a>
        </div>
    </div>
</body>

</html>