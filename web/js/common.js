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

/**
 * Tooltip
 */
$(document).ready(function(){
     $('[data-toggle="tooltip"]').tooltip();
});

/**
 * wishListReload
 */
function wishListReload() {
    setTimeout(function () {
        $.pjax.reload({
            container: '#wishListContainer'
        })
    }, 2000)
}

/**
 * futureConferenceReload
 */
function futureConferenceReload() {
    setTimeout(function () {
        $.pjax.reload({
            container: '#futureConferenceContainer'
        })
    }, 2000)
}
