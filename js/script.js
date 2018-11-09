// var myFetch = fetch(url);
const dossier = document.querySelector('#chemin');
var monDossier = document.querySelectorAll('.dir');


// Affectation de l'élément clic au lien
function dossier_click() {
    alert("coucou");
    console.log("salut");

    fetch(`./explorateur/index.php?var=${monDossier}`)
  .then((response) => {
    return response.json();})

  .then((response) => {
    // var objectURL = URL.createObjectURL(myJson)});
    console.log(response);
    // .catch( (error) => {
    //     console.log(error);
    // });
});
}
  
// var myList = document.querySelector('ul');

// var myRequest = new Request('products.json');

// fetch(myRequest)
//   .then(function(response) { return response.json(); })
//   .then(function(data) {
//     for (var i = 0; i < data.products.length; i++) {
//       var listItem = document.createElement('li');
//       listItem.innerHTML = '<strong>' + data.products[i].Name + '</strong> can be found in ' +
//                            data.products[i].Location +
//                            '. Cost: <strong>£' + data.products[i].Price + '</strong>';
//       myList.appendChild(listItem);
//     }
//   });

// fonction mouseover / mouseout
    // function dossier_over()  {
    //     if (image.style.width = '100px') {
    //         console.log(image)
    //         image.style.width = '200px';
    //     } else  {
    //         image.style.width = '100px';

    //     }

    // };

    // function dossier_out() { 
    //     if (image.style.width = '200px') {
    //         image.style.width = '100px';
    //     } else  {
    //         image.style.width = '100px';

    //     }
    // };*

