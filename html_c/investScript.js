$(document).ready(function(){
    $("form").hide();

    $("#maction").change(function(){
        stateChange($(this).val());
    });

    function stateChange(stateValue){
        $("form").hide();

        switch(stateValue){
        case 'buy':
            $("#investForm").show();
            break;
        case 'sell':
            $("#sellForm").show();
            break;
        }
    }
})