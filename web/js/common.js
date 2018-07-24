/**
 * Load modal form
 * @param url_request
 * @param title
 * @param id
 * @param loadTo
 */
function formLoad(url_request, loadTo, title = null, id = null) {
    if (id != null) {
        url_request += (~url_request.indexOf('?') ? '&id=' : '?id=') + id;
    }

    if (loadTo === 'modal') {
        $('#modalForm').find('.modal-body').load(url_request);
        $('#modalForm').find('.modal-header').html('<span>' + title + '</span><button type="button" class="close" data-dismiss="modal" onclick="formClean()" aria-hidden="true">×</button>');
    } else {
        $('section.content').load(url_request);
    }
}

/**
 * Clean modal body
 */
function formClean() {
    $('#modalForm').find('.modal-body').html('');
}

/**
 * Add conference to wish-list
 * @param id
 */
function addToWishList(id) {
    $.ajax({
        type: 'GET',
        url: '/conference/add-to-wish-list',
        data: { 'id': id, },
        success: function () {
            wishlistReload();
        }
    })
}

/**
 * Delete conference from wish-list
 * @param id
 */
function deleteFromWishList(id) {
    $.ajax({
        type: 'GET',
        url: '/conference/delete-from-wish-list',
        data: { 'id': id, },
        success: function () {
            wishlistReload();
        }
    })
}

/**
 * Register distance participant
 * @param user_id
 * @param conference_id
 * @param method
 */
function registerParticipant(user_id, conference_id, method) {
    $.ajax({
        type: 'GET',
        url: '/user/register-participant',
        data: {
            'user_id': user_id,
            'conference_id': conference_id,
            'method': method,
        },
        success: function () {
            wishlistReload();
        }
    })
}

/**
 * Pjax containers reload
 */
function wishlistReload() {
    $.pjax.reload({
        container: '#futureConferenceContainer'
    });

    setTimeout(function () {
        $.pjax.reload({
            container: '#wishListContainer'
        });
    }, 500);
}

function participantCountReload() {
    setTimeout(function () {
        $.pjax.reload({
            container: '#participantCountContainer'
        });
    }, 1000);
}

function participantCountReload() {
    $.pjax.reload({
        container: '#participantCountContainer'
    });
}

/**
 * Tooltip
 */
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
