
<?php
function MP_Update_Form_AS($MP_userAS , $user_email,$nom,$email,$foto,$person_id)
{//formulario registro amigos de $user_email

    ?>


    <h1>GestiÓn de Usuarios </h1>
    <form class="form" action="?action=my_datos&proceso=updatear" method="POST" enctype="multipart/form-data">
        <label for="clienteMail">Tu correo</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos</legend>
<br/>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $nom ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $email ?>"
        placeholder="kiko@ic.es" />
        <br/>
	<input type="hidden" name="person_id" value="<?php print $person_id ?>">
 <label for="foto_file">Foto</label>
<br/>
<input type="file" id="foto" name="foto" class="item_requerid" value="<?php print $foto ?>" required/>

<br/>
<p>Foto anterior:</p>
<p><img id="imagen" src="<?php print $foto ?>" width="100" height="60"></p>
<br>
<p>Foto actual:</p>
<p> <img id="img_foto" src="" width="100" height="60"></p>
        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>



<script type="text/javascript" charset="utf-8">
      function mostrarFoto(file, imagen) {
	  //carga la imagen de file en el elemento src imagen
         var reader = new FileReader();
         reader.addEventListener("load", function () {
 if (!(/\.(jpg)$/i).test(file.name)) {
	alert('El archivo a adjuntar no es una imagen valida .jpg');
	imagen.src = "";
	document.querySelector("#foto").value="";
 }else {
        
            if (file.size > 200000)
            {
                alert('El peso de la imagen no puede exceder los 200kb y tu imagen tiene: ');
		imagen.src = "";
		document.querySelector("#foto").value="";
            }
            else {
		imagen.src = reader.result;
                alert('Imagen correcta.');            
            }
}
        });
 reader.readAsDataURL(file);

         
}

function ready() {
         var fichero = document.querySelector("#foto");
	//var fichero = document.querySelector('input[type="file"]');
         var imagen  = document.querySelector("#img_foto");
	
	  //escuchamos evento selección nuevo fichero.
         fichero.addEventListener("change", function (event) {
            mostrarFoto(this.files[0], imagen);
		//console.log(fichero.files);
         });
      }

      ready();
   </script>
<?php
}
$table2='A_GrupoCliente';
			$valor=$_GET["person_id"];
			$query = "SELECT     * FROM       $table WHERE person_id=$valor "; 
			$consult = $MP_pdo_AS->prepare($query);
			$a=$consult->execute(array());
			$result=($consult->fetch(PDO::FETCH_ASSOC));
			$MP_userAS=null; //variable a rellenar cuando usamos modificar con este formulario
			$nom= $result["nombre"];
			$email= $result["email"];
            $foto= $result["foto_file"];
            
         MP_pdo_AS($MP_userAS,$user_email,$nom,$email,$foto,$valor);
         
?>