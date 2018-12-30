async function enviaForm(evento) {
   try {
      evento.preventDefault();
      var img  = document.querySelector("#img_foto");
      let url = evento.target.getAttribute("action")
      let data = new FormData(evento.target);
      let init = {
         url: url,
         method: 'post',
         body: data
      };
      let request0 = new Request(url, init);
      const response = await fetch(request0);

      if (!response.ok) {
         throw Error(response.statusText);
      }   
      const result = await response.text();
      console.log('Correcto devuelvo:', result);
      evento.target.reset ();
       img.src = "";



window.alert("Amigo añadido correctamente. ¿Quieres  añadir otro amigo?");
   } catch (error) {
      console.log(error);
   }

}
if (document.forms.length > 0) {
   document.forms[0].addEventListener("submit", function (event) {
      enviaForm(event);

   })
}