<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=moviebase;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('
        <div style="font-family:sans-serif;background:#0d0d0f;color:#e55;min-height:100vh;
                    display:flex;align-items:center;justify-content:center;padding:2rem">
            <div>
                <h2>Erro de Conexão</h2>
                <p style="margin:.5rem 0;color:#aaa">' . htmlspecialchars($e->getMessage()) . '</p>
                <p style="margin-top:1rem">
                    <a href="setup.php" style="color:#f5c518">Executar setup.php primeiro</a>
                </p>
            </div>
        </div>
    ');
}