<?php
session_start();
if (!isset($_SESSION['user_token'])) {
    header("Location: /");
    exit;
}
?>

<div class="container">
    <nav class="navbar">
        <ul>
            <li>Lançamentos</li>
        </ul>
    </nav>
    <?php include 'aside.php'; ?>
    <main>
        <section>
            <div class="card add-transaction">
                <div class="card-header">Cadastrar Transações</div>
                <div class="card-body">
                    <form action="add_transaction.php">
                        <input type="text" placeholder="Valor" name="value" required>
                        <textarea name="description" placeholder="Descrição" rows="5" required></textarea>
                        <button type="submit">Registrar</button>
                    </form>
                </div>
            </div>
            <div class="card resume">
                <div class="card-header">Resumo</div>
                <div class="card-body">
                    <div class="timeline">
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>