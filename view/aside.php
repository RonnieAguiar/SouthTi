<?php
if (!isset($_SESSION['user_token'])) {
    header("Location: /");
    exit;
}
?>

<aside class="menu">
    <div class="logo">
        <a href="/"><img src="../assets/images/ball.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/profile_round.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/script.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/notification_bell.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/message_three_points.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/money.png" alt="Logo"></a>
    </div>
    <div class="menu-item">
        <a href="#"><img src="../assets/images/umbrella_round.png" alt="Logo"></a>
    </div>
</aside>