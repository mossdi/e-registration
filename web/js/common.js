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
 * Add conference to wish-list
 */
function addToWishList(id) {
    $.ajax({
        type: 'GET',
        url: '/conference/add-to-wish-list',
        data: { 'id': id, },
        success: function () {
            pjaxContainersReload();
        }
    })
}

/**
 * Delete conference from wish-list
 */
function deleteFromWishList(id) {
    $.ajax({
        type: 'GET',
        url: '/conference/delete-from-wish-list',
        data: { 'id': id, },
        success: function () {
            pjaxContainersReload();
        }
    })
}

function registerParticipant(id) {
    $.ajax({
        type: 'GET',
        url: '/user/register-participant?user_id=\' . Yii::$app->user->id . \'&conference_id=\' . $model->id . \'&method=\' . Conference::LEARNING_DISTANCE',
        data: { 'id': id },
        success: function () {
            pjaxContainersReload();
        }
    })
}

/**
 * Pjax containers reload
 */
function pjaxContainersReload() {
    $.pjax.reload({
        container: '#futureConferenceContainer'
    });

    setTimeout(function () {
        $.pjax.reload({
            container: '#wishListContainer'
        });
    }, 500);
}
