$(document).ready(function () {

    $(".btnSetDone").click(function (e) {
        var taskId = $(this).data('task-id');
        var done = $.post("ajax/setTaskDone.php",
            {
                task_id: taskId
            },
            function (data, status) {
                if (data.result == true && status == 'success') {
                    $('.badge[data-badge-id="' + taskId + '"]').removeClass('badge-warning').addClass('badge-success').val('done');
                    $('.btnSetDone[data-task-id="' + taskId + '"]').remove();
                    var row = $('tr[data-task-id="' + taskId + '"]').attr('data-task-done', 'true');
                    if (!$('#inputShowAll').is(':checked')) {
                        row.fadeOut();
                    }
                }
            });

        done.fail(function () {
            alert('An Error occured');
        });

    });

    $('#inputShowAll').click(function () {
        if ($(this).is(':checked')) {
            toggleHideDone(false);
        } else {
            toggleHideDone(true);
        }
    });

});

function toggleHideDone(hide = true) {
    if (hide) {
        $('tr[data-task-done="true"]').fadeOut();
    } else {
        $('tr[data-task-done="true"]').fadeIn();
    }
}