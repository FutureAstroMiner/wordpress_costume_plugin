/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var opts = {
    lines: 11, // The number of lines to draw
    length: 15, // The length of each line
    width: 10, // The line thickness
    radius: 30, // The radius of the inner circle
    corners: 1, // Corner roundness (0..1)
    rotate: 0, // The rotation offset
    direction: 1, // 1: clockwise, -1: counterclockwise
    color: '#000', // #rgb or #rrggbb
    speed: 0.6, // Rounds per second
    trail: 60, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    className: 'spinner', // The CSS class to assign to the spinner
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    top: 'auto', // Top position relative to parent in px
    left: 'auto' // Left position relative to parent in px
};
var spinner = require('/spin');
var spinner_div = 0;
jQuery(document).ready(function() {
    jQuery('#createacostume').submit(function(event) {
        event.preventDefault();
        if (spinner === null) {
            spinner = new Spinner(opts).spin(spinner_div);
        } else {
            spinner.spin(spinner_div);
        }
        var aForm = jQuery(this);
        var bForm = aForm.serializeArray();
        bForm.push({name: 'action', value: 'myAjaxFunction'});
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: bForm,
//            dataType: "json",



            success: function(resp) {
//                console.log(resp);
                alert("Thank you for your post. We will review it and approve it shortly" + resp);
                spinner.stop(spinner_div);
                window.location.href = resp;
            },
            error: function(req, status, err) {
                spinner.stop(spinner_div);
                alert('something went wrong, Status: ' + status + ' and error: ' + err);
            }
        })
//                .done(function(secResp) {
//            alert("second response: " + secResp);
//        })
                .fail(function() {
                    alert("error");
                })
                ;
        return false;
    });
});