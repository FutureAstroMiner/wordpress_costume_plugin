/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {
    jQuery('#createacostume').submit(function( event ) {
        event.preventDefault();
        
        var aForm = jQuery(this);
        var bForm = aForm.serializeArray();
        bForm.push({name: 'action', value: 'myAjaxFunction'});
//        var form_data = bForm.serialize();
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: bForm,
//            dataType: "json",
            
            
            success: function(resp) {
//                console.log(resp);
                alert("Thank you for your post. We will review it and approve it shortly" + resp);

            },
            error: function( req, status, err ) {
            	alert( 'something went wrong, Status: ' + status + ' and error: '
                        + err + " and req of " + req);
                console.log("----------------------------------");
                console.log(aForm.serialize());
                console.log(bForm);
                console.log("----------------------------------");
            }
        })
//                .done(function(secResp) {
//            alert("second response: " + secResp);
//        })
//                .fail(function() {
//            alert("error");
//        }
//                )
        ;
        return false;
    });
});