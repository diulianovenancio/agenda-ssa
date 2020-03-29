<?php
include ("../../inc/includes.php");
include ("../../inc/config.php");
global $DB;

Session::checkLoginUser();

include_once("ADM/conexao.php");
$result_events = "SELECT a.*, u.id AS id_user, u.firstname AS nome_usuario, u.realname AS sobrenome_usuario FROM agenda_fullcalendar a
INNER JOIN glpi_users u ON a.criado_por=u.id
where a.departamento = 'cpd'";
$resultado_events = mysqli_query($conn, $result_events);

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset='utf-8' />
		<meta http-equiv='refresh' content='300'>
		<title>Agenda TI</title>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
		<link href='css/fullcalendar.min.css' rel='stylesheet' />
		<link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
		<link href='css/personalizado.css' rel='stylesheet' />
		<link rel="shortcut icon" href="ADM/img/favicon.png" />
		<script src='js/jquery.min.js'></script>
		<script src='js/bootstrap.min.js'></script>
		<script src='js/moment.min.js'></script>
		<script src='js/fullcalendar.min.js'></script>
		<script src='locale/pt-br.js'></script>
		<script>
			$(document).ready(function() {
				$('#calendar').fullCalendar({
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'month,agendaWeek,agendaDay'
					},
					defaultDate: Date(),
					navLinks: true, // can click day/week names to navigate views
					editable: true,
					eventLimit: true, // allow "more" link when too many events
					eventClick: function(event) {
						
						$('#visualizar #id').text(event.id);
						$('#visualizar #title').text(event.title);
						$('#visualizar #obs').text(event.obs);
						$('#visualizar #user_id').text(event.user_id);
						$('#visualizar #user_name').text(event.user_name);
						$('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar').modal('show');
						return false;

					},
					events: [
						<?php
							while($row_events = mysqli_fetch_array($resultado_events)){
								?>
								{
								id: '<?php echo $row_events['id']; ?>',
								title: '<?php echo $row_events['title']; ?>',
								obs: '<?php echo $row_events['obs']; ?>',
								start: '<?php echo $row_events['start']; ?>',
								end: '<?php echo $row_events['end']; ?>',
								color: '<?php echo $row_events['color']; ?>',
								user_name: '<?php echo $row_events['nome_usuario']." ".$row_events['sobrenome_usuario']; ?>',
								user_id: '<?php echo $row_events['criado_por']; ?>',
								},<?php
							}
						?>
					]
				});
			});
		</script>
	</head>
	<body>

		<div class="container">
			<div class="page-header">
				<h1>Agenda - TI</h1>
			</div>
			<div id='calendar'></div>
		</div>

		<div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center">Informações do Evento</h4>
					</div>
					<div class="modal-body">
						<dl class="dl-horizontal">
							<dt>ID:</dt>
							<dd id="id"></dd>
							<dt>Criado por:</dt>
							<dd id="user_id"></dd>
							<dd id="user_name"></dd>
							<dt>Titulo:</dt>
							<dd id="title"></dd>
							<dt>Observação:</dt>
							<dd id="obs"></dd>
							<dt>Inicio:</dt>
							<dd id="start"></dd>
							<dt>Fim:</dt>
							<dd id="end"></dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
