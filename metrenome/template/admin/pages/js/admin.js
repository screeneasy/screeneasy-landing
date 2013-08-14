function supportsInputType(type) {
    var input = document.createElement("input");
    input.setAttribute("type", type);
    return input.type == type;
}

function hideIfSupported(type) {
    if (supportsInputType(type)) {
        $('[type="' + type + '"]').each(function() {
            $('#' + $(this).data('hideifsupported')).hide();
        });
    }
}

$(function() {
    $('[data-focusonload]').focus();

    $('#clear-list-button').on('click', function() {
        if (confirm('Remove all subscribers from the list?')) {
            return true;
        }

        return false;
    });

    hideIfSupported('date');
    hideIfSupported('time');
});