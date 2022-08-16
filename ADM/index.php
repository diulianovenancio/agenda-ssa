<?php
include ("../../../inc/includes.php");
include ("../../../inc/config.php");
global $DB;

Session::checkLoginUser();
//$userID = $_SESSION['glpiID'];

session_start();

include_once("conexao.php");
//$result_events = "SELECT * FROM agenda_fullcalendar where departamento = 'cpd'";
$result_events = "SELECT a.*, u.id AS id_user, u.firstname AS nome_usuario, u.realname AS sobrenome_usuario FROM agenda_fullcalendar a
INNER JOIN glpi_users u ON a.criado_por=u.id
where a.departamento = 'cpd'";
$resultado_events = mysqli_query($conn, $result_events);

//profiles_id = Perfil do Usuario
//is_active = Usuario Ativo (1) e (0) Desativado
//Verifica perfil do usuario
$perfil_query = "SELECT * from glpi_users where id = ".$_SESSION['glpiID'];
$perfil_result = mysqli_query($conn, $perfil_query);
//echo $perfil_query; //testando query
$perfilt_row = mysqli_fetch_assoc($perfil_result);
//echo "<br>".$perfilt_row['is_active']."<br>".$perfilt_row['profiles_id']."<br>".$perfilt_row['id']; //testando informações
//exit();
if ( ($perfilt_row['is_active'] == 1 and $perfilt_row['profiles_id'] == 4) OR ($perfilt_row['id'] == 238) ){}else{$_SESSION['msg'] = "<div class='alert alert-success' role='alert'>Você não possui permissão para acessar o painel de gestão da agenda!<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";header('Location: https://ssa.autus.com.br/agenda/ti/');}

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset='utf-8' />
		<meta http-equiv='refresh' content='300'>
		<title>Agenda - TI</title>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
		<link href='css/fullcalendar.min.css' rel='stylesheet' />
		<link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
		<link href='css/personalizado.css' rel='stylesheet' />
		<link rel="shortcut icon" href="img/favicon.png" />
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
						$('#visualizar #id').val(event.id);
						$('#visualizar #title').text(event.title);
						$('#visualizar #title').val(event.title);
						$('#visualizar #obs').text(event.obs);
						$('#visualizar #obs').val(event.obs);
						$('#visualizar #user_id').text(event.user_id);
						$('#visualizar #user_id').val(event.user_id);
						$('#visualizar #user_name').text(event.user_name);
						$('#visualizar #user_name').val(event.user_name);
						$('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #start').val(event.start.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #end').val(event.end.format('DD/MM/YYYY HH:mm:ss'));
						//$('#visualizar #criado').text(event.criado.format('DD/MM/YYYY HH:mm:ss'));
						//$('#visualizar #criado').val(event.criado.format('DD/MM/YYYY HH:mm:ss'));
						//$('#visualizar #modificado').text(event.modificado.format('DD/MM/YYYY HH:mm:ss'));
						//$('#visualizar #modificado').val(event.modificado.format('DD/MM/YYYY HH:mm:ss'));
						$('#visualizar #color').val(event.color);
						$('#visualizar').modal('show');
						return false;

					},
					
					selectable: true,
					selectHelper: true,
					select: function(start, end){
						$('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm:ss'));
						$('#cadastrar #end').val(moment(end).format('DD/MM/YYYY HH:mm:ss'));
						$('#cadastrar').modal('show');						
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
								//criado: '<?php echo $row_events['criado_em']; ?>',
								//modificado: '<?php echo $row_events['modificado_em']; ?>',
								},<?php
							}
						?>
					]
				});
			});
			
			//Mascara para o campo data e hora
			function DataHora(evento, objeto){
				var keypress=(window.event)?event.keyCode:evento.which;
				campo = eval (objeto);
				if (campo.value == '00/00/0000 00:00:00'){
					campo.value=""
				}
			 
				caracteres = '0123456789';
				separacao1 = '/';
				separacao2 = ' ';
				separacao3 = ':';
				conjunto1 = 2;
				conjunto2 = 5;
				conjunto3 = 10;
				conjunto4 = 13;
				conjunto5 = 16;
				if ((caracteres.search(String.fromCharCode (keypress))!=-1) && campo.value.length < (19)){
					if (campo.value.length == conjunto1 )
					campo.value = campo.value + separacao1;
					else if (campo.value.length == conjunto2)
					campo.value = campo.value + separacao1;
					else if (campo.value.length == conjunto3)
					campo.value = campo.value + separacao2;
					else if (campo.value.length == conjunto4)
					campo.value = campo.value + separacao3;
					else if (campo.value.length == conjunto5)
					campo.value = campo.value + separacao3;
				}else{
					event.returnValue = false;
				}
			}
		</script>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Agenda - TI</h1>
			</div>
			<?php
			if(isset($_SESSION['msg'])){
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
			?>
		
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
						<div class="visualizar">
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
								<!--<dt>Cadastrado:</dt>
								<dd id="criado"></dd>-->
								<!--<dt>Modificado:</dt>
								<dd id="modificado"></dd>-->
							</dl>
							<button class="btn btn-canc-vis btn-warning">Editar</button>
							<button class="btn btn-canc-del btn-danger pull-right">Excluir</button>
						</div>
						<div class="form">
							<form class="form-horizontal" method="POST" action="proc_edit_evento.php">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Usuário ID</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="user" id="user" value="<?php echo $_SESSION['glpiID']; ?>" readonly="readonly" />
										<input type="text" class="form-control" id="user_name" disabled="disabled" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Título</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="title" id="title" placeholder="Titulo">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Observação</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="observ" id="obs" placeholder="Observação">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Cor</label>
									<div class="col-sm-10">
										<input type="color" name="color" id="color" />
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Data Inicial</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="start" id="start" onKeyPress="DataHora(event, this)">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Data Final</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="end" id="end" onKeyPress="DataHora(event, this)">
									</div>
								</div>
								<input type="hidden" class="form-control" name="id" id="id">
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="button" class="btn btn-canc-edit btn-danger pull-right">Cancelar</button>
										<button type="submit" class="btn btn-success">Salvar Alterações</button>
									</div>
								</div>
							</form>
						</div>
						
						<div class="form">
							<form class="form-horizontal" method="POST" action="proc_del_evento.php">
								<div class="form-group">
									<label for="inputEmail3">Tem certeza que deseja excluir?</label>
								</div>
								<input type="hidden" class="form-control" name="id" id="id">
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="button" class="btn btn-canc-edit btn-success pull-right">Cancelar</button>
										<button type="submit" class="btn btn-danger">Excluir</button>
									</div>
								</div>
							</form>						
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title text-center">Cadastrar Evento</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" method="POST" action="proc_cad_evento.php">
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Usuário ID</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="user" id="user" value="<?php echo $_SESSION['glpiID']; ?>" readonly="readonly" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Titulo</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="title" placeholder="Titulo do Evento">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Observação</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="observ" id="observ" placeholder="Observação do Evento" />
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Cor</label>
								<div class="col-sm-10">
									<input type="color" name="color" id="color" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Data Inicial</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="start" id="start" onKeyPress="DataHora(event, this)">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Data Final</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="end" id="end" onKeyPress="DataHora(event, this)">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-success">Cadastrar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			$('.btn-canc-vis').on("click", function() {
				$('.form').slideToggle();
				$('.visualizar').slideToggle();
			});
			$('.btn-canc-edit').on("click", function() {
				$('.visualizar').slideToggle();
				$('.form').slideToggle();
			});
			$('.btn-canc-del').on("click", function() {
				$('.visualizar').slideToggle();
				$('.form').slideToggle();
			});
		</script>
	</body>
</html>
