$(document).ready(function(){
    $("form").hide();

    $("#method").change(function(){
        stateChange($(this).val());
    });

    function stateChange(stateValue){
        $("form").hide();

        switch(stateValue){
        case 'deposit':
            $("#depositForm").show();
            break;
        case 'withdraw':
            $("#withdrawForm").show();
            break;
        }
    }
})