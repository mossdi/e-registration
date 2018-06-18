/**
 * Load modal form
 *
 * @param url_request
 * @param title
 * @param id
 */
function formLoad(url_request, title, id = null) {
    if (id != null) {
        url_request += (~url_request.indexOf('?') ? '&id=' : '?id=') + id;
    }

    $('#modalForm').find('.modal-body').load(url_request);
    $('#modalForm').find('.modal-header').html('<span>' + title + '</span><button type="button" class="close" data-dismiss="modal" onclick="formClean()" aria-hidden="true">×</button>');
}

/**
 * Clean modal body
 */
function formClean() {
    $('#modalForm').find('.modal-body').html('');
}

$(document).ready(function(){
     $('[data-toggle="tooltip"]').tooltip();
});

/**
 * Pjax container refresh
 */
$('.grid-view a#wishlistToggle').on('click', function() {
    $.pjax.reload({
        container: '#wishListContainer'
    });
});
