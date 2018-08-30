<?php
	$servername = "localhost";
	$username = "root";
	$password = "";

	$conn = mysqli_connect("localhost","root","") or die(mysql_error());
	$db = mysqli_select_db($conn,"admindata") or die(mysql_error());


	$query = "SELECT Id,Username, Pass, Indeks, Poeni FROM Users ORDER by Poeni DESC"; 
	$result = mysqli_query($conn,$query) or die(mysql_error());
	$list = array();

	$num = 1 ;
	while($row = mysqli_fetch_array($result))
	{
		$list[] = (object) array('RBroj' => $num ,'id' => $row['Id'], 'username' => $row['Username'], 'Pass' => $row['Pass'], 'Indeks' => $row['Indeks'], 'Poeni' => $row['Poeni']);
		$num = $num + 1;
	}


	$profQuery2 = "SELECT ime, avg(polozhenipredmeti.Ocena) as prosek
	from profesori INNER join polozhenipredmeti on profesori.Profesor_Id = polozhenipredmeti.Profesor_Id
	GROUP by Ime ORDER by prosek DESC";
	$result2 = mysqli_query($conn,$profQuery2) or die(mysql_error());
	$list2 = array();

	while($row = mysqli_fetch_array($result2))
	{
		$list2[] = (object) array('ime' => $row['ime'], 'prosek' => $row['prosek']);
	}


	$profQuery3 = "SELECT ime , (100 / br1) * br2 as prosek FROM profesori, (SELECT profesori.Profesor_Id as pom1, COUNT(predmet_student.Student_Id) as br1 FROM profesori, predmet_student WHERE profesori.Profesor_Id = predmet_student.Profesor_Id GROUP by pom1) p1, (SELECT profesori.Profesor_Id as pom2, COUNT(polozhenipredmeti.IdStudent) as br2 FROM profesori, polozhenipredmeti WHERE profesori.Profesor_Id = polozhenipredmeti.Profesor_Id GROUP by pom2) p2 WHERE profesori.Profesor_Id = p1.pom1 and profesori.Profesor_Id = p2.pom2 ORDER by prosek DESC";
	$result3 = mysqli_query($conn,$profQuery3) or die(mysql_error());
	$list3 = array();

	while($row = mysqli_fetch_array($result3))
	{
		$list3[] = (object) array('ime' => $row['ime'], 'prosek' => $row['prosek']);
	}


	$profQuery4 = "SELECT naziv, prosek from kategorija, ( SELECT finki_predmeti.Kategorija as ktg, AVG((100 / br1) * br2) as prosek FROM finki_predmeti, (SELECT finki_predmeti.Predmet_Id as pom1, COUNT(predmet_student.Student_Id) as br1 FROM finki_predmeti, predmet_student WHERE finki_predmeti.Predmet_Id = predmet_student.Predmet_Id GROUP by pom1) p1, (SELECT finki_predmeti.Predmet_Id as pom2, COUNT(polozhenipredmeti.IdStudent) as br2 FROM finki_predmeti, polozhenipredmeti WHERE finki_predmeti.Predmet_Id = polozhenipredmeti.IdPredmet GROUP by pom2) p2 WHERE finki_predmeti.Predmet_Id = p1.pom1 and finki_predmeti.Predmet_Id = p2.pom2 GROUP by finki_predmeti.Kategorija ) as p1 where kategorija.Kategorija_Id = p1.ktg ORDER by prosek DESC";
	$result4 = mysqli_query($conn,$profQuery4) or die(mysql_error());
	$list4 = array();

	while($row = mysqli_fetch_array($result4))
	{
		$list4[] = (object) array('naziv' => $row['naziv'], 'prosek' => $row['prosek']);
	}

	$profQuery5 = "SELECT Naziv , (100 / br1) * br2 as prosek FROM finki_predmeti, (SELECT finki_predmeti.Predmet_Id as pom1, COUNT(predmet_student.Student_Id) as br1 FROM finki_predmeti, predmet_student WHERE finki_predmeti.Predmet_Id = predmet_student.Predmet_Id GROUP by pom1) p1, (SELECT finki_predmeti.Predmet_Id as pom2, COUNT(polozhenipredmeti.IdStudent) as br2 FROM finki_predmeti, polozhenipredmeti WHERE finki_predmeti.Predmet_Id = polozhenipredmeti.IdPredmet GROUP by pom2) p2 WHERE finki_predmeti.Predmet_Id = p1.pom1 and finki_predmeti.Predmet_Id = p2.pom2 ORDER by prosek DESC";
	$result5 = mysqli_query($conn,$profQuery5) or die(mysql_error());
	$list5 = array();

	while($row = mysqli_fetch_array($result5))
	{
		$list5[] = (object) array('naziv' => $row['Naziv'], 'prosek' => $row['prosek']);
	}


	$profQuery6 = "SELECT Godina, avg(polozhenipredmeti.Ocena) as prosek, COUNT(DISTINCT predmet_student.Id) as broj from predmet_student, polozhenipredmeti where predmet_student.Predmet_Id = polozhenipredmeti.IdPredmet GROUP by Godina";
	$result6 = mysqli_query($conn,$profQuery6) or die(mysql_error());
	$list6 = array();

	while($row = mysqli_fetch_array($result6))
	{
		$list6[] = (object) array('Godina' => $row['Godina'], 'prosek' => $row['prosek'], 'broj' => $row['broj']);
	}

	$profQuery7 = "SELECT naziv, avg(polozhenipredmeti.Ocena) as prosek from finki_predmeti INNER join polozhenipredmeti on finki_predmeti.Predmet_Id = polozhenipredmeti.IdPredmet GROUP by naziv ORDER by prosek DESC";
	$result7 = mysqli_query($conn,$profQuery7) or die(mysql_error());
	$list7 = array();

	while($row = mysqli_fetch_array($result7))
	{
		$list7[] = (object) array('naziv' => $row['naziv'], 'prosek' => $row['prosek']);
	}

	$conn->close();



	if(isset( $_POST['login'])) {
		
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = mysqli_connect("localhost","root","") or die(mysql_error());
		$db = mysqli_select_db($conn,"admindata") or die(mysql_error());

		$user =  $_POST['user2'] ;
		$pass =  $_POST['pas2'] ;

		$query = "SELECT id, Username, Indeks, Poeni, COUNT(users.Id) as broj FROM users WHERE users.Indeks = '".$user."' and users.Pass = '".$pass."'"; 
		$result = mysqli_query($conn,$query) or die(mysql_error());
		$loglist = array();

			while($row = mysqli_fetch_array($result))
			{
				$loglist[] = (object) array('id' => $row['id'], 'username' => $row['Username'], 'Indeks' => $row['Indeks'],'Poeni' => $row['Poeni'], 'broj' => $row['broj']);
			}
					
			if($loglist[0]->broj > 0) {
				
				$broj = 1 ;
				foreach ($list as $value) {
					if($value->id == $loglist[0]->id){
						$broj = $value->RBroj ;
					}
				}
				
				echo "<script> sessionStorage.setItem('Id',". $broj .") </script>";
				echo "<script> sessionStorage.setItem('Username', '".$loglist[0]->username."') </script>";
				echo "<script> sessionStorage.setItem('Indeks', ".$loglist[0]->Indeks.") </script>";
				echo "<script> sessionStorage.setItem('Poeni', ".$loglist[0]->Poeni.") </script>";
				echo "<script> window.location.href = 'login.php?a=". $broj ."' </script>";
			}		
			
			
			else {
				$current_url = 'index.php';
				header("Location: $current_url");
				echo "<script> console.log('nema korisnik') </script>";
				
				
			}
	}
	
	if(isset( $_POST['msg'])){
		
		$msg =  $_POST['poraka'] ;
		
		if($msg != '') {
		
		$servername = "localhost";
		$username = "root";
		$password = "";

		$conn = mysqli_connect("localhost","root","") or die(mysql_error());
		$db = mysqli_select_db($conn,"admindata") or die(mysql_error());

		

		$query = "INSERT into zabeleshki (Text) VALUES ('".$msg."')"; 
		$result = mysqli_query($conn,$query) or die(mysql_error());
		
		echo "<script> window.alert('Успешно испратена забелешка') </script>";
		}
		else {
			echo "<script> window.alert('Немате внесено никаков текст, обидете се повторно') </script>";
		}
	}


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
	
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
	
	
	<!-- za brishenje -->
	<script src="libraries/RGraph.common.core.js"></script>
	<script src="libraries/RGraph.rose.js"></script>
	
	

</head>

<style>
h3 {
	font-family: "Times New Roman", Times, serif;
}

.Red::placeholder
    {
        color: red; /* Most modern browsers support this now. */
    }
#section1 {
	margin-top:50px;
}
</style>


<body>

	<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header col-xs-10 col-md-8">
	  <img class="navbar-brand" src="res/finki.png">
      <a class="navbar-brand" href="#">Финки Статистики</a>
    </div>
	
    <ul id="nv-1" class="nav navbar-nav navbar-right col-xs-8 col-md-4 col-lg-3">
	<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Избери статистика
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
				<li><a class="page-scroll" href="#section1">Пропусност</a></li>
				<li><a class="page-scroll" href="#prosekPoGodini">Просек</a></li>
                <li><a class="page-scroll" href="#T5Prof">Т5 професори</a></li>
				<li><a class="page-scroll" href="#T5Ped" href="#">Т5 предмети</a></li>
                <li><a class="page-scroll" href="#Zabeleshka" href="#">Твоја забелешка</a></li>
        </ul>
      </li>	
      <li role="button" class="najava" onclick="otvoreno()" data-toggle="modal" data-target="#myModal2"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    </ul>
	
  </div>
</nav>

  <section id="section1" class="container-fluid">
  </br></br>
  <div class="col-xs-12 col-md-3">
	
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
    <tr>
      <th scope="row">1</th>
      <td id="p1"></td>
      <td id="p11"></td>
      <td id="p111"></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td id="p2"></td>
      <td id="p22"></td>
      <td id="p222"></td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td id="p3"></td>
      <td id="p33"></td>
      <td id="p333"></td>
    </tr>
	 <tr>
      <th scope="row">4</th>
      <td id="p4"></td>
      <td id="p44"></td>
      <td id="p444"></td>
    </tr>
	 <tr>
      <th scope="row">5</th>
      <td id="p5"></td>
      <td id="p55"></td>
      <td id="p555"></td>
    </tr>
	 <tr>
      <th scope="row">6</th>
      <td id="p6"></td>
      <td id="p66"></td>
      <td id="p666"></td>
    </tr>
	 <tr>
      <th scope="row">7</th>
      <td id="p7"></td>
      <td id="p77"></td>
      <td id="p777"></td>
    </tr>
	 <tr>
      <th scope="row">8</th>
      <td id="p8"></td>
      <td id="p88"></td>
      <td id="p888"></td>
    </tr>
	 <tr>
      <th scope="row">9</th>
      <td id="p9"></td>
      <td id="p99"></td>
      <td id="p999"></td>
    </tr>
	 <tr>
      <th scope="row">10</th>
      <td id="p10"></td>
      <td id="p1010"></td>
      <td id="p101010"></td>
    </tr>
  </tbody>
</table>
	
	</div>
  
  </div>
  
  <div class="col-xs-12 col-md-9">
    <div id="chart" class="row">
      <div class="col-md-6">
		<h3 style="text-align:center"> Пропусност на студенти по професори </h3>
		<div id="donut-chart"></div>
      </div>
	  
	  <div class="col-md-6">
	    <h3 style="text-align:center"> Пропусност на студенти по категорија </h3>
        <div id="donut-chart3"></div>
      </div>
	</div>	
	
	<div class="row">
	<div class="col-md-12">
	    <h3 style="text-align:center"> Пропусност на студенти по предмети </h3>
        <div id="donut-chart2"></div>
      </div>
	</div>
	
	</div>
	
	<div>
		<div class="col-xs-12" id="prosekPoGodini">
		</br></br>
	    <h3 style="text-align:center"> Просек по учебни години </h3>
        <div id="area-chart" style="width:100%; height:400px"></div>
      </div>
	</div>
	
	<div id="Bar" class="col-xs-12"> 
		<div class="block text-center head-text extraPadding" id="T5Prof">
		<h1 style="margin-top:100px">Топ 5 Професори на <i>ФИНКИ</i></h1>
    <br>
     <canvas id="bar-chart" width="300" height="100"></canvas>
		</div>
	</div>
	<hr>
	
	<div id="Bar2" class="col-xs-12"> 
		<div class="block text-center head-text extraPadding" id="T5Ped">
		<h1 style="margin-top:100px">Топ 5 Предмети на <i>ФИНКИ</i></h1>
    <br>
     <canvas id="bar-chart2" width="300" height="100"></canvas>
		</div>
	</div>
	<hr>
	
	<div class="col-md-12"> 
		<h1></h1>
		<br>
		<h1></h1>
		<br>
	</div>
	
	
	<div class="row">
            <div class="box">
                <div class="col-lg-12" id="Zabeleshka">
                    <hr>
                    <h2 class="intro-text text-center">Твоја
                        <strong>забелешка</strong>
                    </h2>
                    <hr>
                    <p> Испратете ни забелешка околу одреден проблем кој настанал или сте го заприметиле на факултетот </p>
					<p><i><b> Ве молиме не ја злоупотребувајте анонимноста која ви ја нудиме во оваа опција </b></i></p>
					</br>
					<iframe name="votar" style="display:none;"></iframe>
					<form role="form" action="index.php" method="post" target="votar">
                        <div class="row">
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-12">
                                <label>Порака</label>
                                <textarea id="poraka" class="form-control" name="poraka" rows="6"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <input type="hidden" name="save" value="contact">
                                <input type="submit" value="Испрати" name="msg" class="btn btn-default btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		</br>
		</br>
  </section>
  
  
  <section>
  
  <iframe name="votar" style="display:none;"></iframe>
  <form class="form-group" action="index.php" method="post" id="form2" >
  <div class="modal fade" id="myModal2" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" onclick="zatvoreno()" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Внесете ги вашите податоци за најава</h4>
        </div>
        <div class="modal-body">
			
            <div class="form-group" id="form2">
                    <input type="text" value="" name="user2" placeholder="Корисничко име" class="form-control" id="usr"> </br>
                    <input type="password" value="" name="pas2" placeholder="Лозинка" class="form-control" id="psw"> </br>
					<input class="form-control btn btn-primary" type="submit" name="login" id="login" value="Најави се">
            </div>

        </div>
		
        <div class="modal-footer">
				<button id="exit" type="button" onclick="zatvoreno()" class="btn btn-danger col-xs-4 col-md-3" style="float:left" data-dismiss="modal">Излези</button>
		</div>
      </div>
      
    </div>
  </div>
  </form>
  </section>
  
	


   
<nav class="navbar navbar-default navbar-fixed-bottom text-center">
    <span class="glyphicon glyphicon-copyright-mark" style="margin-top: 15px;"> ФИНКИ 2018</span>
</nav>

<script>

$(document).ready(function() {
  //barChart();
  //lineChart();
  areaChart();
  donutChart();
  donutChart2();
  donutChart3();

  $(window).resize(function() {
    window.donutChart.redraw();
  });
  
  var ot = sessionStorage.getItem('Otvoreno');
  console.log(ot);
  if(ot == 1){
  $('.najava').trigger('click');
  document.getElementById("usr").className += " Red"
  document.getElementById("psw").className += " Red"
  }
  
  
  
});

var profPropusnost= <?php echo json_encode($list3); ?>;	
console.log(profPropusnost);

function donutChart() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart',
  data: [
    {label: profPropusnost[0].ime, value: profPropusnost[0].prosek},
    {label: profPropusnost[1].ime, value: profPropusnost[1].prosek},
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

var predmetPropusnost= <?php echo json_encode($list5); ?>;	
console.log(predmetPropusnost);

function donutChart2() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart2',
  data: [
    {label: predmetPropusnost[0].naziv, value: predmetPropusnost[0].prosek},
    {label: predmetPropusnost[1].naziv, value: predmetPropusnost[1].prosek},
    {label: predmetPropusnost[2].naziv, value: predmetPropusnost[2].prosek},
    {label: predmetPropusnost[3].naziv, value: predmetPropusnost[3].prosek},
    {label: predmetPropusnost[4].naziv, value: predmetPropusnost[4].prosek}
  ],
  resize: true,
  redraw: true,
  formatter: function (y, data) { return parseFloat(Math.round(y * 100) / 100).toFixed(1) + "%" }
});
}

var katPropusnost= <?php echo json_encode($list4); ?>;	
console.log(katPropusnost);


function donutChart3() {
  window.donutChart = Morris.Donut({
  element: 'donut-chart3',
  data: [
    {label: katPropusnost[0].naziv, value: katPropusnost[0].prosek},
    {label: katPropusnost[1].naziv, value: katPropusnost[1].prosek},
    {label: "Категорија3", value: 5},
	 {label: "Категорија4", value: 5},
	  {label: "Категорија5", value: 5},
  ],
  resize: true,
  redraw: true,
  formatter: function (y, data) { return parseFloat(Math.round(y * 100) / 100).toFixed(1) + "%" }
});
}



var prosekPoGodina= <?php echo json_encode($list6); ?>;	
console.log(prosekPoGodina);

function areaChart() {
  window.areaChart = Morris.Area({
    element: 'area-chart',
    data: [
      { y: '2015/16', a: prosekPoGodina[0].prosek,  b: 0 },
      { y: '2016/17', a: prosekPoGodina[1].prosek,  b: prosekPoGodina[0].prosek - prosekPoGodina[1].prosek },
      { y: '2017/18', a: prosekPoGodina[2].prosek,  b: prosekPoGodina[1].prosek - prosekPoGodina[2].prosek },
      { y: '2018/19', a: prosekPoGodina[3].prosek,  b: prosekPoGodina[2].prosek - prosekPoGodina[3].prosek },
    ],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Просек на оцени', 'Просекот од претходната година бил различен за'],
    lineColors: ['#1e88e5','#ff3321'],
    lineWidth: '3px',
	ymin:6,
	ymax: 10,
    resize: true,
    redraw: true
  });
}


var profProsek= <?php echo json_encode($list2); ?>;	
console.log(profProsek)

new Chart(document.getElementById("bar-chart"), {
  type: 'bar',  
  data: {
    labels: [profProsek[0].ime, profProsek[1].ime, "Europe", "Latin America", "North America", "#"],
    datasets: [
      {
        label: "",
        backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        data: [profProsek[0].prosek, profProsek[1].prosek,8,7,7,6]
      }
    ]
  },
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Просечна оцена по професор'
    },
    layout: {      
      padding: {
        left: 50
      }
    }
  }
});

var prosekPoPredmet= <?php echo json_encode($list7); ?>;	
console.log(prosekPoPredmet);

new Chart(document.getElementById("bar-chart2"), {
  type: 'bar',  
  data: {
    labels: [prosekPoPredmet[0].naziv, prosekPoPredmet[1].naziv, prosekPoPredmet[2].naziv, prosekPoPredmet[3].naziv, prosekPoPredmet[4].naziv, "#"],
    datasets: [
      {
        label: "",
        backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        data: [prosekPoPredmet[0].prosek, prosekPoPredmet[1].prosek,prosekPoPredmet[2].prosek,prosekPoPredmet[3].prosek,prosekPoPredmet[4].prosek,6]
      }
    ]
  },
  options: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Просечна оцена по предмети'
    },
    layout: {      
      padding: {
        left: 50
      }
    }
  }
});



var val= <?php echo json_encode($list); ?>;	
console.log(val);
	var tmp = 1 ;
	for(var i=0; i<val.length ;i++) {
			var pom = 'p' + tmp ;
			document.getElementById(pom).innerHTML=val[i].username;
			var pom = 'p' + tmp + '' + tmp ;
			document.getElementById(pom).innerHTML=val[i].Indeks;
			var pom = 'p' + tmp + '' + tmp + '' + tmp;
			document.getElementById(pom).innerHTML=val[i].Poeni;
		tmp++ ;
	}
	
	function otvoreno() {
		sessionStorage.setItem('Otvoreno', 1);
		console.log(sessionStorage.getItem('Otvoreno'));
	}
	
	function zatvoreno() {
		sessionStorage.setItem('Otvoreno', 0);
		console.log(sessionStorage.getItem('Otvoreno'));
	}
	
	
</script>

</body>
</html>