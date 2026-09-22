<?php
session_start();

$allowed_langs = ['cs', 'en', 'bg'];

// Check if language is provided via GET parameter and is allowed
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Fallback to existing session language or default 'cs'
$current_lang = isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed_langs) ? $_SESSION['lang'] : 'cs';

// Load the appropriate language file
require_once __DIR__ . "/lang/{$current_lang}.php";
?>