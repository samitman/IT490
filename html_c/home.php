<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$fname = $_SESSION["fname"];
	echo "<br>";
	echo "Welcome, ".$fname."!";
}
else {
    echo "<br>";
    echo "Welcome, please log in!";
	//die(header("Location: index.php"));
}
?>
</div>

<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js">
		(function () {
    var initial_deposit = document.querySelector('#initial_deposit'),
        contribution_amount = document.querySelector('#contribution_amount'),
        investment_timespan = document.querySelector('#investment_timespan'),
        investment_timespan_text = document.querySelector('#investment_timespan_text'),
        estimated_return = document.querySelector('#estimated_return'),
        future_balance = document.querySelector('#future_balance');

    function updateValue(element, action) {
        var min = parseFloat(element.getAttribute('min')),
            max = parseFloat(element.getAttribute('max')),
            step = parseFloat(element.getAttribute('step')) || 1,
            oldValue = element.dataset.value || element.defaultValue || 0,
            newValue = parseFloat(element.value.replace(/\$/, ''));

        if (isNaN(parseFloat(newValue))) {
            newValue = oldValue;
        } else {
            if (action == 'add') {
                newValue += step;
            } else if (action == 'sub') {
                newValue -= step;
            }

            newValue = newValue < min ? min : newValue > max ? max : newValue;
        }

        element.dataset.value = newValue;
        element.value = (element.dataset.prepend || '') + newValue + (element.dataset.append || '');

        updateChart();
    }

    function getChartData() {
        var P = parseFloat(initial_deposit.dataset.value), // Principal
            r = parseFloat(estimated_return.dataset.value / 100), // Annual Interest Rate
            c = parseFloat(contribution_amount.dataset.value), // Contribution Amount
            n = parseInt(document.querySelector('[name="compound_period"]:checked').value), // Compound Period
            n2 = parseInt(document.querySelector('[name="contribution_period"]:checked').value), // Contribution Period
            t = parseInt(investment_timespan.value), // Investment Time Span
            currentYear = (new Date()).getFullYear()
            ;

        var labels = [];
        for (var year = currentYear; year < currentYear + t; year++) {
            labels.push(year);
        }

        var principal_dataset = {
            label: 'Total Principal',
            backgroundColor: 'rgb(0, 123, 255)',
            data: []
        };

        var interest_dataset = {
            label: "Total Interest",
            backgroundColor: 'rgb(23, 162, 184)',
            data: []
        };

        for (var i = 1; i <= t; i++) {
            var principal = P + ( c * n2 * i ),
                interest = 0,
                balance = principal;

            if (r) {
                var x = Math.pow(1 + r / n, n * i),
                    compound_interest = P * x,
                    contribution_interest = c * (x - 1) / (r / n2);
                interest = (compound_interest + contribution_interest - principal).toFixed(0)
                balance = (compound_interest + contribution_interest).toFixed(0);
            }

            future_balance.innerHTML = '$' + balance;
            principal_dataset.data.push(principal);
            interest_dataset.data.push(interest);
        }

        return {
            labels: labels,
            datasets: [principal_dataset, interest_dataset]
        }
    }

    function updateChart() {
        var data = getChartData();

        chart.data.labels = data.labels;
        chart.data.datasets[0].data = data.datasets[0].data;
        chart.data.datasets[1].data = data.datasets[1].data;
        chart.update();
    }

    initial_deposit.addEventListener('change', function () {
        updateValue(this);
    });

    contribution_amount.addEventListener('change', function () {
        updateValue(this);
    });

    estimated_return.addEventListener('change', function () {
        updateValue(this);
    });

    investment_timespan.addEventListener('change', function () {
        investment_timespan_text.innerHTML = this.value + ' years';
        updateChart();
    });

    investment_timespan.addEventListener('input', function () {
        investment_timespan_text.innerHTML = this.value + ' years';
    });

    var radios = document.querySelectorAll('[name="contribution_period"], [name="compound_period"]');
    for (var j = 0; j < radios.length; j++) {
        radios[j].addEventListener('change', updateChart);
    }

    var buttons = document.querySelectorAll('[data-counter]');
    for (var i = 0; i < buttons.length; i++) {
        var button = buttons[i];

        button.addEventListener('click', function () {
            var field = document.querySelector('[name="' + this.dataset.field + '"]'),
                action = this.dataset.counter;

            if (field) {
                updateValue(field, action);
            }
        });
    }

    var ctx = document.getElementById('myChart').getContext('2d'),
        chart = new Chart(ctx, {
            type: 'bar',
            data: getChartData(),
            options: {
                legend: {
                    display: false
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ': $' + tooltipItem.yLabel;
                        }
                    }
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Year'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            callback: function (value) {
                                return '$' + value;
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Balance'
                        }
                    }]
                }
            }
        });

})();
	</script>
</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="initial_deposit">Initial Deposit</label>
                <div class="row">
                    <div class="input-group col-md-6 col-sm-8">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary" type="button" data-counter="sub" data-field="initial_deposit">&minus;</button>
                        </div>
                        <input class="form-control text-center" id="initial_deposit" type="text" name="initial_deposit" min="100" max="1000000" step="100" value="$5000" data-value="5000" data-prepend="$">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" data-counter="add" data-field="initial_deposit">&plus;</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="contribution_amount">Contributions</label>
                <div class="row">
                    <div class="input-group col-md-6 col-sm-8">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary" type="button" data-counter="sub" data-field="contribution_amount">&minus;</button>
                        </div>
                        <input class="form-control text-center" id="contribution_amount" type="text" name="contribution_amount" min="0" max="10000" step="50" value="$100" data-value="100" data-prepend="$">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" data-counter="add" data-field="contribution_amount">&plus;</button>
                        </div>
                    </div>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" id="contribution_period_monthly" type="radio" name="contribution_period" value="12" checked>
                    <label class="form-check-label" for="contribution_period_monthly">monthly</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" id="contribution_period_annually" type="radio" name="contribution_period" value="1">
                    <label class="form-check-label" for="contribution_period_annually">annually</label>
                </div>
            </div>

            <div class="form-group">
                <label for="investment_timespan">Investment Time Span</label>
                <div class="row">
                    <div class="col-md-6 col-sm-8">
                        <input class="form-control" id="investment_timespan" type="range" name="investment_timespan" min="2" max="50" step="1" value="5">
                    </div>
                </div>
                <span id="investment_timespan_text">5 years</span>
            </div>

            <div class="form-group">
                <label for="estimated_return">Estimated Rate of Return</label>
                <div class="row">
                    <div class="input-group col-md-6 col-sm-8">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary" type="button" data-counter="sub" data-field="estimated_return">&minus;</button>
                        </div>
                        <input class="form-control text-center" id="estimated_return" type="text" name="estimated_return" min="0" max="50" step="0.25" value="5.00%" data-value="5.00" data-append="%">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" data-counter="add" data-field="estimated_return">&plus;</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <div>Compound Frequency</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="compound_period_daily" type="radio" name="compound_period" value="365">
                        <label class="form-check-label" for="compound_period_daily">daily</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="compound_period_monthly" type="radio" name="compound_period" value="12" checked>
                        <label class="form-check-label" for="compound_period_monthly">monthly</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="compound_period_annually" type="radio" name="compound_period" value="1">
                        <label class="form-check-label" for="compound_period_annually">annually</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <div>Future Balance</div>
            <div class="h3" id="future_balance">?</div>
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

