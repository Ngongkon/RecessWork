<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['School Name', 'Participant ount'],
                ['Best School',     11],
                ['Average School',      6],
                ['Worst School',  2],
                // ['Watcchh TV', 2],
                // ['Sleep',    7]
            ]);

            var options = {
                title: 'Statistics For Mathematics Competition'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
    <style>
      
        #piechart {
            width: 500px;
            height: 300px;
             
        }
    </style>
</head>
<body>
<div id="piechart"></div>
</body>
</html>
