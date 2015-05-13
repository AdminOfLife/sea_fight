function create_board() {
    var board = $("#board");

    var NUMBER_FIELDS = 10;

    for (var i = 1; i <= NUMBER_FIELDS; i++) {

        var row = $('<div>').addClass('row');

        for (var j = 1; j <= NUMBER_FIELDS; j++) {
            $('<div>').addClass('item item_' + (i * NUMBER_FIELDS + j))
                .appendTo(row);
        }

        row.appendTo(board);
    }
}

function set_position() {
    $.ajax({
        type: "POST",
        url: "/app.php",
        dataType: 'json',
        success: function (response) {
            if (typeof(response.success.id) !== "undefined" && typeof(response.success.position) !== "undefined") {
                // очищаем поле
                $(".item").removeClass('select_item');

                $('.position_id').text(response.success.id);

                for (var i in response.success.position) {
                    $(".item_" + response.success.position[i]).addClass('select_item')
                }
            } else {
                alert('Не удалось загрузить новую позицию кораблей.')
            }
        },
        error : function() {
            window.location.href = "/index.html";
        }
    });
}

$(document).ready(function () {
    // обновляем игровое поле
    $("#new_game").click(set_position);

    // создаём иговое поле
    create_board();

    set_position();
});
