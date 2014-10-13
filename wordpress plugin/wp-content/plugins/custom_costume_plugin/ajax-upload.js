/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {
    jQuery('#uploadapiece').submit(function(event) {
        event.preventDefault();

        var aForm = jQuery(this);
        var bForm = aForm.serializeArray();
        bForm.push({name: 'action', value: 'uploadAjaxFunction'});
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: bForm,
//            dataType: "json",

            success: function(resp) {
//                console.log(resp);
                alert("We need to approve this '" + resp + "'"
                );

            },
//            done: function(r) {
//                alert("Result: " + r);
//            },
            error: function(jqXHR, exception) {


                if (jqXHR.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (jqXHR.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (jqXHR.status == 500) {
                    alert(jqXHR.toString() + ' Internal Server Error [500].');
                } else if (exception === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (exception === 'timeout') {
                    alert('Time out error.');
                } else if (exception === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('Uncaught Error.\n' + jqXHR.responseText);
                }
            }
        })
//                .done(function(secResp) {
//            alert("second response: " + secResp);
//        })
//                .fail(function() {
//            alert("error");
//        })
                ;
        return false;
    });
});