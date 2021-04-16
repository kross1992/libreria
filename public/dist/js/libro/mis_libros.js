let eliminar = document.getElementsByClassName("eliminar-libro");
let borrarLibro = function() {
    let id = this.getAttribute("data-id");
    $.ajax({
      url: '/eliminar_libro',
      type: 'POST',
      dataType: 'json',
      data: {'id':id},
      success: function(data) {
        if (data.status) {
          alert("se ha borrado con exito");
          window.location.reload();
        }else{
          alert("no se ha borrado con exito");
        }
      }
    });
}
for (let i = 0; i < eliminar.length; i++) {
    eliminar[i].addEventListener('click', borrarLibro, false);
}
