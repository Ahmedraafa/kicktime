<?php
// Root Path is now handled in config.php
$root = defined('ROOT_URL') ? ROOT_URL : './';
$jsRoot = $root;

if (!isset($pageTitle)) $pageTitle = 'Sports Booking';
if (!isset($pageCSS)) $pageCSS = '';
if (!isset($extraHead)) $extraHead = '';
if (!isset($hideNavbar)) $hideNavbar = false;
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>" dir="<?= $_SESSION['lang'] == 'en' ? 'ltr' : 'rtl' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $root ?>assets/css/styles.css?v=3">
    <link rel="stylesheet" href="<?= $root ?>assets/css/variables.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>window.root = '<?= $jsRoot ?>'; window.apiRoot = '<?= $jsRoot ?>backend/'; window.lang = '<?= $_SESSION['lang'] ?>';</script>

    <?= $extraHead ?>
    <?php if ($pageCSS): ?>
    <style><?= $pageCSS ?></style>
    <?php endif; ?>
</head>
<body>
<?php if (!$hideNavbar): ?>
    <?php include __DIR__ . '/navbar.php'; ?>
<?php endif; ?>
