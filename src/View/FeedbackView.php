<?php declare(strict_types=1); ?>
<!DOCTYPE html>  
<html lang="ru">  
<head>  
    <meta charset="UTF-8">  
    <link rel="stylesheet" href="css/style.css">
    <title>Форма обратной связи</title>  
</head>  
<body>  
    <main class="container">
        <h1>Обратная связь</h1>
        <section class="form-section">
            <form id="feedback-form" novalidate>
                <div class="form-group">
                    <label for="full_name">ФИО</label>
                    <input type="text" id="full_name" name="full_name" required minlength="2" maxlength="100">
                    <span class="error" id="error-full_name"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="error-email"></span>
                </div>

                <div class="form-group">
                    <label for="message">Сообщение</label>
                    <textarea id="message" name="message" rows="5" required minlength="1" maxlength="65535"></textarea>
                    <span class="error" id="error-message"></span>
                </div>

                <button type="submit" id="submit-btn">Отправить</button>
                <div id="form-status" class="status"></div>
            </form>
        </section>

        <section class="messages-section">
            <h2>Сообщения</h2>
            <div id="messages-list">Загрузка...</div>
        </section>
    </main>
    <script type="module" src="js/main.js"></script> 
</body>  
</html>  