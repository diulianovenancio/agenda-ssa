<?php
session_start();

//Incluir conexao com BD
include_once("conexao.php");

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
echo $id;

if(!empty($id)){
	
	$result_events = "DELETE FROM `agenda_fullcalendar` WHERE id='$id'";
	//echo $result_events;
	
	$resultado_events = mysqli_query($conn, $result_events);
	
	//Verificar se deletou no banco de dados atravĂ©s "mysqli_affected_rows"
	if(mysqli_affected_rows($conn)){
		$_SESSION['msg'] = "<div class='alert alert-success' role='alert'>O Evento foi deletado com Sucesso<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		header("Location: index.php");
	}else{
		$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao deletar o evento <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		header("Location: index.php");
	}
	
}else{
	$_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Erro ao deletar o evento <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
	header("Location: index.php");
}
