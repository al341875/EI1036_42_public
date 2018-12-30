
<?php
function MP_Register_FormAS($MP_userAS , $user_email){
    ?>

   <head><script type="text/javascript" src="/wp-content/plugins/my_groupAgroshopping/templates/ASreg.js" charset="utf-8" async="" defer=""></script> 
   </head>
    <h1>Gestión de Usuarios </h1>
    <form class="form" action="?action=my_datos&proceso=registrar" method="POST" enctype="multipart/form-data">
        <label for="clienteMail">Tu correo</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos</legend>
<br/>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userAS["userName"] ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userAS["email"] ?>"
        placeholder="kiko@ic.es" />
        <br/>
 <label for="foto_file">Foto</label>
<br/>
<input type="file" id="foto" name="foto" class="item_requerid" value="<?php print $foto ?>" required />
<br/>
<br>
<p> <img id="img_foto" src="" width="100" height="60"></p>

        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>


<script type="text/javascript" charset="utf-8">

      function mostrarFoto(file, imagen) {
	  //carga la imagen de file en el elemento src imagen
         var reader = new FileReader();
         reader.addEventListener("load", function () {
            imagen.src = reader.result;
         });
         reader.readAsDataURL(file);
      }

      function ready() {
         var fichero = document.querySelector("#foto");
         var imagen  = document.querySelector("#img_foto");
	  //escuchamos evento selección nuevo fichero.
         fichero.addEventListener("change", function (event) {
            mostrarFoto(this.files[0], imagen);
         });
      }

      ready();

   </script>
<?php
}
MP_Register_FormAS($MP_userAS,$user_email);
$MP_userAS=null; 
?>