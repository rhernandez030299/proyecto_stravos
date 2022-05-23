"use strict";
var UserEdit = { init: function() { new KTImageInput("user_edit_avatar") } };
var UserEditUpdate = { init: function() { new KTImageInput("user_edit_update_avatar") } };
jQuery(document).ready(function() {
    UserEditUpdate.init()
    UserEdit.init();
});