<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    function sendPush(text)
    {
        $.ajax({
            type: "POST",
            url: "./send_push.php",
            data: { text: text }
        })
            .done(function( msg ) {
                if (msg == 'true')
                    alert( "Уведомление отправлено!" );
                else
                    alert( "Что-то пошло не так!" );
            });
    }
</script>
<form id="sendpush">
    <input type="text" id="text">
    <input type="button" value="Отправить уведомление" onclick="sendPush(text.value);">
</form>
<a href="index.php?logout">Выйти</a>
