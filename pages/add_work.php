<?php 
include_once "connect.php";
$user_id = '';
$task_id = '';
if(!empty($_GET['id'])){
    $user_id=$_GET['id'];
    if(!empty($_GET['id_task']))$task_id = $_GET['id_task'];
    $_SESSION['id_user']=$user_id;
    $_SESSION['id_task']=$task_id;
    try{
        $res = $dbh->prepare('SELECT * FROM tbl_users WHERE id='.$user_id.' ');
        $res->execute();
    }catch(Exception $e){
        echo "Erreur:".$e->getMessage()."";
    }
    try{
        $res_que = $dbh->prepare('SELECT * FROM tbl_tasks WHERE id_task=:get_id_task && id_user=:get_id_user');
        $res_que->execute(
        array(':get_id_task' => $task_id, ':get_id_user' => $user_id)
        );
    }catch(Exception $e){
        echo "Erreur:".$e->getMessage();
    }
    try{
        $work = $dbh->prepare('SELECT * FROM tbl_work WHERE id_task=:task');
        $work->execute(array(
        ':task' => $task_id
        ));
    }catch(PDOException $e){
    }
}else header("Location: index.php");
?>
<br>
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h4>Travail a deposer</h4>
    </div>
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th colspan="3"><h4>Utilisateur</h4></th>
                </tr>
                <tr class="success">
<?php
$data = $res->fetch(PDO::FETCH_ASSOC);
                echo "
                    <th>".$data['nom']."</th>
                    <th>".$data['prenom']."</th>
                    <th>".$data['email']."</th>
                </tr>
                    ";
$task_data = $res_que->fetch(PDO::FETCH_ASSOC);

                echo "
                <tr>
                    <th colspan=\"3\"><h4>Tache</h4></th>
                </tr>
                <tr class=\"info\">
                    <th>".$task_data['task']."</th>
                    <th>".$task_data['created']."</th>
                    <th>".$task_data['finish']."</th>
                </tr>
                ";        
                echo "
                <tr>
                    <th colspan=\"3\"><h3>Fichiers deposee</h3></th>
                </tr>
                <tr>
                    <th>Type</th>
                    <th>Nom du fichier</th>
                    <th>Date</th>
                    <th>Options</th>
                </tr>
                ";
$work_data = $work->fetchAll();
foreach($work_data as $row){
                echo "
                <tr class=\"success\">
                    <th>".$row['type']."</th>
                    <th>".$row['file']."</th>
                    <th>".$row['date']."</th>
                    <th><a href=\"log.php?action=deletework&id_work=".$row['id_work']."\" class=\"btn btn-info btn-xs\">Supprimer</a></th>
                </tr>
                ";
}
?>
            </table>
        </div>

    
        <div class="col-md-4">
            <h4>Deposer un fichier</h4>   
        <form action="log.php?action=add_work" method="post" role="form" class="form-horisontal" enctype="multipart/form-data">
            <div class="form-group">
                <label for="idfile">Fichier</label>
                <input type="hidden" name="max-size" value="30000">
                <input type="file" id="idfile" name="file">
            </div>
            <br>
            <button type="submit" class="btn btn-success">Envoyer</button>
            <a href="index.php" class="btn btn-primary">Annuler</a>
        </form>
        </div>  
    </div>
</div>
