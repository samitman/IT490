//source for compound interest feature: https://codepen.io/fixturemedia/pen/mddKjZ

$(document).ready(function(){
    !function($){
    var principle = $('#principle'),
    contribution = $('#contribution'),
    frequency = $('#frequency'),
    interest = $('#interest-rate'),
    period = $('#period'),
    result = $('#calc-result'),
    inputs = $('input, select'),

    fv = function () {
        var r = parseFloat(interest.val()) / 100 / 365,
        C = parseFloat(contribution.val()),
        P = parseFloat(principle.val()),
        y = parseFloat(period.val()),
        d = 365 * y,
        n = parseFloat(frequency.val()),
        nn = Math.floor(365 / n),
        total = P + C,//add initial contribution to account for loss of interest
        ri = 0;

        var yr = new Date().getFullYear(),
        count = 0,
        initialDeposit = true,
        z,zz;

        while (count++ < d) {
        z = new Date(yr, 0 , count);
        zz = new Date(yr, 0 , count + 1);

        if (count % n === 0) {
            if (!initialDeposit) {
            total += C;
            } else {
            initialDeposit = false;          
            }
        }

        if (zz.getDate() < z.getDate()) {
            total += ri;
            ri = 0;
        }

        ri += total * r;
        }
        return total;
    },

    update = function () {
        var val = fv();
        result.html( val.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g, '$1,'));
    };

    update();
    inputs.on('change keyup', update);
    }(jQuery);

});