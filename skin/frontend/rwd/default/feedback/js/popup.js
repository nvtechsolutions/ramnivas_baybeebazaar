function openFeedbackWindow(ele,upd_ele,id)
{
    Effect.Appear(ele);
    var back1 = document.getElementById ('backgroundpopup');
    back1.style.display = "block";
}
function closeFeedbackWindow(ele1){
    var val1=document.getElementById(ele1);    
    var background=document.getElementById('backgroundpopup');
    Effect.Fade(val1);
    Effect.Fade(background);
    $$('div.error-massage').each(function(ele){
            ele.hide();
    });
}
function sendFeedback(url){
    if(feedback_form && feedback_form.validate()){
        $('loader').show();
        $('btnsubmit').setAttribute('disabled', true);
        var parameters=$('frm_feedback').serialize(true);
        new Ajax.Request(url, {
                method: 'post',
                dataType: 'json',
                parameters: parameters,
                onSuccess: function(transport) {
                    if(transport.status == 200) {
                        var response=transport.responseText.evalJSON();
                        $('success_message').innerHTML=response.message;
                        if(response.result=='success'){
                            $('success_message').removeClassName('feedback-error-msg');
                            $('success_message').addClassName('feedback-success-msg');
                        }
                        else{
                            $('success_message').removeClassName('feedback-success-msg');
                            $('success_message').addClassName('feedback-error-msg');
                        }
                        $('loader').hide();
                        $('success_message').show();
                        if(response.recaptcha == 1){
                            Recaptcha.reload();
                        }
                        Effect.toggle('success_message', 'appear',{ duration: 5.0});
                        if(response.result=='success'){
                            setTimeout(function (){
                                    closeFeedbackWindow('feedback_information');
                                    $('frm_feedback').reset();
                                    $('btnsubmit').removeAttribute('disabled');
                                },6000);
                        }
                        $('btnsubmit').removeAttribute('disabled');
                        return false;
                    }
                }
        });
        return false;
    }
}