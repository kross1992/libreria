let url = 'https://www.etnassoft.com/api/v1/get/?category=comics&callback=?';
fetch(url).then(res => res.json()).then((out) => {
  $("#libros tbody").empty();
  $.each(out, function(i, f) {
    let tblRow = "<tr>" + "<td>" + f.title + "</td>" + "<td class='text-justify'>" + f.content + "</td>" +  "<td>" + f.author + "</td>" + "<td>" + f.pages + "</td>" + "<td>" + "<a href='#' onClick='verLibro(this)'  data-id='"+f.ID+"' data-title='"+f.title+"' data-content='"+f.content+"' data-author='"+f.author+"' data-date='"+f.publisher_date+"' data-language='"+f.language+"' data-pages='"+f.pages+"' data-image='"+f.cover+"' data-tags='"+f.tags+"'>"+'Ver'+"</a>" +" "+"<a href='/crear_libro'>"+'Agregar'+"</a>" + "</td>" + "</tr>"
    $(tblRow).appendTo("#libros tbody");
 });
}).catch(err => {
  throw err
});

var verLibro    = (a) => {
  let id        = a.getAttribute("data-id");
  let title     = a.getAttribute("data-title");
  let content   = a.getAttribute("data-content");
  let author    = a.getAttribute("data-author");
  let date      = a.getAttribute("data-date");
  let pages     = a.getAttribute("data-pages");
  let images    = a.getAttribute("data-image");
  let language  = a.getAttribute("data-language");
  let tag       = a.getAttribute("data-tags");
  document.getElementById('title').innerHTML = title;
  document.getElementById('content').innerHTML = content;
  document.getElementById('author').innerHTML = 'Autor: '+ author;
  document.getElementById('date').innerHTML = 'Fecha De Publicación: '+ date;
  document.getElementById('pages').innerHTML = 'Número De Paginas: '+ pages;
  document.getElementById('lenguage').innerHTML = 'Idioma: '+ language;
  document.getElementById("images").src = images;
  document.getElementById("tags").innerHTML = tags;
  $("#modal-libro").modal();
};
