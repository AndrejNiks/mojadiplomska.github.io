<?php

$tmp = $_GET['a'];

$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect("localhost","root","") or die(mysql_error());
$db = mysqli_select_db($conn,"admindata") or die(mysql_error());

$query = "SELECT Id,Username, Pass, Indeks, Poeni FROM Users ORDER by Poeni DESC"; 
$result = mysqli_query($conn,$query) or die(mysql_error());
$list = array();

while($row = mysqli_fetch_array($result))
{
	$list[] = (object) array('id' => $row['Id'], 'username' => $row['Username'], 'Pass' => $row['Pass'], 'Indeks' => $row['Indeks'], 'Poeni' => $row['Poeni']);
}

$query2 = "SELECT users.Id, Username, ime, avg(polozhenipredmeti.Ocena) as prosek FROM users, polozhenipredmeti, profesori where users.Id = ". $tmp ." and polozhenipredmeti.Profesor_Id = profesori.Profesor_Id GROUP by Username, ime ORDER BY prosek DESC"; 
$result2 = mysqli_query($conn,$query2) or die(mysql_error());
$list2 = array();

while($row = mysqli_fetch_array($result2))
{
	$list2[] = (object) array('id' => $row['Id'], 'username' => $row['Username'], 'ime' => $row['ime'], 'prosek' => $row['prosek']);
}

$query3 = "SELECT users.Id, users.Username, kategorija.Naziv, avg(polozhenipredmeti.Ocena) as prosek FROM users, polozhenipredmeti, finki_predmeti, kategorija where users.Id = ". $tmp ." and polozhenipredmeti.IdPredmet = finki_predmeti.Predmet_Id and finki_predmeti.Kategorija = kategorija.Kategorija_Id GROUP by users.Username, kategorija.Naziv ORDER BY prosek DESC";
$result3 = mysqli_query($conn,$query3) or die(mysql_error());
$list3 = array();

while($row = mysqli_fetch_array($result3))
{
	$list3[] = (object) array('id' => $row['Id'], 'username' => $row['Username'], 'naziv' => $row['Naziv'], 'prosek' => $row['prosek']);
}


$query4 = "SELECT Ispitna_Sesija as isp, AVG(polozhenipredmeti.Ocena) as prosek from polozhenipredmeti where polozhenipredmeti.IdStudent = ". $tmp ." GROUP by Ispitna_Sesija";
$result4 = mysqli_query($conn,$query4) or die(mysql_error());
$list4 = array();

while($row = mysqli_fetch_array($result4))
{
	$list4[] = (object) array('isp' => $row['isp'], 'prosek' => $row['prosek']);
}

$query5 = "SELECT Ispitna_Sesija as isp, COUNT(polozhenipredmeti.IdPredmet)as prosek from polozhenipredmeti where polozhenipredmeti.IdStudent = ". $tmp ." GROUP by Ispitna_Sesija";
$result5 = mysqli_query($conn,$query5) or die(mysql_error());
$list5 = array();

while($row = mysqli_fetch_array($result5))
{
	$list5[] = (object) array('isp' => $row['isp'], 'prosek' => $row['prosek']);
}



$conn->close();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Finki</title>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- C3 Charts JavaScript -->
    <script src="js/d3.min.js"></script>
    <script src="js/c3.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="js/custom.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="fonts/css/font-awesome.css">
	
	
	<!-- <link rel="stylesheet" href="data\vendor\morrisjs\morris.css" />-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
	
	<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
	
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<!-- za brishenje -->
	<script src="libraries/RGraph.common.core.js"></script>
	<script src="libraries/RGraph.rose.js"></script>
	
	

</head>

<style>
h3 {
	font-family: "Georgia", serif;
	 font-weight: bold;
	  font-size:22px;
	  color: #4d4d4d;
}


</style>


<body>

	<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header col-xs-6 col-lg-8">
	  <img class="navbar-brand" src="res/finki.png">
      <a class="navbar-brand" href="#">Финки Статистики</a>
    </div>
	
	<ul id="nv-2" class="nav navbar-nav navbar-right col-xs-3 col-lg-4">
	<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Избери статистика
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
				<li><a class="page-scroll" href="#section1">Просечна оцена</a></li>
				<li><a class="page-scroll" href="#ProsekPoIspitna">Просек по сесија</a></li>
				<li><a class="page-scroll" href="#brojPredmeti">Положени предмети по сесија</a></li>
        </ul>
      </li>	
	  <li> <a id="najaven_e" href="#"><span class="glyphicon glyphicon-user"></span></a></li>
      <li role="button" onclick="odjava()"><a href="#"><span class="glyphicon glyphicon-log-in"></span> LogOut</a></li>
  </div>
</nav>
  
  
  <section id="section1" class="container-fluid">
	</br></br></br>
	<div class="col-md-3">
	
	<div class="row">
		<h3 style="text-align:center"> ПрвНаФИНКИ </h3>
	</div>
	
	<div class="row">
  <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Име</th>
      <th scope="col">Индекс</th>
      <th scope="col">Поени</th>
    </tr>
  </thead>
  <tbody>
    <tr id="1">
      <th scope="row">1</th>
      <td id="s1"></td>
      <td id="s11"></td>
      <td id="s111"></td>
    </tr>
    <tr id="2">
      <th scope="row">2</th>
      <td id="s2"></td>
      <td id="s22"></td>
      <td id="s222"></td>
    </tr>
    <tr id="3">
      <th scope="row">3</th>
      <td id="s3"></td>
      <td id="s33"></td>
      <td id="s333"></td>
    </tr>
	 <tr id="4">
      <th scope="row">4</th>
      <td id="s4"></td>
      <td id="s44"></td>
      <td id="s444"></td>
    </tr>
	 <tr id="5">
      <th scope="row">5</th>
      <td id="s5"></td>
      <td id="s55"></td>
      <td id="s555"></td>
    </tr>
	 <tr id="6">
      <th scope="row">6</th>
      <td id="s6"></td>
      <td id="s66"></td>
      <td id="s666"></td>
    </tr>
	 <tr id="7">
      <th scope="row">7</th>
      <td id="s7"></td>
      <td id="s77"></td>
      <td id="s777"></td>
    </tr>
	 <tr id="8">
      <th scope="row">8</th>
      <td id="s8"></td>
      <td id="s88"></td>
      <td id="s888"></td>
    </tr>
	 <tr id="9">
      <th scope="row">9</th>
      <td id="s9"></td>
      <td id="s99"></td>
      <td id="s999"></td>
    </tr>
	 <tr id="10">
      <th scope="row">10</th>
      <td id="s10"></td>
      <td id="s1010"></td>
      <td id="s101010"></td>
    </tr>
	 <tr>
      <th scope="col">#</th>
      <th scope="col">Мое Име</th>
      <th scope="col">Мој Индекс</th>
      <th scope="col">Мои Поени</th>
    </tr>
	<tr style="color:white ; background-color:#428bca">
      <th id="userId" scope="col">#</th>
      <th id="userName" scope="col">Име</th>
      <th id="userIndeks" scope="col">Индекс</th>
      <th id="userPoeni" scope="col">Поени</th>
    </tr>
  </tbody>
</table>
	
	</div>
  
  </div>
  
  <div class="col-md-9">
    <div id="chart" class="row">
      <div class="col-md-6">
		<h3 style="text-align:center"> Просечна оцена по професор </h3>
		<div id="donut-chart"></div>
      </div>
	  
	  <div class="col-md-6">
	    <h3 style="text-align:center"> Просечна оцена по категорија </h3>
        <div id="donut-chart2"></div>
      </div>
	</div>
	</br>
	<div class="row" id="ProsekPoIspitna">
		<div class="col-md-12">
		</br></br></br>
			<div id="chartContainer" style="height: 500px; width: 100%;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" id="brojPredmeti">
			</br></br></br>
			<h3 style="text-align:center ; font-size:33px"> Број на положени предмети по сесија </h3>
			<div id="chart3"></div>
		</div>
	</div>
	
</div>
	
  </section>
	</br>
  
	


   
<nav class="navbar navbar-default navbar-fixed-bottom text-center">
    <span class="glyphicon glyphicon-copyright-mark" style="margin-top: 15px;"> ФИНКИ 2018</span>
</nav>

<script>

$(document).ready(function() {
	
	donutChart();
	donutChart2();
	
	
	console.log(sessionStorage.getItem('Id'))
	console.log(sessionStorage.getItem('Username'))
	
	var std_id = sessionStorage.getItem('Id');
	var std_name = sessionStorage.getItem('Username');
	var std_indeks = sessionStorage.getItem('Indeks');
	var std_poeni = sessionStorage.getItem('Poeni');
	
	if(std_id<=10) document.getElementById("userId").innerHTML=std_id;
	document.getElementById("userName").innerHTML=std_name;
	document.getElementById("userIndeks").innerHTML=std_indeks;
	document.getElementById("userPoeni").innerHTML=std_poeni;
	
	
	document.getElementById("najaven_e").innerHTML = "<span class='glyphicon glyphicon-user'></span> &nbsp " + std_name + " " + std_indeks;
	
	if(std_id <= 10){
		 document.getElementById(std_id).style.backgroundColor = "#d9534f";
		 document.getElementById(std_id).style.color = "white" ;
	}
			
	
})




var Std_ProsekPoProf = <?php echo json_encode($list2); ?>;	
console.log(Std_ProsekPoProf);

function donutChart() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart',
  data: [
    {label: Std_ProsekPoProf[0].ime, value: Std_ProsekPoProf[0].prosek},
    {label: Std_ProsekPoProf[1].ime, value: Std_ProsekPoProf[1].prosek},
    {label: "Mail-Order Sales", value: 5},
    {label: "Uploaded Sales", value: 10},
    {label: "Video Sales", value: 10}
  ],
  pointFillColors: ['#ffffff'],
  pointStrokeColors: ['black'],
  resize: true,
  redraw: true,
  formatter: function (y, data) { return parseFloat(Math.round(y * 100) / 100).toFixed(1) + "%" }
});
}


var Std_ProsekPoKategorija = <?php echo json_encode($list3); ?>;	
console.log(Std_ProsekPoKategorija);

function donutChart2() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart2',
  data: [
    {label: Std_ProsekPoKategorija[0].naziv, value: Std_ProsekPoKategorija[0].prosek},
    {label: Std_ProsekPoKategorija[1].naziv, value: Std_ProsekPoKategorija[1].prosek},
    {label: "Mail-Order Sales", value: 5},
    {label: "Uploaded Sales", value: 10},
    {label: "Video Sales", value: 10}
  ],
  pointFillColors: ['#ffffff'],
  pointStrokeColors: ['black'],
  resize: true,
  redraw: true,
  formatter: function (y, data) { return parseFloat(Math.round(y * 100) / 100).toFixed(1) + "%" }
});
}


var Std_ProsekPoSesija = <?php echo json_encode($list4); ?>;	
console.log(Std_ProsekPoSesija);

window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	//exportEnabled: true,
	animationEnabled: true,
	title: {
		text: "Просек на оцени по испитна сесија"
	},
	data: [{
		type: "pie",
		startAngle: 25,
		toolTipContent: "<b>{label}</b> - Просечна оцена: {y}",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{label} - {y}  ",
		dataPoints: [
			{ y: Std_ProsekPoSesija[0].prosek, label: "Јануарска сесија" },
			{ y: 6, label: "Јунска сесија" },
			{ y: 8, label: "Септемвриска сесија" },
		]
	}]
});
chart.render();

}



var BrPredmetiPoSesija = <?php echo json_encode($list5); ?>;	
console.log(BrPredmetiPoSesija);

var y0=[],y1=[],y2=[];

    if(typeof(BrPredmetiPoSesija[0]) == 'undefined'){
		y0[0] = 0;
	}
	else {
	y0[0] = BrPredmetiPoSesija[0].prosek
	}
	
    if(typeof(BrPredmetiPoSesija[1]) == 'undefined'){
		y1[0] = 0;
	}
	else {
	y1[0] = BrPredmetiPoSesija[1].prosek
	}
	
	if(typeof(BrPredmetiPoSesija[2]) == 'undefined'){
		y2[0] = 0;
	}
	else {
	y2[0] = BrPredmetiPoSesija[2].prosek
	}


var trace1 = {
  y: y0,
  type: 'box',
  name: 'Јануарска сесија'
};

var trace2 = {
  y: y1,
  type: 'box',
  name: 'Јунска сесија'
};

var trace3 = {
  y: y2,
  type: 'box',
  name: 'Септемвриска сесија'
};

var data = [trace1, trace2, trace3];

Plotly.newPlot('chart3', data);





var val= <?php echo json_encode($list); ?>;	
console.log(val);
	var tmp = 1 ;
	for(var i=0; i<val.length ;i++) {
			var pom = 's' + tmp ;
			document.getElementById(pom).innerHTML=val[i].username;
			var pom = 's' + tmp + '' + tmp ;
			document.getElementById(pom).innerHTML=val[i].Indeks;
			var pom = 's' + tmp + '' + tmp + '' + tmp;
			document.getElementById(pom).innerHTML=val[i].Poeni;
		tmp++ ;
	}
	




function odjava() {
	sessionStorage.clear();
	window.location.href = "index.php";
}



</script>

</body>
</html>