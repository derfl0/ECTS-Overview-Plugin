$(document).ready(function() {
    $('tbody tr th').parent().addClass('semester')
    $('tbody tr th:first-child').prepend($('<input>').attr('type', 'checkbox').click(function() {
        var status = $(this).is(':checked');
        $(this).parentsUntil('tr').parent().nextUntil('.semester').find('input[type=checkbox]').attr('checked', status);
    }));
});